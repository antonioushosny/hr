<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\Area;
use App\Reason;
use Auth;
use App;
class ReasonController extends Controller
{
    //
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
        $title = 'reasons';
 
        $reasons = Reason::where('type','reason')->orderBy('id', 'DESC')->get();
        // return $admins ; 
        return view('reasons.index',compact('reasons','title','lang'));

    }
    public function add()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'reasons';
        return view('reasons.add',compact('title','lang'));
    }


    public function store(Request $request)
    {
        //return $request;
        if($request->id ){
            $rules =
            [
                'title_ar'  =>'required|arabic|max:190',           
                'title_en'  =>'required|english|max:190',           
                'status'  =>'required',   
            ];
            
        }     
    
        else{
            $rules =
            [
                'title_ar'  =>'required|arabic|max:190',           
                'title_en'  =>'required|english|max:190',                       
                'status'  =>'required'      
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         
        // return $request ;
        if($request->id ){
            $reason = Reason::find( $request->id );
        }
        else{
            $reason = new Reason ;

        }

        $reason->title_ar          = $request->title_ar ;
        $reason->title_en         = $request->title_en ;
        $reason->status        = $request->status ;
        $reason->type        = 'reason';
        $reason->save();
        $reason = Reason::where('id',$reason->id)->first();
        return response()->json($reason);

    }
    public function changestatus($id)
    {
            $title =  'reasons' ;
            $reason = Reason::where('id',$id)->first();
            if($reason){
                if($reason->status == 'active'){
                    $reason->status = 'not_active' ;
                }
                else{
                    $reason->status = 'active' ;                    
                }
                $reason->save();
                return redirect()->route('reasons');
            }
            else
            {
                return redirect(url('error'));
            }
    }

    public function edit($id)
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'reasons';
        $reason = Reason::where('id',$id)->orderBy('id', 'DESC')->first();
        if($reason)
        {
            // return $admin ; 
            return view('reasons.edit',compact('reason','title','lang'));

        }
        else
        {
            return redirect(url('error'));
        }
    }
}
