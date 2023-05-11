<?php

namespace App\Http\Controllers\MasterWilayah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterWilayah\Province;
use App\Models\MasterWilayah\City;
use App\Models\MasterWilayah\District;
use App\Models\MasterWilayah\Village;

class MainController extends Controller
{
    public function getProvince(Request $request)
    {
        $datas = new Province;
        if($request->has('name')){
            $datas = $datas->where('province_name',"like","%".$request->name."%");
        }
        return $datas->take(10)->get();
    }
    public function getCity(Request $request)
    {
        $datas = New City;
        if($request->has('province_id')){
            $datas = $datas->where('province_id',$request->province_id);
        }else{
            return null;
        }
        if($request->has('name')){
            $datas = $datas->where('city_name',"like","%".$request->name."%");
        }
        return $datas->take(10)->get();
    }
    public function getDistrict(Request $request)
    {
        $datas = New District;
        if($request->has('city_id')){
            $datas = $datas->where('city_id',$request->city_id);
        }
        if($request->has('name')){
            $datas = $datas->where('district_name',"like","%".$request->name."%");
        }
        return $datas->take(10)->get();
    }
    public function getVillage(Request $request)
    {
        $datas = New Village;
        if($request->has('district_id')){
            $datas =  $datas->where('district_id',$request->district_id);
        }
        if($request->has('name')){
            $datas = $datas->where('village_name',"like","%".$request->name."%");
        }
        return $datas->take(10)->get();
    }
}
