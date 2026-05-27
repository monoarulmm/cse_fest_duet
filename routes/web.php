<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;

// ১. হোম পেজ রাউট
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/test-link', function () {

    Artisan::call('storage:link');

    return Artisan::output();
});

// ২. ড্যাশবোর্ড (শুধুমাত্র লগইন করা ইউজারদের জন্য মিডলওয়্যারসহ)
Route::get('/dashboard', [HomeController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

// ৩. অন্যান্য পেজের রাউটস
Route::get('/cse-gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/schedule', [HomeController::class, 'schedule'])->name('schedule');

Route::post('/check-result', [HomeController::class, 'checkResult'])->name('check.result');







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






// IUPC
use App\Http\Controllers\IupcController;
use App\Http\Controllers\RegistrationController;

// IUPC Public Routes
// Route::get('/iupc/register', [IupcController::class, 'create'])->name('iupc.register');
// Route::post('/iupc/register', [IupcController::class, 'store'])->name('iupc.store');

// Route::any('/payment/invoice', [RegistrationController::class, 'callback'])->name('payment.callback');















// ════════════════════════════════════════════════════════════════════════════
//  REGISTRATION ROUTES
// ════════════════════════════════════════════════════════════════════════════
    Route::get('/register/{slug}', [RegistrationController::class, 'create'])->name('event.register');
    Route::post('/register/store', [RegistrationController::class, 'store'])->name('registration.store');


// ════════════════════════════════════════════════════════════════════════════
//  PAYMENT ROUTES
// ════════════════════════════════════════════════════════════════════════════

// ─── 1. ICT-Olympiad ও অন্যান্য single-registration event ───────────────
//  RegistrationController.store() থেকে redirect হয়ে আসে এখানে
Route::get('/payment/make/{registration_id}', [PaymentController::class, 'makePayment'])
    ->name('payment.make');

// ICT/single event পেমেন্ট রিট্রাই
Route::get('/payment/retry/{registration_id}', [PaymentController::class, 'retryPayment'])
    ->name('payment.retry');

// ─── 2. IUPC — final form update + pay ───────────────────────────────────
//  Dashboard থেকে POST করা হয়
// Route::post('/payment/iupc/update-and-pay', [PaymentController::class, 'iupc_updateAndPay'])
//     ->name('payment.iupc.updateAndPay');

// ─── 3. Project-Showcase & AI-Hackathon — direct pay ─────────────────────
//  slug: project-showcase অথবা ai-hackathon
Route::get('/payment/{slug}/final-pay/{id}', [PaymentController::class, 'finalRegDirectPay'])
    ->name('payment.finalRegDirectPay')
    ->where('slug', 'project-showcase|ai-hackathon');

// ─── 4. ShurjoPay Callback — সব event এর জন্য একটাই ─────────────────────
//  ShurjoPay dashboard এ এই URL দিন:
//  https://yourdomain.com/payment/callback
//  ShurjoPay কখনো GET কখনো POST পাঠায়, তাই দুটোই রাখা হয়েছে
Route::match(['get', 'post'], '/payment/callback', [PaymentController::class, 'callback'])
    ->name('payment.callback');



use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;

Route::middleware(['auth', 'verified', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/coupons/bulk-delete', [AdminController::class, 'bulkDelete'])->name('coupons.bulkDelete');
    Route::post('/upload-results', [AdminController::class, 'uploadExcel'])->name('admin.upload.result'); // Admin 
    Route::get('/admin/export-iupc-slots', [AdminController::class, 'exportIUPCTeams'])->name('admin.iupc.export');    // Route::get('/admin/export-excel/{event_id}', [AdminController::class, 'downloadExcel'])->name('admin.export.excel');
    // routes/web.php
    Route::post('/admin/event/{id}/import-coupons', [AdminController::class, 'import'])->name('admin.coupons.import');
    Route::delete('/admin/slots/bulk-delete', [AdminController::class, 'bulkDelete'])->name('admin.slots.bulkDelete');    // Route::post('/admin/coupons/import/{eventId}', [AdminController::class, 'import'])->name('coupons.import');
    Route::get('/admin/coupons/export/{eventId}', [AdminController::class, 'export'])->name('coupons.export');

    // অ্যাডমিন প্রোটেক্টেড গ্রুপের ভেতরে রাখা ভালো

    // স্লট আপলোড করার ফর্ম বা পেজ দেখার জন্য
    Route::get('/slots/upload', [AdminController::class, 'index'])->name('admin.slots.index');

    // এক্সেল ফাইল প্রসেস করার জন্য POST রাউট
    Route::post('/slots/upload', [AdminController::class, 'upload_slots'])->name('admin.slots.upload');


    // ফ্রন্টএন্ডে ইউজারদের স্লট লিস্ট দেখানোর জন্য


    Route::get('/admin/export-excel', [AdminController::class, 'exportExcel'])->name('admin.export.all_event');

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
   





    Route::post('/admin/event/{id}/send-bulk-link', [AdminController::class, 'sendBulkContestLink'])->name('admin.event.sendBulkLink');
});




Route::get('/event/{slug}/admit-card/{id}', [EventController::class, 'downloadAdmitCard'])
    ->name('event.admit_card');
Route::prefix('iupc')->name('iupc.')->group(function () {
    // কুপন ভেরিফিকেশন পেজ দেখার জন্য
    // কুপন ভেরিফাই করার প্রসেস (POST মেথড)

    Route::post('/verify-coupon/form', [PaymentController::class, 'iupc_updateAndPay'])->name('verify.process');
    Route::post('/verify-coupon', [PaymentController::class, 'iupc_verifyCoupon'])->name('verify_coupon');

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



// Direct Final Registration for other events
Route::get('event/{slug}/final-reg/{id}', [PaymentController::class, 'finalRegDirectPay'])->name('event.final_reg_direct');


Route::get('/event/{slug}/slots', [EventController::class, 'slot_list'])->name('event.slot_list');





Route::get('/test_payment', [PaymentTestController::class, 'create'])->name('paymentCreate');
Route::post('/shurjopay', [PaymentTestController::class, 'send_payment_request_to_shurjopay'])->name('shurjopay.lara');
Route::get('/paymentUpdate', [PaymentTestController::class, 'verify_payment']);;
Route::delete('/result/delete/{id}', [AdminController::class, 'deleteResult'])->name('admin.result.delete');
// একাধিক রেজিস্ট্রেশন একসাথে ডিলিট (Bulk Action)
Route::delete('/registrations/bulk-delete', [AdminController::class, 'bulkDeleteRegistrations'])
    ->name('admin.registrations.bulkDelete');


Route::get('/storage-link', function () {
    $targetFolder = storage_path('app/public');
    $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';

    if (file_exists($linkFolder)) {
        // Optional: delete the existing symlink
        if (is_link($linkFolder)) {
            unlink($linkFolder);
        } else {
            return 'Storage folder already exists and is not a symlink!';
        }
    }

    symlink($targetFolder, $linkFolder);

    return 'Storage link created successfully!';
});
