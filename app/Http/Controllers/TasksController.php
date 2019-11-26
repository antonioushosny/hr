<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Employee;
 use App\Task;
use Auth;
use App;
use DB;
use App\Notifications\Notifications;
class TasksController extends Controller
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
        $title = 'tasks';
        $tasks = Task::orderBy('id', 'DESC')->get();
        // return $admins ; 
        return view('tasks.index',compact('tasks','title','lang'));

    }
    public function add()
    {
        $lang = App::getlocale();
        $allemployees = Employee::all();
        $employees = array_pluck($allemployees,'name', 'id');
        
        $title = 'tasks';
        return view('tasks.add',compact('title','employees','lang'));
    }
    public function store(Request $request)
    {
        // 'name', 'email', 'password','mobile','national_id','mac_address','net_salary','cross_salary','insurance','annual_vacations','accidental_vacations','image','device_token','status','type','department_id','lang'
        // return $request ;
        if($request->id ){
            $rules =
            [
                'date'  =>'required',
                'time'  =>'required',            
                'project_name'  =>'required',   
                'status'  =>'required',   
                'employee_id'  =>'required',   
                'title'  =>'required', 
             ];
            
        }     
    
        else{
            $rules =
            [
                'date'  =>'required',
                'time'  =>'required',            
                'project_name'  =>'required',   
                'status'  =>'required',   
                'employee_id'  =>'required', 
                'title'  =>'required', 
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        if($request->id ){
            $task = Task::find( $request->id );
            $type = "task";
            $msg =   "  You Have New task no  ".  $task->id  ;
        
            $employee = Employee::find($request->employee_id) ;
            $employee->notify(new Notifications($msg,$type ));
            $device_token = $employee->device_token ;
            if($device_token){
                $this->notification($device_token,$msg,$msg);
             }
              
        }
        else{
            $task = new Task ;
           
        }        
        $task->title          = $request->title ;
        $task->date          = $request->date ;
        $task->time         = $request->time ;
        $task->project_name        = $request->project_name ;
        $task->status        = $request->status ;
        $task->employee_id        = $request->employee_id ;

        $task->save();

        return redirect()->route('tasks');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $lang = App::getlocale();
        $data = Task::where('id',$id)->orderBy('id', 'DESC')->first();
        if($data)
        {
            $allemployees = Employee::all();
            $employees = array_pluck($allemployees,'name', 'id');
            $title = 'tasks';
             return view('tasks.edit',compact('data','employees','title','lang'));

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
       
        $id = Task::find( $id );
        $id ->delete();
        return response()->json($id);
   
    }

    public function deleteall(Request $request)
    {

        if($request->ids){
         
            $ids = Task::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
        
    }
}
