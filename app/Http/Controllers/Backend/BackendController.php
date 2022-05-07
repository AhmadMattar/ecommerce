<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Backend\AdminInfoRequest;

class BackendController extends Controller
{
    public function login()
    {
        return view('backend.login');
    }

    public function forget_password()
    {
        return view('backend.forgot-password');
    }

    public function index()
    {
        return view('backend.index');
    }

    public function account_settings()
    {
        return view('backend.account_settings');
    }
    public function update_account_settings(AdminInfoRequest $request)
    {
        $input['first_name'] = $request->first_name;
        $input['last_name'] = $request->last_name;
        $input['username'] = $request->username;
        $input['email'] = $request->email;
        $input['mobile'] = $request->mobile;
        if($request->password != ''){
            $input['password'] = bcrypt($request->password);
        }

        if($image = $request->file('user_image')){
            if(auth()->user()->user_image != null && File::exists('uploads/users/'. auth()->user()->user_image)){
                unlink('uploads/users/'. auth()->user()->user_image);
            }
            $image_name = Str::slug($request->username).".".$image->getClientOriginalExtension();
            $path = public_path('uploads/users/' . $image_name);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $input['user_image'] = $image_name;
        }

        auth()->user()->update($input);

        return redirect()->route('admin.account_settings')->with([
            'message' => 'Updated successfully',
            'alert-type' => 'success'
        ]);
    }

    public function remove_image()
    {
        if(File::exists('uploads/users/'. auth()->user()->user_image)){
            unlink('uploads/users/'. auth()->user()->user_image);
            auth()->user()->user_image = null;
            auth()->user()->save();
        }
        return true;
    }
}
