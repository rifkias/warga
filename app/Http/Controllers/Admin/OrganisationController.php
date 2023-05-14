<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Organisation;
use App\Models\Admin\OrganisationType;
use App\Http\Requests\Admin\OrganisationRequest;
use App\Http\Controllers\ApiLogController as ApiLog;
use DataTables,Session;
class OrganisationController extends Controller
{
    function __construct()
    {
        $this->ApiLog = new Apilog;
    }
    public function index()
    {

        $this->data['type'] = $this->ApiLog->getOrganisationType();
        $this->data['wilayahs'] = $this->ApiLog->getWilayahList();
        $this->data['parent'] = Organisation::with('wilayah')->nullParent()->get();
        return view('main.admin.organisation.organisation')->with($this->data);
    }
    public function dataTables(Request $request)
    {
        $datas = Organisation::with(['wilayah','type','parent','parent.wilayah']);
        if(auth()->user()->getRoles() <> 'superadmin'){
            $datas = $datas->where('id',auth()->user()->organisation_id)->orWhere('parent_id',auth()->user()->organisation_id);
        }

        return Datatables::of($datas)
        ->addIndexColumn()
        ->removeColumn('id')
        ->addColumn('wilayahName',function($data){
            return $data->wilayah->wilayah_name;
        })
        ->addColumn('parentName',function($data){
            $res = '-';
            if($data->parent){
                $res = $data->parent->wilayah->wilayah_name;
            }
            return $res;
        })
        ->addColumn('action',function($data){
            $button = "
            <button type='button' class='btn btn-danger btn-icon' onclick="."Delete(".$data->id.")".">
                <i class='mdi mdi-delete'></i>
            </button>";
            return $button;
        })
        ->make();
    }
    public function store(OrganisationRequest $request)
    {
        if(auth()->user()->role()->first()->name == 'superadmin'){
            Organisation::create($request->except('_token'));
        }else{
            Organisation::create($request->except('_token')+["parent_id"=>auth()->user()->organisation()->first()->id]);
        }
        Session::flash('success','Data Berhasil Ditambahkan');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        try{
            $data = Organisation::findOrFail($request->id);
            $data->delete();
            Session::flash('Success','Data Berhasil Dihapus');
            return $this->ApiLog->ReturnResult(200,'Data Berhasil Dihapus',$data,'');
        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Data Gagal Dihapus','',$e->getMessage());
        }
    }
}
