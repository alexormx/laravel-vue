<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index()
    {
       if(\Gate::allows('isAdmin') || \Gate::allows('isUser')) {
            //$this->authorize('isAdmin');
            return Role::orderBy('id', 'ASC')->get();
       }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'display_name'=> 'required|string|max:191'
        ]);
        return Role::create([
            'name' => $request['name'],
            'display_name' => $request['display_name'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $role = Role::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'display_name'=> 'required|string|max:191'
        ]);

        $role->update($request->all());
        return ['message' => 'Update user info' ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $role = Role::findOrFail($id);

        // delete the user
        $role->delete();

        return ['message' => 'User Deleted'];
    }

    public function search(){
        if ($search = \Request::get('q')) {
            $roles = Role::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                    ->orWhere('display_name','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $roles = Role::latest()->paginate(5);
        }
        return $roles;
    }
}
