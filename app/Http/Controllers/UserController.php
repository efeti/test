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
        } else {
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

        // Return the JSON response
        return response()->json($response);
    }



    public function  encryptText($text, $key, $iv)
    {
        $encrypted = openssl_encrypt($text, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return bin2hex($encrypted);
    }

    public function decryptText($encryptedText, $key, $iv)
    {
        $encryptedText = hex2bin($encryptedText);
        $decrypted = openssl_decrypt($encryptedText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

    public function program()
    {

        // Text to encrypt
        $text = 'Welcome to Lagos';

        // Encryption key (256-bit)
        $key = '0123456789abcdef0123456789abcdef';

        // Initialization vector (IV) - 16 bytes
        $iv = '1234567890abcdef';

        // Encrypt the text
        $encryptedText = $this->encryptText($text, $key, $iv);
        echo 'Encrypted: ' . $encryptedText . '<br>';

        // Decrypt the text
        $decryptedText = $this->decryptText($encryptedText, $key, $iv);
        echo 'Decrypted: ' . $decryptedText;

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Data saved successfully',
        // ], 200);
    }
}
