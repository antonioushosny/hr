<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Employee;
 use App\Change;
use Auth;
use App;
use DB;
use App\Notifications\Notifications;
class ChangesController extends Controller
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
        $title = 'changes';
        $changes = Change::orderBy('id', 'DESC')->get();

        return view('changes.index',compact('changes','title','lang'));

    }
    public function accept($id )
    {
        $change = Change::find( $id );
        $change->status = 'accepted';
        $change->save() ;
        
        $type = "change";
        $msg =   "   Your request for change department was accepted "   ;
    
        $employee = Employee::find($change->employee_id) ;
     
        $employee->department_id = $change->department_id ;
        $employee->save();
         
        $employee->notify(new Notifications($msg,$type ));
        $device_token = $employee->device_token ;
        if($device_token){
            $this->notification($device_token,$msg,$msg);
         }
         
         return redirect()->route('changes');
    }
    public function reject($id )
    {
        $change = Change::find( $id );
        $change ->status = 'rejected';
        $change->save() ;
        $type = "change";
        $msg =   "  Your  request for change department was rejected  "   ;
    
        $employee = Employee::find($change->employee_id) ;
        $employee->notify(new Notifications($msg,$type ));
        $device_token = $employee->device_token ;
        if($device_token){
            $this->notification($device_token,$msg,$msg);
         }
         
         return redirect()->route('changes');
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
       
        $id = Change::find( $id );
        $id ->delete();

        session()->flash('alert-danger', trans('admin.record_deleted'));   
        return response()->json($id);$id = Change::find( $id );
        $id ->delete();

        session()->flash('alert-danger', trans('admin.record_deleted'));   
        return response()->json($id);
        // return view('admin.index',compact('admins','title'));
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
         
            $ids = Change::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
        
    }
}
