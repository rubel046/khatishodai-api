<?php

namespace App\Http\Controllers;

use App\Model\CompanyPhoto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CompanyPhotosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $resutls= CompanyPhoto::paginate(PER_PAGE);
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
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'status' =>  'required|numeric',
            'created_by' => 'sometimes|numeric',
        ]);

        try {
            $postData=$request->except('photo');
            $postData['ip_address']=$request->ip();
            $compData= CompanyPhoto::create($postData);
            if ($request->hasFile('photo')) {
                $original_filename = $request->file('photo')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/company_photo/';
                $image = $compData->id.'-' . time() . '.' . $file_ext;

                if ($request->file('photo')->move($destination_path, $image)) {
                    $compData->photo = '/upload/company_photo/' . $image;
                }
            } else {
                $compData->photo ='';
            }
            $compData->save();

            return response()->json(['data' => $compData, 'message' => SAVE_SUCCESS], 201);
        } catch (\Exception $e) {
            $errMgs = $e->getMessage();
            return response()->json(['message' => $errMgs], 409);
        }
    }


    public function show($id)
    {
        try {
            $data = CompanyPhoto::findOrFail($id);

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
            $data = CompanyPhoto::query()
                ->where('company_id', 'LIKE', "%{$searchItem}%")
               //` ->orWhere('about_us', 'LIKE', "%{$searchItem}%")
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
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg|max:512',
            'status' =>  'required|numeric',
            'updated_by' => 'sometimes|numeric',
        ]);

        try {
            $data = $request->except('photo');
            $data['ip_address'] = $request->ip();
            CompanyPhoto::where('id', $id)->update($data);

            $compData = CompanyPhoto::findOrFail($id);
            if ($request->hasFile('photo')) {
                $original_filename = $request->file('photo')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/company_photo/';
                $image = $id.'-' . time() . '.' . $file_ext;

                if ($request->file('photo')->move($destination_path, $image)) {
                    $filename = base_path().'/public/'.$compData->photo;
                    File::delete($filename);
                    $compData->photo = '/upload/company_photo/' . $image;
                }
            } else {
                $compData->photo = '';
            }
            $compData->save();

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
            CompanyPhoto::findOrFail($id)->delete();
            return response()->json(['message' => DELETE_SUCCESS], 200);

        } catch (\Exception $e) {

            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }

}