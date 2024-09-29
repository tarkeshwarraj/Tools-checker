<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\Email;

class SMTPCheckController extends Controller
{
    public function checkSMTP(Request $request)
    {
        $smtpDetails = $request->input('smtp_details');
        $timeout = $request->input('timeout', 30); // Default timeout is 30 seconds if not provided
        
        $smtpServers = explode("\n", trim($smtpDetails));

        $liveSMTP = [];
        $deadSMTP = [];

        foreach ($smtpServers as $server) {
            $parts = explode('|', $server);
            if (count($parts) == 4) {
                $smtpHost = trim($parts[0]);
                $smtpPort = (int) trim($parts[1]);
                $smtpUser = trim($parts[2]);
                $smtpPass = trim($parts[3]);

                // Create a dynamic transport with the timeout value
                $transport = (new EsmtpTransport($smtpHost, $smtpPort))
                    ->setUsername($smtpUser)
                    ->setPassword($smtpPass);

                $mailer = new Mailer($transport);

                // Attempt to check SMTP status
                try {
                    // Create the Email object
                    $email = (new Email())
                        ->from($smtpUser)
                        ->to($smtpUser) // Send to the same address for testing
                        ->subject('Test SMTP Connection')
                        ->text('This is a test email to check SMTP connection.'); // Add a text body

                    // Create the Envelope object (optional)
                    $envelope = new Envelope($email->getFrom()[0], $email->getTo());

                    // Try to send the email
                    $mailer->send($email);

                    $liveSMTP[] = $server;
                } catch (TransportExceptionInterface | TransportException $e) {
                    $deadSMTP[] = $server;
                }
            }
        }

        return response()->json([
            'success' => true,
            'live' => $liveSMTP,
            'dead' => $deadSMTP,
        ]);
    }
}
