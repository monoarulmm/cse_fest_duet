<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }



    public function sendResetRequest(Request $request)
    {
        $request->validate(['identifier' => 'required']);

        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$user) {
            return back()->withErrors(['identifier' => 'এই তথ্য দিয়ে কোনো ইউজার পাওয়া যায়নি।']);
        }

        // ওটিপি জেনারেট করা
        $otp = rand(1111, 9999);

        // সেশনে সেভ করা (ভেরিফিকেশনের জন্য)
        session(['reset_otp' => $otp, 'reset_identifier' => $request->identifier]);

        // ১. যদি ইমেইল হয়
        if (filter_var($request->identifier, FILTER_VALIDATE_EMAIL)) {
            try {
                // ম্যানুয়ালি মেইল পাঠানো
                Mail::raw("আপনার পাসওয়ার্ড রিসেট ওটিপি কোড হলো: $otp Don't Share Your OTP ,Thanks Taka ID", function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Password Reset OTP');
                });

                return redirect()->route('password.otp.verify')->with('status', 'আপনার ইমেইলে ওটিপি পাঠানো হয়েছে।');
            } catch (\Exception $e) {
                return back()->withErrors(['identifier' => 'মেইল পাঠানো সম্ভব হয়নি: ' . $e->getMessage()]);
            }
        }

        // ২. যদি ফোন হয় (আপনার আগের লজিক)
        if ($user->phone) {
            $otp = rand(1111, 9999);
            session(['reset_otp' => $otp, 'reset_phone' => $user->phone]);

            try {
                $result = \App\Services\SmsService::sendOtp($user->phone, $otp);

                // এসএমএস না আসলে গেটওয়ের রেসপন্স চেক করুন
                if (!isset($result['response_code']) || $result['response_code'] != 202) {
                    dd("SMS Gateway Response:", $result);
                }

                return redirect()->route('password.otp.verify')->with('status', 'আপনার ফোনে ওটিপি পাঠানো হয়েছে।');
            } catch (\Exception $e) {
                dd("SMS Server Error: " . $e->getMessage());
            }
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);

        if ($request->otp == session('reset_otp')) {
            session(['can_reset_password' => true]);
            session()->forget('reset_otp');
            return redirect()->route('password.reset.form');
        }
        return back()->withErrors(['otp' => 'ওটিপি সঠিক নয়।']);
    }







    public function showResetForm()
    {
        // সেশন চেক করুন যে সে ওটিপি ভেরিফাই করে এসেছে কিনা
        if (!session('can_reset_password')) {
            return redirect()->route('password.request')->withErrors(['identifier' => 'প্রথমে ওটিপি ভেরিফাই করুন।']);
        }

        return view('auth.reset-form'); // নিশ্চিত করুন এই ভিউ ফাইলটি আছে
    }



    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        if (!session('can_reset_password')) {
            return redirect()->route('password.request');
        }

        // ভুল এখানে ছিল: আপনি reset_phone খুঁজছিলেন কিন্তু সেভ করেছিলেন reset_identifier নামে
        $identifier = session('reset_identifier');

        $user = User::where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // সব সেশন ক্লিয়ার করে দিন
            session()->forget(['reset_identifier', 'can_reset_password', 'reset_otp']);

            return redirect('/login')->with('status', 'পাসওয়ার্ড সফলভাবে আপডেট হয়েছে!');
        }

        return redirect()->route('password.request')->withErrors(['identifier' => 'ইউজার খুঁজে পাওয়া যায়নি।']);
    }
}
