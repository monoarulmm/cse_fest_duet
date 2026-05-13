<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'        => 'required|string|max:255',
            'logo'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon'          => 'nullable|image|mimes:png,ico,svg,jpg|max:1024',
            'main_banner1'      => 'nullable|image|max:5120', // ব্লেড অনুযায়ী নাম ঠিক করা হয়েছে
            'main_banner2'     => 'nullable|image|max:5120',
            'main_banner3'     => 'nullable|image|max:5120',
            'sponsor_banner.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10048',
        ]);

        $setting = Setting::first() ?? new Setting();

        // টেক্সট ফিল্ড আপডেট
        $setting->site_name = $request->site_name;
        $setting->email = $request->email;
        $setting->phone_primary = $request->phone_primary;
        $setting->phone_secondary = $request->phone_secondary;
        $setting->address = $request->address;
        $setting->fb_link = $request->fb_link;
        $setting->youtube_link = $request->youtube_link;
        $setting->whatsapp_link = $request->whatsapp_link;

        // ১. সিঙ্গেল ইমেজ হ্যান্ডলিং
        // কারেকশন: ',main_banner2' এর আগের কমা সরানো হয়েছে এবং main_banner1 কে main_banner করা হয়েছে
        $singleImages = ['logo', 'favicon', 'main_banner1', 'main_banner2', 'main_banner3'];

        foreach ($singleImages as $img) {
            if ($request->hasFile($img)) {
                // আগের ফাইল থাকলে ডিলিট করা
                if ($setting->$img) {
                    Storage::disk('public')->delete($setting->$img);
                }
                $path = $request->file($img)->store('settings', 'public');
                $setting->$img = $path;
            }
        }

        // ২. মাল্টিপল স্পন্সর ব্যানার হ্যান্ডলিং
        // কারেকশন: মডেলে যেহেতু $casts['array'] আছে, তাই json_decode করার দরকার নেই
        $sponsorBanners = $setting->sponsor_banner ?? [];

        // নির্দিষ্ট স্পন্সর রিমুভ করা
        if ($request->has('remove_sponsors')) {
            foreach ($request->remove_sponsors as $index) {
                if (isset($sponsorBanners[$index])) {
                    Storage::disk('public')->delete($sponsorBanners[$index]);
                    unset($sponsorBanners[$index]);
                }
            }
            $sponsorBanners = array_values($sponsorBanners); // ইনডেক্স রিসেট
        }

        // নতুন স্পন্সর যোগ করা
        if ($request->hasFile('sponsor_banner')) {
            foreach ($request->file('sponsor_banner') as $file) {
                $path = $file->store('settings', 'public');
                $sponsorBanners[] = $path;
            }
        }

        // কারেকশন: মডেলে $casts থাকলে সরাসরি অ্যারে অ্যাসাইন করা যায়, json_encode লাগে না
        $setting->sponsor_banner = $sponsorBanners;

        $setting->save();

        return redirect()->back()->with('success', 'Settings Updated Successfully!');
    }
}
