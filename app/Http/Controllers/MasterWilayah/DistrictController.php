<?php

namespace App\Http\Controllers\MasterWilayah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterWilayah\District;
use App\Models\MasterWilayah\City;
use App\Models\MasterWilayah\Province;
use App\Http\Requests\MasterWilayah\DistrictRequest;
use App\Http\Controllers\ApiLogController as ApiLog;
use DataTables,Session;
class DistrictController extends Controller
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

        return view('main.master.wilayah.district');
    }
    public function dataTables(Request $request)
    {
        $datas = District::with('city','city.province');

        return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('action',function($data){
            $button = "
            <button type='button' class='btn btn-danger btn-icon' onclick="."Delete(".$data->id.")".">
                <i class='mdi mdi-delete'></i>
            </button>";
            return $button;
        })
        ->make();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DistrictRequest $request)
    {
        District::create($request->except(['_token','province_id']));
        Session::flash('success','Data Berhasil Ditambahkan');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
            $data = District::findOrFail($request->id);
            $data->delete();
            Session::flash('Success','Data Berhasil Dihapus');
            return $this->ApiLog->ReturnResult(200,'Data Berhasil Dihapus',$data,'');
        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Data Gagal Dihapus','',$e->getMessage());
        }
    }
}
