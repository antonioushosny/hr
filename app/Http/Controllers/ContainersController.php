<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\emailnotify;
use App\Container;
use Auth;
use App;
class ContainersController extends Controller
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
        $title = 'containers';
        $containers = Container::orderBy('id', 'DESC')->get();
        // return $admins ; 
        return view('containers.index',compact('containers','title','lang'));

    }
    public function add()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'containers';
        return view('containers.add',compact('title','lang'));
    }
    public function store(Request $request)
    {
        // return $request;
        if($request->id ){
            $rules =
            [
                'name_ar'  =>'required|max:190',           
                'name_en'  =>'required|max:190',           
                'size'  =>'required',            
                'status'  =>'required',  
                 
            ];
            
        }     
    
        else{
            $rules =
            [
                'name_ar'  =>'required|max:190',           
                'name_en'  =>'required|max:190', 
                'size'  =>'required',                
                // 'country_id'  =>'required',     
                'status'  =>'required'    

            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         
        // return $request ;
        if($request->id ){
            $container = Container::find( $request->id );
        }
        else{
            $container = new Container ;

        }

        $container->name_ar          = $request->name_ar ;
        $container->name_en         = $request->name_en ;
        $container->status        = $request->status ;
        $container->size        = $request->size ;
        $container->desc_ar        = $request->desc_ar ;
        $container->desc_en        = $request->desc_en ;
        $container->save();
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $container->image   = $name;  
        }
        $container->save();
        return response()->json($container);

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
        $title = 'containers';
        $container = Container::where('id',$id)->orderBy('id', 'DESC')->first();
        // return $container ; 
        return view('containers.edit',compact('container','title','lang'));
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
        $id = Container::find( $id );
        $id ->delete();
        return response()->json($id);
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
            $ids = Container::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
    }
}
