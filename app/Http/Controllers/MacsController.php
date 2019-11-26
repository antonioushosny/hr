<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Employee;
 use App\Mac;
use Auth;
use App;
use DB;
use App\Notifications\Notifications;
class MacsController extends Controller
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
        $title = 'macs';
        $macs = Mac::orderBy('id', 'DESC')->get();

        return view('macs.index',compact('macs','title','lang'));

    }
    public function accept($id )
    {
        $mac = Mac::find( $id );
        $mac->status = 'accepted';
        $mac->save() ;
        
        $type = "mac";
        $msg =   "   Your request for change mac address was accepted "   ;
    
        $employee = Employee::find($mac->employee_id) ;
     
        $employee->mac_address  = $mac->mac_address  ;
        $employee->save();
         
        $employee->notify(new Notifications($msg,$type ));
        $device_token = $employee->device_token ;
        if($device_token){
            $this->notification($device_token,$msg,$msg);
         }
         
         return redirect()->route('macs');
    }
    public function reject($id )
    {
        $mac = Mac::find( $id );
        $mac ->status = 'rejected';
        $mac->save() ;
        $type = "mac";
        $msg =   "  Your  request for change mac address was rejected  "   ;
    
        $employee = Employee::find($mac->employee_id) ;
        $employee->notify(new Notifications($msg,$type ));
        $device_token = $employee->device_token ;
        if($device_token){
            $this->notification($device_token,$msg,$msg);
         }
         
         return redirect()->route('macs');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
         //
    }

    public function update(Request $request, $id)
    {
       
      
    }

    public function destroy($id)
    {
       
        $id = Mac::find( $id );
        $id ->delete();
        
        session()->flash('alert-danger', trans('admin.record_deleted'));   
        return response()->json($id);
        // return view('admin.index',compact('admins','title'));
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
         
            $ids = Mac::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
        
    }
}
