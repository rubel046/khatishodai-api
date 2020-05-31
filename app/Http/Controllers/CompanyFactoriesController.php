<?php

namespace App\Http\Controllers;

use App\Model\CompanyFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompanyFactoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $resutls= CompanyFactory::paginate(PER_PAGE);
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
            'location' => 'required|string',
            'size_id' => 'required|numeric',
            'staff_number_id' => 'required|numeric',
            'rnd_staff_id' => 'required|numeric',
            'production_line_id' => 'required|numeric',
            'annual_output_id' => 'required|numeric',
            'status' => 'required|numeric',
        ]);

        try {
            $postData = $request->all();
            $postData['ip_address'] = $request->ip();
            $compFactory= CompanyFactory::create($postData);

            return response()->json(['data' => $compFactory, 'message' => SAVE_SUCCESS], 201);
        } catch (\Exception $e) {
            $errMgs = $e->getMessage();
            return response()->json(['message' => $errMgs], 409);
        }
    }


    public function show($id)
    {
        try {
            $data = CompanyFactory::findOrFail($id);

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
            $data = CompanyFactory::query()
                ->where('company_id', 'LIKE', "%{$searchItem}%")
                ->orWhere('location', 'LIKE', "%{$searchItem}%")
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
            'location' => 'required|string',
            'size_id' => 'required|numeric',
            'staff_number_id' => 'required|numeric',
            'rnd_staff_id' => 'required|numeric',
            'production_line_id' => 'required|numeric',
            'annual_output_id' => 'required|numeric',
            'status' => 'required|numeric',
        ]);

        try {
            $data=$request->all();
            $data['updated_by'] = 1;
            $data['updated_at'] = Carbon::now();
            $data['ip_address'] = $request->ip();
            CompanyFactory::where('id', $id)->update($data);
            $compFactory = CompanyFactory::findOrFail($id);

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
            CompanyFactory::findOrFail($id)->delete();
            return response()->json(['message' => DELETE_SUCCESS], 200);

        } catch (\Exception $e) {

            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }

}