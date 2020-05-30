<?php

namespace App\Http\Controllers;

use App\Model\CompanyNearestPort;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompanyNearestPortsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $resutls= CompanyNearestPort::paginate(PER_PAGE);
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
            'company_id' => 'required|numeric',
            'name' => 'required|string',
            'status' => 'required|numeric',
            'created_by' => 'sometimes|numeric',
        ]);

        try {
            $postData = $request->all();
            $postData['ip_address'] = $request->ip();
            $compData= CompanyNearestPort::create($postData);

            return response()->json(['data' => $compData, 'message' => SAVE_SUCCESS], 201);
        } catch (\Exception $e) {
            $errMgs = $e->getMessage();
            return response()->json(['message' => $errMgs], 409);
        }
    }


    public function show($id)
    {
        try {
            $data = CompanyNearestPort::findOrFail($id);

            return response()->json(['data' => $data], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => NO_DATA], 500);
        }
    }


    public function search(Request $request)
    {
        $this->validate($request,['searchStr'=>'required|string']);
        try {
            $searchItem = $request->searchStr;
            $data = CompanyNearestPort::query()
                ->where('company_id', 'LIKE', "%{$searchItem}%")
                ->orWhere('name', 'LIKE', "%{$searchItem}%")
                ->get();
                
            if(!$data->isEmpty()){
                return response()->json(['datas' => $data,'message' => DATA_FOUND], 200);
            }else{
                return response()->json(['datas' => $data,'message' => NO_DATA], 404);
            }


        } catch (\Exception $e) {
            $errMgs = $e->getMessage();
            return response()->json(['message' => $errMgs], 500);
        }

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'company_id' => 'required|numeric',
            'name' => 'required|string',
            'status' => 'required|numeric',
            'updated_by' => 'sometimes|numeric',
        ]);

        try {
            $data=$request->all();
            //$data['updated_at'] = Carbon::now();
            $data['ip_address'] = $request->ip();
            CompanyNearestPort::where('id', $id)->update($data);
            $compFactory = CompanyNearestPort::findOrFail($id);

            return response()->json(['message' => UPDATE_SUCCESS,'results'=>$compFactory], 200);
        } catch (\Exception $e) {
            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            CompanyNearestPort::findOrFail($id)->delete();
            return response()->json(['message' => DELETE_SUCCESS], 200);

        } catch (\Exception $e) {

            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }

}