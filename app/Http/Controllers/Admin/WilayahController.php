<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Wilayah;
use App\Http\Requests\Admin\WilayahRequest;
use App\Http\Controllers\ApiLogController as ApiLog;
use DataTables,Session,Auth;
class WilayahController extends Controller
{
    function __construct()
    {
        $this->ApiLog = new Apilog;
    }
    public function index()
    {
        return view('main.master.data.wilayah');
    }
    public function dataTables(Request $request)
    {

        $datas = Wilayah::query();
        return Datatables::of($datas)
        ->addIndexColumn()
        ->removeColumn('id')
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
    public function store(WilayahRequest $request)
    {
        Wilayah::create($request->except(['_token','id'])+['negara'=>'Indonesia']);
        Session::flash('success','Data Berhasil Ditambahkan');
        return redirect()->back();
    }
    public function show(Request $request)
    {
        try{
            $data = Wilayah::findOrFail($request->id);
            return $this->ApiLog->ReturnResult(200,'Success',$data,'');

        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Failed','',$e->getMessage());
        }
    }
    public function update(WilayahRequest $request)
    {
        try{
            $data = Wilayah::findOrFail($request->id);
            $data->update($request->except(['_token']));
            Session::flash('success','Data Berhasil Diubah');
            return redirect()->back();

        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Failed','',$e->getMessage());
        }
    }
    public function destroy(Request $request)
    {
        try{
            $data = Wilayah::findOrFail($request->id);
            $data->delete();
            Session::flash('Success','Data Berhasil Dihapus');
            return $this->ApiLog->ReturnResult(200,'Data Berhasil Dihapus',$data,'');
        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Data Gagal Dihapus','',$e->getMessage());
        }
    }
}
