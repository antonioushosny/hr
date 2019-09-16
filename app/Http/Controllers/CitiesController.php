<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\emailnotify;
use App\City;
use App\Area;
use App\Country;
use Auth;
use App;
class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
     
    }
    public function index()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'cities';
        $allcountries = Country::where('id','<>','1')->get();
        $countries = array_pluck($allcountries,'name_ar', 'id'); $allcountries = Country::all();
        $countries = array_pluck($allcountries,'name_ar', 'id');
        $cities = City::with('country')->orderBy('id', 'DESC')->get();
        // return $admins ; 
        return view('cities.index',compact('cities','countries','title','lang'));

    }

    public function areas(Request $request, $id) {
        // return $id ;
        if ($request->ajax()) {
            $lang = App::getlocale();
            if($lang == 'ar'){
                $areas = Area::where('city_id', $id)->select('name_ar AS name','id')->get();
            }else{
                $areas = Area::where('city_id', $id)->select('name_en AS name','id')->get();
            }
            return response()->json([
                'areas' => $areas ,
            ]);
        }
    }
    public function add()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'cities';
        $allcountries = Country::all();
        if($lang == 'ar'){
            $countries = array_pluck($allcountries,'name_ar', 'id'); 
        }else{
            $countries = array_pluck($allcountries,'name_en', 'id');
        }
        return view('cities.add',compact('title','countries','lang'));
    }
    public function store(Request $request)
    {
        
        if($request->id ){
            $rules =
            [
                'name_ar'  =>'required|arabic|max:190',           
                'name_en'  =>'required|english|max:190', 
                'country_id'  =>'required',           
                'status'  =>'required',   
            ];
            
        }     
    
        else{
            $rules =
            [
                'name_ar'  =>'required|arabic|max:190',           
                'name_en'  =>'required|english|max:190',              
                // 'image'  =>'required',           
                'country_id'  =>'required',     
                'status'  =>'required'      
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         
        // return $request ;
        if($request->id ){
            $city = City::find( $request->id );
        }
        else{
            $city = new City ;

        }

        $city->name_ar          = $request->name_ar ;
        $city->name_en         = $request->name_en ;
        $city->country_id         = $request->country_id ;
        $city->status        = $request->status ;
        $city->save();

        $city->save();
        $city = City::where('id',$city->id)->with('country')->first();
        return response()->json($city);

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'cities';
        $citie = City::where('id',$id)->with('country')->orderBy('id', 'DESC')->first();
        if($citie)
        {
            $allcountries = Country::all();
            if($lang == 'ar'){
                $countries = array_pluck($allcountries,'name_ar', 'id'); 
            }else{
                $countries = array_pluck($allcountries,'name_en', 'id');
            }
            // return $admin ; 
            return view('cities.edit',compact('citie','countries','title','lang'));

        }
        else
        {
            return redirect(url('error'));
        }
    }

    public function update(Request $request, $id)
    {

      
    }


    public function destroy($id)
    {
       
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $id = City::find( $id );
        $id ->delete();
        return response()->json($id);
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
            foreach($request->ids as $id){
                $id = city::find($id);
            }
            $ids = City::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
    }
}
