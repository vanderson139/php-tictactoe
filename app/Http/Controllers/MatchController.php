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
     * TODO it's mocked, make this work :)
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function match($id)
    {
        return response()->json([
            'id'     => $id,
            'name'   => 'Match' . $id,
            'next'   => 2,
            'winner' => 0,
            'board'  => [
                1, 0, 2,
                0, 1, 2,
                0, 0, 0,
            ],
        ]);
    }

    /**
     * Makes a move in a match
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function move($id)
    {
        $board = [
            1, 0, 2,
            0, 1, 2,
            0, 0, 0,
        ];

        $position = Input::get('position');
        $board[$position] = 2;

        return response()->json([
            'id'     => $id,
            'name'   => 'Match' . $id,
            'next'   => 1,
            'winner' => 0,
            'board'  => $board,
        ]);
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

    /**
     * Creates a fake array of matches
     *
     * @return \Illuminate\Support\Collection
     */
    private function fakeMatches()
    {
        return collect([
            [
                'id'     => 1,
                'name'   => 'Match1',
                'next'   => 2,
                'winner' => 1,
                'board'  => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 2, 1,
                ],
            ],
            [
                'id'     => 2,
                'name'   => 'Match2',
                'next'   => 1,
                'winner' => 0,
                'board'  => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 0, 0,
                ],
            ],
            [
                'id'     => 3,
                'name'   => 'Match3',
                'next'   => 1,
                'winner' => 0,
                'board'  => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 2, 0,
                ],
            ],
            [
                'id'     => 4,
                'name'   => 'Match4',
                'next'   => 2,
                'winner' => 0,
                'board'  => [
                    0, 0, 0,
                    0, 0, 0,
                    0, 0, 0,
                ],
            ],
        ]);
    }

}
