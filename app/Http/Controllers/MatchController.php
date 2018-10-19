<?php

namespace App\Http\Controllers;

use App\Exceptions\GameOverException;
use App\Exceptions\IllegalMoveException;
use App\Exceptions\NotYourTurnException;
use App\Match;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Response;

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
     * @throws NotYourTurnException
     * @throws IllegalMoveException
     */
    public function move($id)
    {
        $match = Match::findOrFail($id);

        $player = Input::get('player');
        $position = Input::get('position');

        $this->checkIfHasAWinner($match);

        $this->checkIfIsYourTurn($match, $player);
        $this->checkIfThePositionIsTaken($match, $position);

        $board = $match->board;

        $board[$position] = $player;

        $match->next = $this->getNextPlayer($player);

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

    private function checkIfIsYourTurn(Match $match, $player)
    {
        if ($match->next != $player) {
            throw new NotYourTurnException(Response::HTTP_FORBIDDEN);
        }
    }

    private function getNextPlayer($player)
    {
        return $player == 1 ? 2 : 1;
    }

    private function checkIfThePositionIsTaken(Match $match, $position)
    {
        if ($match->board[$position] != 0) {
            throw new IllegalMoveException(Response::HTTP_FORBIDDEN);
        }
    }

    private function checkIfHasAWinner(Match $match)
    {
        if ($match->getMatchWinner() > 0) {
            throw new GameOverException(Response::HTTP_FORBIDDEN);
        }
    }
}
