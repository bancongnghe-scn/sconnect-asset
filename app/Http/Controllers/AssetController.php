<?php

namespace App\Http\Controllers;

use App\Imports\AssetImport;
use App\Support\Constants\AppErrorCode;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AssetController extends Controller
{
    public function importAsset(Request $request)
    {
        $file = $request->file('excel_file');
        // Kiểm tra file có hợp lệ không
        if (!$file) {
            return response_error(AppErrorCode::CODE_1000, 'File không hợp lệ');
        }

        $import = new AssetImport();
        Excel::import($import, $file);
        if (!empty($import->listError)) {
            return response_error(extraData: $import->listError);
        }

        return response_success();
    }
}
