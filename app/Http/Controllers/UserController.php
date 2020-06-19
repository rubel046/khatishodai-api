<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use  App\User;
use  App\Model\Address;
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
    use ApiResponse;
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
        /*
         % of profile completion will be defined as per the below weights -
            Personal information: 50
            Company basic: 25
            Company detail: 5
            Certification: 10
            Factory details: 5
            Trade details: 5
         * */
        $personalInfo = 50;
        $companyBasic = 25;
        $companyDtls = 5;
        $companyCertification = 10;
        $companyFactoryDtls = 5;
        $companyTrade = 5;
        $authUserData = auth()->user()->load('address')->load('company')->toArray();

        if ($authUserData['photo'] == '') $personalInfo -= 3;
        if ($authUserData['email'] == '') $personalInfo -= 3;
        if ($authUserData['phone'] == '') $personalInfo -= 3;
        if ($authUserData['telephone'] == '') $personalInfo -= 3;
        if ($authUserData['job_title'] == '') $personalInfo -= 3;
        if (empty($authUserData['address'])) $personalInfo -= 25;

        $customerCompany = Company::whereUserId(auth()->id())->with('operationalAddress', 'registerAddress', 'CompanyDetail', 'company_certificate', 'CompanyFactory', 'CompanyTradeInfo')->get()->toArray();
        if (!empty($customerCompany)) {
            if ($customerCompany[0]['display_name'] == '') --$companyBasic;
            if ($customerCompany[0]['establishment_date'] == '') --$companyBasic;
            if ($customerCompany[0]['website'] == '') --$companyBasic;
            if ($customerCompany[0]['email'] == '') --$companyBasic;
            if ($customerCompany[0]['phone'] == '') --$companyBasic;
            if ($customerCompany[0]['cell'] == '') --$companyBasic;
            if ($customerCompany[0]['fax'] == '') --$companyBasic;
            if ($customerCompany[0]['number_of_employee'] == '') --$companyBasic;
            if ($customerCompany[0]['ownership_type'] == '') --$companyBasic;
            if ($customerCompany[0]['ownership_type'] == '') --$companyBasic;
            if (empty($customerCompany[0]['operational_address'])) $companyBasic -= 5;
            if (empty($customerCompany[0]['register_address'])) $companyBasic -= 5;

            if (empty($customerCompany[0]['company_detail'])) $companyDtls = 0;
            if (empty($customerCompany[0]['company_certificate'])) $companyCertification = 0;
            if (empty($customerCompany[0]['company_factory'])) $companyFactoryDtls = 0;
            if (empty($customerCompany[0]['company_trade_info'])) $companyTrade = 0;

        } else {
            $companyBasic = 0;
        }
        $authUserData['profile_completion'] = $personalInfo + $companyBasic + $companyDtls + $companyCertification + $companyFactoryDtls + $companyTrade;
        $customerDetailsInfo = ['user' => $authUserData];
        // return $this->showMessage($customerDetailsInfo);
        return response()->json($customerDetailsInfo, 200);
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
        $address = $request->address;
        if (!empty($address)) auth()->user()->address()->updateOrCreate(['addressable_id' => auth()->id(), 'addressable_type' => User::class], $address);
        $data = $request->except('address');
        $data['photo'] = $this->uploadImage($request);

        $this->model->update($data, $id);
        return redirect()->to('account/profile');

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
            'address' => 'array',
        ]);

    }

    private function uploadImage(Request $request)
    {
        if ($request->hasFile('photo')) {
            $file_ext = $request->file('photo')->clientExtension();
            $destination_path = base_path('public/upload/users');
            $image = uniqid() . '-' . time() . '.' . $file_ext;

            if ($request->file('photo')->move($destination_path, $image)) {
                return '/upload/users/' . $image;
            }
        }
        return null;
    }


}
