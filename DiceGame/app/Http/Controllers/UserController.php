<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function updateName(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'newName' => 'nullable|string|unique:users,name,' . $request->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Authenticate the user
        $user = Auth::guard('api')->user();

        // default 'Anonymous'
        $newName = $request->newName ?:'anonymous';

        User::where('id', $user->id)->update(['name' => $newName]);

        return response()->json(['message' => 'Player name updated successfully']);
    }



}

