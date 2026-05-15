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


    public function exportIUPCTeams()
    {
        // ফাইলের নামে স্পষ্ট করে IUPC উল্লেখ করা হলো
        $fileName = 'IUPC_Registration_Slot_Entry_' . date('d_M_Y') . '.xlsx';

        return Excel::download(new TeamsExport, $fileName);
    }

    public function downloadExcel($eventId)
    {
        // ফাইলের নামে ইভেন্ট আইডি বা বর্তমান তারিখ যোগ করতে পারেন চেনার সুবিধার জন্য
        $fileName = 'result_entry_event_' . $eventId . '.xlsx';

        return Excel::download(new ResultTemplateExport($eventId), $fileName);
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



    public function dashboard(Request $request)
    {
        $events = Event::all();
        $selectedEventId = $request->get('event_id', $events->first()?->id);
        $selectedEvent = Event::find($selectedEventId);

        $query = Registration::where('event_id', $selectedEventId);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('university_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('team_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('m1_name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $teams = $query->latest()->paginate(20)->appends([
            'event_id' => $selectedEventId,
            'search' => $request->search
        ]);

        $stats = Registration::where('event_id', $selectedEventId)
            ->select('university_name', \DB::raw('count(*) as total'))
            ->groupBy('university_name')
            ->orderBy('total', 'desc')
            ->get();

        // রেজাল্ট টেবিল থেকে ডাটা আনা (event_name দিয়ে ফিল্টার)
        $results = \App\Models\Result::where('event_name', $selectedEvent->name)
            ->latest()
            ->get();

        $coupons = ($selectedEvent && $selectedEvent->slug == 'iupc')
            ? \App\Models\Coupon::where('event_id', $selectedEventId)->latest()->get()
            : collect();

        return view('admin.dashboard', compact('teams', 'stats', 'events', 'selectedEvent', 'coupons', 'results'));
    }
    // কুপন পাঠানো এবং ইমেইল হ্যান্ডলিং


    // প্রতিটি রেজিস্ট্রেশনের ফুল ডিটেইলস দেখার জন্য
    public function show($id)
    {
        $registration = Registration::with('event')->findOrFail($id);
        return view('admin.pre_reg_show', compact('registration'));
    }
    public function common_updateStatus(Request $request, $id)
    {
        $registration = Registration::with('event')->findOrFail($id);
        $newStatus = $request->status;

        // ১. ডাটা আপডেট অ্যারে
        $updateData = ['status' => $newStatus];

        // ২. ভেরিফাইড হলে পেমেন্ট এবং আইডি সেট করা
        if ($newStatus === 'verified') {
            $updateData['payment_status'] = 'paid';
            if (!$registration->participant_id) {
                // ইভেন্ট অনুযায়ী প্রিফিক্স সেট করা (যেমন: ICT-XXXX)
                $prefix = strtoupper(substr($registration->event->slug, 0, 3));
                $updateData['participant_id'] = $prefix . '-' . rand(1000, 9999);
            }
        }

        $registration->update($updateData);

        try {
            // ৩. মেইল পাঠানোর লজিক (Selected বা Verified হলে)
            if (in_array($newStatus, ['selected', 'verified'])) {
                // ডিফল্টভাবে m1_email এ মেইল যাবে
                Mail::to($registration->m1_email)->send(new ProjectSelectionMail($registration, $registration->event->slug));
                $message = "Status updated to {$newStatus} and notification sent.";
            } else {
                $message = "Status updated successfully.";
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Data updated but email failed: ' . $e->getMessage());
        }
    }

    // project and iupc
    public function updateStatus(Request $request, $id)
    {
        $registration = Registration::with('event')->findOrFail($id);
        $eventSlug = $registration->event->slug;
        $newStatus = $request->status;

        // আপডেট ডাটা অ্যারে
        $updateData = ['status' => $newStatus];

        // যদি ভেরিফাইড (পেইড) করা হয়
        if ($newStatus === 'verified') {
            $updateData['payment_status'] = 'paid';

            // যদি আগে থেকে আইডি না থাকে তবেই নতুন আইডি জেনারেট হবে
            if (!$registration->participant_id) {
                $updateData['participant_id'] = 'DUET-CSE-' . strtoupper(Str::random(4)) . '-' . rand(100, 999);
            }
        }

        $registration->update($updateData);

        try {
            // শুধুমাত্র selected অথবা verified হলে মেইল যাবে
            if (in_array($newStatus, ['selected', 'verified'])) {
                if ($eventSlug === 'iupc') {
                    // IUPC হলে কোচের ইমেইলে যাবে
                    Mail::to($registration->coach_email)->send(new ProjectSelectionMail($registration, 'iupc'));
                } else {
                    // Project Showcase হলে লিডারের ইমেইলে যাবে
                    Mail::to($registration->m1_email)->send(new ProjectSelectionMail($registration, 'project'));
                }
                $message = "Status updated to {$newStatus}, Payment set to Paid & ID Generated.";
            } else {
                $message = "Status updated to {$newStatus}.";
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Update successful, but email failed: ' . $e->getMessage());
        }
    }

    /**
     * AI Hackathon Update
     */
    public function ai_updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,selected,verified,rejected',
        ]);

        $registration = Registration::findOrFail($id);
        $newStatus = $request->status;

        $updateData = ['status' => $newStatus];

        // ভেরিফাইড হলে পেমেন্ট স্ট্যাটাস এবং আইডি জেনারেশন
        if ($newStatus === 'verified') {
            $updateData['payment_status'] = 'paid';
            if (!$registration->participant_id) {
                $updateData['participant_id'] = 'AI-HACK-' . rand(1000, 9999);
            }
        }

        $registration->update($updateData);

        // মেইল পাঠানোর লজিক (Selected, Verified বা Rejected হলে)
        if (in_array($newStatus, ['selected', 'verified', 'rejected'])) {
            $emails = array_filter([
                $registration->m1_email,
                $registration->m2_email,
                $registration->m3_email
            ]);

            if (!empty($emails)) {
                try {
                    Mail::to($emails)->send(new AIRegistrationStatusMail($registration, $newStatus));
                } catch (\Exception $e) {
                    return back()->with('success', 'Status & Payment updated, but email failed.');
                }
            }
        }

        return back()->with('success', 'Status updated to ' . $newStatus . ' and Participant ID assigned.');
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


    /**
     * একাধিক রেজিস্ট্রেশন একসাথে ডিলিট করার জন্য (Bulk Delete)
     */
    public function bulkDeleteRegistrations(Request $request)
    {
        // ব্লেড ফাইল থেকে পাঠানো 'ids' অ্যারে চেক করা
        $ids = $request->input('ids');

        if (!$ids || count($ids) == 0) {
            return back()->with('error', 'কোন রেকর্ড সিলেক্ট করা হয়নি।');
        }

        try {
            // একসাথে সব সিলেক্টেড আইডি ডিলিট করা
            Registration::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' টি রেজিস্ট্রেশন সফলভাবে ডিলিট হয়েছে।');
        } catch (\Exception $e) {
            return back()->with('error', 'ডিলিট করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * ইমপোর্ট করা সিঙ্গেল রেজাল্ট ডিলিট করার জন্য
     */
    public function deleteResult($id)
    {
        try {
            // রেজাল্ট মডেল থেকে আইডি খুঁজে ডিলিট করা
            // দ্রষ্টব্য: আপনার রেজাল্ট মডেলের নাম EventResult না হলে সেটি পরিবর্তন করুন
            $result = \App\Models\Result::findOrFail($id);
            $result->delete();

            return back()->with('success', 'রেজাল্টটি সফলভাবে রিমুভ করা হয়েছে।');
        } catch (\Exception $e) {
            return back()->with('error', 'রেজাল্ট ডিলিট করা যায়নি।');
        }
    }
}
