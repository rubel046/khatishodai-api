<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use  App\User;
use  App\Model\Company;
use App\Repositories\Repository;
use Illuminate\Http\Request;

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
        return response()->json(['user' => auth()->user()->load('address')->load('company')], 200);
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

    public function update(Request $request, $id)
    {
        $this->validation($request, $id);
        $data = $request->all();
        $data['photo'] = $this->uploadImage($request);

        return $this->model->update($data, $id);
    }

    public function company()
    {
        $userId = auth()->id();
        $companyInfo = Company::with('operationalAddress', 'registerAddress')->where('user_id', $userId)->first();
        return response()->json(['result' => $companyInfo], 200);


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

    private function validation(Request $request, $id = false)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'job_title' => 'required|string',
            'email' => 'email|string',
            'photo' => 'image|mimes:jpeg,png,jpg|max:512',
            'address' => 'string',
            'city_id' => 'numeric',
            'area_id' => 'numeric',
            'division_id' => 'numeric',
            'district_id' => 'numeric',
            'country_id' => 'numeric',
        ]);

    }

    private function uploadImage(Request $request)
    {
        if ($request->hasFile('photo')) {
            $file_ext = $request->file('photo')->clientExtension();
            $destination_path = base_path('public/upload/users');
            $image = uniqid() . '-' . time() . '.' . $file_ext;

            if ($request->file('photo')->move($destination_path, $image)) {
                return '/upload/brands/' . $image;
            }
        }
        return null;
    }


}
