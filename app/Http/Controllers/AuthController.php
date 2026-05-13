<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth; // ফাইলের একদম উপরে এটি অবশ্যই লিখবেন
class AuthController extends Controller
{
    // রেজিস্ট্রেশন পেজ দেখানো
    public function showRegister()
    {
        return view('auth.register');
    }

    // ডাটা সেভ এবং মেইল পাঠানো
    public function register(Request $request)
    {
        // ১. ভ্যালিডেশন (ফোন এবং ইন্সটিটিউশন যোগ করা হয়েছে)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|numeric|digits_between:10,15', // ফোন নাম্বার ভ্যালিডেশন
            'password' => 'required|min:6|confirmed', // password_confirmation ফিল্ড লাগবে ফর্মে
        ]);

        // ২. ভেরিফিকেশন কোড জেনারেট
        $code = rand(100000, 999999);

        // ৩. ইউজার তৈরি করা (ফোন এবং ইন্সটিটিউশন এখানে যোগ করুন)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,           // ডাটাবেসে ফোন সেভ হবে
            'password' => Hash::make($request->password),
            'verification_code' => $code,
        ]);

        // ৪. ইমেইল পাঠানো
        Mail::raw("Your verification code is: $code", function ($message) use ($user) {
            $message->to($user->email)->subject('Verify Your Account');
        });

        return redirect()->route('verify.page')->with('success', 'আপনার ইমেইলে একটি কোড পাঠানো হয়েছে।');
    }


    public function verify(Request $request)
    {
        // ১. কোড ভ্যালিডেশন
        $request->validate([
            'verification_code' => 'required|numeric',
        ]);

        // ২. ডাটাবেসে এই কোডটি কোন ইউজারের আছে তা খুঁজে বের করা
        $user = User::where('verification_code', $request->verification_code)->first();

        if ($user) {
            // ৩. কোড মিললে ইউজারকে ভেরিফাইড হিসেবে আপডেট করা
            $user->is_verified = true;
            $user->verification_code = null; // কাজ শেষ, তাই কোডটি মুছে ফেলা ভালো
            $user->save();

            return redirect()->route('login')->with('success', 'আপনার অ্যাকাউন্ট সফলভাবে ভেরিফাই হয়েছে। এখন লগইন করুন।');
        } else {
            // ৪. কোড না মিললে এরর মেসেজ
            return redirect()->back()->with('error', 'ভুল কোড! আবার চেষ্টা করুন।');
        }
    }


    // লগইন পেজ দেখানো
    public function showLogin()
    {
        return view('auth.login');
    }

    // লগইন লজিক
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $credentials = $request->only('email', 'password');

    //     // Auth::attempt স্বয়ংক্রিয়ভাবে পাসওয়ার্ড হ্যাশ চেক করে লগইন করায়
    //     if (Auth::attempt($credentials)) {

    //         // ৫. চেক করা—ইউজার কি ভেরিফাইড?
    //         if (Auth::user()->is_verified == false) {
    //             Auth::logout(); // ভেরিফাইড না হলে লগআউট করে দেওয়া
    //             return redirect()->route('verify.page')->with('error', 'আপনার অ্যাকাউন্ট এখনো ভেরিফাই করা হয়নি।');
    //         }

    //         return redirect()->route('dashboard')->with('success', 'লগইন সফল হয়েছে!');
    //     }

    //     return redirect()->back()->with('error', 'ইমেইল বা পাসওয়ার্ড ভুল!');
    // }

    public function login(Request $request)
    {
        // ভ্যালিডেশন: 'login' ফিল্ডটি ইমেইল বা ফোন দুটোর জন্যই কাজ করবে
        $request->validate([
            'login'    => 'required',
            'password' => 'required',
        ]);

        // ইনপুটটি ইমেইল নাকি অন্য কিছু (ফোন) তা নির্ধারণ করা
        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        // ক্রেডেনশিয়াল সেট করা
        $credentials = [
            $login_type => $request->input('login'),
            'password'  => $request->input('password'),
        ];

        // লগইন চেষ্টা করা
        if (Auth::attempt($credentials)) {

            // ইউজার ভেরিফাইড কি না চেক করা
            if (Auth::user()->is_verified == false) {
                Auth::logout();
                return redirect()->route('verify.page')->with('error', 'আপনার অ্যাকাউন্ট এখনো ভেরিফাই করা হয়নি।');
            }

            return redirect()->route('dashboard')->with('success', 'লগইন সফল হয়েছে!');
        }

        return redirect()->back()->with('error', 'আপনার দেওয়া তথ্যগুলো সঠিক নয়!');
    }

    // লগআউট লজিক
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'লগআউট সফল হয়েছে।');
    }
}
