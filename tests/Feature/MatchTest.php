<?php

namespace Tests\Feature;

use App\Match;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        foreach($this->fakeMatches() as $match) {
            Match::create($match);
        }
    }

    /**
     * @return void
     */
    public function test_if_it_creates_a_new_match_and_returns_the_new_list_of_matches()
    {
        $response = $this->post('/api/match');

        $newMatch = [
            'id' => 5,
            'name' => 'Match5',
            'next' => 1,
            'winner' => 0,
            'board' => [
                0, 0, 0,
                0, 0, 0,
                0, 0, 0,
            ],
        ];

        $matches = $this->fakeMatches();
        $matches[] = $newMatch;

        $this->assertEquals($matches, json_decode($response->getContent(), true));
    }

    /**
     * @return void
     */
    public function test_if_it_deletes_the_match_and_returns_the_new_list_of_matches()
    {
        $response = $this->delete('/api/match/1');

        $matches = $this->fakeMatches();
        array_shift($matches);

        $this->assertEquals($matches, json_decode($response->getContent(), true));
    }

    /**
     * @return void
     */
    public function test_if_it_returns_a_list_of_matches()
    {
        $response = $this->get('/api/match');

        $this->assertEquals($this->fakeMatches(), json_decode($response->getContent(), true));
    }

    /**
     * @return void
     */
    public function test_if_it_returns_the_state_of_a_single_match()
    {
        $response = $this->get('/api/match/1');

        $this->assertEquals($this->fakeMatches()[0], json_decode($response->getContent(), true));
    }

    /**
     * @return void
     */
    public function test_if_it_makes_a_move_in_a_match()
    {
        $response = $this->put('/api/match/1',
            [
                'position' => 1,
                'player' => 1
            ]
        );

        $board = [
            0, 1, 0,
            0, 0, 0,
            0, 0, 0,
        ];

        $this->assertEquals($board, json_decode($response->getContent(), true)['board']);
    }

    /**
     * @return void
     */
    public function test_if_it_sets_the_next_player_turn()
    {
        $response = $this->put('/api/match/1',
            [
                'position' => 1,
                'player' => 1
            ]
        );

        $this->assertEquals(2, json_decode($response->getContent(), true)['next']);
    }

    /**
     * @return void
     */
    public function test_if_it_identifies_the_winner()
    {
        $response = $this->put('/api/match/4',
            [
                'position' => 8,
                'player' => 1
            ]
        );

        $this->assertEquals(1, json_decode($response->getContent(), true)['winner']);
    }

    /**
     * Creates a fake array of matches
     *
     * @return array
     */
    private function fakeMatches()
    {
        return [
            [
                'id' => 1,
                'name' => 'Match1',
                'next' => 1,
                'winner' => 0,
                'board' => [
                    0, 0, 0,
                    0, 0, 0,
                    0, 0, 0,
                ],
            ],
            [
                'id' => 2,
                'name' => 'Match2',
                'next' => 1,
                'winner' => 0,
                'board' => [
                    0, 0, 0,
                    0, 0, 0,
                    0, 0, 0,
                ],
            ],
            [
                'id' => 3,
                'name' => 'Match3',
                'next' => 1,
                'winner' => 0,
                'board' => [
                    0, 0, 0,
                    0, 0, 0,
                    0, 0, 0,
                ],
            ],
            [
                'id' => 4,
                'name' => 'Match4',
                'next' => 1,
                'winner' => 0,
                'board' => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 0, 0,
                ],
            ],
        ];
    }
}
