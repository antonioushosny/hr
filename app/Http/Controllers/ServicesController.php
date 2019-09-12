<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\emailnotify;
use App\City;
use App\Area;
use App\Service;
use Auth;
use App;
class ServicesController extends Controller
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
        $title = 'services';
 
        $services = service::orderBy('id', 'DESC')->get();
        // return $admins ; 
        return view('services.index',compact('services','title','lang'));

    }


    public function deleted()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'services_deleted';
 
        $services = service::orderBy('id', 'DESC')->onlyTrashed()->get();
        //return $services ; 
        return view('services.deleted',compact('services','title','lang'));

    }
    public function restore($id) 
    { 
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'services_deleted';
        $service = service::withTrashed()->find($id)->restore();
        $service1 = service::find($id);
        $service1->status='not_active';
        $service1->save();
        return response()->json($id);
        //return redirect ('services');
    }
    public function restoreall(Request $request)
    {
        
        
        if($request->ids){
            foreach($request->ids as $id){
                $id = Service::find($id);
            }
            $ids = Service::whereIn('id',$request->ids)->restore();
            foreach($request->ids as $id){
                $id = Service::find($id);
                $id->status='not_active';
                $id->save();
            }
        }
        return response()->json($request->ids);
    }
   
    public function add()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'services';
        return view('services.add',compact('title','lang'));
    }
    public function store(Request $request)
    {
        
        if($request->id ){
            $rules =
            [
                'name_ar'  =>'required|max:190',           
                'name_en'  =>'required|max:190',           
                'status'  =>'required',   
            ];
            
        }     
    
        else{
            $rules =
            [
                'name_ar'  =>'required|max:190',           
                'name_en'  =>'required|max:190',              
                'image'  =>'required',           
                'status'  =>'required'      
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         
        // return $request ;
        if($request->id ){
            $service = Service::find( $request->id );
        }
        else{
            $service = new Service ;

        }

        $service->name_ar          = $request->name_ar ;
        $service->name_en         = $request->name_en ;
        $service->status        = $request->status ;
        $service->save();
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $service->image   = $name;  
        }
        $service->save();
        $service = service::where('id',$service->id)->first();
        return response()->json($service);

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
        $title = 'services';
        $service = Service::where('id',$id)->orderBy('id', 'DESC')->first();
        if($service)
        {
            // return $admin ; 
            return view('services.edit',compact('service','title','lang'));

        }
        else
        {
            return redirect(url('error'));
        }
    }


    public function showtechnicians($id)
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'services';
        $service = Service::where('id',$id)->with('fannies')->orderBy('id', 'DESC')->first();
        if($service)
        {
             //return $service ; 
            return view('services.technicians',compact('service','title','lang'));

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
        $id = Service::find( $id );
        $id->status='deleted';
        $id->save();
        $id ->delete();
        return response()->json($id);
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
            foreach($request->ids as $id){
                $id = Service::find($id);
                $id->status='deleted';
                $id->save();
            }
            $ids = Service::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
    }
}
