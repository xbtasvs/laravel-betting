<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        try {
          $name = $request->params['name'];
          $email = $request->params['email'];
          $message = $request->params['message'];

          Mail::to('dr.randjelovic42@gmail.com')->send(new ContactMessage($name, $email, $message));
        } catch (\Exception $e) {
          Log::critical('Error in sending email from the website contact form - params: ', $request->params, 'exception: ', $e);
        }
    }
}
