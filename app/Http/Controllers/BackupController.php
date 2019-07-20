<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BackupController extends Controller 
{
    public function export(Request $request) 
    {
        return Excel::download(new UsersExport(), date('Y-m-d H:i:s', time()).' backup.xlsx');
    }
}