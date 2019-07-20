<?php

namespace App\Http\Controllers;

use App\BackupUser;
use App\User;
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
        $snapshot = new SnapShot();

        $user_data = User::withTrashed()->get();

        $snapshot->dump_data = json_encode($user_data);

        $snapshot->save();

        return response()->json($snapshot, Response::HTTP_OK);
    }

    public function export(Request $request, $id){
        return Excel::download(new UsersExport($id), date('Y-m-d H:i:s', time()).' backup.xlsx');
    }

    public function rollback(Request $request, $id){
        $dump_data = Snapshot::findOrFail($id);
        $dump_array = (array)json_decode($dump_data->dump_data);

        User::truncate();

        foreach($dump_array as $row){
            $user = new BackupUser();
            $user->id = $row->id;
            $user->name = $row->name;
            $user->birth = $row->birth;
            $user->zip_code = $row->zip_code;
            $user->address = $row->address;
            $user->job = $row->job;
            $user->level = $row->level;
            $user->phone_number = $row->phone_number;
            $user->created_at = $row->created_at;
            $user->updated_at = $row->updated_at;
            $user->deleted_at = $row->deleted_at;
            
            $user->save();
        }

        $result = User::all();
        return response()->json($result, Response::HTTP_OK);
    }

    public function show(Request $request){
        $snapshots = Snapshot::all();

        return response()->json($snapshots, Response::HTTP_OK);
    }

    public function destroy(Request $request, $id){
        $snapshot = Snapshot::findOrFail($id);

        $snapshot->destroy();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}