<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail(Request $request)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'message' => 'required',
        'smtp' => 'required',
        'fromname' => 'required|string|max:255',
    ]);

    // Extract SMTP details
    $smtpDetails = explode('|', $validatedData['smtp']);

    // Check if we got the expected number of SMTP details
    if (count($smtpDetails) !== 4) {
        return response()->json(['message' => 'Invalid SMTP details provided.'], 400);
    }

    list($smtpHost, $smtpPort, $smtpUser, $smtpPass) = $smtpDetails;

    // Configure mail settings
    config([
        'mail.mailers.smtp.host' => $smtpHost,
        'mail.mailers.smtp.port' => $smtpPort,
        'mail.mailers.smtp.username' => $smtpUser,
        'mail.mailers.smtp.password' => $smtpPass,
    ]);

    // Try to send the email and handle any exceptions
    try {
        Mail::raw($validatedData['message'], function ($message) use ($validatedData) {
            $message->to($validatedData['email'])
                    ->subject($validatedData['subject'])
                    ->from(env('MAIL_FROM_ADDRESS'), $validatedData['fromname']); //Use the from name here

        });

        return response()->json(['status' => 'success'], 200);
    } catch (\Exception $e) {
        \Log::error('Mail sending failed: ' . $e->getMessage());
        return response()->json(['status' => 'fail'], 500);
    }
}
}
