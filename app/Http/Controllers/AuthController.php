<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Helper\CommonHelper;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use  App\User;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use App\Model\Company;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'emailOrPhone' => 'required|string|unique:users,userName',
            'password' => 'required|confirmed|min:6',// password_confirmation ( field is Required)
            /*'email' => 'required|email|unique:users',
            'phone' => 'unique:users',*/
            'userType' => 'required|numeric',
            'country' => 'required|numeric',
            /*'companyName' => 'required|string',*/
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'messages' => 'User registration failed, Data not save to record.'], 422);
        }
        $login = $this->findLoginWith($request->emailOrPhone);

        $email = $phone = null;
        if ($login == 'email') {
            $email = $request->input('emailOrPhone');
            $validator = Validator::make($request->all(), [
                'emailOrPhone' => 'email|unique:users,email',
            ], [
                'emailOrPhone.email' => 'Invalid email or Phone Number',
                'emailOrPhone.unique' => 'The email is already taken'
            ]);
        } else {
            $phone = $request->input('emailOrPhone');
            $validator = Validator::make($request->all(), [
                'emailOrPhone' => 'numeric|digits:11|unique:users,phone',
            ], [
                'emailOrPhone.numeric' => 'Invalid email or Phone Number',
                'emailOrPhone.unique' => 'The phone number is already taken',
                'emailOrPhone.digits' => 'The phone number must be 11 digits'

            ]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'messages' => 'Operation Not success!'], 422);
        }

        try {
            $user = new User;
            $user->first_name = $request->input('firstName');
            $user->last_name = $request->input('lastName');
            $user->userName = $request->input('emailOrPhone');
            $user->email = $email;
            $user->account_type = $request->input('userType');
            $user->phone = $phone;
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->is_verified = 0;
            $user->save();

            if ($request->companyName) {
                // Insert Company Name
                $companyData = new Company();
                $companyData->user_id = $user->id;
                $companyData->name = $request->input('companyName');
                $companyData->ip_address = $request->ip();
                $companyData->created_by = $user->id;
                $companyData->save();
            }

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
                    return response()->json(['messages' => 'Registration form submitted successfully!, Email sending failed. Contact with admin'], 409);
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
                    return response()->json(['messages' => 'Registration form submitted successfully!, Mobile OTP sending failed. Contact with admin'], 201);
                }
            }

        } catch (\Exception $e) {
            return response()->json(['messages' => $e->getMessage()], 409);
            return response()->json(['messages' => 'User Registration Failed!'], 409);
        }

    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        //validate incoming request
        $login = $this->findLoginWith($request->emailOrPhone);
        $validator = Validator::make($request->all(), [
            'emailOrPhone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        //$credentials = $request->only(['email', 'password']);
        $credentials = [];
        if ($login == 'email') {
            $credentials = [
                'email' => $request->emailOrPhone,
                'password' => $request->password,
                'status' => 1
            ];
        } else {
            $credentials = [
                'phone' => $request->emailOrPhone,
                'password' => $request->password,
                'status' => 1
            ];
        }
        if (!$token = Auth::attempt($credentials, ['expires_in' => Carbon::now()->addDays(7)->timestamp])) {
            return response()->json(['errors' => ['message' => 'You enter user name password is invalid']], 404);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        if (Auth::user()) {
            Auth::invalidate();
            return response()->json(['message' => "Logged out"], 200);
        } else {
            return response()->json(['message' => "Invalid token"], 200);
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
         $this->sendPhoneCode($phone,'test-code');
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

    public function sendPhoneCode($phone, $otp, $gateWay = null)
    {
        $gateWay = $gateWay ?? ENV('SMS_GATEWAY');
       // echo $gateWay; die();
        $sentStatus = false;
        $message = "Your tizaara mobile verification OTP code is " . $otp;

        if ($gateWay == 'greenweb') {
            // greenweb sms

            $to = $phone ?? '01814111176';
            $token = ENV('GREEN_WEB_SMS_TOKEN');
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
                'api_key' => ENV('SMS_BD_API_KEY'),// 'b1af6725e5e788d3e3096803f5953ef913c56873',
                'type' => 'text',  // unicode or text
                'contacts' => $phone ?? '8801814111176',
                'senderid' => ENV('SMS_BD_SENDER_ID'), //'8801844502926',
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
            dd($post_response);
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
dd($sentStatus);
        return $sentStatus;

    }


}
