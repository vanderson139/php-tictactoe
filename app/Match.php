<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    const DEFAULT_MATCH = [
        'next'   => 1,
        'winner' => 0,
        'board'  => [
            0, 0, 0,
            0, 0, 0,
            0, 0, 0,
        ],
    ];

    const POSSIBLE_WINS_HORIZONTAL = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8],
    ];

    const POSSIBLE_WINS_VERTICAL = [
        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8],
    ];

    const POSSIBLE_WINS_DIAGONAL = [
        [0, 4, 8],
        [2, 4, 6],
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['board', 'next'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'board' => 'array'
    ];

    public static function newMatch()
    {
        self::create(self::DEFAULT_MATCH);
    }

    public function setBoardAttribute($value)
    {
        $this->attributes['board'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getMatchName()
    {
        return 'Match' . $this->attributes['id'];
    }

    public function getMatchWinner()
    {
        if ($this->theWinnerIs1()) {
            return 1;
        }

        if ($this->theWinnerIs2()) {
            return 2;
        }

        return 0;
    }

    private function theWinnerIs1()
    {
        $player = 1;

        return $this->winsInHorizontal($player)
            || $this->winsInVertical($player)
            || $this->winsInDiagonal($player);
    }

    private function theWinnerIs2()
    {
        $player = 2;

        return $this->winsInHorizontal($player)
            || $this->winsInVertical($player)
            || $this->winsInDiagonal($player);
    }

    private function winsInHorizontal($player)
    {
        $result = array_keys($this->board, $player);

        foreach (self::POSSIBLE_WINS_HORIZONTAL as $win) {
            if (count(array_intersect($result, $win)) == 3) {
                return true;
            }
        }

        return false;
    }

    private function winsInVertical($player)
    {
        $result = array_keys($this->board, $player);

        foreach (self::POSSIBLE_WINS_VERTICAL as $win) {
            if (count(array_intersect($result, $win)) == 3) {
                return true;
            }
        }

        return false;
    }

    private function winsInDiagonal($player)
    {
        $result = array_keys($this->board, $player);

        foreach (self::POSSIBLE_WINS_DIAGONAL as $win) {
            if (count(array_intersect($result, $win)) == 3) {
                return true;
            }
        }

        return false;
    }

    public function toArray()
    {
        $attributes = parent::toArray();

        $attributes['name'] = $this->getMatchName();
        $attributes['winner'] = $this->getMatchWinner();

        return $attributes;
    }
}
