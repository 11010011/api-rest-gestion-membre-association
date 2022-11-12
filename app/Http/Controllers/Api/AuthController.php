<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  /**
   * Register user
   *
   * @return UserResource
   */
  public function register(Request $request): UserResource
  {
    $request->validate([
      'name' => 'required|min:4',
      'email' => 'required|unique:users',
      'password' => 'required|min:8|max:16|confirmed',
    ]);

    try{
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
      ]);
    }catch(Exception $e){
      throw $e;
    }

    return new UserResource($user);
  }

  /**
   * Login user
   * 
   * @return \Illuminate\Http\Response
   */
  public function login(Request $request) {
    $request->validate([
      'email' => 'required',
      'password' => 'required',
    ]);

    try{
      $user = User::where('email', $request->email)->first();
      if($user && Hash::check($request->password, $user->password)){
        $abilities = $user->role == 1 ? ['*'] : ['account:update'];
        $token = $user->createToken('auth', $abilities);
        $data = [
          'message' => 'Login success',
          'data' => [
            'user' => $user,
            'token' => [
              'abilities' => $token->accessToken->abilities,
              'value' => $token->plainTextToken
            ],
          ]
        ];
      }else{
        $data = [
          'message' => 'Email or password incorrect',
          'errors' => 'Error credentials '
        ];
      }
    }catch(Exception $e){
      throw $e;
    }

    return response()->json($data);
  }
}
