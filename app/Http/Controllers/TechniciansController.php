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
use App\Service;
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
            $allservices = Service::select('id',DB::raw('name_ar AS name'))->get();
            
        }
        else
        {
            $allcountries = Country::select('id',DB::raw('name_en AS name'))->get();
            $allcities = City::select('id','country_id',DB::raw('name_en AS name'))->get();
            $allareas = Area::select('id','city_id',DB::raw('name_en AS name'))->get();
            $allnationalites = Nationality::select('id',DB::raw('name_en AS name'))->get();
            $allservices = Service::select('id',DB::raw('name_en AS name'))->get();
        }
            $countries = array_pluck($allcountries,'name', 'id');
            $cities = array_pluck($allcities,'name', 'id');
            $areas = array_pluck($allareas,'name', 'id');
            $nationalites = array_pluck($allnationalites,'name', 'id');
            $services = array_pluck($allservices,'name', 'id');
        //return $countries;
        return view('technicians.add',compact('title','lang','countries','cities','areas','nationalites','services','allcities','allareas','allnationalites','allservices'));
    }
    public function maps()
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
        //return $technicians;
        return view('technicians.map',compact('technicians','countries','cities','title','lang'));
        
    }

    public function changestatus($id)
    {
        $title =  'technicians' ;
        $user = User::where('id',$id)->first();
        if($user){
            if($user->status == 'active'){
                $user->status = 'not_active' ;
            }
            else{
                $user->status = 'active' ;                    
            }
            $user->save();
            return redirect()->route('technicians');
        }
        else
        {
            return redirect(url('error'));
        }
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
        //return $request;

       if($request->id ){
        $rules =
        [
            'name'  =>'required|max:190',
            'email'  =>'required|email|max:190', 
             'mobile'=>'between:8,14',  
             'address'=>'required|max:200',
             'location.*'=>'required',         
             'status'  =>'required',    
            'country_id'  =>'required',  
            'city_id'  =>'required',  
            'area_id'  =>'required',  
            'nationality_id'  =>'required',  
            'service_id'=>'required', 
        ];
        
    }     

        else{
            $rules =
            [
                'name'  =>'required|max:190',
                'email'  =>'required|email|unique:users,email|max:190',  
                'mobile'=>'required|between:8,14|unique:users,mobile',
                'address'=>'required|max:200',
                'location.*'=>'required',
                'status'  =>'required',  
                'country_id'  =>'required',  
                'city_id'  =>'required',  
                'area_id'  =>'required',  
                'nationality_id'  =>'required', 
                'service_id'=>'required',       
            ];
        }
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        if($request->id ){
            $user = User::find( $request->user_id );
            if($user->mobile != $request->mobile)
            {
               $rules=['mobile'=>'required|between:8,14|unique:users,mobile',];
            }
            if($user->email != $request->email)
            {
                $rules=['email'=>'required|email|unique:users,email|max:190',];
            }
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            $user->email=$request->email;
            $user->mobile=$request->mobile;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                if($user->image)
                {
                    \File::delete(public_path(). '/img/' . $user->image);

                }
            }

            $technical=Technician::find( $request->id );
            if ($request->hasFile('identity')) {
                $image = $request->file('identity');
                if($technical->identity_photo)
                {
                    \File::delete(public_path(). '/img/' . $technical->identity_photo);

                }
            }
        }
        else{
            $user = new User ;
            $user->email=$request->email;
            $user->mobile=$request->mobile;
            $technical=new Technician;


        }

        $user->name          = $request->name ;
        $user->address         = $request->address ;
        $user->status        = $request->status ;
        $user->lat          =$request->location[0];
        $user->lng         =$request->location[1];
        $user->role         ='fannie';
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $user->image   = $name;  
        }
        
        if($user->save())
        {
            if ($request->hasFile('identity')) {
    
                $image = $request->file('identity');
                $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                $destinationPath = public_path('/img');
                $image->move($destinationPath, $name);
                $technical->identity_photo   = $name;  
            }
            $technical->user_id   = $user->id; 

            $technical->renewal_date   = $request->renewal_date; 
            $technical->available   = $request->available; 
            $technical->brief   = $request->brief; 
            $technical->service_id   = $request->service_id; 
            $technical->city_id   = $request->city_id;
            $technical->area_id   = $request->area_id; 
            $technical->country_id   = $request->country_id;
            $technical->nationality_id   = $request->nationality_id;   
            $technical->save();
        }
        
        $user = User::where('id',$user->id)->first();
        return response()->json($user);
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

        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'technicians';
        $technical = Technician::where('user_id',$id)->with('user')->orderBy('id', 'DESC')->first();
        //return $technical;
        if($technical)
        {
            if($lang=='ar')
            {
                $allcountries = Country::select('id',DB::raw('name_ar AS name'))->get();
                $allcities = City::select('id','country_id',DB::raw('name_ar AS name'))->get();
                $allareas = Area::select('id','city_id',DB::raw('name_ar AS name'))->get();
                $allnationalites = Nationality::select('id',DB::raw('name_ar AS name'))->get();
                $allservices = Service::select('id',DB::raw('name_ar AS name'))->get();
                
            }
            else
            {
                $allcountries = Country::select('id',DB::raw('name_en AS name'))->get();
                $allcities = City::select('id','country_id',DB::raw('name_en AS name'))->get();
                $allareas = Area::select('id','city_id',DB::raw('name_en AS name'))->get();
                $allnationalites = Nationality::select('id',DB::raw('name_en AS name'))->get();
                $allservices = Service::select('id',DB::raw('name_en AS name'))->get();
            }
                $countries = array_pluck($allcountries,'name', 'id');
                $cities = array_pluck($allcities,'name', 'id');
                $areas = array_pluck($allareas,'name', 'id');
                $nationalites = array_pluck($allnationalites,'name', 'id');
                $services = array_pluck($allservices,'name', 'id');
            return view('technicians.edit',compact('technical','title','lang','countries','cities','areas','nationalites','services','allcities','allareas','allnationalites','allservices'));
            
        }
        else
        {
            return redirect(url('error'));
        }
    }
    public function orders($id)
    {
        $lang = App::getlocale();
            if(Auth::user()->role != 'admin' ){
                $role = 'admin';
                return view('unauthorized',compact('role','admin'));
            }
            $title = 'technicians';
            $user = User::where('id',$id)->orderBy('id', 'DESC')->first();
            if($user)
            {
                $orders = Order::where('fannie_id',$id)->with('user')->with('fannie')->orderBy('id', 'DESC')->get();
                //return $orders ; 
                return view('technicians.orders',compact('user','orders','title','lang'));
                
            }
            else
            {
                return redirect(url('error'));
            }
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
