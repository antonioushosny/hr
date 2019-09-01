<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\emailnotify;
use App\City;
use App\Area;
use App\Nationality;
use Auth;
use App;
class NationalitiesController extends Controller
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
        $title = 'nationalities';
 
        $nationalities = Nationality::orderBy('id', 'DESC')->get();
        // return $admins ; 
        return view('nationalities.index',compact('nationalities','title','lang'));

    }

    public function cities(Request $request, $id) {
        // return $id ;
        if ($request->ajax()) {
            $lang = App::getlocale();
            if($lang == 'ar'){
                $cities = City::where('nationality_id', $id)->select('name_ar AS name','id')->get();
            }else{
                $cities = City::where('nationality_id', $id)->select('name_en AS name','id')->get();
            }
            return response()->json([
                'cities' => $cities ,
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
        $title = 'nationalities';
        return view('nationalities.add',compact('title','lang'));
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
                // 'image'  =>'required',           
                // 'nationality_id'  =>'required',     
                'status'  =>'required'      
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         
        // return $request ;
        if($request->id ){
            $nationality = nationality::find( $request->id );
        }
        else{
            $nationality = new nationality ;

        }

        $nationality->name_ar          = $request->name_ar ;
        $nationality->name_en         = $request->name_en ;
        $nationality->status        = $request->status ;
        $nationality->save();
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $nationality->image   = $name;  
        }
        $nationality->save();
        $nationality = nationality::where('id',$nationality->id)->first();
        return response()->json($nationality);

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
        $title = 'nationalities';
        $nationalitie = nationality::where('id',$id)->orderBy('id', 'DESC')->first();
        // return $admin ; 
        return view('nationalities.edit',compact('nationalitie','title','lang'));
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
        $id = nationality::find( $id );
        $id ->delete();
        return response()->json($id);
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
            foreach($request->ids as $id){
                $id = nationality::find($id);
            }
            $ids = nationality::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
    }
}
