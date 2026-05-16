<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;

class HomeController extends Controller
{
    /**
     * হোম পেজ ভিউ
     */
    public function index()
    {
        return view('users.home');
    }

    /**
     * ড্যাশবোর্ড ভিউ
     */
    public function dashboard()
    {
        return view('users.home');
    }

    /**
     * গ্যালারি পেজ
     */
    public function gallery()
    {
        return view('users.gallery');
    }

    /**
     * কন্টাক্ট পেজ
     */
    public function contact()
    {
        return view('users.contact');
    }

    /**
     * আমাদের সম্পর্কে (About) পেজ
     */
    public function about()
    {
        return view('users.about');
    }

    /**
     * শিডিউল (Schedule) পেজ
     */
    public function schedule()
    {
        return view('users.schedule');
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
