<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;


class UserController extends Controller
{
    public function datatables(Request $request)
    {
        $datas = User::orderBy('id')
            ->get()
            ->map(function ($user) {
                $user->formatted_created_at = Carbon::parse($user->created_at)->format('d-m-Y H:i');
                return $user;
            });

        return Datatables::of($datas)
            ->addColumn('phone', function(User $data) {                                    
                return '<div class="action-list">'.$data->phone.'</div>';
            })
            ->addColumn('created_at', function(User $data) {
                return date("Y-m-d\TH:i:sP", strtotime($data->created_at)); 
            })
            ->addColumn('action', function(User $data) {
                $class = $data->block == 0 ? 'drop-success' : 'drop-danger';
                $s = $data->block == 1 ? 'selected' : '';
                $ns = $data->block == 0 ? 'selected' : '';
                $ban = '<select class="process select droplinks '.$class.'">'.
                    '<option data-val="0" value="'. route('admin-user-block',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Block</option>'.
                    '<option data-val="1" value="'. route('admin-user-block',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>UnBlock</option></select>';
                return '<div class="action-list">'.
                    '<a data-href="' . route('admin-user-edit',$data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>'.
                    $ban.
                    '</div>';
            }) 
            ->rawColumns(['phone','created_at','action'])
            ->toJson(); // Returning Json Data To Client Side
    }

    public function index(Request $request){
        
        return view('admin.user.index');
    }

    public function block($id1,$id2)
    {
        $user = User::findOrFail($id1);
        $user->ban = $id2;
        $user->update();
        $msg[0] = 'success';
        return response()->json($msg);     
    }

    

}
