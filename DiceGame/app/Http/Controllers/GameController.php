<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *  Route::post('/players/{id}/games/','play')->name('games.play');
    *Route::delete('/players/{id}/games/','delete')->name('games.delete');
    *
     */
    public function index()
    {
        //Route::get('players/{id}/games','index')->name('games.index');
        return view ('games.index',['games'=>Game::all()]);
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
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
