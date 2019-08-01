<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\emailnotify;
use App\User;
use Spatie\Permission\Models\Role;
use Auth;
use App;
use DB;
class ProvidersController extends Controller
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
        $title = 'providers';
        $providers = User::where('role','provider')->orderBy('id', 'DESC')->get();
        // return $admins ; 
        return view('providers.index',compact('providers','title','lang'));

    }
    public function add()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'providers';
        return view('providers.add',compact('title','lang'));
    }

    public function centers(Request $request, $id) {
        // return $id ;
        if ($request->ajax()) {
            $lang = App::getlocale();
            $centers = User::where('provider_id', $id)->select('name','id')->where('role','center')->get();
            
            return response()->json([
                'centers' => $centers ,
            ]);
        }
    }

    public function store(Request $request)
    {
       
        // return $request ;
        if($request->id ){
            $rules =
            [
                'responsible_name'  =>'required|max:190',
                'company_name'  =>'required|max:190',
                'email'  =>'required|email|max:190',            
                'status'  =>'required',   
            ];
        }     
    
        else{
            $rules =
            [
                'responsible_name'  =>'required|max:190',
                'company_name'  =>'required|max:190',
                'email'  =>'required|email|unique:users,email|max:190',            
                'status'  =>'required',       
                // 'password'  =>'required|min:6|max:190',     
                // 'logo'  =>'required',      
                // 'description' =>'required',
                // 'address'    =>'required',   
                // 'mobile'     =>'required',   
                // 'join_date'     =>'required',
            ];
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
         $user->company_name  = $request->company_name ;
         $user->email         = $request->email ;
         $user->status        = $request->status ;
         $user->mobile        = $request->mobile ;
         $user->desc          = $request->description ;
         $user->address       = $request->address ;
         $user->join_date     = $request->join_date ;
         $user->role          = 'provider';
         $user->save();
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $user->image   = $name;  
        }

        $user->save();

        $lang = App::getlocale();
        $title = 'providers';
        $providers = User::where('role','provider')->orderBy('id', 'DESC')->get();
        return redirect()->route('providers');
        // return \Redirect::back();
        // return view('providers.index',compact('admins','title','lang'));

        return response()->json($user);

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
        $title = 'providers';
        $provider = User::where('id',$id)->orderBy('id', 'DESC')->first();
        // return $admin ; 
        return view('providers.edit',compact('provider','title','lang'));
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
        // session()->flash('alert-danger', trans('admin.record_selected_deleted'));
        // return redirect()->route('admins');
      
    }
}
