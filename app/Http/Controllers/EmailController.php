<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function welcomeEmail()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];

        try {

            // pengiriman email secara langsung
            // Mail::to('recipient@example.com')->send(new WelcomeMail($data));

            // pengiriman email melalui queue
            Mail::to('recipient@example.com')->queue(new WelcomeMail($data));

            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Email sending failed', 'error' => $e->getMessage()], 500);
        }
    }
}
