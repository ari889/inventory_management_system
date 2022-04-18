<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Uploadable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileUpdateFormRequest;
use App\Http\Requests\PasswordUpdateFormRequest;

class MyProfileController extends Controller
{
    use Uploadable;
    protected function setPageData($page_title, $sub_title, $page_icon){
        view()->share(['page_title' => $page_title, 'sub_title' => $sub_title, 'page_icon' => $page_icon]);
    }

    public function index(){
        $this->setPageData('My Profile', 'My Profile', 'fas fa-id-badge');
        return view('user.my-profile');
    }

    public function updateProfile(ProfileUpdateFormRequest $request){
        if($request->ajax()){
            $collection = collect($request->validated())->except(['email', 'avatar']);
            $avatar = !empty($request->old_avatar) ? $request->old_avatar : null;
            if($request->hasFile('avatar')){
                $avatar = $this->upload_file($request->file('avatar'), USER_AVATAR_PATH);

            }
            $collection = $collection->merge(compact('avatar'));
            $result = User::find(auth()->user()->id)->update($collection->all());
            if($result){
                if($request->hasFile('avatar')){
                    $this->delete_file($request->old_avatar, USER_AVATAR_PATH);
                }
                $output = ['status' => 'success', 'message' => 'Profile updated successfully!'];
            }else{
                if($request->hasFile('avatar')){
                    $this->delete_file($avatar, USER_AVATAR_PATH);
                }
                $output = ['status' => 'error', 'message' => 'Failed to update data!'];
            }

            return response()->json($output);
        }
    }

    public function updatePassword(PasswordUpdateFormRequest $request){
        if($request->ajax()){
            if(auth()->check()){
                $user = Auth::user();
                if(!Hash::check($request->current_password, $user->password)){
                    $output = ['status' => 'error', 'message' => 'Password not matched!'];
                }else{
                    $user->password = $request->password;
                    if($user->save()){
                        $output = ['status' => 'success', 'message' => 'Password changed successfully!'];
                    }else{
                        $output = ['status' => 'error', 'message' => 'Failed to change password!'];
                    }
                }
                return response()->json($output);
            }
        }
    }
}
