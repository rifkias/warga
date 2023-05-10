<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Menu;
use App\Models\Admin\MenuPermission;
use App\Http\Controllers\ApiLogController as ApiLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\MenuRequest;
use DataTables,Session,File,Auth,URL;
class MenuController extends Controller
{
    function __construct()
    {
        $this->ApiLog = new Apilog;
    }
    public function index()
    {
        $this->data['parrentLists'] = Menu::nullParent()->get(['id','name']);
        return view('main.admin.menu')->with($this->data);
    }
    public function dataTables(Request $request)
    {
        $datas = Menu::orderBy('created_at','desc');
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
    public function store(MenuRequest $request)
    {
        Menu::create($request->except('_token'));
        Session::flash('success','Data Berhasil Ditambahkan');
        return redirect()->back();
    }
    public function show(Request $request)
    {
        try{
            $data = Menu::findOrFail($request->id);
            return $this->ApiLog->ReturnResult(200,'Success',$data,'');

        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Failed','',$e->getMessage());
        }
    }

    public function update(MenuRequest $request)
    {
        try{
            $data = Menu::findOrFail($request->id);
            $data->update($request->except(['_token','id']));
            Session::flash('success','Data Berhasil Diubah');
            return redirect()->back();

        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Failed','',$e->getMessage());
        }
    }
    public function destroy(Request $request)
    {
        try{
            $data = Menu::findOrFail($request->id);
            $data->delete();
            Session::flash('Success','Data Berhasil Dihapus');
            return $this->ApiLog->ReturnResult(200,'Data Berhasil Dihapus',$data,'');
        }catch(ModelNotFoundException $e){
            return $this->ApiLog->ReturnResult(500,'Data Gagal Dihapus','',$e->getMessage());
        }
    }
    public function test(Request $request)
    {
        return $this->ApiLog->getMenu();
        // $url = request()->path();
        // $url = explode('/',$url);
        // if($url[0] == 'dashboard' && !isset($url[1])){
        //     return true;
        // }
        // if($url[1]){
        //     $check = Menu::with('childs')->where('link',$url[1])->first();
        //     if($check){
        //         if($url[2]){
        //             $check2 = Menu::where(['link'=>$url[2],'parent'=>$check->id])->first();
        //             if($check2){
        //                 $attemp = MenuPermission::where(['menu_id'=>$check2->id,'role_id'=>Auth::user()->role_id])->first();
        //                 if($attemp){
        //                     return true;
        //                 }else{
        //                     Session::flash('warning',"You Doesn't Have Permisison to This Route, Please Contact Administrator");
        //                     return false;
        //                 }
        //             }
        //         }else{
        //             $attemp = MenuPermisison::where(['menu_id'=>$check->id,'role_id'=>Auth::user()->role_id])->first();
        //             if($attemp){
        //                     return true;
        //             }else{
        //                     Session::flash('warning',"You Doesn't Have Permisison to This Route, Please Contact Administrator");
        //                 return false;
        //             }
        //         }
        //     }else{
        //         Session::flash('warning',"Route Doesn't exists, Please Contact Administrator");
        //         return false;
        //     }
        // }
        // Session::flash('warning','Error Unknown');
        // return true;
    }
}
