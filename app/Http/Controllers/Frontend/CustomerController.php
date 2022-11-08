<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Frontend\ProfileRequest;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    public function profile()
    {
        return view('frontend.customer.profile');
    }

    public function update_profile(ProfileRequest $request)
    {
        $user = auth()->user();
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['mobile'] = $request->mobile;

        if(!empty($request->password) && !Hash::check($request->password, $user->password)) {
            $data['password'] = Hash::make($request->password);
        }

        if($user_image = $request->file('user_image')){
            if($user->user_image != '' && File::exists(public_path('uploads/users/' . $user->user_image))) {
                unlink('uploads/users/' . $user->user_image);
            }

            $image_name = $user->username . '.' . $user_image->extension();
            $path = public_path('uploads/users/' . $image_name);
            Image::make($user_image->getRealPath())->resize(300, null, function($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $data['user_image'] = $image_name;
        }

        $user->update($data);
        Alert::toast('Profile updated ✔️', 'success');
        return back();

    }

    public function remove_profile_image()
    {
        $user = auth()->user();
        if($user->user_image != '' && File::exists(public_path('uploads/users/' . $user->user_image))) {
            unlink('uploads/users/' . $user->user_image);
        }
        $user->user_image = '';
        $user->save();
        Alert::toast('Profile image removed ✔️', 'success');
        return back();
    }

    public function dashboard()
    {
        return view('frontend.customer.index');
    }
    public function addresses()
    {
        return view('frontend.customer.addresses');
    }

    public function orders()
    {
        return view('frontend.customer.orders');
    }
}
