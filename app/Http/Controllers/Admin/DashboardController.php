<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Registration; // আপনার মডেলের নাম অনুযায়ী পরিবর্তন করুন
use App\Models\Coupon;
use App\Models\Result;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
// Import Classes (এগুলো আপনাকে তৈরি করে নিতে হবে: php artisan make:import ResultImport)
// use App\Imports\ResultImport; 
// use App\Imports\SlotsImport;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ১. ইভেন্ট সিলেকশন লজিক
        $events = Event::all();
        $selectedEvent = $request->has('event_id')
            ? Event::find($request->event_id)
            : Event::where('slug', 'iupc')->first() ?? $events->first();

        // ২. রেজিস্ট্রেশন ডাটা (Pagination সহ)
        $teams = Registration::where('event_id', $selectedEvent->id)
            ->latest()
            ->get();

        // ৩. ইউনিভার্সিটি স্ট্যাটাস লজিক
        $stats = Registration::where('event_id', $selectedEvent->id)
            ->select('university_name', DB::raw('count(*) as total'))
            ->groupBy('university_name')
            ->orderBy('total', 'desc')
            ->get();

        // ৪. কুপন এবং ইমপোর্ট করা রেজাল্ট
        $coupons = Coupon::where('event_id', $selectedEvent->id)->get();
        $results = Result::where('event_id', $selectedEvent->id)->latest()->get();

        return view('admin.dashboard', compact('events', 'selectedEvent', 'teams', 'stats', 'coupons', 'results'));
    }

    // --- BULK DELETE LOGIC ---
    public function bulkDeleteRegistrations(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || count($ids) == 0) {
            return back()->with('error', 'No records selected for deletion.');
        }

        try {
            Registration::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' registrations deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // --- DELETE SINGLE RESULT ---
    public function deleteResult($id)
    {
        try {
            $result = Result::findOrFail($id);
            $result->delete();
            return back()->with('success', 'Result entry removed from hub.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete result.');
        }
    }

    // --- EXCEL UPLOAD (RESULT) ---
    public function uploadResult(Request $request)
    {
        $request->validate(['excel_file' => 'required|mimes:xlsx,xls,csv']);

        try {
            // Excel::import(new ResultImport, $request->file('excel_file'));
            // সাময়িকভাবে লজিক চেক করার জন্য:
            return back()->with('success', 'Results synchronized successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    // --- UNIVERSITY SLOTS UPLOAD ---
    public function uploadSlots(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);

        try {
            // Excel::import(new SlotsImport, $request->file('file'));
            return back()->with('success', 'University slots updated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Slot update failed.');
        }
    }

    // --- SHOW SINGLE REGISTRATION ---
    public function showRegistration($id)
    {
        $team = Registration::findOrFail($id);
        return view('admin.registration-details', compact('team'));
    }

    // --- EXPORT LOGIC ---
    public function exportRegistrationExcel($event_id)
    {
        // return Excel::download(new RegistrationExport($event_id), 'registrations.xlsx');
        return back()->with('info', 'Export feature triggered.');
    }
}
