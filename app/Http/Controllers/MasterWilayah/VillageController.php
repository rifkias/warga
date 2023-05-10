<?php

namespace App\Http\Controllers\MasterWilayah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterWilayah\Village;
use App\Http\Requests\MasterWilayah\VillageRequest;
use App\Http\Controllers\ApiLogController as ApiLog;
use DataTables,Session;
class VillageController extends Controller
{
    function __construct()
    {
        $this->ApiLog = new Apilog;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('main.master.wilayah.village');
    }
    public function dataTables(Request $request)
    {
        $datas = Village::with('district','district.city','district.city.province');
        // return $datas;
        return Datatables::of($datas)
        ->addIndexColumn()
        ->removeColumn('id')
        ->addColumn('action',function($data){
            $button = "
            <button type='button' class='btn btn-danger btn-icon' onclick="."Delete(".$data->id.")".">
                <i class='mdi mdi-delete'></i>
            </button>";
            return $button;
        })
        ->make(true);
    }
    public function store(VillageRequest $request)
    {
        Village::create($request->except(['_token','id']));
        Session::flash('success','Data Berhasil Ditambahkan');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        try{
            $data = Village::findOrFail($request->id);
            $data->delete();
            Session::flash('Success','Data Berhasil Dihapus');
            return $this->ApiLog->ReturnResult(200,'Data Berhasil Dihapus',$data,'');
        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Data Gagal Dihapus','',$e->getMessage());
        }
    }
}
