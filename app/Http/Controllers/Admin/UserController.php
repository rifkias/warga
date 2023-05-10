<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\User;
use App\Http\Controllers\ApiLogController as ApiLog;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\UserRequest;

use DataTables,Session,Auth;
class UserController extends Controller
{
    function __construct()
    {
        $this->ApiLog = new Apilog;
    }
    public function index()
    {
        $this->data['roles'] = $this->ApiLog->getRoleList();
        $this->data['wilayahs'] = $this->ApiLog->getWilayahList();
        return view('main.admin.users')->with($this->data);
    }
    public function dataTables(Request $request)
    {
        $datas = User::with(['role','wilayah']);
        $datas->where('id','!=',Auth::user()->id);
        $datas->orderBy('created_at','desc');
        return Datatables::of($datas)
        ->addIndexColumn()
        ->removeColumn('id')
        ->addColumn('wilayahName',function($data){
            return $data->wilayah->wilayah_name;
        })
        ->addColumn('action',function($data){
            $button = "
            <button type='button' onclick="."Edit(".$data->id.")"." class='btn btn-warning btn-icon'>
            <i class='mdi mdi-pencil'></i>
            </button>";
            $button .= "
            <button type='button' class='btn btn-danger btn-icon' onclick="."Delete(".$data->id.")".">
                <i class='mdi mdi-delete'></i>
            </button>";
            return $button;
        })
        ->make();
    }
    public function store(UserRequest $request)
    {
        // return $request->all();
        User::create($request->except(['_token','id']));
        Session::flash('success','Data Berhasil Ditambahkan');
        return redirect()->back();
    }
    public function show(Request $request)
    {
        try{
            $data = User::findOrFail($request->id);
            return $this->ApiLog->ReturnResult(200,'Success',$data,'');

        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Failed','',$e->getMessage());
        }
    }
    public function destroy(Request $request)
    {
        try{
            $data = User::findOrFail($request->id);
            $data->delete();
            Session::flash('Success','Data Berhasil Dihapus');
            return $this->ApiLog->ReturnResult(200,'Data Berhasil Dihapus',$data,'');
        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Data Gagal Dihapus','',$e->getMessage());
        }
    }
    public function update(UserRequest $request)
    {
        try{
            $data = User::findOrFail($request->id);
            $data->update($request->except(['_token']));
            Session::flash('success','Data Berhasil Diubah');
            return redirect()->back();

        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Failed','',$e->getMessage());
        }
    }
}
