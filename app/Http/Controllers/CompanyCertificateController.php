<?php

namespace App\Http\Controllers;

use App\Model\CompanyCertificate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CompanyCertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $resutls= CompanyCertificate::paginate(PER_PAGE);
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
            'reference_number' => 'required|string',
            'issued_by' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'certificate_photo_name' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'status' =>  'required|numeric',
        ]);

        try {
            $postData=$request->except('certificate_photo_name');
            $postData['ip_address']=$request->ip();
            $compCertificagte= CompanyCertificate::create($postData);
            if ($request->hasFile('certificate_photo_name')) {
                $original_filename = $request->file('certificate_photo_name')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/company_certificates/';
                $image = $compCertificagte->id.'-' . time() . '.' . $file_ext;

                if ($request->file('certificate_photo_name')->move($destination_path, $image)) {
                    $compCertificagte->certificate_photo_name = '/upload/company_certificates/' . $image;
                }
            } else {
                $compCertificagte->certificate_photo_name ='';
            }
            $compCertificagte->save();

            return response()->json(['data' => $compCertificagte, 'message' => SAVE_SUCCESS], 201);
        } catch (\Exception $e) {
            $errMgs = $e->getMessage();
            return response()->json(['message' => $errMgs], 409);
        }
    }


    public function show($id)
    {
        try {
            $data = CompanyCertificate::findOrFail($id);

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
            $data = CompanyCertificate::query()
                ->where('name', 'LIKE', "%{$searchItem}%")
                ->orWhere('reference_number', 'LIKE', "%{$searchItem}%")
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
            'reference_number' => 'required|string',
            'issued_by' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'certificate_photo_name' => 'sometimes|image|mimes:jpeg,png,jpg|max:512',
            'status' =>  'required|numeric',
        ]);

        try {
            $data = $request->except('certificate_photo_name');
            $data['updated_by'] = 1;
            $data['updated_at'] = Carbon::now();
            $data['ip_address'] = $request->ip();
            CompanyCertificate::where('id', $id)->update($data);

            $compCert = CompanyCertificate::findOrFail($id);
            if ($request->hasFile('certificate_photo_name')) {
                $original_filename = $request->file('certificate_photo_name')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/company_certificates/';
                $image = $id.'-' . time() . '.' . $file_ext;

                if ($request->file('certificate_photo_name')->move($destination_path, $image)) {
                    $filename = base_path().'/public/'.$compCert->certificate_photo_name;
                    //dd($filename);
                    File::delete($filename);
                    $compCert->certificate_photo_name = '/upload/company_certificates/' . $image;
                }
            } else {
                $compCert->certificate_photo_name = '';
            }
            $compCert->save();

            return response()->json(['message' => UPDATE_SUCCESS,'results'=>$compCert], 200);
        } catch (\Exception $e) {
            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            CompanyCertificate::findOrFail($id)->delete();
            return response()->json(['message' => DELETE_SUCCESS], 200);

        } catch (\Exception $e) {

            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['errorCode'=>$errCode,'message' => $errMgs ], 500);
        }
    }

}