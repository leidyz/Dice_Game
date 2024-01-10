<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Logging\JUnit\TestRunnerExecutionFinishedSubscriber;

class UserController extends Controller
{
    public function update(Request $request, $id)
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

    private function index() {
        $users = User::with(['games' => function ($query) {
            $query->select('user_id', 'isWon'); // Load only necessary columns
        }])
        ->select('id', 'name', 'email') // Load only necessary columns
        ->get()
        ->map(function ($user) {
            $totalGames = $user->games->count();
            $totalWins = $user->games->where('isWon', true)->count();
    
            $successRate = $totalGames > 0 ? ($totalWins / $totalGames) * 100 : 0;
    
            return [
                'Name' => $user->name,
                'E-mail' => $user->email,
                'success rate' => $successRate,
            ];
        });
    
        return response()->json(['users' => $users], 200);

    }
}