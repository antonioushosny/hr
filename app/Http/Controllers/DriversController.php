<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\emailnotify;
use App\User;
use App\City;
use App\Area;
use Spatie\Permission\Models\Role;
use Auth;
use App;
use DB;
class DriversController  extends Controller
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
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'provider' || Auth::user()->role == 'center')){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'drivers';
        if(Auth::user()->role == 'admin'){
            $drivers = User::where('role','driver')->orderBy('id', 'DESC')->get();
        }
        else if(Auth::user()->role == 'provider'){
            $drivers = User::where('role','driver')->where('provider_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        }else{
            $drivers = User::where('role','driver')->where('center_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        }
        // return $admins ; 
        return view('drivers.index',compact('drivers','title','lang'));

    }
    public function add()
    {
        $lang = App::getlocale();
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'provider' || Auth::user()->role == 'center') ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'drivers';

        $allcenters = User::where('role','center')->get();
        $centers = array_pluck($allcenters,'name', 'id'); 
    //    return $centers ;
        $allproviders = User::where('role','provider')->get();
        $providers = array_pluck($allproviders,'company_name', 'id');

        return view('drivers.add',compact('title','centers','providers','lang'));
    }
    public function store(Request $request)
    {
       
        // return $request ;
        if($request->id ){
            $rules =
            [
                'center_id'   =>'required', 
                'responsible_name'  =>'required|max:190',
                'email'  =>'required|email|max:190',            
                'status'  =>'required',   
            ];
            
        }     
    
        else{
            $rules =
            [
                'center_id'   =>'required', 
                'responsible_name'  =>'required|max:190',
                'email'  =>'required|email|unique:users,email|max:190',            
                'status'  =>'required',       
                'password'  =>'required|min:6|max:190',  
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
         $user->email         = $request->email ;
         $user->status        = $request->status ;
         $user->provider_id   = $request->provider_id ;
         $user->center_id     = $request->center_id ;
         $user->available     = '0';
         $user->role          = 'driver';
         $user->save();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $user->image   = $name;  
        }

        $user->save();
        return redirect()->route('drivers');
        // return \Redirect::back();
        // return view('drivers.index',compact('admins','title','lang'));

        return response()->json($user);

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $lang = App::getlocale();
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'provider' || Auth::user()->role == 'center')){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'drivers';
        
         

        $allproviders = User::where('role','provider')->get();
        $providers = array_pluck($allproviders,'company_name', 'id');

        $driver = User::where('id',$id)->with('center')->orderBy('id', 'DESC')->first();
        $provider = User::where('id',$driver->center->provider_id)->first() ;

        $allcenters = User::where('role','center')->where('provider_id',$driver->center->provider_id)->get();
        $centers = array_pluck($allcenters,'name', 'id'); 
        // return $provider ; 
        return view('drivers.edit',compact('driver','centers','providers','provider','title','lang'));
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
       
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'provider' || Auth::user()->role == 'center')){
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
