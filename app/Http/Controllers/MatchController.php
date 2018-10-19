<?php

namespace App\Http\Controllers;

use App\Match;
use Illuminate\Support\Facades\Input;

class MatchController extends Controller
{

    public function index()
    {
        return view('index');
    }

    /**
     * Returns a list of matches
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function matches()
    {
        return response()->json(Match::all());
    }

    /**
     * Returns the state of a single match
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function match($id)
    {
        return response()->json(Match::findOrFail($id));
    }

    /**
     * Makes a move in a match
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function move($id)
    {
        $match = Match::findOrFail($id);

        $position = Input::get('position');

        $board = $match->board;

        $board[$position] = 1;

        $match->board = $board;

        $match->save();

        return response()->json($match);
    }

    /**
     * Creates a new match and returns the new list of matches
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        Match::newMatch();

        return response()->json(Match::all());
    }

    /**
     * Deletes the match and returns the new list of matches
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $match = Match::findOrFail($id);
        $match->delete();

        return response()->json(Match::all());
    }
}
