<?php

namespace App\Http\Controllers;

use App\Http\Helper\CommonHelper;
use App\Mail\SendRegOTP;
use App\Services\MailService;
use App\Traits\ApiResponse;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;

    public function sendRegistrationOTP(Request $request)
    {
        Validator::make($request->all(), [
            'username' => 'required|string|unique:users,username',
        ])->validate();

        $regMedium = $this->findLoginWith($request->username);

        $this->validateUsername($request, $regMedium);

        $email = $regMedium == 'email' ? $request->username : null;
        $phone = $regMedium == 'email' ? null : $request->username;

        try {
            $user = User::create($request->only((new User())->getFillable()) + [
                    'password' => Hash::make('123456'),
                    'email' => $email,
                    'phone' => $phone,
                    'verification_token' => CommonHelper::generateOTP(4)
                ]);

            if ($email) {
                $mailClass = new SendRegOTP($user);
                MailService::mailSend($user->email, $mailClass);

                return $this->successResponse('An OTP sent to your mail. Please use this OTP', $user);
            }

            if ($this->sendPhoneCode($phone, $user->verification_token)) {
                return $this->successResponse('An OTP sent to your phone. Please use this OTP', $user);
            }
            return $this->errorResponse('OTP sending failed');

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function register(Request $request)
    {
        $user = User::where('username', $request->username)
            //->whereVerificationToken($request->verification_token)
            ->first();

        if (empty($user)) {
            return $this->errorResponse('You entered wrong username or OTP.');
        } else if ($user->is_verified) {
            return $this->errorResponse('This username already been taken');
        }

        Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'required|min:6',// password_confirmation ( field is Required)
            'verification_token' => 'required'
            /*'userType' => 'required|integer',
            'country' => 'required|integer',
            'companyName' => 'required|string',*/
        ])->validate();

        try {
            $user->fill($request->only(['name']) + [
                    'password' => Hash::make($request->password),
                    'verification_token' => null,
                    'is_verified' => 1,
                    'status' => 1,
                ]);

            $user->save();

            // Insert Company Name
            /*            $companyData = new Company();
                        $companyData->user_id = $user->id;
                        $companyData->name = $request->input('companyName');
                        $companyData->ip_address = $request->ip();
                        $companyData->created_by = $user->id;
                        $companyData->save();

                        $toName = $user->first_name . ' ' . $user->last_name;

                        if ($email) {
                            $user->verificationToken = CommonHelper::strRandom(40);
                            $toEmail = $email;
                            $data = [
                                'id' => $user->id,
                                'email' => $email,
                                'name' => $toName,
                                'verificationToken' => $user->verificationToken
                            ];
                            $user->save();
                            try {
                                // sending verification Email
                                Mail::send('mail.reg_verification_email', $data, function ($message) use ($toName, $toEmail) {
                                    $message->to($toEmail)->subject('Tizaara Registration Verification');
                                });

                                unset($data['verificationToken']);
                                return response()->json([
                                    'user' => $data,
                                    'signUpBy' => 'email',
                                    'message' => 'Registration form submitted successfully, Please check email ' . $email . ' to verify your account!'], 201);
                            } catch (\Exception $e) {
                                return response()->json(['messages' => 'Registration form submitted successfully! Email sending failed. Contact with admin'], 409);
                            }

                        } else {
                            $data = [
                                'id' => $user->id,
                                'phone' => $phone,
                                'name' => $toName
                            ];
                            // Sending Mobile OTP
                            $otp = rand(100000, 999999);
                            if ($this->sendPhoneCode($phone, $otp)) {
                                return response()->json([
                                    'user' => $data,
                                    'signUpBy' => 'phone',
                                    'message' => 'Registration form submitted successfully. Please, Check your mobile ' . $phone . ' for verification OTP to verify'], 201);
                            } else {
                                $user->delete();
                                return response()->json(['messages' => 'Registration fail! Mobile OTP can not sent. Contact with admin'], 504);
                            }
                        }*/

            return $this->successResponse('Congratulation ! Your registration has been succeed');

        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only(['username', 'password'] + [
                'status' => 1
            ]);

        if (!$token = Auth::attempt($credentials, ['expires_in' => Carbon::now()->addDays(7)->timestamp])) {
            return $this->errorResponse('The username or password you entered is incorrect', [], 404);
        }

        return $this->respondWithToken($token, 'You have logged in successfully');
    }

    public function logout()
    {
        auth()->logout();

        return $this->successResponse('You have successfully logged out', '', 200);
    }

    private function validateUsername($request, $regMedium)
    {
        if ($regMedium == 'email') {
            return Validator::make($request->all(), [
                'username' => 'email|unique:users,email',
            ], [
                'username.email' => 'Invalid email',
                'username.unique' => 'This email is already taken'
            ])->validate();
        } else {
            return Validator::make($request->all(), [
                'username' => 'numeric|digits:11|unique:users,phone',
            ], [
                'username.numeric' => 'Invalid email or Phone Number',
                'username.unique' => 'The phone number is already taken',
                'username.digits' => 'The phone number must be 11 digits'
            ])->validate();
        }
    }

    public function registerTokenVerification($id, $token)
    {
        $user = User::where(['id' => $id, 'verificationToken' => $token])->first();

        if (empty($user)) {
            return 'Invalid request!!';
        } else {
            $user->is_verified = 1;
            $user->status = 1;
            $user->verificationToken = null;
            $user->save();
            $toName = $user->first_name . " " . $user->last_name;
            $toEmail = $user->email;
            $data = [
                'email' => $toEmail,
                'name' => $toName,
            ];
            Mail::send('mail.verified_success_email', $data, function ($message) use ($toName, $toEmail) {
                $message->to($toEmail)->subject('Tizaara Registration Verification Success!');
            });
            echo 'Verification email is success! Click to login <a href="' . config('services.siteUrl') . '/account/login' . '">www.tizaara.com/account/login</a>';
            return;
            return View('signup_verify_success');

        }
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findLoginWith($login)
    {
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        return $fieldType;
    }


    public function testOtp($phone)
    {
        if ($this->checkOtpSent($phone) == 0) $this->sendPhoneCode($phone, 'test-1234');
        // for check balance
        /*$post_url = 'https://portal.smsinbd.com/api/' ;
        $post_values = array(
            'api_key' => 'b1af6725e5e788d3e3096803f5953ef913c56873',
            'act' => 'balance',
            'method' => 'api'
        );

        $post_string = "";
        foreach( $post_values as $key => $value )
        { $post_string .= "$key=" . urlencode( $value ) . "&"; }
        $post_string = rtrim( $post_string, "& " );

        $request = curl_init($post_url);
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
        $post_response = curl_exec($request);
        curl_close ($request);

        $responses=array();
        $array =  json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $post_response), true );

        if($array){
            echo $array['balance'];
            echo $array['type'];
            print_r($array);
        }
        */
    }


    public function checkOtpSent($phone)
    {
        $response = 0;
        $nowDate = date('Y-m-d');
        $data = User::whereDate('created_at', '=', $nowDate)
            ->where('phone', 'LIKE', "%$phone%")
            ->where('is_verified', '=', "0")
            ->first();
        if (!empty($data)) {
            // $nowDate = date('Y-m-d H:i:s');
            $date1 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->timestamp;
            $date2 = \Carbon\Carbon::createFromFormat('Y-m-d', $nowDate)->timestamp;
            $diff = $date2 - $date1;
            if ($diff < 3601) {// if attempt within 60 min 3601
                $response = 1;
            }
        }
        //dd($data);
        return $response;
    }


    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $res = 'failed';
        $user = User::where('phone', 'LIKE', "%$request->phone%")
            ->where('verificationToken', '=', $request->otp)
            ->first();
        if (!empty($user)) {
            $res = 'success';
            $mgs = 'OTP Verification Success';
            $user->is_verified = 1;
            $user->status = 1;
            $user->save();

            return response()->json(['status' => $res, 'user' => ['phone' => $user->phone], 'messages' => $mgs]);
        }
        $mgs = 'Verification Failed! Please, try again with correct OTP sent to ' . $request->phone;
        return response()->json(['status' => $res, 'user' => ['phone' => $request->phone], 'messages' => $mgs]);


    }

    public function sendPhoneCode($phone, $otp)
    {
        $sentStatus = false;
        $message = "Your " . env('APP_NAME') . " mobile verification OTP code is " . $otp;

        if (ENV('SMS_GATEWAY') == 'greenweb') {
            // greenweb sms

            $to = $phone ?? '01814111176';
            $token = "bbec12acef3b509fcf05ab5ff68fb861";
            $url = "http://api.greenweb.com.bd/api.php";
            $data = array(
                'to' => $to,
                'message' => $message,
                'token' => $token
            );
            // Add parameters in key value
            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $smsResult = curl_exec($ch);
            $status = explode(':', $smsResult)[0];
            if ($smsResult !== false) {
                if ($status != 'Error') {
                    // save or update otp
                    User::updateOrCreate(
                        ['phone' => $phone],
                        ['verificationToken' => $otp]
                    );
                    $sentStatus = true;
                } else {
                    // Invalid Mobile Number
                }
            } else {
                // Problem with connection
            }
        } else {
            // sms in bd api
            //https://portal.smsinbd.com/smsapi?api_key=b1af6725e5e788d3e3096803f5953ef913c56873&type=text&contacts=8801814111176&senderid=8801552146120&msg=test&method=api
            $post_url = 'https://portal.smsinbd.com/smsapi/';
            $post_values = array(
                'api_key' => 'b1af6725e5e788d3e3096803f5953ef913c56873',
                'type' => 'text',  // unicode or text
                'contacts' => $phone ?? '8801814111176',
                'senderid' => '8801552146120',
                'msg' => 'test',
                'method' => 'api'
            );

            $post_string = "";
            foreach ($post_values as $key => $value) {
                $post_string .= "$key=" . urlencode($value) . "&";
            }
            $post_string = rtrim($post_string, "& ");
            $request = curl_init();
            curl_setopt($request, CURLOPT_URL, $post_url);
            curl_setopt($request, CURLOPT_ENCODING, '');
            curl_setopt($request, CURLOPT_HEADER, 0);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($post_values));
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
            $post_response = curl_exec($request);
            curl_close($request);
            $responses = array();
            $array = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $post_response), true);

            if ($array) {
                // save or update otp
                User::updateOrCreate(
                    ['phone' => $phone],
                    ['verificationToken' => $otp]
                );
                $sentStatus = true;
            }
            // end sms in bd
        }

        return $sentStatus;

    }


}
