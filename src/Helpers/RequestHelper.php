<?php

namespace Defrindr\Crudify\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RequestHelper
{
    /**
     * Mengunggah gambar ke server
     *
     * @param  Request  $request
     * @param  string  $argName  nama argumen dari file eg: photo
     * @param  string  $path  lokasi file akan disimpan
     * @param  string|null  $oldFile  file lama yang akan dihapus ketika berhasil mengunggah file baru
     * @return array berisi parameter success & fileName
     */
    public static function uploadImage(UploadedFile $file, string $path, ?string $oldFile = null)
    {
        // Handle file Upload
        try {
            // Get filename with the extension
            $filenameWithExt = $file->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileName = $filename . '_' . time() . '.' . $extension;
            // Upload Image
            $file = $file->storeAs('public/' . $path, $fileName);

            // remove file is old file exist
            if ($oldFile && Storage::fileExists('public/' . $path . $oldFile)) {
                Storage::delete('public/' . $path . $oldFile);
            }

            $success = true;
        } catch (\Throwable $th) {
            $success = false;
            $fileName = null;
        }

        return compact('success', 'fileName');
    }
}
