<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration; // আপনার কমন মডেল
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\CouponCodeMail;
use App\Mail\ProjectSelectionMail;
use App\Mail\AIHackathonContestLinkMai;
use App\Mail\AIRegistrationStatusMail;
use App\Exports\RegistrationExport;
use App\Exports\TeamsExport;
use App\Exports\ResultTemplateExport;
use App\Imports\ResultsImport;
use App\Imports\CouponImport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\Imports\UniversitySlotImport;

class AdminController extends Controller
{


    public function downloadExcel()
    {
        return Excel::download(new TeamsExport, 'teams_slot_entry.xlsx');
    }

    // app/Http/Controllers/Admin/CouponController.php
    public function import(Request $request, $eventId)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new CouponImport($eventId), $request->file('excel_file'));

        return back()->with('success', 'Coupons generated and emails sent successfully!');
    }

    public function downloadResultTemplate($eventId)
    {
        $fileName = 'result_upload_sheet_event_' . $eventId . '.xlsx';
        return Excel::download(new ResultTemplateExport($eventId), $fileName);
    }
    public function exportExcel(Request $request)
    {
        $eventId = $request->get('event_id');
        $event = Event::findOrFail($eventId);

        $fileName = Str::slug($event->name) . '_registrations_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new RegistrationExport($eventId), $fileName);
    }

    // public function dashboard(Request $request)
    // {
    //     // ১. সব ইভেন্ট নিয়ে আসা (ট্যাব দেখানোর জন্য)
    //     $events = Event::all();

    //     // ২. বর্তমানে কোন ইভেন্ট দেখা হচ্ছে সেটি নির্ধারণ করা (ডিফল্ট প্রথমটি)
    //     $selectedEventId = $request->get('event_id', $events->first()?->id);
    //     $selectedEvent = Event::find($selectedEventId);

    //     // ৩. কুয়েরি শুরু করা (শুধুমাত্র সিলেক্টেড ইভেন্টের ডাটা)
    //     $query = Registration::where('event_id', $selectedEventId);

    //     // ৪. সার্চ ফিল্টার (টিম নাম, ইউনিভার্সিটি বা লিডারের নাম অনুযায়ী)
    //     if ($request->filled('search')) {
    //         $searchTerm = $request->search;
    //         $query->where(function ($q) use ($searchTerm) {
    //             $q->where('university_name', 'LIKE', '%' . $searchTerm . '%')
    //                 ->orWhere('team_name', 'LIKE', '%' . $searchTerm . '%')
    //                 ->orWhere('m1_name', 'LIKE', '%' . $searchTerm . '%');
    //         });
    //     }

    //     // ৫. ডাটা নিয়ে আসা (Pagination সহ)
    //     $teams = $query->latest()->paginate(20)->appends(['event_id' => $selectedEventId, 'search' => $request->search]);

    //     // ৬. স্ট্যাটিস্টিকস (সিলেক্টেড ইভেন্টের জন্য কোন ইউনিভার্সিটির কয়টি রেজিস্ট্রেশন)
    //     $stats = Registration::where('event_id', $selectedEventId)
    //         ->select('university_name', DB::raw('count(*) as total'))
    //         ->groupBy('university_name')
    //         ->orderBy('total', 'desc')
    //         ->get();

    //     return view('admin.dashboard', compact('teams', 'stats', 'events', 'selectedEvent'));
    // }


    public function dashboard(Request $request)
    {
        // ১. সব ইভেন্ট নিয়ে আসা (ট্যাব দেখানোর জন্য)
        $events = Event::all();

        // ২. বর্তমানে কোন ইভেন্ট দেখা হচ্ছে সেটি নির্ধারণ করা
        $selectedEventId = $request->get('event_id', $events->first()?->id);
        $selectedEvent = Event::find($selectedEventId);

        // ৩. কুয়েরি শুরু করা (শুধুমাত্র সিলেক্টেড ইভেন্টের ডাটা)
        $query = Registration::where('event_id', $selectedEventId);

        // ৪. সার্চ ফিল্টার
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('university_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('team_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('m1_name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // ৫. ডাটা নিয়ে আসা (Pagination সহ)
        $teams = $query->latest()->paginate(20)->appends([
            'event_id' => $selectedEventId,
            'search' => $request->search
        ]);

        // ৬. স্ট্যাটিস্টিকস
        $stats = Registration::where('event_id', $selectedEventId)
            ->select('university_name', \DB::raw('count(*) as total'))
            ->groupBy('university_name')
            ->orderBy('total', 'desc')
            ->get();

        // --- নতুন অংশ: কুপন ডাটা হ্যান্ডলিং ---
        // যদি সিলেক্টেড ইভেন্ট IUPC হয়, তবে কুপন নিয়ে আসবে, নাহলে খালি কালেকশন পাঠাবে
        $coupons = ($selectedEvent && $selectedEvent->slug == 'iupc')
            ? \App\Models\Coupon::where('event_id', $selectedEventId)->latest()->get()
            : collect();

        return view('admin.dashboard', compact('teams', 'stats', 'events', 'selectedEvent', 'coupons'));
    }
    // কুপন পাঠানো এবং ইমেইল হ্যান্ডলিং
    public function sendCoupon($id)
    {
        // ১. ডাটা খুঁজে বের করা এবং সাথে ইভেন্ট রিলেশন লোড করা
        $team = Registration::with('event')->findOrFail($id);

        // ২. কুপন চেক
        if ($team->coupon_code) {
            return back()->with('error', 'Coupon already sent to this team!');
        }

        // ৩. কুপন জেনারেট
        $coupon = strtoupper(Str::random(8));

        // ৪. আপডেট (Status & Coupon)
        $team->update([
            'coupon_code' => $coupon,
            'status' => 'selected'
        ]);

        try {
            // ৫. ইমেইল লজিক: IUPC হলে কোচ, নয়তো লিডার (m1)
            $recipientEmail = ($team->event->slug === 'iupc')
                ? $team->coach_email
                : $team->m1_email;

            if ($recipientEmail) {
                Mail::to($recipientEmail)->send(new CouponCodeMail($team));
                return back()->with('success', 'Confirmation & Coupon sent to ' . $recipientEmail);
            }

            return back()->with('warning', 'Data updated, but no email address found.');
        } catch (\Exception $e) {
            return back()->with('error', 'Database updated but email failed: ' . $e->getMessage());
        }
    }

    // প্রতিটি রেজিস্ট্রেশনের ফুল ডিটেইলস দেখার জন্য
    public function show($id)
    {
        $registration = Registration::with('event')->findOrFail($id);
        return view('admin.pre_reg_show', compact('registration'));
    }




    public function updateStatus(Request $request, $id)
    {
        $registration = Registration::with('event')->findOrFail($id);

        // ১. প্রজেক্ট শোকেসের জন্য সিলেকশন লজিক
        if ($registration->event->slug === 'project-showcase') {
            $registration->update([
                'status' => 'selected' // অ্যাডমিন ম্যানুয়ালি সিলেক্ট করল
            ]);

            try {
                // প্রজেক্ট সিলেকশনের মেইল পাঠানো
                Mail::to($registration->m1_email)->send(new ProjectSelectionMail($registration));
                return back()->with('success', 'Project selected and email sent to team leader.');
            } catch (\Exception $e) {
                return back()->with('error', 'Status updated but email failed.');
            }
        }

        return back()->with('error', 'Invalid action for this event.');
    }

    // ai hackton
    // ১. সবাইকে একসাথে লিঙ্ক পাঠানোর মেথড
    public function sendBulkContestLink(Request $request, $eventId)
    {
        $request->validate([
            'contest_link' => 'required|url'
        ]);

        // ওই ইভেন্টের সব রেজিস্ট্রেশন যারা এখনো রিজেক্টেড না
        $registrations = Registration::where('event_id', $eventId)
            ->where('status', '!=', 'rejected')
            ->get();

        $link = $request->contest_link;

        try {
            foreach ($registrations as $reg) {
                // সকল মেম্বারের ইমেইল একটি অ্যারেতে নেওয়া হচ্ছে
                $emails = array_filter([
                    $reg->m1_email,
                    $reg->m2_email, // মেম্বার ২ এর ইমেইল
                    $reg->m3_email  // মেম্বার ৩ এর ইমেইল
                ]);

                // যদি ইমেইল অ্যারেটি খালি না হয়, তবে মেইল পাঠানো হবে
                if (!empty($emails)) {
                    Mail::to($emails)->send(new AIHackathonContestLinkMai($reg, $link));
                }
            }


            return back()->with('success', 'Contest link sent to all participants!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send some emails. Please check configuration.');
        }
    }

    // ২. স্ট্যাটাস আপডেট করার মেথড (এখন আর মেইল যাবে না, শুধু স্ট্যাটাস চেঞ্জ হবে)
    public function ai_updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,selected,verified,rejected',
        ]);

        $registration = Registration::findOrFail($id);
        $registration->update(['status' => $request->status]);

        // শুধুমাত্র 'selected' অথবা 'rejected' হলে ইমেইল পাঠানো হবে
        if (in_array($request->status, ['selected', 'rejected'])) {

            // সকল মেম্বারের ইমেইল সংগ্রহ (ফাঁকা ইমেইলগুলো বাদ দিয়ে)
            $emails = array_filter([
                $registration->m1_email,
                $registration->m2_email,
                $registration->m3_email
            ]);

            if (!empty($emails)) {
                try {
                    // RegistrationStatusMail ক্লাসে রেজিস্ট্রেশন অবজেক্ট এবং নতুন স্ট্যাটাস পাঠানো হচ্ছে
                    Mail::to($emails)->send(new AIRegistrationStatusMail($registration, $request->status));
                } catch (\Exception $e) {
                    // মেইল না গেলেও স্ট্যাটাস আপডেট যেন সাকসেস দেখায়, তাই এররটি ইগনোর করা বা লগ করা যেতে পারে
                    return back()->with('success', 'Status updated, but email could not be sent.');
                }
            }
        }

        return back()->with('success', 'Status updated successfully to ' . $request->status);
    }









    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ResultsImport, $request->file('excel_file'));
            return back()->with('success', 'সব রেজাল্ট সফলভাবে আপলোড হয়েছে!');
        } catch (\Exception $e) {
            // এরর চেক করার জন্য সাময়িকভাবে নিচের লাইনটি ব্যবহার করতে পারেন:
            // return back()->with('error', $e->getMessage()); 
            return back()->with('error', 'ফাইলে সমস্যা আছে। আবার চেষ্টা করুন।');
        }
    }


    public function upload_slots(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new UniversitySlotImport, $request->file('file'));

        return back()->with('success', 'University slots uploaded successfully!');
    }
}
