<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('users.home');
});


Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/register', function () {
    return view('auth.register');
});
Route::get('/cse-gallery', function () {
    return view('users.gallery');
});
Route::get('/contact', function () {
    return view('users.contact');
});
Route::get('/about', function () {
    return view('users.about');
});
Route::get('/schedule', function () {
    return view('users.schedule');
});


use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PaymentTestController;

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/verify', function () {
    return view('auth.verify.page');
})->name('verify.page');
// Verification Routes
Route::post('/verify', [AuthController::class, 'verify'])->name('verify.submit');

// Login Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//forgot
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetRequest']);
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetRequest'])->name('password.otp.resend');

// OTP রিসেট রাউটস
Route::get('password/otp-verify', function () {
    return view('auth.otp-verify');
})->name('password.otp.verify');
Route::post('password/otp-verify', [ForgotPasswordController::class, 'verifyOtp']);

Route::post('password/reset-new', [ForgotPasswordController::class, 'updatePassword'])->name('password.reset.new');

Route::get('/password-reset-form', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');

Route::get('password/reset/{token}', function ($token) {
    return view('auth.reset-form', ['token' => $token]);
})->name('password.reset');

// Dashboard (শুধুমাত্র লগইন করা ইউজাররা দেখতে পাবে)
Route::get('/dashboard', function () {
    return view('users.home');
})->middleware('auth')->name('dashboard');





// IUPC
use App\Http\Controllers\IupcController;
use App\Http\Controllers\RegistrationController;

// IUPC Public Routes
Route::get('/iupc', [IupcController::class, 'index'])->name('iupc.index');
// Route::get('/iupc/register', [IupcController::class, 'create'])->name('iupc.register');
// Route::post('/iupc/register', [IupcController::class, 'store'])->name('iupc.store');
Route::get('/register/{slug}', [RegistrationController::class, 'create'])->name('event.register');
Route::post('/register/store', [RegistrationController::class, 'store'])->name('registration.store');


// পেমেন্ট ইনিশিয়েট করার রাউট
Route::get('/payment/make/{registration_id}', [RegistrationController::class, 'makePayment'])->name('payment.make');
// Route::get('/payment/make/{id}', [RegistrationController::class, 'makePayment'])->name('payment.make');
Route::any('/payment/callback', [RegistrationController::class, 'callback'])->name('payment.callback');
// পেমেন্ট গেটওয়ে থেকে ফিরে আসার রাউট (Success/Fail)
Route::post('/payment/success', [RegistrationController::class, 'success'])->name('payment.success');
Route::post('/payment/fail', [RegistrationController::class, 'fail'])->name('payment.fail');


Route::get('/registration-success', function () {
    return view('users.iupc.success'); // আপনার সাকসেস ভিউ ফাইল
})->name('success_page');










// Route::post('/verify-coupon', [IupcController::class, 'verifyCoupon'])->name('verify_coupon');
// কুপন ভেরিফিকেশন এবং এডিট পেজ
// Route::get('/iupc/final-step', [IupcController::class, 'finalStepForm'])->name('iupc.final.form');
// Route::post('/iupc/verify-coupon', [IupcController::class, 'verifyCoupon'])->name('iupc.verify');

// Route::post('/update-and-pay', [IupcController::class, 'updateAndPay'])->name('iupc.updateAndPay');
Route::get('/final-registration/{slug}', [IupcController::class, 'showFinalForm'])->name('final_form');

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;

Route::middleware(['auth', 'verified', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/upload-results', [AdminController::class, 'uploadExcel'])->name('admin.upload.result'); // Admin 
    Route::get('/teams/export', [AdminController::class, 'downloadExcel'])->name('admin.teams.export');
    // routes/web.php
    Route::post('/admin/event/{id}/import-coupons', [AdminController::class, 'import'])->name('admin.coupons.import');


    // অ্যাডমিন প্রোটেক্টেড গ্রুপের ভেতরে রাখা ভালো

    // স্লট আপলোড করার ফর্ম বা পেজ দেখার জন্য
    Route::get('/slots/upload', [AdminController::class, 'index'])->name('admin.slots.index');

    // এক্সেল ফাইল প্রসেস করার জন্য POST রাউট
    Route::post('/slots/upload', [AdminController::class, 'upload_slots'])->name('admin.slots.upload');


    // ফ্রন্টএন্ডে ইউজারদের স্লট লিস্ট দেখানোর জন্য


    Route::get('/admin/export-excel', [AdminController::class, 'exportExcel'])->name('admin.export.excel');

    Route::get('/admin/export-result-template/{event}', [AdminController::class, 'downloadResultTemplate'])
        ->name('admin.export.result.template');
    // Route::post('/admin/send-coupon/{id}', [AdminController::class, 'sendCoupon'])->name('admin.send.coupon');
    Route::get('/registration/{id}/show', [AdminController::class, 'show'])->name('admin.registration.show');

    // কুপন কোড পাঠানো (IUPC কোচ বা অন্যদের মেম্বার ১ কে)
    Route::post('/registration/{id}/send-coupon', [AdminController::class, 'sendCoupon'])->name('admin.send.coupon');

    // রেজিস্ট্রেশন স্ট্যাটাস আপডেট করার রাউট
    Route::patch('/registration/{id}/status/pw', [AdminController::class, 'updateStatus'])
        ->name('admin.registration.updateStatus_pw');
    // ইভেন্ট ম্যানেজমেন্ট রাউটস
    Route::patch('/registration/{id}/status/ai', [AdminController::class, 'ai_updateStatus'])
        ->name('admin.registration.updateStatus.ai');
    // ইভেন্ট ম্যানেজমেন্ট রাউটস

    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');

    // ডাটা সেভ বা আপডেট করার জন্য একটিই রাউট
    Route::post('/settings/update', [SettingController::class, 'update'])->name('admin.settings.update');
    Route::get('/events', [EventController::class, 'index'])->name('admin.events.index');
    Route::post('/events/store', [EventController::class, 'store'])->name('admin.events.store');
    Route::put('/events/update/{id}', [EventController::class, 'update'])->name('events.update');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');

    Route::delete('/admin/events/{id}/delete', [EventController::class, 'destroy'])->name('admin.events.destroy');
    // Route::get('/event/{slug}', [EventController::class, 'showDashboard'])->name('event.dashboard');
    // Route::get('/event/{slug}/pre-registered', [EventController::class, 'preRegistered'])->name('event.pre_registered');
    // Route::get('/event/{slug}/final-registered', [EventController::class, 'finalRegistered'])->name('event.final_registered');
    // আরও রাউট যেমন: rulebook, seat-plan ইত্যাদি...






    Route::post('/admin/event/{id}/send-bulk-link', [AdminController::class, 'sendBulkContestLink'])->name('admin.event.sendBulkLink');
});



// Route::prefix('event/{slug}')->group(function () {
//     // মেইন পোর্টাল/ড্যাশবোর্ড
//     Route::get('/', [EventController::class, 'showDashboard'])->name('event.dashboard');

//     // মেম্বার লিস্ট
//     Route::get('/pre-registered', [EventController::class, 'preRegistered'])->name('event.pre_registered');
//     Route::get('/final-registered', [EventController::class, 'finalRegistered'])->name('event.final_registered');
//     Route::get('/selected-teams', [EventController::class, 'selectedTeams'])->name('event.select_registered');    // Route::get('/final-registered', [EventController::class, 'finalRegistered'])->name('event.final_registered');

//     // সিলেকশন এবং অন্যান্য
//     Route::get('/selected-teams', [EventController::class, 'selectedTeams'])->name('event.selected_teams');

//     // অ্যাডমিট কার্ড (ICT Olympiad এর জন্য)
//     Route::get('/admit-download/{id}', [EventController::class, 'downloadAdmit'])->name('event.admit_download');
// });

Route::get('/event/{slug}/admit-card/{id}', [EventController::class, 'downloadAdmitCard'])
    ->name('event.admit_card');
Route::prefix('iupc')->name('iupc.')->group(function () {
    // কুপন ভেরিফিকেশন পেজ দেখার জন্য


    // কুপন ভেরিফাই করার প্রসেস (POST মেথড)
    Route::post('/verify-coupon', [PaymentController::class, 'iupc_updateAndPay'])->name('verify.process');
});

// Route::get('/event/{slug}/final-registration', [EventController::class, 'showFinalRegForm'])->name('iupc.final.reg.form');
Route::get('/finalize-registration/{id}', [EventController::class, 'showFinalRegForm'])->name('iupc.final.reg.form');
Route::prefix('event/{slug}')->group(function () {
    // মেইন পোর্টাল/ড্যাশবোর্ড
    Route::get('/', [EventController::class, 'showDashboard'])->name('event.dashboard');

    // মেম্বার লিস্ট
    Route::get('/pre-registered', [EventController::class, 'preRegistered'])->name('event.pre_registered');
    // Route::get('final-registration', [EventController::class, 'showFinalRegForm'])->name('iupc.final.reg.form');
    // সিলেকশন পেজ (এই নামটিই ড্যাশবোর্ড ও ট্যাবে ব্যবহার করুন)
    Route::get('/selected-teams', [EventController::class, 'selectedTeams'])->name('event.select_registered');
    // Route::post('/verify-coupon', [EventController::class, 'verifyCoupon'])->name('event.verify_coupon');
    // ফাইনাল রেজিস্টার্ড
    Route::get('/final-registered', [EventController::class, 'finalRegistered'])->name('event.final_registered');
    // সঠিক রাউট ফরম্যাট
    Route::get('/schedule', [EventController::class, 'schedule'])->name('event.schedule');
    // Judges & Problem Setters Introduction Route
    Route::get('/judges', [EventController::class, 'judges'])->name('event.judges');

    // ইনস্টিটিউট অনুযায়ী পার্টিসিপেন্ট দেখার রাউট
    Route::get('/institutes', [EventController::class, 'institutes'])->name('event.institutes');
    // অ্যাডমিট কার্ড

    // Admit Card/Confirmation ডাউনলোড রাউট
    // Route::get('/admit-card/{team_id}', [EventController::class, 'downloadAdmitCard'])->name('event.admit_card');

    Route::get('/ict-admit-card/{id}', [EventController::class, 'ictAdmitCard'])
        ->name('event.ict_admit_card');
});


// IUPC পেমেন্ট রাউটস

// IUPC Final Registration & Payment Routes
// Route::prefix('iupc-payment')->group(function () {

//     // ১. ফর্ম সাবমিট এবং পেমেন্ট গেটওয়েতে পাঠানো
//     Route::post('/process', [EventController::class, 'updateAndPay'])
//         ->name('iupc.payment.process');

//     // ২. shurjoPay থেকে ফিরে আসার রাস্তা (Callback)
//     // অনেক সময় গেটওয়ে মেথড পরিবর্তন করে, তাই match(['get', 'post']) নিরাপদ
//     Route::match(['get', 'post'], '/callback', [EventController::class, 'paymentCallback'])
//         ->name('iupc.payment.callback');
// });
// এটি অবশ্যই POST হতে হবে কারণ আপনি কুপন ডাটা পাঠাচ্ছেন

// ঐচ্ছিক: কেউ যদি ভুল করে রিফ্রেশ দেয় বা সরাসরি লিঙ্কে ঢুকে তবে তাকে ফেরত পাঠানো
Route::get('event/{slug}/verify-coupon', function ($slug) {
    return redirect()->route('event.select_registered', $slug);
});


// payment controller for iupc
Route::post('event/{slug}/verify-coupon', [PaymentController::class, 'verifyCoupon'])->name('event.verify_coupon');
Route::post('/iupc-payment/process', [PaymentController::class, 'updateAndPay'])->name('iupc.payment.process');
Route::match(['get', 'post'], '/iupc-payment/callback', [PaymentController::class, 'paymentCallback'])->name('iupc.payment.callback');
// IUPC Coupon Verification

// Direct Final Registration for other events
Route::get('event/{slug}/final-reg/{id}', [PaymentController::class, 'finalRegDirectPay'])->name('event.final_reg_direct');


Route::post('/check-result', [EventController::class, 'checkResult'])->name('check.result');

Route::get('/checkout', [PaymentController::class, 'pay']);
Route::get('/shurjopay/callback', [PaymentController::class, 'callback']);


Route::get('/event/{slug}/slots', [EventController::class, 'slot_list'])->name('event.slot_list');





Route::get('/test_payment', [PaymentTestController::class, 'create'])->name('paymentCreate');
Route::post('/shurjopay', [PaymentTestController::class, 'send_payment_request_to_shurjopay'])->name('shurjopay.lara');
Route::get('/paymentUpdate', [PaymentTestController::class, 'verify_payment']);
