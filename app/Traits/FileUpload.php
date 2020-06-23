<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait FileUpload
{
    /**
     * Image upload trait used in controllers to upload files
     */

    protected function saveImages(Request $request, $file, $folder)
    {
        $file_ext = $request->file($file)->clientExtension();
        $file_name = uniqid() . '.' . $file_ext;
        $destinationPath = base_path('public/upload/' . $folder);
        if ($request->file($file)->move($destinationPath, $file_name)) {
            return '/upload/'.$folder.'/' . $file_name;
        }
        return $file_name;
    }
}
