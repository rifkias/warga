<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Wilayah;
use App\Http\Requests\Admin\WilayahRequest;
use App\Http\Controllers\ApiLogController as ApiLog;
use DataTables,Session;
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
    public function store(Request $request)
    {
        return $request->all();
    }
}
