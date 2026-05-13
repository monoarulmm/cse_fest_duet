<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Event;
use ShurjopayPluginPhp\Shurjopay;
use ShurjopayPluginPhp\ShurjopayConfig;
use ShurjopayPluginPhp\PaymentRequest;

class RegistrationController extends Controller
{
    public function create($slug)
    {
        // স্ল্যাগ দিয়ে ইভেন্ট খুঁজে বের করা
        $event = Event::where('slug', $slug)->firstOrFail();

        // যদি IUPC হয় তবে আলাদা ভিউ, নাহলে জেনারেল ভিউ (আপনার ইচ্ছা অনুযায়ী)
        return view('users.events.pre_reg_form', compact('event'));
    }

    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'event_id' => 'required|exists:events,id', // নিশ্চিত করা যে ইভেন্ট আইডি ঠিক আছে
    //         'university_name' => 'required',
    //         'team_name' => 'required|unique:iupc_registrations',
    //         'coach_email' => 'nullable|email',
    //         'm1_email' => 'required|email',
    //     ]);

    //     // ডাটা সেভ করা
    //     Registration::create($request->all());

    //     return redirect()->back()->with('success', 'রেজিস্ট্রেশন সফল হয়েছে!');
    // }




    // public function store(Request $request)
    // {
    //     // ১. কমন ভ্যালিডেশন (সব ইভেন্টের জন্য বেসিক ডাটা)
    //     $commonRules = [
    //         'event_id'        => 'required|exists:events,id',
    //         'university_name' => 'required|string|max:255',
    //         'm1_name'         => 'required|string|max:255',
    //         'm1_email'        => 'required|email|max:255',
    //         'm1_phone'        => 'required|string|max:20',
    //         'm1_tshirt'       => 'required|string',
    //     ];

    //     $event = \App\Models\Event::findOrFail($request->event_id);
    //     $eventSlug = $event->slug;
    //     $eventRules = [];

    //     // ২. ইভেন্ট অনুযায়ী কড়া (Strict) ভ্যালিডেশন

    //     // --- IUPC Registration ---
    //     if ($eventSlug === 'iupc') {
    //         $eventRules = [
    //             'team_name'    => 'required|unique:registrations,team_name|max:255',
    //             'coach_name'   => 'required|string|max:255',
    //             'coach_email'  => 'required|email',
    //             'coach_phone'  => 'required|string',
    //             'coach_tshirt' => 'required|string',
    //             'm1_cf_handle' => 'required|string',
    //             'm2_name'      => 'required|string|max:255',
    //             'm2_email'     => 'required|email',
    //             'm2_phone'     => 'required|string',
    //             'm2_tshirt'    => 'required|string',
    //             'm2_cf_handle' => 'required|string',
    //             // ৩য় মেম্বার অপশনাল হলে এখানে দরকার নেই, ব্লেড ফাইল লজিক হ্যান্ডেল করবে
    //         ];
    //     }
    //     // --- Project Showcase ---
    //     elseif ($eventSlug === 'project-showcase') {
    //         $eventRules = [
    //             'team_name'     => 'required|unique:registrations,team_name|max:255',
    //             'project_title' => 'required|string|max:500',
    //             'domain'        => 'required|string',
    //             'abstract_file' => 'required|mimes:pdf|max:3072', // ৩ এমবি লিমিট
    //             'm2_name'       => 'required|string',
    //             'm2_email'      => 'required|email',
    //             'm2_phone'      => 'required|string',
    //             'm2_tshirt'     => 'required|string',
    //         ];
    //     }
    //     // --- ICT Olympiad (Solo) ---
    //     elseif ($eventSlug === 'ict-olympiad') {
    //         $eventRules = [
    //             'student_id' => 'required|string|max:50',
    //         ];
    //     }
    //     // --- AI Hackathon ---
    //     elseif ($eventSlug === 'ai-hackathon') {
    //         $eventRules = [
    //             'team_name' => 'required|unique:registrations,team_name|max:255',
    //             // 'm1_github' => 'required|url', // হ্যাকাথনে গিটহাব লিঙ্ক জরুরি
    //             'm2_name'   => 'required|string',
    //             'm2_email'  => 'required|email',
    //             'm2_phone'  => 'required|string',
    //             'm2_tshirt' => 'required|string',
    //         ];
    //     }

    //     // ভ্যালিডেশন রান করা
    //     $validatedData = $request->validate(array_merge($commonRules, $eventRules));

    //     try {
    //         // ৩. "Others" ইউনিভার্সিটি হ্যান্ডেল করা
    //         if ($request->university_name === 'Others' && $request->filled('other_university')) {
    //             $validatedData['university_name'] = $request->other_university;
    //         }

    //         // ৪. ফাইল হ্যান্ডলিং (Abstract PDF)
    //         if ($request->hasFile('abstract_file')) {
    //             $fileName = time() . '_abstract_' . str_replace(' ', '_', $request->team_name) . '.pdf';
    //             $path = $request->file('abstract_file')->storeAs('abstracts', $fileName, 'public');
    //             $validatedData['abstract_file'] = $path;
    //         }

    //         // ৫. ডাটাবেসে এন্ট্রি করা
    //         \App\Models\Registration::create($validatedData);

    //         return redirect()->back()->with('success', 'Registration successful for ' . $event->name . '!');
    //     } catch (\Exception $e) {
    //         // এরর হলে আপলোড করা ফাইল ডিলিট করার লজিক এখানে রাখতে পারেন
    //         return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
    //     }
    // }


    // public function store(Request $request)
    // {
    //     // ১. কমন ভ্যালিডেশন
    //     $commonRules = [
    //         'event_id'        => 'required|exists:events,id',
    //         'university_name' => 'required|string|max:255',
    //         'm1_name'         => 'required|string|max:255',
    //         'm1_email'        => 'required|email|max:255',
    //         'm1_phone'        => 'required|string|max:20',
    //         'm1_tshirt'       => 'required|string',
    //     ];

    //     $event = \App\Models\Event::findOrFail($request->event_id);
    //     $eventSlug = $event->slug;
    //     $eventRules = [];

    //     // ২. ইভেন্ট অনুযায়ী ভ্যালিডেশন (অপশনাল ফিল্ডসহ)
    //     if ($eventSlug === 'iupc') {
    //         $eventRules = [
    //             'team_name'    => 'required|unique:registrations,team_name|max:255',
    //             'coach_name'   => 'required|string|max:255',
    //             'coach_email'  => 'required|email',
    //             'coach_phone'  => 'required|string',
    //             'coach_desgination'  => 'required|string',
    //             'coach_tshirt' => 'required|string',
    //             'm1_cf_handle' => 'required|string',
    //             'm2_name'      => 'required|string|max:255',
    //             'm2_email'     => 'required|email',
    //             'm2_phone'     => 'required|string',
    //             'm2_tshirt'    => 'required|string',
    //             'm2_cf_handle' => 'required|string',
    //             // ৩য় মেম্বার অপশনাল (অবশ্যই nullable দিতে হবে)
    //             'm3_name'      => 'nullable|string|max:255',
    //             'm3_email'     => 'nullable|email',
    //             'm3_phone'     => 'nullable|string',
    //             'm3_tshirt'    => 'nullable|string',
    //             'm3_cf_handle' => 'nullable|string',
    //         ];
    //     } elseif ($eventSlug === 'project-showcase') {
    //         $eventRules = [
    //             'team_name'     => 'required|unique:registrations,team_name|max:255',
    //             'project_title' => 'required|string|max:500',
    //             'domain'        => 'required|string',
    //             'abstract_file' => 'required|mimes:pdf|max:3072',
    //             'm2_name'       => 'required|string',
    //             'm2_email'      => 'required|email',
    //             'm2_phone'      => 'required|string',
    //             'm2_tshirt'     => 'required|string',
    //             // প্রজেক্টে ৩য় মেম্বার অপশনাল হলে
    //             'm3_name'       => 'nullable|string',
    //             'm3_email'      => 'nullable|email',
    //             'm3_phone'      => 'nullable|string',
    //             'm3_tshirt'     => 'nullable|string',
    //         ];
    //     } elseif ($eventSlug === 'ai-hackathon') {
    //         $eventRules = [
    //             'team_name' => 'required|unique:registrations,team_name|max:255',
    //             'm2_name'   => 'required|string',
    //             'm2_email'  => 'required|email',
    //             'm2_phone'  => 'required|string',
    //             'm2_tshirt' => 'required|string',
    //             // হ্যাকাথনে ৩য় মেম্বার অপশনাল হলে
    //             'm3_name'   => 'nullable|string',
    //             'm3_email'  => 'nullable|email',
    //             'm3_phone'  => 'nullable|string',
    //             'm3_tshirt' => 'nullable|string',
    //         ];
    //     }

    //     // ভ্যালিডেশন রান করা (validatedData এখন অপশনাল ফিল্ডগুলোও ধারণ করবে)
    //     $validatedData = $request->validate(array_merge($commonRules, $eventRules));

    //     try {
    //         // ৩. "Others" ইউনিভার্সিটি হ্যান্ডেল করা
    //         if ($request->university_name === 'Others' && $request->filled('other_university')) {
    //             $validatedData['university_name'] = $request->other_university;
    //         }

    //         // ৪. ফাইল হ্যান্ডলিং
    //         if ($request->hasFile('abstract_file')) {
    //             $fileName = time() . '_abstract_' . str_replace(' ', '_', $request->team_name) . '.pdf';
    //             $path = $request->file('abstract_file')->storeAs('abstracts', $fileName, 'public');
    //             $validatedData['abstract_file'] = $path;
    //         }

    //         // ৫. ডাটাবেসে এন্ট্রি করা
    //         \App\Models\Registration::create($validatedData);

    //         return redirect()->back()->with('success', 'Registration successful for ' . $event->name . '!');
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
    //     }
    // }

    // public function store(Request $request)
    // {
    //     // ১. কমন ভ্যালিডেশন রুলস
    //     $commonRules = [
    //         'event_id'        => 'required|exists:events,id',
    //         'university_name' => 'required|string|max:255',
    //         'm1_name'         => 'required|string|max:255',
    //         'm1_email'        => 'required|email|max:255',
    //         'm1_phone'        => 'required|string|max:20',
    //         'm1_tshirt'       => 'required|string',
    //     ];

    //     $event = \App\Models\Event::findOrFail($request->event_id);
    //     $eventSlug = $event->slug;
    //     $eventRules = [];

    //     // ২. ইভেন্ট অনুযায়ী ডাইনামিক ভ্যালিডেশন
    //     if ($eventSlug === 'ict-olympiad') {
    //         $eventRules = [
    //             'student_id' => 'required|string|max:50',
    //             // অলিম্পিয়াডে টিম নেই, তাই team_name রুলস এখানে নেই
    //         ];
    //     } elseif ($eventSlug === 'iupc') {
    //         $eventRules = [
    //             'team_name'         => 'required|unique:registrations,team_name|max:255',
    //             'team_id'         => 'required|unique:registrations,team_id|max:25',
    //             'coach_name'        => 'required|string',
    //             'coach_email'       => 'required|email',
    //             'coach_phone'       => 'required|string',
    //             'coach_designation' => 'required|string',
    //             'coach_tshirt'      => 'required|string',
    //             'm1_cf_handle'      => 'required|string',
    //             'm2_name'           => 'required|string',
    //             'm2_email'          => 'required|email',
    //             'm2_phone'          => 'required|string',
    //             'm2_tshirt'         => 'required|string',
    //             'm2_cf_handle'      => 'required|string',
    //             'm3_name'           => 'nullable|string',
    //             'm3_email'          => 'nullable|email',
    //             'm3_phone'          => 'nullable|string',
    //             'm3_tshirt'         => 'nullable|string',
    //             'm3_cf_handle'      => 'nullable|string',
    //         ];
    //     } elseif ($eventSlug === 'project-showcase' || $eventSlug === 'ai-hackathon') {
    //         $eventRules = [
    //             'team_name'     => 'required|unique:registrations,team_name|max:255',
    //             'team_id'         => 'required|unique:registrations,team_id|max:25',

    //             'project_title' => $eventSlug === 'project-showcase' ? 'required|string' : 'nullable',
    //             'abstract_file' => $eventSlug === 'project-showcase' ? 'required|mimes:pdf|max:3072' : 'nullable',
    //             'm2_name'       => 'required|string',
    //             'm2_email'      => 'required|email',
    //             'm2_phone'      => 'required|string',
    //             'm2_tshirt'     => 'required|string',
    //             'm3_name'       => 'nullable|string',
    //         ];
    //     }

    //     $validatedData = $request->validate(array_merge($commonRules, $eventRules));

    //     try {
    //         // ৩. ইউনিভার্সিটি হ্যান্ডেল করা
    //         if ($request->university_name === 'Others' && $request->filled('other_university')) {
    //             $validatedData['university_name'] = $request->other_university;
    //         }

    //         // ৪. ফাইল আপলোড (Project Showcase)
    //         if ($request->hasFile('abstract_file')) {
    //             $fileName = time() . '_abstract_' . str_replace(' ', '_', $request->team_name) . '.pdf';
    //             $validatedData['abstract_file'] = $request->file('abstract_file')->storeAs('abstracts', $fileName, 'public');
    //         }

    //         // ৫. ডাটা সেভ করা (Payment Status Default 'pending')
    //         $registration = \App\Models\Registration::create($validatedData);

    //         // ৬. ICT Olympiad এর জন্য সরাসরি পেমেন্ট গেটওয়েতে পাঠানো
    //         if ($eventSlug === 'ict-olympiad') {
    //             // এখানে আপনার পেমেন্ট গেটওয়ে কন্ট্রোলারের রাউটটি হবে
    //             // উদাহরন: SSLCommerz বা bKash এর পেমেন্ট ইনিশিয়েট পেজ
    //             return redirect()->route('payment.make', [
    //                 'registration_id' => $registration->id,
    //                 'amount'          => $event->reg_fee
    //             ]);
    //         }

    //         // ৭. অন্য ইভেন্টের জন্য সাকসেস মেসেজ (যেহেতু ওগুলো সিলেকশনের পর পেমেন্ট হবে)
    //         return redirect()->back()->with('success', 'Registration submitted for ' . $event->name . '. Please wait for selection.');
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
    //     }
    // }


    public function store(Request $request)
    {
        // ১. কমন ভ্যালিডেশন রুলস
        $commonRules = [
            'event_id'        => 'required|exists:events,id',
            'university_name' => 'required|string|max:255',
            'm1_name'         => 'required|string|max:255',
            'm1_email'        => 'required|email|max:255',
            'm1_phone'        => 'required|string|max:20',
            'm1_tshirt'       => 'required|string',
        ];

        $event = \App\Models\Event::findOrFail($request->event_id);
        $eventSlug = $event->slug;
        $eventRules = [];

        // ২. ইভেন্ট অনুযায়ী ডাইনামিক ভ্যালিডেশন
        if ($eventSlug === 'ict-olympiad') {
            $eventRules = [
                'student_id' => 'required|string|max:50',
            ];
        } elseif ($eventSlug === 'iupc') {
            $eventRules = [
                'team_name'         => 'required|unique:registrations,team_name|max:255',
                'coach_name'        => 'required|string',
                'coach_email'       => 'required|email',
                'coach_phone'       => 'required|string',
                'coach_designation' => 'required|string',
                'coach_tshirt'      => 'required|string',
                'm1_cf_handle'      => 'required|string',
                'm2_name'           => 'required|string',
                'm2_email'          => 'required|email',
                'm2_phone'          => 'required|string',
                'm2_tshirt'         => 'required|string',
                'm2_cf_handle'      => 'required|string',
                'm3_name'           => 'nullable|string',
                'm3_email'          => 'nullable|email',
                'm3_phone'          => 'nullable|string',
                'm3_tshirt'         => 'nullable|string',
                'm3_cf_handle'      => 'nullable|string',
            ];
        } elseif ($eventSlug === 'project-showcase' || $eventSlug === 'ai-hackathon') {
            $eventRules = [
                'team_name'     => 'required|unique:registrations,team_name|max:255',
                'project_title' => $eventSlug === 'project-showcase' ? 'required|string' : 'nullable',
                'abstract_file' => $eventSlug === 'project-showcase' ? 'required|mimes:pdf|max:3072' : 'nullable',
                'm2_name'       => 'required|string',
                'm2_email'      => 'required|email',
                'm2_phone'      => 'required|string',
                'm2_tshirt'     => 'required|string',
                'm3_name'       => 'nullable|string',
                'm3_email'       => 'nullable|string',
                'm3_phone'       => 'nullable|string',
                'm3_tshirt'       => 'nullable|string',
            ];
        }

        $validatedData = $request->validate(array_merge($commonRules, $eventRules));

        try {
            // ৩. ইউনিভার্সিটি হ্যান্ডেল করা
            if ($request->university_name === 'Others' && $request->filled('other_university')) {
                $validatedData['university_name'] = $request->other_university;
            }

            // ৪. অটো টিম আইডি জেনারেশন (শুধুমাত্র টিম ইভেন্টের জন্য)
            if ($eventSlug !== 'ict-olympiad') {
                // ফরম্যাট: EVENT-PREFIX + RANDOM NUMBER (যেমন: IUPC-7241)
                $prefix = strtoupper(str_replace('-', '', $eventSlug));
                $prefix = substr($prefix, 0, 4); // প্রথম ৪ অক্ষর

                do {
                    $teamId = $prefix . '-' . rand(1000, 9999);
                } while (\App\Models\Registration::where('team_id', $teamId)->exists());

                $validatedData['team_id'] = $teamId;
            }

            // ৫. ফাইল আপলোড (Project Showcase)
            if ($request->hasFile('abstract_file')) {
                $fileName = time() . '_abstract_' . str_replace(' ', '_', $request->team_name) . '.pdf';
                $validatedData['abstract_file'] = $request->file('abstract_file')->storeAs('abstracts', $fileName, 'public');
            }

            // ৬. ডাটা সেভ করা
            $registration = \App\Models\Registration::create($validatedData);

            // ৭. ICT Olympiad এর জন্য সরাসরি পেমেন্টে পাঠানো
            if ($eventSlug === 'ict-olympiad') {
                return redirect()->route('payment.make', [
                    'registration_id' => $registration->id,
                    'amount'          => $event->reg_fee
                ]);
            }

            // ৮. টিম ইভেন্টের জন্য টিম আইডিসহ সাকসেস মেসেজ
            return redirect()->back()->with('success', 'Registration submitted for ' . $event->name . '. Your Team ID: ' . $registration->team_id . '. Please save it for future reference.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    // ict 
    private $sp_instance;

    public function __construct()
    {
        // ডকুমেন্টেশন অনুযায়ী কনফিগ সেটআপ
        $config = new ShurjopayConfig();
        $config->setMerchantUsername(env('SP_USERNAME'));
        $config->setMerchantPassword(env('SP_PASSWORD'));
        $config->setMerchantPrefix(env('SP_PREFIX'));
        $config->setShurjopayApi(env('SHURJOPAY_API'));
        $config->setShurjopayCallbackUrl(env('SP_CALLBACK'));
        $config->setLogLocation(base_path(env('SP_LOG_LOCATION')));

        $this->sp_instance = new Shurjopay($config);
    }

    public function makePayment($registration_id)
    {
        $registration = Registration::with('event')->findOrFail($registration_id);

        // ১. পেমেন্ট রিকোয়েস্ট অবজেক্ট তৈরি
        $request = new PaymentRequest();
        $request->currency = 'BDT';
        $request->amount = $registration->event->reg_fee;
        $request->customerName = $registration->m1_name;
        $request->customerPhone = $registration->m1_phone;
        $request->customerEmail = $registration->m1_email;
        $request->customerAddress = 'DUET, Gazipur';
        $request->customerCity = 'Gazipur';
        $request->customerPostcode = '1700';
        $request->customerCountry = 'Bangladesh';

        // Custom Data (ভেরিফিকেশনের সময় কাজে লাগবে)
        $request->value1 = $registration->id;

        // ২. ট্রানজেকশন ডাটাবেসে সেভ (Status: Pending)
        // নোট: shurjoPay অটোমেটিক একটি order_id জেনারেট করে যা makePayment রিটার্ন করবে

        return $this->sp_instance->makePayment($request);
    }

    public function callback(Request $request)
    {
        // সূর্যমুখী থেকে ফিরে আসা অর্ডার আইডি
        $order_id = $request->order_id;

        // ৩. পেমেন্ট ভেরিফাই করা
        $verification = $this->sp_instance->verifyPayment($order_id);

        // সফল পেমেন্ট চেক (sp_code 1000 মানে Success)
        if ($verification[0]->sp_code == '1000') {

            $registration_id = $verification[0]->value1;
            $registration = Registration::find($registration_id);

            if ($registration) {
                // ট্রানজেকশন রেকর্ড আপডেট
                Transaction::create([
                    'transaction_id' => $order_id,
                    'event_id'       => $registration->event_id,
                    'team_id'        => $registration->team_id,
                    'student_id'     => $registration->student_id,
                    'amount'         => $verification[0]->amount,
                    'status'         => 'Successful',
                ]);

                // রেজিস্ট্রেশন স্ট্যাটাস আপডেট
                $registration->update([
                    'transaction_id' => $order_id,
                    'pending_status' => 'paid',
                    'status'         => 'verified'
                ]);

                return redirect()->route('event.hub', $registration->event->slug)
                    ->with('success', 'Payment successful!');
            }
        }

        return redirect()->route('home')->with('error', 'Payment failed!');
    }
}
