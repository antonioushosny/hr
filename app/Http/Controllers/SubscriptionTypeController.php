<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\emailnotify;
use App\City;
use App\Area;
use App\SubscriptionType;
use Auth;
use App;
class SubscriptionTypeController extends Controller
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
        $title = 'subscriptions';
 
        $subscriptions = SubscriptionType::orderBy('id', 'DESC')->get();
        // return $admins ; 
        return view('subscriptions.index',compact('subscriptions','title','lang'));

    }

    public function add()
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'subscriptions';
        return view('subscriptions.add',compact('title','lang'));
    }

    public function store(Request $request)
    {
       // return $request;
        if($request->id ){
            $rules =
            [
                'name_ar'  =>'required|max:190',           
                'name_en'  =>'required|max:190',  
                'no_month'=>'required',
                'cost' =>'required',        
                'status'  =>'required',   
            ];
            
        }     
    
        else{
            $rules =
            [
                'name_ar'  =>'required|max:190',           
                'name_en'  =>'required|max:190',
                'no_month'=>'required',
                'cost' =>'required',                        
                'status'  =>'required'      
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         
        // return $request ;
        if($request->id ){
            $subscription = SubscriptionType::find( $request->id );
        }
        else{
            $subscription = new SubscriptionType ;

        }

        $subscription->name_ar          = $request->name_ar ;
        $subscription->name_en         = $request->name_en ;
        $subscription->no_month          = $request->no_month ;
        $subscription->cost         = $request->cost ;
        $subscription->status        = $request->status ;
        $subscription->save();
      
        $subscription = SubscriptionType::where('id',$subscription->id)->first();
        return response()->json($subscription);

    }

    public function edit($id)
    {
        $lang = App::getlocale();
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $title = 'subscriptions';
        $subscription = SubscriptionType::where('id',$id)->orderBy('id', 'DESC')->first();
        // return $admin ; 
        return view('subscriptions.edit',compact('subscription','title','lang'));
    }

    public function destroy($id)
    {
       
        if(Auth::user()->role != 'admin' ){
            $role = 'admin';
            return view('unauthorized',compact('role','admin'));
        }
        $id = SubscriptionType::find( $id );
        $id ->delete();
        return response()->json($id);
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
            foreach($request->ids as $id){
                $id = SubscriptionType::find($id);
            }
            $ids = SubscriptionType::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
    }
}
