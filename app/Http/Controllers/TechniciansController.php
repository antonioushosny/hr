<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Country;
use App\City; 
use App\Area; 
use App\Order; 
use App\Technician;
use App\Nationality;
use Auth;
use App;
use DB;
class TechniciansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'technicians';
        $allcountries = Country::where('id','<>','1')->get();
        $countries = array_pluck($allcountries,'name_ar', 'id');
        $allcities = City::where('id','<>','1')->get();
        $cities = array_pluck($allcities,'name_ar', 'id');
        $technicians = User::where('role','fannie')->orderBy('id', 'DESC')->get();
        return view('technicians.index',compact('technicians','countries','cities','title','lang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function add()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'technicians';

        if($lang=='ar')
        {
            $allcountries = Country::select('id',DB::raw('name_ar AS name'))->get();
            $allcities = City::select('id','country_id',DB::raw('name_ar AS name'))->get();
            $allareas = Area::select('id','city_id',DB::raw('name_ar AS name'))->get();
            $allnationalites = Nationality::select('id',DB::raw('name_ar AS name'))->get();
            
        }
        else
        {
            $allcountries = Country::select('id',DB::raw('name_en AS name'))->get();
            $allcities = City::select('id','country_id',DB::raw('name_en AS name'))->get();
            $allareas = Area::select('id','city_id',DB::raw('name_en AS name'))->get();
            $allnationalites = Nationality::select('id',DB::raw('name_en AS name'))->get();
        }
            $countries = array_pluck($allcountries,'name', 'id');
            $cities = array_pluck($allcities,'name', 'id');
            $areas = array_pluck($allareas,'name', 'id');
            $nationalites = array_pluck($allnationalites,'name', 'id');
        //return $countries;
        return view('technicians.add',compact('title','lang','countries','cities','areas','nationalites','allcities','allareas','allnationalites'));
    }
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
