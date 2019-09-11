<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\emailnotify;
use App\User;
use App\Country;
use App\City; 
use App\Order; 
use App\Rate; 
use Auth;
use App;
class UsersController extends Controller
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
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'users';
        $allcountries = Country::where('id','<>','1')->get();
        $countries = array_pluck($allcountries,'name_ar', 'id');
        $allcities = City::where('id','<>','1')->get();
        $cities = array_pluck($allcities,'name_ar', 'id');
        $users = User::where('role','user')->orderBy('id', 'DESC')->get();
 
        return view('users.index',compact('users','countries','cities','title','lang'));

    }


    public function add()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'users';
        return view('users.add',compact('title','lang'));
    }
    public function changestatus($id)
    {
            $title =  'users' ;
            $user = User::where('id',$id)->first();
            if($user){
                if($user->status == 'active'){
                    $user->status = 'not_active' ;
                }
                else{
                    $user->status = 'active' ;                    
                }
                $user->save();
                return redirect()->route('users');
            }
            else
            {
                return redirect(url('error'));
            }
    }
    public function ratings($id)
    {
        $lang = App::getlocale();
            if(Auth::user()->role != 'admin' ){
                $role = 'admin';
                return view('unauthorized',compact('role','admin'));
            }
            $title = 'users';
            $user = User::where('id',$id)->orderBy('id', 'DESC')->first();
            if($user)
            {
                $ratings = Rate::where('evaluator_to',$id)->with('evaluatorfrom')->orderBy('id', 'DESC')->get();
                //return $ratings ; 
                return view('users.ratings',compact('user','ratings','title','lang'));
                
            }
            else
            {
                return redirect(url('error'));
            }
    }
    public function store(Request $request)
    {
        
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
            ];
        }
        
        
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //return $request;

        if($request->id ){
            $user = User::find( $request->id );
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
        }
        else{
            $user = new User ;
            $user->email=$request->email;
            $user->mobile=$request->mobile;


        }

        $user->name          = $request->name ;
        $user->address         = $request->address ;
        $user->status        = $request->status ;
        $user->lat          =$request->location[0];
        $user->lng =$request->location[1];
        $user->role         ='user';
        $user->save();
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $user->image   = $name;  
        }
        $user->save();
        $user = User::where('id',$user->id)->first();
        return response()->json($user);
    }


public function orders($id)
{
    $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'users';
        $user = User::where('id',$id)->orderBy('id', 'DESC')->first();
        if($user)
        {
            $orders = Order::where('user_id',$id)->with('user')->with('fannie')->orderBy('id', 'DESC')->get();
             //return $orders ; 
            return view('users.orders',compact('user','orders','title','lang'));
            
        }
        else
        {
            return redirect(url('error'));
        }
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
        $title = 'users';
        $user = User::where('id',$id)->orderBy('id', 'DESC')->first();
        if($user)
        {
            // return $admin ; 
            return view('users.edit',compact('user','title','lang'));
            
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
        session()->flash('alert-danger', trans('admin.record_selected_deleted'));
        // return redirect()->route('users');
      
    }
}
