<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Employee;
 use App\Attendance;
use Auth;
use App;
use DB;
use App\Notifications\Notifications;
class AttendancesController extends Controller
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
        $title = 'attendances';
        $attendances = Attendance::orderBy('id', 'DESC')->get();
        return view('attendances.index',compact('attendances','title','lang'));

    }
    public function add()
    {
       //
    }
    public function store(Request $request)
    {
       //
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
      
        $id = Attendance::find( $id );
        $id ->delete();

        session()->flash('alert-danger', trans('admin.record_deleted'));   
        return response()->json($id);
        // return view('admin.index',compact('admins','title'));
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
         
            $ids = Attendance::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
        
    }
}
