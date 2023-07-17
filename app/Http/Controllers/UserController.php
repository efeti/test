<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendEmail;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            $failedFields = $errors->keys();
            

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'failedFields' => $failedFields,
                'errors' => $errors,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }else {
            $name = $request->input('name');
            $email = $request->input('email');
            $phone = $request->input('phone');
        
            // Save the data to the database
            // Example code assuming you have a "User" model:
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->phone_number = $phone;
            $user->save();

            // FacadesMail::to($request->email)->send(new SendEmail());
            Mail::to($request->email)->send(new SendEmail());
            // Return a success response if needed
            return response()->json([
                'success' => true,
                'message' => 'Data saved successfully',
            ], 200);
        }

        logger($request);
        FacadesMail::to($request->email)->send(new SendEmail());
        // $email = new SendEmail();
        // Mail::send($email);

    }

    public function register(Request $request)
    {
        logger($request);

        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'mobile_network' => 'required|in:mtn,airtel,9mobile,glo',
            'message' => 'required',
            'ref_code' => 'required|unique:registrations'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $failedFields = $errors->keys();
            

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'failedFields' => $failedFields,
                'errors' => $errors,
            ], 422);
        }

        $registration = new Registration();
        $registration->phone_number = $request->phone_number;
        $registration->mobile_network = $request->mobile_network;
        $registration->message = $request->message;
        $registration->ref_code = $request->ref_code;

        // You can add additional fields and their values as needed

        $registration->save();

     
        $response = [
            'phone_number' => $request->phone_number,
            'mobile_network' => $request->mobile_network,
            'status' => 'success',
            'message' => 'Registration successful'
        ];
logger('here');
        // Return the JSON response
        return response()->json($response);

    }
}
