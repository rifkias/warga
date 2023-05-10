<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    function __construct()
    {
        $this->ApiLog = new Apilog;
    }
    public function index()
    {
        $this->data['roles'] = $this->ApiLog->getRoleList();
        $this->data['wilayahs'] = $this->ApiLog->getWilayahList();
        return view('main.master.data.wilayah')->with($this->data);
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
}
