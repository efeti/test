<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail as FacadesMail;

class UserController extends Controller
{
    public function onlineform()
    {
            return view('onlineform');
    }

    public function save(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'regex:/^(.+)@(yahoo\.com|gmail\.com)$/'],
            'phone' => 'required|digits:11',
        ]);
        logger($request);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        logger($request);
        FacadesMail::to($request->email)->send(new SendEmail());
        // $email = new SendEmail();
        // Mail::send($email);

    }

    public function submit()
    {

    }
}
