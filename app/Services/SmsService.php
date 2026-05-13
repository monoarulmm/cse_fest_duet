<?php


namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    private static function getApiKey()
    {
        // .env থেকে ডেটা না পেলে সরাসরি স্ট্রিং ব্যবহার করবে
        return env('BULKSMSBD_API_KEY') ?? 'hHcLbvnc4eDHzT6onaC5';
    }

    private static function getSenderId()
    {
        // নিশ্চিত হোন এই নম্বরটি আপনার প্যানেলে 'Approved' অবস্থায় আছে
        return env('BULKSMSBD_SENDER_ID') ?? '8809648907648';
    }

    /**
     * ফোন নম্বর ফরম্যাট (৮৮০ নিশ্চিত করা)
     */
    public static function formatNumber($phone)
    {
        $number = trim($phone);
        // যদি শুরুতে ৮৮০ না থাকে তবে যোগ করা
        if (!str_starts_with($number, '880')) {
            $number = '880' . ltrim($number, '0');
        }
        return $number;
    }

    /**
     * OTP এবং সাধারণ SMS এর জন্য সার্ভিস
     */
    public static function sendOtp($phone, $otp)
    {
        $message = "আপনার পাসওয়ার্ড রিসেট কোডটি হলো: $otp";
        return self::executeApi($phone, $message);
    }

    public static function sendSms($phone, $message)
    {
        return self::executeApi($phone, $message);
    }

    /**
     * BulkSMSBD Official CURL Request Method
     */
    private static function executeApi($phone, $message)
    {
        $url = "http://bulksmsbd.net/api/smsapi";
        $number = self::formatNumber($phone);

        $data = [
            "api_key"  => trim(self::getApiKey()),
            "senderid" => trim(self::getSenderId()),
            "number"   => $number,
            "message"  => $message
        ];

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            // http_build_query ব্যবহার করা হয়েছে যাতে ফরম ডাটা হিসেবে সাবমিট হয়
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::error("BulkSMSBD CURL Error: " . $curlError);
                return ['response_code' => 500, 'error_message' => 'Connection Failed'];
            }

            // রেসপন্স ডিকোড করা
            $result = json_decode($response, true);

            // লগে স্টোর করা (ডিবাগিং এর জন্য ভালো)
            Log::info("BulkSMSBD Response: " . $response);

            return $result;
        } catch (\Exception $e) {
            Log::error("SmsService Exception: " . $e->getMessage());
            return ['response_code' => 500, 'error_message' => $e->getMessage()];
        }
    }
}
