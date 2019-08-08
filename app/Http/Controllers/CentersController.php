<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\emailnotify;
use App\User;
use App\City;
use App\Area;
use App\Container;
use App\CenterContainer;

use Spatie\Permission\Models\Role;
use Auth;
use App;
use DB;
class CentersController  extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
    
        // if(Auth::user()->role != 'admin' ){
        //     return view('unauthorized',compact('role','admin'));
        // }
        
    }
    public function index()
    {
        $lang = App::getlocale();
        if(!(Auth::user()->role == 'admin' ||  Auth::user()->role == 'provider')){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'centers';
        if(Auth::user()->role != 'provider'){
            $centers = User::where('role','center')->orderBy('id', 'DESC')->get();
        }else{
            $centers = User::where('role','center')->where('provider_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        }
        // return $centers ; 
        return view('centers.index',compact('centers','title','lang'));

    }
    public function add()
    {
        $lang = App::getlocale();
        if(!(Auth::user()->role == 'admin' ||  Auth::user()->role == 'provider')){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'centers';
        $allcities = City::all();
        if($lang == 'ar'){
            $cities = array_pluck($allcities,'name_ar', 'id'); 
        }else{
            $cities = array_pluck($allcities,'name_en', 'id');
        }

        $allcontainers = Container::all();
        if($lang == 'ar'){
            $containers = array_pluck($allcontainers,'name_ar', 'id'); 
        }else{
            $containers = array_pluck($allcontainers,'name_en', 'id');
        }

        $allproviders = User::where('role','provider')->get();
        $providers = array_pluck($allproviders,'company_name', 'id'); 
        
        return view('centers.add',compact('title','providers','containers','cities','lang'));
    }
    public function store(Request $request)
    {
       
        // return $request ;
        if($request->id ){
            $rules =
            [
                'provider_id'   =>'required', 
                'city_id'   =>'required', 
                'area_id'   =>'required', 
                'responsible_name'  =>'required|max:190',
                'email'  =>'required|email|max:190',            
                'status'  =>'required',   
                'lat' =>'required',
                'lng'    =>'required', 
            ];
            
        }     
    
        else{
            $rules =
            [
                'provider_id'   =>'required', 
                'city_id'   =>'required', 
                'area_id'   =>'required', 
                'responsible_name'  =>'required|max:190',
                'email'  =>'required|email|unique:users,email|max:190',            
                'status'  =>'required',       
                // 'password'  =>'required|min:6|max:190',     
                // 'image'  =>'required',      
                'lat' =>'required',
                'lng'    =>'required',   
                // 'mobile'     =>'required',   
            ];
        }
        
        if($request->mobile){
            $rules['mobile'] = "between:8,11" ;
        }
         $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }

        // return $request ;
         if($request->id ){
            $user = User::find( $request->id );

            if($request->email != $user->email){
                $rules =
                [       
                    'email'  =>'required|email|unique:users,email',     
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
                }
            }
            
            if ($request->hasFile('image')) {

                $imageName =  $user->image; 
                \File::delete(public_path(). '/img/' . $imageName);
            }
            if($request->password){
                $rules =
                [
                    'password'  =>'min:6',                    
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()){
                    return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
                }
                $password = \Hash::make($request->password);
                $user->password      = $password ;
            }
         }
         else{
            $user = new User ;
            $password = \Hash::make($request->password);
            $user->password      = $password ;
        }
        
         $user->name          = $request->responsible_name ;
         $user->lat           = $request->lat ;
         $user->lng           = $request->lng ;
         $user->email         = $request->email ;
         $user->status        = $request->status ;
         $user->mobile        = $request->mobile ;
         $user->city_id       = $request->city_id ;
         $user->area_id       = $request->area_id ;
         $user->provider_id       = $request->provider_id ;

         $user->role          = 'center';
         $user->save();
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $user->image   = $name;  
        }

        $user->save();
        $containers  = CenterContainer::where('center_id',$user->id)->delete();
        $i = 0; 
        foreach($request->containers as $container_id){
          
            if($container_id != '' || $container_id != null){
                $container = CenterContainer::where('center_id',$user->id)->where('container_id',$container_id)->first();
                if(!$container){

                    $container = new CenterContainer ;
                    $container->container_id = $container_id ;
                    $container->center_id = $user->id ;
                    $container->price = $request->price[$i] ;
                    $container->save();
                }
                $i ++ ;
            }
        }

        return redirect()->route('centers');
        // return \Redirect::back();
        // return view('centers.index',compact('admins','title','lang'));

        return response()->json($user);

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $lang = App::getlocale();
        if(!(Auth::user()->role == 'admin' ||  Auth::user()->role == 'provider')){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'centers';
        $allcities = City::all();
        if($lang == 'ar'){
            $cities = array_pluck($allcities,'name_ar', 'id'); 
        }else{
            $cities = array_pluck($allcities,'name_en', 'id');
        }
        $allproviders = User::where('role','provider')->get();
        $providers = array_pluck($allproviders,'company_name', 'id');  

        $allcontainers = Container::all();
        if($lang == 'ar'){
            $containers = array_pluck($allcontainers,'name_ar', 'id'); 
        }else{
            $containers = array_pluck($allcontainers,'name_en', 'id');
        }

        $center = User::where('id',$id)->with('containers')->orderBy('id', 'DESC')->first();
        $allareas = Area::where('city_id',$center->city_id)->get();
        if($lang == 'ar'){
            $areas = array_pluck($allareas,'name_ar', 'id'); 
        }else{
            $areas = array_pluck($allareas,'name_en', 'id');
        }
        // return $center ; 
        return view('centers.edit',compact('center','areas','cities','containers','providers','title','lang'));
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
       
        if(!(Auth::user()->role == 'admin' ||  Auth::user()->role == 'provider')){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $id = User::find( $id );
        $imageName =  $id->image; 
        \File::delete(public_path(). '/img/' . $imageName);
        $id ->delete();

        session()->flash('alert-danger', trans('admin.record_deleted'));   
        return response()->json($id);
        // return view('admin.index',compact('admins','title'));
    }

    public function deleteall(Request $request)
    {
        if($request->ids){
            foreach($request->ids as $id){
                $id = User::find($id);
                $imageName =  $id->image; 
                \File::delete(public_path(). '/img/' . $imageName);
            }
            $ids = User::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
        // session()->flash('alert-danger', trans('admin.record_selected_deleted'));
        // return redirect()->route('admins');
      
    }
}
