<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Transaction;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ShurjopayPlugin\Shurjopay;
use ShurjopayPlugin\PaymentRequest;
use Exception;

class RegistrationController extends Controller
{
    private $sp_instance;

    public function __construct(Shurjopay $sp_instance)
    {
        $this->sp_instance = $sp_instance;
    }

    // =========================================================================
    // HELPER: Phone normalizer
    // =========================================================================
    private function normalizePhone(?string $phone): string
    {
        if (empty($phone)) return '01700000000';

        $digits = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($digits) === 13 && str_starts_with($digits, '880'))
            $digits = '0' . substr($digits, 3);
        if (strlen($digits) === 12 && str_starts_with($digits, '88'))
            $digits = '0' . substr($digits, 2);

        if (strlen($digits) !== 11 || !str_starts_with($digits, '01'))
            return '01700000000';

        return $digits;
    }

    // =========================================================================
    // HELPER: Email normalize
    // =========================================================================
    private function normalizeEmail(?string $email): string
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Invalid email normalized to fallback', ['original' => $email]);
            return 'noreply@placeholder.com';
        }
        return $email;
    }

    // =========================================================================
    // HELPER: Friendly Error Message
    // =========================================================================
    private function friendlyError(Exception $e): string
    {
        return "পেমেন্ট গেটওয়েতে সাড়া পাওয়া যাচ্ছে না। অনুগ্রহ করে কিছুক্ষণ পর আবার চেষ্টা করুন।";
    }

    // =========================================================================
    // CREATE — Registration form দেখানো
    // =========================================================================
    public function create($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('users.events.pre_reg_form', compact('event'));
    }

    // =========================================================================
    // STORE — Registration save + conditional redirect (With DB Transaction)
    // =========================================================================
    public function store(Request $request)
    {
        // ১. Common validation rules
        $commonRules = [
            'event_id'        => 'required|exists:events,id',
            'university_name' => 'required|string|max:255',
            'm1_name'         => 'required|string|max:255',
            'm1_email'        => 'required|email|max:255',
            'm1_phone'        => 'required|digits:11',
            'm1_tshirt'       => 'nullable|string',
            'm1_prev_ex'      => 'nullable|string',
        ];

        $event     = Event::findOrFail($request->event_id);
        $eventSlug = $event->slug;
        $eventRules = [];

        // ২. Event-specific validation
        if ($eventSlug === 'ict-olympiad') {
            $eventRules = [
                'student_id' => 'required|string|max:50',
            ];
        } elseif ($eventSlug === 'iupc') {
            $eventRules = [
                'team_name'         => 'required|unique:registrations,team_name|max:255',
                'coach_name'        => 'required|string',
                'coach_email'       => 'required|email',
                'coach_phone'       => 'required|digits:11',
                'coach_designation' => 'required|string',
                'coach_tshirt'      => 'required|string',
                'm1_cf_handle'      => 'required|string',
                'm2_name'           => 'required|string',
                'm2_email'          => 'required|email',
                'm2_phone'          => 'required|digits:11',
                'm2_tshirt'         => 'required|string',
                'm2_prev_ex'        => 'required|string',
                'm2_cf_handle'      => 'required|string',
                'm3_name'           => 'nullable|string',
                'm3_email'          => 'nullable|email',
                'm3_phone'          => 'nullable|digits:11',
                'm3_tshirt'         => 'nullable|string',
                'm3_prev_ex'        => 'nullable|string',
                'm3_cf_handle'      => 'nullable|string',
            ];
        } elseif (in_array($eventSlug, ['project-showcase', 'ai-hackathon'])) {
            $eventRules = [
                'team_name'     => 'required|unique:registrations,team_name|max:255',
                'team_person'   => 'required|string',
                'project_title' => $eventSlug === 'project-showcase' ? 'required|string' : 'nullable',
                'abstract_file' => $eventSlug === 'project-showcase' ? 'required|mimes:pdf|max:3072' : 'nullable',
                'm2_name'       => 'required|string',
                'm2_email'      => 'required|email',
                'm2_phone'      => 'required|digits:11',
                'm2_tshirt'     => 'required|string',
                'm3_name'       => 'nullable|string',
                'm3_email'      => 'nullable|email',
                'm3_phone'      => 'nullable|digits:11',
                'm3_tshirt'     => 'nullable|string',
                'm3_prev_ex'    => 'nullable|string',
                'm2_prev_ex'    => 'nullable|string',
                'm3_cf_handle'  => 'nullable|string',
                'm2_cf_handle'  => 'nullable|string',
                'm1_cf_handle'  => 'nullable|string',
            ];
        }

        $validatedData = $request->validate(array_merge($commonRules, $eventRules));

        // 🛠️ ডাটাবেজ ট্রানজেকশন শুরু
        DB::beginTransaction();

        try {
            // Other university handling
            if ($request->university_name === 'Others' && $request->filled('other_university')) {
                $validatedData['university_name'] = $request->other_university;
            }

            // Abstract file upload
            if ($request->hasFile('abstract_file')) {
                $path = $request->file('abstract_file')->store('abstracts', 'public');
                $validatedData['abstract_file'] = $path;
            }

            // Team ID generation
            $teamEvents = ['iupc', 'project-showcase', 'ai-hackathon'];
            if (in_array($eventSlug, $teamEvents)) {
                $prefix = strtoupper(substr(str_replace('-', '', $eventSlug), 0, 4));
                do {
                    $teamId = $prefix . '-' . rand(1000, 9999);
                } while (Registration::where('team_id', $teamId)->exists());

                $validatedData['team_id'] = $teamId;
            }

            // ✅ ফিক্স: আপনার মাইগ্রেশন অনুযায়ী সকল ইভেন্টের ডিফল্ট status হবে 'pre-registered'
            $validatedData['status']         = 'pre-registered';

            // ✅ ফিক্স: পেমেন্ট ট্র্যাকিংয়ের জন্য 'payment_status' কলামে 'pending' সেট করা হলো
            $validatedData['payment_status'] = 'pending';
            $validatedData['event_id']       = $event->id;

            $registration = Registration::create($validatedData);

            // ⚡ টিম ইভেন্ট (IUPC, Project, AI) হলে সরাসরি ডাটাবেজে কমিট করে ব্যাক করবে
            if (in_array($eventSlug, $teamEvents)) {
                DB::commit();
                $msg = 'Registration submitted successfully for ' . $event->title;
                if ($registration->team_id ?? false) {
                    $msg .= '. Your Team ID: ' . $registration->team_id;
                }
                return redirect()->back()->with('success', $msg);
            }

            // 🛑 ICT Olympiad বা অন্যান্য সিঙ্গেল ইভেন্টের জন্য ইনস্ট্যান্ট পেমেন্ট প্রসেস
            if (! $this->sp_instance) {
                throw new Exception('ShurjoPay instance is null');
            }

            $order_id = strtoupper($eventSlug) . '-' . uniqid();
            $registration->update(['order_id' => $order_id]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = (float) $registration->event->reg_fee;
            $paymentRequest->orderId         = (string) $order_id;
            $paymentRequest->customerName    = (string) $registration->m1_name;
            $paymentRequest->customerPhone   = (string) $this->normalizePhone($registration->m1_phone);
            $paymentRequest->customerEmail   = (string) $this->normalizeEmail($registration->m1_email);
            $paymentRequest->customerAddress = (string) ($registration->university_name ?? 'DUET, Gazipur');
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = (string) $registration->id;

            // Shurjopay থেকে চেকআউট ইউআরএল রেসপন্স জেনারেট করা
            $response = $this->sp_instance->makePayment($paymentRequest);

            // 🌟 গেটওয়ে রেসপন্স সাকসেসফুলি আসলে তবেই ডাটাবেজে ট্রানজেকশন সফল (Commit) হবে
            DB::commit();

            return $response;
        } catch (Exception $e) {
            // 🔄 কোনো এরর আসলে ডাটাবেজের এই এন্ট্রি সম্পূর্ণ রোলব্যাক (মুছে) হয়ে যাবে!
            DB::rollBack();

            Log::error('Registration & Payment Process Failed', [
                'event' => $eventSlug ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            // আপলোড করা পিডিএফ ফাইল থাকলে তা ডিলিট করা
            if (isset($path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }

            if (in_array($eventSlug ?? '', $teamEvents)) {
                return back()->with('error', 'রেজিস্ট্রেশন সেভ করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।')->withInput();
            }

            // ফ্রন্টএন্ডে ক্লিন ও ইউজার ফ্রেন্ডলি এরর দেখাবে
            return $this->paymentErrorView($this->friendlyError($e), $registration ?? null);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER: Payment error view
    // ─────────────────────────────────────────────────────────────────────────
    private function paymentErrorView(string $message, ?Registration $registration = null, string $type = 'gateway_error')
    {
        $slug = optional($registration?->event)->slug ?? 'event';

        return view('payment.error', [
            'registration' => $registration,
            'error_type'   => $type,
            'message'      => $message,
            'slug'         => $slug,
        ]);
    }

    // =========================================================================
    // RETRY PAYMENT — পেমেন্ট ফেইল করলে রিট্রাই করার জন্য রাউট লিংক
    // =========================================================================
    public function retryPayment($registration_id)
    {
        $registration = Registration::with('event')->findOrFail($registration_id);

        if ($registration->payment_status === 'paid') {
            return redirect()->route('home')->with('info', 'আপনার পেমেন্ট অলরেডি সম্পন্ন হয়েছে।');
        }

        return $this->makePayment($registration->id);
    }

    // =========================================================================
    // MAKE PAYMENT — পেমেন্ট রিট্রাই হ্যান্ডলার
    // =========================================================================
    public function makePayment($registration_id)
    {
        $registration = Registration::with('event')->findOrFail($registration_id);

        if ($registration->payment_status === 'paid') {
            return redirect()->route('home')->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');
        }

        if (! $this->sp_instance) {
            Log::error('ShurjoPay instance is null', ['registration_id' => $registration_id]);
            return $this->paymentErrorView('পেমেন্ট গেটওয়ে সংযোগ করা যায়নি। Admin কে জানান।', $registration);
        }

        try {
            $order_id = strtoupper($registration->event->slug) . '-' . uniqid();

            // আপডেটের সময়ও payment_status যেন pending থাকে নিশ্চিত করা
            $registration->update([
                'order_id'       => $order_id,
                'payment_status' => 'pending'
            ]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = (float) $registration->event->reg_fee;
            $paymentRequest->orderId         = (string) $order_id;
            $paymentRequest->customerName    = (string) $registration->m1_name;
            $paymentRequest->customerPhone   = (string) $this->normalizePhone($registration->m1_phone);
            $paymentRequest->customerEmail   = (string) $this->normalizeEmail($registration->m1_email);
            $paymentRequest->customerAddress = (string) ($registration->university_name ?? 'DUET, Gazipur');
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = (string) $registration->id;

            return $this->sp_instance->makePayment($paymentRequest);
        } catch (Exception $e) {
            Log::error('makePayment retry error', [
                'registration_id' => $registration_id,
                'error'           => $e->getMessage(),
            ]);

            return $this->paymentErrorView($this->friendlyError($e), $registration);
        }
    }
}
