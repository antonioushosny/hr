<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Employee;
 use App\Vacation;
use Auth;
use App;
use DB;
use App\Notifications\Notifications;
class VacationsController extends Controller
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
        $title = 'vacations';
        $vacations = Vacation::orderBy('id', 'DESC')->get();
        return view('vacations.index',compact('vacations','title','lang'));

    }
    public function accept($id )
    {
        $vacation = Vacation::find( $id );
        $vacation ->status = 'accepted';
        $vacation->save() ;
        
        $type = "vacation";
        $msg =   "   Your vacation was accepted "   ;
    
        $employee = Employee::find($vacation->employee_id) ;
        if($vacation->type == 'annual'){
            $employee->annual_vacations =  $employee->annual_vacations -$vacation->days ;
            $employee->save();
        }elseif($vacation->type == 'accidental '){
            $employee->accidental_vacations =  $employee->accidental_vacations -$vacation->days ;
            $employee->save();
        }
        $employee->notify(new Notifications($msg,$type ));
        $device_token = $employee->device_token ;
        if($device_token){
            $this->notification($device_token,$msg,$msg);
         }
         
         return redirect()->route('vacations');
    }
    public function reject($id )
    {
        $vacation = Vacation::find( $id );
        $vacation ->status = 'rejected';
        $vacation->save() ;
        $type = "vacation";
        $msg =   "  Your vacation was rejected  "   ;
    
        $employee = Employee::find($vacation->employee_id) ;
        $employee->notify(new Notifications($msg,$type ));
        $device_token = $employee->device_token ;
        if($device_token){
            $this->notification($device_token,$msg,$msg);
         }
         
         return redirect()->route('vacations');
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
       
        $id = Vacation::find( $id );
        $id ->delete();

        session()->flash('alert-danger', trans('admin.record_deleted'));   
        return response()->json($id);$id = Vacation::find( $id );
        $id ->delete();

        session()->flash('alert-danger', trans('admin.record_deleted'));   
        return response()->json($id);
        // return view('admin.index',compact('admins','title'));
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
         
            $ids = Vacation::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
        
    }
}
