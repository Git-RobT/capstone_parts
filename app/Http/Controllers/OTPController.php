<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Exception\Exception as VonageException;

class OTPController extends Controller
{
    public function index()
    {
        return view('otp.form');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^(\+63)[0-9]{10}$/',
        ]);

        $otp = rand(100000, 999999);  // Generate a 6-digit OTP
        $phone = $request->phone;

        // Store OTP in the session
        Session::put('otp', $otp);
        Session::put('phone', $phone);

        // Send OTP via Vonage
        try {
            $this->sendSms($phone, "Your OTP is: $otp");
            return back()->with('success', 'OTP sent successfully.');
        } catch (VonageException $e) {
            return back()->with('error', 'Failed to send OTP: ' . $e->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        if ($request->otp == Session::get('otp')) {
            return redirect()->route('otp.form')->with('success', 'OTP verified successfully!');
        }

        return back()->with('error', 'Invalid OTP. Please try again.');
    }

    private function sendSms($phone, $message)
    {
        // Create Vonage client with your credentials
        $basic = new Basic("f7bbb140", "5XCGkYf1Orf0OMwK"); // Replace with env() variables for security
        $client = new Client($basic);

        // Send SMS
        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS($phone, 'YourBrand', $message)
        );

        $msg = $response->current();

        if ($msg->getStatus() != 0) {
            throw new \Exception("The message failed with status: " . $msg->getStatus());
        }
    }
}
