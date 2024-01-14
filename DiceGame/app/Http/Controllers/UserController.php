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
    public function update(Request $request)
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

        return response()->json(['message' => 'Player name updated successfully'], 200);
    }

    private function getUserSuccessRate()
    {
        $user = Auth::guard('api')->user();

        return User::whereHas('roles', function ($query) {
            $query->where('name', 'player');})

            ->withCount(['games as games_played'])
            ->withCount(['games as games_won' => function ($query) {
                $query->where('isWon', true);
            }])
            ->get()
            ->each(function ($user) {
                $user->success_rate = ($user->games_played > 0)
                    ? round(($user->games_won / $user->games_played) * 100, 1)
                    : 0;
            }); 
    }

    public function index() {

        $users = $this->getUserSuccessRate()

        ->map(function ($user) {
            return [
                'Name' => $user->name,
                'E-mail' => $user->email,
                'Success rate' => $user->success_rate,
            ];
        });

         $averageSuccessRate= $users->avg('success_rate');
    
         return response()->json(['Players Average Success Rate' => round($averageSuccessRate, 1), 'users' => $users],200);

    }

    public function getRanking(){

        $users = $this->getUserSuccessRate()
        ->sortByDesc('success_rate')
        ->values()
        ->map(function($user){
            return[
                'Name' => $user->name,
                'E-mail' => $user->email,
                'Success rate' => $user->success_rate, 
            ];
        });
        return response()->json(['Players Ranking' => $users],200);

    }

    public function getLoser(){
 
        $users = $this->getUserSuccessRate();
        $loser = $users->sortBy('success_rate')->first();
        return response()->json(['Worst Success Rate'=> $loser],200);
    }

    public function getWinner(){
   
        $users = $this->getUserSuccessRate();
        $winner = $users->sortByDesc('success_rate')->first();
        return response()->json(['Best Success Rate'=> $winner], 200);
    }

}