<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\MenuPermission;
use App\Models\Admin\Menu;
use App\Models\Admin\Role;
use App\Http\Requests\Admin\MenuPermissionRequest;
use App\Http\Controllers\ApiLogController as ApiLog;
use DataTables,Session;
class MenuPermissionController extends Controller
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
        $this->data['menus'] = Menu::nullChild()->active()->orHasParent()->active()->select('id','name')->get();
        $this->data['roles'] = Role::select('id','name')->get();

        return view('main.admin.menuPermission')->with($this->data);
    }
    public function dataTables(Request $request)
    {
        $datas = MenuPermission::with(['menu','role']);
        return Datatables::of($datas)
        ->addIndexColumn()
        // ->removeColumn('id')
        ->removeColumn(['menu.id','role.id','menu_id','role_id'])
        ->addColumn('action',function($data){
            $button = "<button type='button' class='btn btn-danger btn-icon' onclick="."Delete('".$data->id."')"."><i class='mdi mdi-delete'></i></button>";

            return $button;
        })
        ->make();
    }
            // echo json_encode($data);
            // echo $data->id;
            // echo "<br><br> \n \n";
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuPermissionRequest $request)
    {
        $check = MenuPermission::where('menu_id',$request->menu_id)->where('role_id',$request->role_id)->first();
        if($check){
            Session::flash('warning','Data Sudah Ada !!!!');
        }else{
            MenuPermission::create($request->except('_token'));
            Session::flash('success','Data Berhasil Ditambahkan');
        }
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
            $data = MenuPermission::findOrFail($request->id);
            $data->delete();
            Session::flash('Success','Data Berhasil Dihapus');
            return $this->ApiLog->ReturnResult(200,'Data Berhasil Dihapus',$data,'');
        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Data Gagal Dihapus','',$e->getMessage());
        }
    }
}
