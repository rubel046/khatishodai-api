<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use App\Http\Helper\CommonHelper;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use  App\User;
use Validator;


class PasswordController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request $request
     * @return Response
     */
    public function forgetPassword(Request $request)
    {
        $authController = new AuthController;
        $userName= $authController->findLoginWith($request->emailOrPhone);

        $user = User::where('userName', $request->emailOrPhone)
            ->orWhere('phone',$request->emailOrPhone)
            ->orWhere('email',$request->emailOrPhone)->first();
        if($userName=='email'){
            $validator = Validator::make($request->all(), [
                'emailOrPhone' => 'email|required',
            ],[
                'emailOrPhone.email' => 'Invalid email or Phone Number',
                'emailOrPhone.required' => 'The email or phone number is required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status'=>'failed','errors'=>$validator->errors(),'message'=>'Operation failed, please try again!'], 422);
            }

            $email=$request->emailOrPhone;
            if (empty($user)) {
                return response()->json(['status'=>'failed','message' => 'User Not found with email '.$email.'. please, check your email correctly or register now!'], 401);
            } else {
                $verify_token = rand(100000, 999999);
                $user->verificationToken = $verify_token;
                $user->save();

                $toEmail=$user->email;
                $toName=$user->first_name.' '.$user->last_name;
                $data=[
                    'id'=>$user->id,
                    'email'=>$toEmail,
                    'name'=>$toName,
                    'verificationToken'=>$user->verificationToken
                ];

                try{
                    if($user->email){
                        Mail::send('mail.password_reset_email',$data,function($message) use ($toName,$toEmail){
                            $message->to($toEmail)->subject('Forget Password Request Tizaara login');
                        });
                        return response()->json([
                            'userId' => $user->id,
                            'userName' => $request->emailOrPhone,
                            'status' => 'success',
                            'signUpBy' => 'email',
                            'message' => 'Your login reset code has been sent to your email '.$email.', Please check your '.$userName.' to complete the change'], 200);
                    }else{
                        return response()->json([
                            'userId' => $user->id,
                            'userName' => $request->emailOrPhone,
                            'status' => 'failed',
                            'signUpBy' => 'email',
                            'message' => 'Oops! something went wrong email not sent to '.$email.'!'], 409);
                    }

                }catch (\Exception $e){
                    return response()->json([
                        'userId' => $user->id,
                        'userName' => $request->emailOrPhone,
                        'status' => 'failed',
                        'signUpBy' => 'email',
                        'message' => 'Something went wrong!'], 409);
                }
            }

        }else{
            $validator = Validator::make($request->all(), [
                'emailOrPhone' => 'numeric|required|digits:11',
            ],[
                'emailOrPhone.numeric' => 'Invalid email or Phone Number',
                'emailOrPhone.digits' => 'The phone number must be 11 digits or give a correct email',
                'emailOrPhone.required' => 'The email or phone number is required'

            ]);

            if ($validator->fails()) {
                return response()->json(['errors'=>$validator->errors(),'message'=>'Operation failed, please try again!'], 422);
            }

            $phone=$request->emailOrPhone;
            if (empty($user)) {
                return response()->json(['message' => 'User Not found with phone number '.$phone.'. please, check it correctly!'], 401);
            } else {
                $verify_token = rand(100000, 999999);
                $user->verificationToken = $verify_token;
                $user->save();

                $toEmail=$user->email;
                $toName=$user->first_name.' '.$user->userName;
                $data=[
                    'id'=>$user->id,
                    'email'=>$toEmail,
                    'name'=>$toName,
                    'verificationToken'=>$user->verificationToken
                ];
                try{
                    // Sending Mobile OTP
                    if($authController->checkOtpSent($phone)==0){
                       $results= $authController->sendPhoneCode($phone,$verify_token);
                    }
                    return response()->json([
                        'userId' => $user->id,
                        'userName' => $phone,
                        'status' => 'success',
                        'signUpBy' => 'phone',
                        'message' => 'Your login reset code has been sent to your mobile number '.$phone.', Please check your phone for OTP'], 200);
                }catch (\Exception $e){
                    return response()->json([
                        'userId' => $user->id,
                        'userName' => $phone,
                        'status' => 'failed',
                        'signUpBy' => 'phone',
                        'message' => 'Something went wrong!'
                    ], 409);
                }
            }
        }


    }

    public function resetPassword($id, $verify_token)
    {
        $user = User::where(['id'=> $id,'verificationToken'=> $verify_token])->first();

        if (empty($user)) {
            return response()->json(['message' => 'Invalid Request'], 401);
        } else {
            $toName=$user->first_name." ".$user->last_name;
            $toEmail=$user->email;
            $data=[
                'id'=>$user->id,
                'verificationToken'=>$user->verificationToken,
                'email'=>$toEmail,
                'name'=>$toName,
            ];
            return response()->json(['user' => $data, 'message' => 'Password Request user data'], 200);


        }
    }

    public function resetPasswordSave(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric',
            'password' => 'required|confirmed|min:6',// password_confirmation ( field is Required)
            'verificationToken' => 'required',
        ],[

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors'=>$validator->errors(),
                'message'=>'Operation Failed, check input correctly'
            ], 422);
        }

        $id= $request->userId;
        $user = User::where(['id'=> $id])->first();


        if (empty($user)) {
            return response()->json([
                'user' => $user->userName,
                'status' => 'failed',
                'message' => 'You enter email or phone is invalid, user not found'
            ], 402);
        } else {
            if($user->verificationToken!=$request->verificationToken){
                return response()->json([
                    'message' => 'Invalid OTP code Or it has Expired!',
                    'errors'=>['verificationToken'=>['Invalid code Or Expired']]], 422);
            }

            $user->password = app('hash')->make($request->password);
            $user->verificationToken = null;
            $user->is_verified = 1;
            $user->status = 1;
            $user->save();

            $authController= new AuthController();
            $userName= $authController->findLoginWith($request->emailOrPhone);
            if($userName=='email'){
                $toName=$user->first_name." ".$user->last_name;
                $toEmail=$user->email;
                $data=[
                    'id'=>$user->id,
                    'verificationToken'=>$user->verificationToken,
                    'email'=>$toEmail,
                    'name'=>$toName,
                ];

                Mail::send('mail.password_changed_success_email',$data,function($message) use ($toName,$toEmail){
                    $message->to($toEmail)->subject('Password Changed successfully');
                });
            }else{
                // Mobile Confirmation

            }

            return response()->json([
                'user' => $user->userName,
                'status' => 'success',
                'message' => 'Your account password reset has successfully changed'
            ], 200);


        }
    }

}