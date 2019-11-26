<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Employee;
 use App\Department;
 use App\Discount;
 use App\Reward;
 use App\Task;
 use App\Vacation;
 use App\Attendance;
 use Carbon\Carbon;
use Auth;
use App;
use DB;
class EmployeesController extends Controller
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
        $title = 'employees';
        $employees = Employee::orderBy('id', 'DESC')->get();
        return view('employees.index',compact('employees','title','lang'));

    }
    public function add()
    {
        $lang = App::getlocale();
        $alldepartments = Department::all();
        $departments = array_pluck($alldepartments,'title', 'id');
        
        $title = 'employees';
        return view('employees.add',compact('title','departments','lang'));
    }
    public function store(Request $request)
    {
        // 'name', 'email', 'password','mobile','national_id','mac_address','net_salary','cross_salary','insurance','annual_vacations','accidental_vacations','image','device_token','status','type','department_id','lang'
        // return $request ;
        if($request->id ){
            $rules =
            [
                'name'  =>'required|max:190',
                'email'  =>'required|email|max:190',            
                'status'  =>'required',   
                'mobile'  =>'required',   
                'national_id'  =>'required',   
                'mac_address'  =>'required',   
                'net_salary'  =>'required',   
                'cross_salary'  =>'required',   
                'insurance'  =>'required',   
                'annual_vacations'  =>'required',   
                'accidental_vacations'  =>'required',   
                'department_id'  =>'required',   
             ];
            
        }     
    
        else{
            $rules =
            [
                'name'  =>'required|max:190',
                'email'  =>'required|email|unique:users,email|max:190',            
                'password'  =>'required|min:6|max:190',     
                'status'  =>'required',      
                'mobile'  =>'required',   
                'national_id'  =>'required',   
                'mac_address'  =>'required',   
                'net_salary'  =>'required',   
                'cross_salary'  =>'required',   
                'insurance'  =>'required',   
                'annual_vacations'  =>'required',   
                'accidental_vacations'  =>'required',   
                'department_id'  =>'required',    
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }


        // return $request ;
        if($request->id ){
            $employee = Employee::find( $request->id );

            if($request->email != $employee->email){
                $rules =
                [       
                    'email'  =>'required|email|unique:employees,email',     
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
                }
            }

            if($request->mobile != $employee->mobile){
                $rules =
                [       
                    'mobile'  =>'required|unique:employees,mobile',     
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
                }
            }
            
            
            if ($request->hasFile('image')) {

                $imageName =  $employee->image; 
                \File::delete(public_path(). '/img/' . $imageName);
            }
            if($request->password){
                $rules =
                [
                    'password'  =>'min:6',                    
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()){
                    return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
                }
                $password = \Hash::make($request->password);
                $employee->password      = $password ;
            }
        }
        else{
            $employee = new Employee ;
            $password = \Hash::make($request->password);
            $employee->password      = $password ;
           
        }        
        $employee->name          = $request->name ;
        $employee->email         = $request->email ;
        $employee->mobile        = $request->mobile ;
        $employee->status        = $request->status ;
        $employee->national_id        = $request->national_id ;
        $employee->mac_address        = $request->mac_address ;
        $employee->net_salary        = $request->net_salary ;
        $employee->cross_salary        = $request->cross_salary ;
        $employee->insurance        = $request->insurance ;
        $employee->annual_vacations        = $request->annual_vacations ;
        $employee->accidental_vacations        = $request->accidental_vacations ;
        $employee->department_id        = $request->department_id ;
        $employee->save();

      
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $employee->image   = $name;  
        }

        $employee->save();
        return redirect()->route('employees');
    }
    
    public function edit($id)
    {
        $lang = App::getlocale();

        $employee = Employee::where('id',$id)->orderBy('id', 'DESC')->first();
        if($employee)
        {
            $alldepartments = Department::all();
            $departments = array_pluck($alldepartments,'title', 'id');
            $title = 'employees';
             return view('employees.edit',compact('employee','departments','title','lang'));

        }
        else
        {
            return redirect(url('error'));
        }
    }

    public function view($id)
    {
        $lang = App::getlocale();
        $dt = Carbon::now();
        $date  = date('Y-m-d', strtotime($dt));
        $month = date('m', strtotime($dt));
        $monthName = date('F', mktime(0, 0, 0, $month, 10));
        $year = date('Y', strtotime($dt));
        $user = Employee::where('id',$id)->orderBy('id', 'DESC')->first();
        if($user)
        {
            $rewards =  Reward::where('employee_id',$user->id)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->orderBy('id', 'desc')->get();
            $sumrewards =  Reward::where('employee_id',$user->id)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->orderBy('id', 'desc')->sum('amount');
             $rewardss = [] ;
            $i = 0 ;
            if(sizeof($rewards) > 0){
                foreach($rewards as $reward){
                    $rewardss[$i]['amount']  = $reward->amount ;
                    $rewardss[$i]['reason']  = $reward->reason ;
                    $rewardss[$i]['created_at']  = $reward->created_at ;
                
                    $i++;
                }
            }
            $discounts =  Discount::where('employee_id',$user->id)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->orderBy('id', 'desc')->get();
            $sumdiscounts =  Discount::where('employee_id',$user->id)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->orderBy('id', 'desc')->sum('amount');
             $discountss = [] ;
            $i = 0 ;
            if(sizeof($discounts) > 0){
                foreach($discounts as $discount){
                    $discountss[$i]['amount']  = $discount->amount ;
                    $discountss[$i]['reason']  = $discount->reason ;
                    $discountss[$i]['created_at']  = $discount->created_at ;
                
                    $i++;
                }
            }
            $tasks =  Task::where('employee_id',$user->id)->whereMonth('date', '=', $month)->orderBy('id', 'desc')->get();
             $taskss = [] ;
            $i = 0 ;
            if(sizeof($tasks) > 0){
                foreach($tasks as $task){
                    $taskss[$i]['date']  = $task->date ;
                    $taskss[$i]['time']  = $task->time ;
                    $taskss[$i]['title']  = $task->title ;
                    $taskss[$i]['project_name']  = $task->project_name ;
                    $taskss[$i]['status']  = $task->status ;
                
                    $i++;
                }
            }

            $attendences =  Attendance::where('employee_id',$user->id)->whereMonth('date', '=', $month)->orderBy('id', 'desc')->get();
            $attendencess = [] ;
            $i = 0 ;
            if(sizeof($attendences) > 0){
                foreach($attendences as $attendence){
                    $attendencess[$i]['date']  = $task->date ;
                    $attendencess[$i]['check_in']  = $task->time ;
                    $attendencess[$i]['check_out']  = $task->title ;
                    $i++;
                }
            }

            $vacations =  Vacation::where('employee_id',$user->id)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->orderBy('id', 'desc')->get();
            $vacationss = [] ;
            $i = 0 ;
            if(sizeof($vacations) > 0){
                foreach($vacations as $vacation){
                    $vacationss[$i]['title']  = $vacation->title ;
                    $vacationss[$i]['from']  = $vacation->from ;
                    $vacationss[$i]['to']  = $vacation->to ;
                    $vacationss[$i]['days']  = $vacation->days ;
                    $vacationss[$i]['type']  = $vacation->type ;
                    $vacationss[$i]['status']  = $vacation->status ;
                    $i++;
                }
            }

            $data['month'] = $monthName ;
            $data['rewards'] = $rewardss ;
            $data['tasks'] = $taskss ;
            $data['attendences'] = $attendencess ;
            $data['vacations'] = $vacationss ;
            $data['discounts'] = $discountss ;
            $data['net_salary'] = $user->net_salary  ;
            $data['cross_salary'] = $user->cross_salary  ;
            $data['insurance '] = $user->insurance  ;
            $data['sumrewards'] = $sumrewards  ;
            $data['sumdiscounts'] = $sumdiscounts  ;
            $data['total_salary'] = $user->net_salary + $sumrewards -  $sumdiscounts ;
            $employee = $user ;
            $title = 'employees';
            return view('employees.view',compact('employee','data','title','lang'));

        }
        else
        {
            return redirect(url('error'));
        }
    }

    public function destroy($id)
    {
        $id = Employee::find( $id );
        $imageName =  $id->image; 
        \File::delete(public_path(). '/img/' . $imageName);
        $id ->delete();

        session()->flash('alert-danger', trans('admin.record_deleted'));   
        return response()->json($id);
        // return view('admin.index',compact('admins','title'));
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
            foreach($request->ids as $id){
                $id = Employee::find($id);
                $imageName =  $id->image; 
                \File::delete(public_path(). '/img/' . $imageName);
            }
            $ids = Employee::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
        session()->flash('alert-danger', trans('admin.record_selected_deleted'));
        return redirect()->route('admins');
      
    }

    //for discount 
    public function discounts($id)
    {
        $lang = App::getlocale();
        $title = 'employees';
        $employee = Employee::where('id',$id)->first();
        $discounts = Discount::where('employee_id',$id)->orderBy('id', 'DESC')->get();
        return view('employees.discounts',compact('discounts','employee','id','title','lang'));
    }
    public function adddiscount($id)
    {
        $lang = App::getlocale();
        $title = 'employees';
        $employee = Employee::where('id',$id)->first();
        return view('employees.discountform',compact('title','employee','id','lang'));
    }

    public function editdiscount($id)
    {
        $lang = App::getlocale();
        
        $data = Discount::where('id',$id)->orderBy('id', 'DESC')->first();
        if($data)
        { 
             $employee = Employee::where('id',$data->employee_id)->orderBy('id', 'DESC')->first();
             $id =  $employee->id ;
             $title = 'employees';
             return view('employees.discountform',compact('data','employee','id','title','lang'));

        }
        else
        {
            return redirect(url('error'));
        }
    }

    public function storediscount(Request $request)
    {
          if($request->id ){
            $rules =
            [
                'amount'  =>'required',
                'reason'  =>'required',      
             ];
            
        }     
    
        else{
            $rules =
            [
                'amount'  =>'required',
                'reason'  =>'required',     
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        // return $request ;
        if($request->id ){
            $discount = Discount::find( $request->id );
        }
        else{
            $discount = new Discount ;
           
        }        
        $discount->amount          = $request->amount ;
        $discount->reason         = $request->reason ;
        $discount->employee_id         = $request->employee_id ;
        
        $discount->save();
        return redirect()->route('discounts',$request->employee_id);
    }

    public function destroydiscount($id)
    {
        $id = Discount::find( $id );
        $id ->delete();
        return response()->json($id);
    }

    public function deletealldiscount(Request $request)
    {
        if($request->ids){
            $ids = Discount::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
    }


  //for awards 
    public function awards($id)
    {
        $lang = App::getlocale();
        $title = 'employees';
        $employee = Employee::where('id',$id)->first();
        $awards = Reward::where('employee_id',$id)->orderBy('id', 'DESC')->get();
        return view('employees.awards',compact('awards','employee','id','title','lang'));
    }
    public function addaward($id)
    {
        // return $id ;
        $lang = App::getlocale();
        $title = 'employees';
        $employee = Employee::where('id',$id)->first();
        return view('employees.awardform',compact('title','employee','id','lang'));
    }

    public function editaward($id)
    {
        $lang = App::getlocale();
        
        $data = Reward::where('id',$id)->orderBy('id', 'DESC')->first();
        if($data)
        { 
             $employee = Employee::where('id',$data->employee_id)->orderBy('id', 'DESC')->first();
             $id =  $employee->id ;
             $title = 'employees';
             return view('employees.awardform',compact('data','employee','id','title','lang'));

        }
        else
        {
            return redirect(url('error'));
        }
    }

    public function storeaward(Request $request)
    {
          if($request->id ){
            $rules =
            [
                'amount'  =>'required',
                'reason'  =>'required',      
             ];
            
        }     
    
        else{
            $rules =
            [
                'amount'  =>'required',
                'reason'  =>'required',     
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        // return $request ;
        if($request->id ){
            $award = Reward::find( $request->id );
        }
        else{
             $award = new Reward ;
           
        }        
        $award->amount          = $request->amount ;
        $award->reason         = $request->reason ;
        $award->employee_id         = $request->employee_id ;
        
        $award->save();
        return redirect()->route('awards',$request->employee_id);
    }

    public function destroyaward($id)
    {
        $id = Reward::find( $id );
        $id ->delete();
        return response()->json($id);
    }

    public function deleteallaward(Request $request)
    {
        if($request->ids){
            $ids = Reward::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
    }
    
    
}
