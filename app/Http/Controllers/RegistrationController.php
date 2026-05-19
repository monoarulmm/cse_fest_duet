<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Exception;

class RegistrationController extends Controller
{
    // =========================================================================
    // CREATE — Registration form দেখানো
    // =========================================================================
    public function create($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('users.events.pre_reg_form', compact('event'));
    }

    // =========================================================================
    // STORE — Registration save + conditional redirect
    // =========================================================================
    public function store(Request $request)
    {
        $event = Event::findOrFail($request->event_id);
        $eventSlug = $event->slug;

        // ── 1. Common validation rules ────────────────────────────────────────
        $rules = [
            'event_id'        => 'required|exists:events,id',
            'university_name' => 'required|string|max:255',
            'other_university' => 'nullable|required_if:university_name,Others|string|max:255',
            'm1_name'         => 'required|string|max:255',
            'm1_email'        => 'required|email|max:255',
            'm1_phone'        => 'required|digits:11',
            'm1_tshirt'       => 'nullable|string',
            'm1_prev_ex'      => 'nullable|string',
        ];

        // ── 2. Event-specific validation & Unique Constraints ──────────────────
        if ($eventSlug === 'ict-olympiad') {
            $rules['student_id'] = [
                'required',
                'string',
                'max:50',
                // একই ইভেন্টে যেন একই স্টুডেন্ট আইডি দ্বিতীয়বার রেজিস্টার্ড হতে না পারে
                Rule::unique('registrations', 'student_id')->where(function ($query) use ($event) {
                    return $query->where('event_id', $event->id);
                })
            ];
        } elseif ($eventSlug === 'iupc') {
            $rules = array_merge($rules, [
                'team_name'         => 'required|unique:registrations,team_name|max:255',
                'coach_name'        => 'required|string|max:255',
                'coach_email'       => 'required|email|max:255',
                'coach_phone'       => 'required|digits:11',
                'coach_designation' => 'required|string|max:255',
                'coach_tshirt'      => 'required|string',
                'm1_cf_handle'      => 'required|string|max:255',
                'm2_name'           => 'required|string|max:255',
                'm2_email'          => 'required|email|max:255',
                'm2_phone'          => 'required|digits:11',
                'm2_tshirt'         => 'required|string',
                'm2_prev_ex'        => 'required|string',
                'm2_cf_handle'      => 'required|string|max:255',
                'm3_name'           => 'nullable|string|max:255',
                'm3_email'          => 'nullable|email|max:255',
                'm3_phone'          => 'nullable|digits:11',
                'm3_tshirt'         => 'nullable|string',
                'm3_prev_ex'        => 'nullable|string',
                'm3_cf_handle'      => 'nullable|string|max:255',
            ]);
        } elseif (in_array($eventSlug, ['project-showcase', 'ai-hackathon'])) {
            $rules = array_merge($rules, [
                'team_name'     => 'required|unique:registrations,team_name|max:255',
                'team_person'   => 'required|string',
                'project_title' => $eventSlug === 'project-showcase' ? 'required|string|max:255' : 'nullable',
                'abstract_file' => $eventSlug === 'project-showcase' ? 'required|mimes:pdf|max:5120' : 'nullable', // Max 5MB
                'm2_name'       => 'required|string|max:255',
                'm2_email'      => 'required|email|max:255',
                'm2_phone'      => 'required|digits:11',
                'm2_tshirt'     => 'required|string',
                'm3_name'       => 'nullable|string|max:255',
                'm3_email'      => 'nullable|email|max:255',
                'm3_phone'      => 'nullable|digits:11',
                'm3_tshirt'     => 'nullable|string',
            ]);
        }

        // ভ্যালিডেশন রান করা
        $validatedData = $request->validate($rules);

        // ── 3. Registration DB save ───────────────────────────────────────────
        try {
            // "Others" ইউনিভার্সিটি হ্যান্ডেল করা
            if ($request->university_name === 'Others') {
                $validatedData['university_name'] = $request->other_university;
            }

            // ফাইল আপলোড হ্যান্ডেল করা
            if ($request->hasFile('abstract_file')) {
                $path = $request->file('abstract_file')->store('abstracts', 'public');
                $validatedData['abstract_file'] = $path;
            }

            // নিরাপদ Team ID জেনারেশন (৬ ডিজিটের র‍্যান্ডম নাম্বার ব্যবহার করা হয়েছে কোলিশন এড়াতে)
            $teamEvents = ['iupc', 'project-showcase', 'ai-hackathon'];
            if (in_array($eventSlug, $teamEvents)) {
                $prefix = strtoupper(substr(str_replace('-', '', $eventSlug), 0, 4));
                do {
                    $teamId = $prefix . '-' . rand(100000, 999999); // ৪ থেকে বাড়িয়ে ৬ ডিজিট করা হলো
                } while (Registration::where('team_id', $teamId)->exists());

                $validatedData['team_id'] = $teamId;
            }

            // অতিরিক্ত রিকোয়ার্ড ফিল্ডসমূহ
            $validatedData['status'] = 'pre-registered';

            // ডাটাবেজে ইনসার্ট (অনুমোদিত ফিল্ডগুলোর নিরাপদ এন্ট্রি)
            $registration = Registration::create($validatedData);
        } catch (Exception $e) {
            Log::error('Registration store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()
                ->with('error', 'রেজিস্ট্রেশন সম্পূর্ণ করা যায়নি। দয়া করে আবার চেষ্টা করুন বা অ্যাডমিনের সাথে যোগাযোগ করুন।')
                ->withInput();
        }

        // ── 4. Conditional redirect ───────────────────────────────────────────
        if (in_array($eventSlug, ['iupc', 'project-showcase', 'ai-hackathon'])) {
            $msg = 'Registration submitted successfully for ' . $event->name;
            if (!empty($registration->team_id)) {
                $msg .= '. Your Team ID: ' . $registration->team_id;
            }
            return redirect()->back()->with('success', $msg);
        }

        // ICT Olympiad বা অন্যান্য সিঙ্গেল ইভেন্টের জন্য পেমেন্টে পাঠানো
        return redirect()->route('payment.make', $registration->id);
    }

    // =========================================================================
    // RETRY PAYMENT — ICT / single events এর জন্য
    // =========================================================================
    public function retryPayment($registration_id)
    {
        $registration = Registration::with('event')->findOrFail($registration_id);

        if ($registration->payment_status === 'paid') {
            return redirect()->route('home')->with('info', 'আপনার পেমেন্ট অলরেডি সম্পন্ন হয়েছে।');
        }

        return redirect()->route('payment.make', $registration_id);
    }
}
