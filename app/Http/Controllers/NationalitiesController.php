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
                'name_ar'  =>'required|arabic|max:190',           
                'name_en'  =>'required|english|max:190',           
                'status'  =>'required',   
            ];
            
        }     
    
        else{
            $rules =
            [
                'name_ar'  =>'required|arabic|max:190',           
                'name_en'  =>'required|english|max:190',              
     
                'status'  =>'required'      
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         
        // return $request ;
        if($request->id ){
            $nationality = Nationality::find( $request->id );
        }
        else{
            $nationality = new Nationality ;

        }

        $nationality->name_ar          = $request->name_ar ;
        $nationality->name_en         = $request->name_en ;
        $nationality->status        = $request->status ;
      
        $nationality->save();
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
        $nationalitie = Nationality::where('id',$id)->orderBy('id', 'DESC')->first();
        if($nationalitie)
        {
            $title = 'nationalities';
            // return $admin ; 
            return view('nationalities.edit',compact('nationalitie','title','lang'));

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
        $id = Nationality::find( $id );
        $id ->delete();
        return response()->json($id);
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
            foreach($request->ids as $id){
                $id = Nationality::find($id);
            }
            $ids = Nationality::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
    }
}
