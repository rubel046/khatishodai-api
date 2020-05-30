<?php

namespace App\Http\Controllers;

use App\Model\CompanyDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CompanyDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $resutls= CompanyDetail::paginate(PER_PAGE);
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
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'about_us' => 'required|string',
            'mission' => 'required|string',
            'vision' => 'required|string',
            'youtube_link' => 'required|string',
            'fb_link' => 'required|string',
            'status' =>  'required|numeric',
        ]);

        try {
            $postData=$request->except('logo');
            $postData['ip_address']=$request->ip();
            $compDtls= CompanyDetail::create($postData);
            if ($request->hasFile('logo')) {
                $original_filename = $request->file('logo')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/company_details/';
                $image = $compDtls->id.'-' . time() . '.' . $file_ext;

                if ($request->file('logo')->move($destination_path, $image)) {
                    $compDtls->logo = '/upload/company_details/' . $image;
                }
            } else {
                $compDtls->logo ='';
            }
            $compDtls->save();

            return response()->json(['data' => $compDtls, 'message' => SAVE_SUCCESS], 201);
        } catch (\Exception $e) {
            $errMgs = $e->getMessage();
            return response()->json(['message' => $errMgs], 409);
        }
    }


    public function show($id)
    {
        try {
            $data = CompanyDetail::findOrFail($id);

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
            $data = CompanyDetail::query()
                ->where('company_id', 'LIKE', "%{$searchItem}%")
                ->orWhere('about_us', 'LIKE', "%{$searchItem}%")
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
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg|max:512',
            'about_us' => 'required|string',
            'mission' => 'required|string',
            'vision' => 'required|string',
            'youtube_link' => 'required|string',
            'fb_link' => 'required|string',
            'status' =>  'required|numeric',
        ]);

        try {
            $data = $request->except('logo');
            $data['updated_by'] = 1;
            $data['updated_at'] = Carbon::now();
            $data['ip_address'] = $request->ip();
            CompanyDetail::where('id', $id)->update($data);

            $compDtls = CompanyDetail::findOrFail($id);
            if ($request->hasFile('logo')) {
                $original_filename = $request->file('logo')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/company_details/';
                $image = $id.'-' . time() . '.' . $file_ext;

                if ($request->file('logo')->move($destination_path, $image)) {
                    $filename = base_path().'/public/'.$compDtls->logo;
                    //dd($filename);
                    File::delete($filename);
                    $compDtls->logo = '/upload/company_details/' . $image;
                }
            } else {
                $compDtls->logo = '';
            }
            $compDtls->save();

            return response()->json(['message' => UPDATE_SUCCESS,'results'=>$compDtls], 200);
        } catch (\Exception $e) {
            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            CompanyDetail::findOrFail($id)->delete();
            return response()->json(['message' => DELETE_SUCCESS], 200);

        } catch (\Exception $e) {

            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }

}