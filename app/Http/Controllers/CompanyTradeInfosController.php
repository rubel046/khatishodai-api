<?php

namespace App\Http\Controllers;

use App\Model\CompanyTradeInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompanyTradeInfosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $resutls= CompanyTradeInfo::paginate(PER_PAGE);
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
            'annual_revenue_id' => 'required|numeric',
            'export_percent_id' => 'required|numeric',
            'status' => 'required|numeric',
            'created_by' => 'sometimes|numeric',
        ]);

        try {
            $postData = $request->all();
            $postData['ip_address'] = $request->ip();
            $compData= CompanyTradeInfo::create($postData);

            return response()->json(['data' => $compData, 'message' => SAVE_SUCCESS], 201);
        } catch (\Exception $e) {
            $errMgs = $e->getMessage();
            return response()->json(['message' => $errMgs], 409);
        }
    }


    public function show($id)
    {
        try {
            $data = CompanyTradeInfo::findOrFail($id);

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
            $data = CompanyTradeInfo::query()
                ->where('company_id', 'LIKE', "%{$searchItem}%")
                //->orWhere('annual_revenue_id', 'LIKE', "%{$searchItem}%")
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
            'annual_revenue_id' => 'required|numeric',
            'export_percent_id' => 'required|numeric',
            'status' => 'required|numeric',
            'updated_by' => 'sometimes|numeric',
        ]);

        try {
            $data=$request->all();
            //$data['updated_at'] = Carbon::now();
            $data['ip_address'] = $request->ip();
            CompanyTradeInfo::where('id', $id)->update($data);
            $compData = CompanyTradeInfo::findOrFail($id);

            return response()->json(['message' => UPDATE_SUCCESS,'results'=>$compData], 200);
        } catch (\Exception $e) {
            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            CompanyTradeInfo::findOrFail($id)->delete();
            return response()->json(['message' => DELETE_SUCCESS], 200);

        } catch (\Exception $e) {

            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }

}