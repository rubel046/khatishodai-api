<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\CompanyCertificate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $resutls= Company::paginate(PER_PAGE);
        $items=$resutls->items();
        $meta=[
           'per_page'=> $resutls->perPage(),
           'total_page'=> $resutls->lastPage(),
           'total_item'=> $resutls->total(),
           'current_page'=> $resutls->currentPage()
        ];
        return response()->json(['results' => $items,'meta'=>$meta], 200);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|numeric',
            'name' => 'required|string|unique:company_basic_infos,name',
            'display_name' => 'required|string',
            'establishment_date' => 'required|date',
            'office_space' => 'required|string',
            'operation_address' => 'required|string',
            'website' => 'required|string|unique:company_basic_infos,website',
            'email' => 'required|email',
            'phone' => 'required|string',
            'cell' => 'required|string',
            'fax' => 'required|string',
            'number_of_employee' => 'required|numeric',
            'ownership_type' => 'required|numeric',
            'turnover_id' => 'required|numeric',
            'status' => 'required|numeric'
        ]);

        try {

            $postData=$request->all();
            $postData['ip_address']=$request->ip();
            $data= Company::create($postData);
            $data->ip_address = $request->ip();
            $data->save();
            return response()->json(['data' => $data, 'message' => SAVE_SUCCESS], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => ERROR_MSG,], 409);
        }
    }


    public function show($id)
    {
        try {
            $data = Company::findOrFail($id);

            return response()->json(['data' => $data], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => ERROR_MSG], 404);
        }
    }


    public function search(Request $request)
    {
        $this->validate($request,['searchStr'=>'required|string']);
        try {
            $searchItem = $request->searchStr;
            $data = Company::query()
                ->where('name', 'LIKE', "%{$searchItem}%")
                ->orWhere('website', 'LIKE', "%{$searchItem}%")
                ->get();
            if(!$data->isEmpty()){
                return response()->json(['data' => $data,'message' => DATA_FOUND], 200);
            }else{
                return response()->json(['data' => $data,'message' => NO_DATA], 404);
            }


        } catch (\Exception $e) {
            //dd($e);
            return response()->json(['message' => ERROR_MSG], 500);
        }

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:company_basic_infos,name,'.$id,
            'website' => 'required|string|unique:company_basic_infos,website,'.$id,
            'user_id' => 'required|numeric',
            'display_name' => 'required|string',
            'establishment_date' => 'required|date',
            'office_space' => 'required|string',
            'operation_address' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'cell' => 'required|string',
            'fax' => 'required|string',
            'number_of_employee' => 'required|numeric',
            'ownership_type' => 'required|numeric',
            'turnover_id' => 'required|numeric',
            'status' => 'required|numeric'
        ]);

        try {
            $data = $request->all();
            $data['updated_by'] = 1;
            $data['updated_at'] = Carbon::now();
            $data['ip_address'] = $request->ip();
            Company::where('id', $id)->update($request->all());
            return response()->json(['message' => UPDATE_SUCCESS], 200);
        } catch (\Exception $e) {
            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            Company::findOrFail($id)->delete();
            return response()->json(['message' => DELETE_SUCCESS], 200);
        } catch (\Exception $e) {

            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }

}