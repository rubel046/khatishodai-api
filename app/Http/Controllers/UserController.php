<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use  App\User;
use App\Repositories\Repository;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    private $model;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->model = new Repository($user);
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Get all User.
     *
     * @return Response
     */
    public function index()
    {
        return $this->model->paginate();
    }

    /**
     * Get one user.
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->model->show($id);

    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logout'], 200);
    }


    //send email
    public function sendEmail()
    {
        $to_name = "Shamim reza";
        $to_email = "mrezashamim@gmail.com";
        $data = [
            'name' => 'Jhone due',
            'body' => 'test email body'
        ];
        Mail::send('mail.test_mail', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email)->subject('Tizaara api email test');
        });
        echo 'success!';
        //Mail::to('somebody@example.org')->send(new MyEmail());
    }


}