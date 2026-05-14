<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\UniversitySlot;
use App\Models\Result;
use App\Models\Registration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // ফাইল এর উপরে এটি ইমপোর্ট করুন

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('admin.event_management', compact('events'));
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'slug' => 'required|string|unique:events,slug',
    //         'reg_fee' => 'required|numeric',
    //         'min_members' => 'required|integer',
    //         'max_members' => 'required|integer',
    //         'end_date' => 'required|date',
    //         'description' => 'nullable|string',
    //         'rules' => 'nullable|string',
    //         'result' => 'nullable|string',
    //         'seatplan' => 'nullable|string',
    //         'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // প্রতিটা ইমেজের ভ্যালিডেশন
    //     ]);

    //     // একাধিক ইমেজ হ্যান্ডেল করা
    //     $imagePaths = [];
    //     if ($request->hasFile('images')) {
    //         foreach ($request->file('images') as $image) {
    //             // public/event_images ফোল্ডারে সেভ হবে
    //             $path = $image->store('event_images', 'public');
    //             $imagePaths[] = $path;
    //         }
    //     }

    //     Event::create([
    //         'name' => $request->name,
    //         // Slug স্ট্রিং ক্লিন করার জন্য Str::slug ব্যবহার করা ভালো, তবে আপনার লজিকও ঠিক আছে
    //         'slug' => strtolower(str_replace(' ', '-', $request->slug)),
    //         'reg_fee' => $request->reg_fee,
    //         'min_members' => $request->min_members,
    //         'max_members' => $request->max_members,
    //         'description' => $request->description,
    //         'rules' => $request->rules,
    //         'result' => $request->result,
    //         'seatplan' => $request->seatplan,
    //         'images' => $imagePaths, // মডেলে $casts থাকলে এটি অটো JSON হয়ে যাবে
    //         'needs_coach' => $request->has('needs_coach'),
    //         'start_date' => now(),
    //         'end_date' => $request->end_date,
    //         'is_active' => true,
    //     ]);

    //     return redirect()->back()->with('success', 'New event segment added successfully!');
    // }


    public function edit($id)
    {
        // আইডি অনুযায়ী ইভেন্টটি খুঁজে বের করা, না পেলে ৪-০-৪ এরর দিবে
        $event = Event::findOrFail($id);

        // এডিট পেজ বা ভিউতে ইভেন্ট ডাটা পাঠানো
        return view('admin.event_edit', compact('event'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:events,slug',
            'reg_fee' => 'required|numeric',
            'min_members' => 'required|integer',
            'max_members' => 'required|integer',
            'end_date' => 'required|date',
            'description' => 'nullable|string',
            'rules' => 'nullable|string',
            'result' => 'nullable|string',
            'seatplan' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // প্রতিটা ইমেজের ভ্যালিডেশন
        ]);

        // একাধিক ইমেজ হ্যান্ডেল করা
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // public/event_images ফোল্ডারে সেভ হবে
                $path = $image->store('event_images', 'public');
                $imagePaths[] = $path;
            }
        }

        Event::create([
            'name' => $request->name,
            // Slug স্ট্রিং ক্লিন করার জন্য Str::slug ব্যবহার করা ভালো, তবে আপনার লজিকও ঠিক আছে
            'slug' => strtolower(str_replace(' ', '-', $request->slug)),
            'reg_fee' => $request->reg_fee,
            'min_members' => $request->min_members,
            'max_members' => $request->max_members,
            'description' => $request->description,
            'rules' => $request->rules,
            'result' => $request->result,
            'seatplan' => $request->seatplan,
            'images' => $imagePaths, // মডেলে $casts থাকলে এটি অটো JSON হয়ে যাবে
            'needs_coach' => $request->has('needs_coach'),
            'start_date' => now(),
            'end_date' => $request->end_date,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'New event segment added successfully!');
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:events,slug,' . $id,
            'reg_fee' => 'required|numeric',
            'min_members' => 'required|integer',
            'max_members' => 'required|integer',
            'end_date' => 'required|date',
            'description' => 'nullable|string',
            'rules' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // ইমেজ হ্যান্ডেলিং
        $imagePaths = $event->images; // আগের ইমেজগুলো ডিফল্ট রাখা হলো

        if ($request->hasFile('images')) {
            // ১. আগের ইমেজগুলো স্টোরেজ থেকে ডিলিট করা
            if (!empty($event->images)) {
                foreach ($event->images as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // ২. নতুন ইমেজগুলো সেভ করা
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('event_images', 'public');
            }
        }

        $event->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'reg_fee' => $request->reg_fee,
            'min_members' => $request->min_members,
            'max_members' => $request->max_members,
            'description' => $request->description,
            'rules' => $request->rules,
            'result' => $request->result,
            'seatplan' => $request->seatplan,
            'images' => $imagePaths,
            'needs_coach' => $request->has('needs_coach'),
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // ১. সার্ভার থেকে ইমেজ ডিলিট করা
        if (!empty($event->images)) {
            foreach ($event->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // ২. ডাটাবেস রেকর্ড ডিলিট করা
        $event->delete();

        return redirect()->back()->with('success', 'Event segment deleted successfully!');
    }

    //  USer Even Controlelr 
    // public function showDashboard($slug)
    // {
    //     $event = Event::where('slug', $slug)->firstOrFail();

    //     // ইভেন্ট আইডি অনুযায়ী আলাদা আলাদা কাউন্ট
    //     $counts = [
    //         'pre-registered'  => Registration::where('event_id', $event->id)->where('status', 'pre-registered')->count(),
    //         'selected' => Registration::where('event_id', $event->id)->where('status', 'selected')->count(),
    //         'verified' => Registration::where('event_id', $event->id)->where('status', 'verified')->count(),
    //         'institutes' => Registration::where('event_id', $event->id)
    //             ->whereNotNull('university_name')
    //             ->count(),
    //     ];

    //     $totalRegistered = Registration::where('event_id', $event->id)
    //         ->whereIn('status', ['pre-registered', 'selected', 'verified'])
    //         ->count();
    //     return view('users.events.dashboard', compact('event', 'counts', 'totalRegistered'));
    // }

    // public function showDashboard($slug)
    // {
    //     $event = Event::where('slug', $slug)->firstOrFail();

    //     // JSON থেকে ইমেজ অ্যারে ডিকোড করা (যদি ডাটাবেসে JSON আকারে থাকে)
    //     $judges = json_decode($event->judges, true) ?? [];

    //     $counts = [
    //         'pre-registered' => Registration::where('event_id', $event->id)->where('status', 'pre-registered')->count(),
    //         'selected'       => Registration::where('event_id', $event->id)->where('status', 'selected')->count(),
    //         'verified'       => Registration::where('event_id', $event->id)->where('status', 'verified')->count(),
    //         'institutes'     => Registration::where('event_id', $event->id)->whereNotNull('university_name')->distinct('university_name')->count(),
    //     ];

    //     $totalRegistered = Registration::where('event_id', $event->id)->whereIn('status', ['pre-registered', 'selected', 'verified'])->count();

    //     return view('users.events.dashboard', compact('event', 'counts', 'totalRegistered', 'judges'));
    // }

    public function showDashboard($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        // বর্তমান লগইন করা ইউজারের এই ইভেন্টের রেজিস্ট্রেশন ডাটা খুঁজে বের করা
        $team = Registration::where('event_id', $event->id)
            ->first();

        $judges = json_decode($event->judges, true) ?? [];

        $counts = [
            'pre-registered' => Registration::where('event_id', $event->id)->where('status', 'pre-registered')->count(),
            'selected'       => Registration::where('event_id', $event->id)->where('status', 'selected')->count(),
            'verified'       => Registration::where('event_id', $event->id)->where('status', 'verified')->count(),
            'institutes'     => Registration::where('event_id', $event->id)->whereNotNull('university_name')->distinct('university_name')->count(),
        ];

        $totalRegistered = Registration::where('event_id', $event->id)->whereIn('status', ['pre-registered', 'selected', 'verified'])->count();

        // compact-এ 'team' ভেরিয়েবলটি যুক্ত করুন
        return view('users.events.dashboard', compact('event', 'counts', 'totalRegistered', 'judges', 'team'));
    }

    public function showFinalRegForm($team_id)
    {
        // টিম এবং ইভেন্ট ডাটা লোড করা
        $team = Registration::with('event')->findOrFail($team_id);

        // ইভেন্টের রেগুলার ফি
        $finalAmount = $team->event->reg_fee;

        return view('users.events.final_reg_form', compact('team', 'finalAmount'));
    }
    /**
     * Pre-registered টিমের লিস্ট (সার্চ সুবিধাসহ)
     */

    /**
     * Pre-registered Teams: যাদের স্ট্যাটাস 'pending'
     */
    public function preRegistered(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $query = Registration::where('event_id', $event->id)
            ->where('status', 'pre-registered');

        $this->applySearch($query, $request);

        $teams = $query->latest()->paginate(20);

        return view('users.events.pre_reg', compact('event', 'teams'));
    }

    /**
     * Selected Teams: যাদের স্ট্যাটাস 'selected'
     */
    public function selectedTeams(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $query = Registration::where('event_id', $event->id)
            ->where('status', 'selected');

        $this->applySearch($query, $request);

        $teams = $query->latest()->paginate(20);

        return view('users.events.selected', compact('event', 'teams'));
    }

    /**
     * Final Registered: যাদের স্ট্যাটাস 'verified' (পেমেন্ট সম্পন্ন)
     */
    /**
     * Final Registered: যাদের স্ট্যাটাস 'verified' (পেমেন্ট সম্পন্ন)
     */
    public function finalRegistered(Request $request, $slug)
    {
        // ১. ইভেন্ট খুঁজে বের করা
        $event = Event::where('slug', $slug)->firstOrFail();

        // ২. কুয়েরি বিল্ডার শুরু করা (শুধুমাত্র পেইড মেম্বারদের জন্য)
        $query = Registration::where('event_id', $event->id)
            ->where('payment_status', 'paid');

        // ৩. শুধুমাত্র সার্চ ইনপুট থাকলে এবং সেটি আইডি ভিত্তিক হলে ডাটা প্রসেস করা
        if ($request->filled('search')) {
            // শুধুমাত্র আইডি ভিত্তিক সার্চ অ্যাপ্লাই করা
            $this->applyIdOnlySearch($query, $request);

            $teams = $query->latest()->paginate(20);
        } else {
            // সার্চ না করলে খালি কালেকশন পাঠানো
            $teams = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }

        // ৪. কাউন্ট ডেটা
        $counts = [
            'pending'  => Registration::where('event_id', $event->id)->where('status', 'pending')->count(),
            'selected' => Registration::where('event_id', $event->id)->where('status', 'selected')->count(),
            'verified' => Registration::where('event_id', $event->id)->where('status', 'verified')->count(),
        ];

        return view('users.events.final_reg', compact('event', 'teams', 'counts'));
    }

    /**
     * শুধুমাত্র ID ভিত্তিক সার্চের জন্য নতুন মেথড
     */
    private function applyIdOnlySearch($query, $request)
    {
        $searchTerm = $request->search;

        // #00005 বা 00005 থেকে মূল সংখ্যা (5) বের করা
        $numericSearch = ltrim($searchTerm, '#');
        $numericSearch = ltrim($numericSearch, '0');

        $query->where(function ($q) use ($searchTerm, $numericSearch) {
            $q->where('id', $numericSearch) // ডাটাবেজ প্রাইমারি আইডি (Registration ID)
                ->orWhere('team_id', $searchTerm) // ইউনিক টিম আইডি (Exact Match)
                ->orWhere('participant_id', 'LIKE', "%{$searchTerm}%") // যদি আলাদা রেজিস্ট্রেশন আইডি কলাম থাকে
                ->orWhere('team_name', 'LIKE', "%{$searchTerm}%") // যদি আলাদা রেজিস্ট্রেশন আইডি কলাম থাকে
                ->orWhere('student_id', 'LIKE', "%{$searchTerm}%"); // যদি আলাদা রেজিস্ট্রেশন আইডি কলাম থাকে
        });
    }

    /**
     * সাধারণ সার্চ (নাম, ভার্সিটি ইত্যাদি) যা অন্য মেথডে ব্যবহার হবে
     */
    private function applySearch($query, $request)
    {
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('m1_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('university_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('student_id', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('team_id', 'LIKE', "%{$searchTerm}%");
            });
        }
    }
    // public function finalRegistered(Request $request, $slug)
    // {
    //     // ১. ইভেন্ট খুঁজে বের করা
    //     $event = Event::where('slug', $slug)->firstOrFail();

    //     // ২. শুধুমাত্র 'verified' এবং 'paid' স্ট্যাটাস চেক করা (নিরাপত্তার জন্য)
    //     // Controller এর মূল কুয়েরি এমন হওয়া উচিত
    //     $query = Registration::where('event_id', $event->id)->where('payment_status', 'paid');
    //     $this->applySearch($query, $request);
    //     $teams = $query->paginate(10);

    //     // ৩. সার্চ ফিল্টার অ্যাপ্লাই করা
    //     $this->applySearch($query, $request);

    //     // ৪. লেটেস্ট ডেটা আগে দেখানো এবং প্যাজিনেশন
    //     $teams = $query->latest()->paginate(20);

    //     // ৫. কাউন্ট পাঠানো (যদি ব্লেডে ট্যাব মেনু আন-কমেন্ট করেন তবে এটি লাগবে)
    //     $counts = [
    //         'pending'  => Registration::where('event_id', $event->id)->where('status', 'pending')->count(),
    //         'selected' => Registration::where('event_id', $event->id)->where('status', 'selected')->count(),
    //         'verified' => Registration::where('event_id', $event->id)->where('status', 'verified')->count(),
    //     ];

    //     return view('users.events.final_reg', compact('event', 'teams', 'counts'));
    // }

    // private function applySearch($query, $request)
    // {
    //     if ($request->filled('search')) {
    //         $searchTerm = $request->search;
    //         $query->where(function ($q) use ($searchTerm) {
    //             $q->where('team_name', 'LIKE', "%{$searchTerm}%")
    //                 ->orWhere('team_id', 'LIKE', "%{$searchTerm}%") // team_id যোগ করা হয়েছে
    //                 ->orWhere('university_name', 'LIKE', "%{$searchTerm}%")
    //                 ->orWhere('m1_name', 'LIKE', "%{$searchTerm}%")
    //                 ->orWhere('student_id', 'LIKE', "%{$searchTerm}%");
    //         });
    //     }
    // }
    public function slot_list($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        // ক্যাটাগরি অনুযায়ী ডাটা গ্রুপ করা হচ্ছে
        $universitySlots = UniversitySlot::orderBy('category')
            ->orderBy('university_name')
            ->get()
            ->groupBy('category');

        return view('users.events.slot_list', compact('event', 'universitySlots'));
    }

    public function schedule($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        // যদি শিডিউল আলাদা টেবিলে থাকে তবে তা রিলেশন দিয়ে নিয়ে আসতে পারেন
        // উদাহরণ: $schedules = $event->schedules;

        return view('users.events.schedule', compact('event'));
    }

    public function judges($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        // যদি images কলামে JSON ডেটা থাকে, তবে তা ডিকোড করা
        $judges = is_string($event->images) ? json_decode($event->images, true) : $event->images;

        return view('users.events.judges', compact('event', 'judges'));
    }


    public function institutes($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        // ইউনিভার্সিটি অনুযায়ী গ্রুপ করে কাউন্ট করা
        $institutes = DB::table('registrations') // আপনার টেবিল নাম
            ->where('event_id', $event->id)
            ->select(
                'university_name',
                DB::raw('count(*) as total_registrations'),
                DB::raw('count(DISTINCT team_id) as total_teams')
            )
            ->groupBy('university_name')
            ->orderBy('total_registrations', 'desc')
            ->get();

        return view('users.events.institutes_report', compact('event', 'institutes'));
    }


    // public function downloadAdmitCard($slug, $team_id)
    // {
    //     $event = Event::where('slug', $slug)->firstOrFail();
    //     $team = Registration::where('team_id', $team_id)
    //         ->where('event_id', $event->id)
    //         ->where('status', 'verified')
    //         ->firstOrFail();

    //     return view('users.events.admit_card', compact('event', 'team'));
    // }

    /**
     * ICT Olympiad Admit Card Download
     */


    /**
     * Universal Admit Card Download Logic
     */
    public function downloadAdmitCard($slug, $id)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        // ভেরিফাইড রেজিস্ট্রেশন চেক
        $team = Registration::where('event_id', $event->id)
            ->where('id', $id)
            ->where('status', 'verified')
            ->firstOrFail();

        // ইভেন্ট অনুযায়ী আলাদা ভিউ রিটার্ন
        $teamEvents = ['iupc', 'ai-hackathon', 'project-showcase'];

        if (in_array($event->slug, $teamEvents)) {
            // টিম ভিত্তিক ইভেন্টের জন্য
            return view('users.events.admit_card_team', compact('event', 'team'));
        } else {
            // ICT Olympiad এবং অন্যান্য ইভেন্টের জন্য
            return view('users.events.admit_card_single', compact('event', 'team'));
        }
    }



    public function checkResult(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|string'
        ]);

        $result = Result::where('participant_id', $request->participant_id)->first();

        if (!$result) {
            return back()->with('not_found', 'দুঃখিত, এই আইডির কোনো রেজাল্ট পাওয়া যায়নি।');
        }

        return view('results.index', compact('result'));
    }
}
