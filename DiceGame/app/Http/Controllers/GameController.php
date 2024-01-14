<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $userGames = Game::where('user_id', $user->id)->get();

        return response()->json($userGames);
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function createGame($user){

        $dice1 = random_int(1,6);
        $dice2 = random_int(1,6);
        $isWon = false;

        if($dice1 + $dice2 == 7){
            $isWon = true;
        }
        
        return Game::create([
            'dice1' => $dice1,
            'dice2'=> $dice2,
            'isWon'=> $isWon,
            'user_id'=> $user->id,
        ]);
    }
    public function play($user){

        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

       $game = $this->createGame($user);

        return response()->json([
            'welcome' => 'Welcome to the Dice Game!',
            'dice1'=> 'Your first dice is '.$game->dice1,
            'dice2'=> 'Your second dice is '.$game->dice2,
            'total'=> 'The total of your dices are '. ($game->dice1+$game->dice2),
            'result'=> $game->isWon ? 'Congratulations, you won!':'Sorry, you lost!',
            'status' => 'success',
            'message'=> 'Your game has registered successfully!',
            'game' => $game,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        Game::where('user_id', $user->id)->delete();

       
        return response()->json(['message'=> 'All dice rolls deleted successfully!']);
    }

}