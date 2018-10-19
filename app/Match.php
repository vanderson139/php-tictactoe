<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    const DEFAULT_MATCH = [
        'next' => 1,
        'winner' => 0,
        'board' => [
            0, 0, 0,
            0, 0, 0,
            0, 0, 0,
        ],
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
        'board' => 'array',
        'next' => 'integer'
    ];

    public static function newMatch() {
        self::create(self::DEFAULT_MATCH);
    }

    public function setBoardAttribute($value)
    {
        $this->attributes['board'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getMatchName() {
        return 'Match' . $this->attributes['id'];
    }

    public function toArray()
    {
        $attributes = parent::toArray();

        $attributes['name'] = $this->getMatchName();
        $attributes['winner'] = 0;

        return $attributes;
    }
}
