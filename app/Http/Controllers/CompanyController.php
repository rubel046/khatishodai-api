<?php

namespace App\Http\Controllers;

use App\Model\Company;
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
            'name' => 'required|string|unique:companies,name',
            'web_url' => 'required|string|unique:companies,web_url',
            'business_type_id' => 'required|numeric',
            'address' => 'required|string',
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
                ->orWhere('web_url', 'LIKE', "%{$searchItem}%")
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
            'name' => 'required|string|unique:companies,name,'.$id,
            'web_url' => 'required|string|unique:companies,web_url,'.$id,
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
            return response()->json(['error code'=>$errCode,'message' => $errMgs ], 500);
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
            return response()->json(['error code'=>$errCode,'message' => $errMgs ], 500);
        }
    }

}