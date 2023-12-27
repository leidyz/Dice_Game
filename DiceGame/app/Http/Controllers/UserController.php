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

    }

    public function login(Request $request){

    }

    public function logout(Request $request){
        
    }
}
