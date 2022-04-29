<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SupervisorRequest;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermissions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(!auth()->user()->ability('admin', 'manage_supervisors, show_supervisors')){
            return redirect()->route('admin.index');
        }

        $supervisors = User::whereHas('roles', function($query){
            $query->Where('name', 'SuperVisor');
        })
        ->when(request()->keyword != null, function ($query) {
            $query->search(request()->keyword);
        })
        ->when(request()->status != null, function ($query) {
            $query->whereStatus(request()->status);
        })
        ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
        ->paginate(request()->limit_by ?? 10);
        return view('backend.supervisors.index', compact('supervisors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->ability('admin', 'create_supervisors')){
            return redirect()->route('admin.index');
        }
        // get the permissions and send to the view to choose the permissions to the user that i want to create
        $permissions = Permission::get(['id', 'display_name']);
        return view('backend.supervisors.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupervisorRequest $request)
    {
        if(!auth()->user()->ability('admin', 'create_supervisors')){
            return redirect()->route('admin.index');
        }

        $input['first_name'] = $request->first_name;
        $input['last_name'] = $request->last_name;
        $input['username'] = $request->username;
        $input['email'] = $request->email;
        $input['mobile'] = $request->mobile;
        $input['password'] = bcrypt($request->password);
        $input['status'] = $request->status;

        if ($image = $request->file('user_image')) {
            $image_name = Str::slug($request->username).".".$image->getClientOriginalExtension();
            $path = public_path('uploads/users/' . $image_name);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $input['user_image'] = $image_name;
        }

        $supervisor = User::create($input);
        $supervisor->markEmailAsVerified();
        $supervisor->attachRole(Role::whereName('SuperVisor')->first()->id);
        //add permission
        if(isset($request->permissions) && count($request->permissions) > 0){
            $supervisor->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'Created successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $supervisor)
    {
        if(!auth()->user()->ability('admin', 'display_supervisors')){
            return redirect()->route('admin.index');
        }

        return view('backend.supervisors.show', compact('supervisor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $supervisor)
    {
        if(!auth()->user()->ability('admin', 'update_supervisors')){
            return redirect()->route('admin.index');
        }
        $permissions = Permission::get(['id', 'display_name']);

        //get the permission that the user($supervisor) have it
        $supervisorPermission = UserPermissions::whereUserId($supervisor->id)->pluck('permission_id')->toArray();
        return view('backend.supervisors.edit', compact('supervisor', 'permissions', 'supervisorPermission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupervisorRequest $request, User $supervisor)
    {
        if(!auth()->user()->ability('admin', 'update_supervisors')){
            return redirect()->route('admin.index');
        }

        $input['first_name'] = $request->first_name;
        $input['last_name'] = $request->last_name;
        $input['username'] = $request->username;
        $input['email'] = $request->email;
        $input['mobile'] = $request->mobile;
        if(trim($request->password) != ''){
            $input['password'] = bcrypt($request->password);
        }
        $input['status'] = $request->status;


        if($image = $request->file('user_image')){
            if($supervisor->user_image != null && File::exists('uploads/users/'. $supervisor->user_image)){
                unlink('uploads/users/'. $supervisor->user_image);
            }
            $image_name = Str::slug($request->username).".".$image->getClientOriginalExtension();
            $path = public_path('uploads/users/' . $image_name);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $input['user_image'] = $image_name;
        }

        $supervisor->update($input);
        //update permission
        if(isset($request->permissions) && count($request->permissions) > 0){
            $supervisor->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'Updated successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $supervisor)
    {
        if(!auth()->user()->ability('admin', 'delete_supervisors')){
            return redirect()->route('admin.index');
        }

        if(File::exists('uploads/users/'. $supervisor->user_image)){
            unlink('uploads/users/'. $supervisor->user_image);
        }

        $supervisor->delete();

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'danger'
        ]);
    }

    public function remove_image(Request $request)
    {
        if(!auth()->user()->ability('admin', 'delete_supervisors')){
            return redirect()->route('admin.index');
        }

        $supervisor = User::findOrFail($request->supervisor_id);
        if(File::exists('uploads/users/'. $supervisor->user_image)){
            unlink('uploads/users/'. $supervisor->user_image);
            $supervisor->user_image = null;
            $supervisor->save();
        }
        return true;
    }
}
