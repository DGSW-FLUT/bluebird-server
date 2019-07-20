<?php

namespace App\Http\Controllers;

use App\Snapshot;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BackupController extends Controller 
{
    public function save(Request $request) 
    {
        $exports = new UsersExport();
        
        $snapshot = new SnapShot();
        $snapshot->dump_data = $exports->collection();

        $snapshot->save();

        return response()->json($snapshot, Response::HTTP_OK);
    }
}