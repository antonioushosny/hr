<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\User;
 use App\Department;
use Auth;
use App;
use DB;
class DepartmentsController extends Controller
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
        $title = 'departments';
        $departments = Department::orderBy('id', 'DESC')->get();
        return view('departments.index',compact('departments','title','lang'));

    }
    public function add()
    {
        $lang = App::getlocale();
        $title = 'departments';
        return view('departments.add',compact('title','lang'));
    }
    public function store(Request $request)
    {
       
        // return $request ;
        if($request->id ){
            $rules =
            [
                'title'  =>'required|max:190',
                'status'  =>'required',   
            ];
            
        }     
    
        else{
            $rules =
            [
                'title'  =>'required|max:190',
                'status'  =>'required',       
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }


        // return $request ;
        if($request->id ){
            $department = Department::find( $request->id );
            if ($request->hasFile('image')) {

                $imageName =  $department->image; 
                \File::delete(public_path(). '/img/' . $imageName);
            }
             
        }
        else{
            $department = new Department ;
            
        }        
        $department->title          = $request->title ;
        $department->status        = $request->status ;
        $department->save();
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img');
            $image->move($destinationPath, $name);
            $department->image   = $name;  
        }

        $department->save();
        return redirect()->route('departments');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $lang = App::getlocale();
        $data = Department::where('id',$id)->orderBy('id', 'DESC')->first();
        if($data)
        {
            $title = 'departments';
             return view('departments.edit',compact('data','title','lang'));

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
       
        $id = Department::find( $id );
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
                $id = Department::find($id);
                $imageName =  $id->image; 
                \File::delete(public_path(). '/img/' . $imageName);
            }
            $ids = Department::whereIn('id',$request->ids)->delete();
        }
        return response()->json($request->ids);
        session()->flash('alert-danger', trans('admin.record_selected_deleted'));
        return redirect()->route('departments');
      
    }
}
