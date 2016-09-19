<?php

use Illuminate\Auth\GenericUser;
use JeroenNoten\LaravelPoll\Models\PollQuestion;

class AdminCanSeeResultsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->actingAs(new GenericUser([]));
    }

    public function testDisplayResults()
    {
        $poll = $this->createPoll();

        $poll->pollAnswers()->createMany([
            ['text' => 'Answer 1', 'votes' => '1'],
            ['text' => 'Answer 2', 'votes' => '2'],
            ['text' => 'Answer 3', 'votes' => '3'],
        ]);

        $this->visit("admin/polls/$poll->id/edit");

        $this->see('1 stem');
        $this->see('2 stemmen');
        $this->see('3 stemmen');
    }

    /** @return PollQuestion */
    private function createPoll()
    {
        return factory(PollQuestion::class)->create();
    }
}
