<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(){
      $users = User::latest()->get();
      return $users;
      }

      public function storeuser(request $request){

        $formfield = $request->validate([
          'name'=>'required',
          'email'=>['required', 'email', Rule::unique('users', 'email')],
          'password'=> 'required|min:8'
      ]);
      $formfield['password'] = bcrypt($formfield['password']);
      $user = User::create($formfield);

        return $user;
      }

      public function update(User $user){
        $user->update([
          'name' => request('name'),
          'email' => request('email'),
          'password' => request('password') ? bcrypt(request('password')) : $user->password,
        ]);

        return $user;
      }

}
