<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use App;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:role_list');
         $this->middleware('permission:role_create', ['only' => ['create','store']]);
         $this->middleware('permission:role_edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role_delete', ['only' => ['destroy']]);
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang = App::getlocale();
        $title = 'roles' ;
        $roles = Role::orderBy('id','DESC')->get();
        return view('roles.index',compact('roles','title','lang'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lang = App::getlocale();
        $title = 'roles' ;
        $permission = Permission::get();
        return view('roles.create',compact('permission','title','lang'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->id ){
            $rules =
            [
                'name' => 'required',
                'permission' => 'required',
            ];
            
        }     
    
        else{
            $rules =
            [
                'name' => 'required|unique:roles,name',
                'permission' => 'required',    
            ];
        }
        
        
         $validator = \Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
         }
        // $this->validate($request, [
        //     'name' => 'required|unique:roles,name',
        //     'permission' => 'required',
        // ]);

        if($request->id ){
            $role = Role::find($request->id);
            if($role->name != $request->name){
                $rules['name'] = 'unique:roles,name' ;
            }
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return \Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            $role->name = $request->input('name');
            $role->save();
    
    
            $role->syncPermissions($request->input('permission'));
        }else{

            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($request->input('permission'));
        }
        return response()->json($role);

        // return redirect()->route('roles.index')
        //                 ->with('success','تم الحفظ بنجاح');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lang = App::getlocale();
        $title = 'roles' ;
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();


        return view('roles.show',compact('role','rolePermissions','title','lang'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lang = App::getlocale();
        $title = 'roles' ;
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();


        return view('roles.edit',compact('role','permission','rolePermissions','title','lang'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return response()->json($id);
    }

    public function deleteall(Request $request)
    {
        
        
        if($request->ids){
            foreach($request->ids as $id){
                DB::table("roles")->where('id',$id)->delete();
            }
           
        }
        return response()->json($request->ids);
    }
}