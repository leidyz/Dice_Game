<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;
    public $createdStatus = 201;
    public $unauthorizedStatus = 401;
    public $notFoundStatus = 404;

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=> 'sometimes|unique:users',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], $this->unauthorizedStatus);            
        }

        $input = $request->all(); 
        $input['name']= $request->input('name','anonymous');//handle optional input fields with a default value.
        $input['password'] = bcrypt($input['password']); //this hashing is more resistant to brute-force attacks.
        $user = User::create($input)->assignRole('player');
        $success['token'] =  $user->createToken('DiceGame')-> accessToken; 
        $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this->createdStatus); 
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], $this->unauthorizedStatus);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {// checking the provided credentials 
            $user = Auth::user();
            $success['token'] = $user->createToken('DiceGame')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorized'], $this->unauthorizedStatus);
        }
    }

    public function logout(Request $request){
        $user = Auth::user();
        $token = $user->token();
        $token->revoke();
        return response()->json(['message' => 'Successfully logged out'], $this->successStatus);
    }

}

