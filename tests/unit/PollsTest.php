<?php

use Illuminate\Auth\GenericUser;
use JeroenNoten\LaravelPoll\Models\PollAnswer;
use JeroenNoten\LaravelPoll\Models\PollQuestion;

class PollsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->actingAs(new GenericUser([]));
    }

    public function testIndex()
    {
        /** @var PollQuestion $pollQuestion */
        $pollQuestion = factory(PollQuestion::class)->create();

        $this->visit('admin/polls');

        $this->see($pollQuestion->text);
    }

    public function testStore()
    {
        $this->makeRequest('POST', 'admin/polls', [
            'question'    => 'Really?',
            'new_answers' => ['yes', 'no'],
        ]);

        $this->seeInDatabase('poll_questions', ['text' => 'Really?']);

        $question = PollQuestion::first();
        $this->seeInDatabase('poll_answers', ['text' => 'yes', 'poll_question_id' => $question->id]);
        $this->seeInDatabase('poll_answers', ['text' => 'no', 'poll_question_id' => $question->id]);
    }

    public function testRedirectedAfterStore()
    {
        $this->call('POST', 'admin/polls', [
            'question'    => 'Really?',
            'new_answers' => ['yes', 'no'],
        ]);

        $this->assertRedirectedToRoute('admin.polls.index');
    }

    public function testUpdate()
    {
        /** @var PollQuestion $question */
        $question = factory(PollQuestion::class)->create(['text' => 'old text']);
        $answers = factory(PollAnswer::class, 3)->make();
        $question->pollAnswers()->saveMany($answers);

        $this->makeRequest('PUT', "admin/polls/$question->id", [
            'question'    => 'new text',
            'answers'     => [
                $answers[1]->id => $answers[1]->text,
                $answers[2]->id => 'new answer text',
            ],
            'new_answers' => ['new answer 1', 'new answer 2'],
        ]);

        $this->seeInDatabase('poll_questions', ['id' => $question->id, 'text' => 'new text']);
        $this->dontSeeInDatabase('poll_answers', ['id' => $answers[0]->id]);
        $this->seeInDatabase('poll_answers', [
            'id'          => $answers[1]->id,
            'poll_question_id' => $question->id,
            'text'        => $answers[1]->text,
        ]);
        $this->seeInDatabase('poll_answers', [
            'id'          => $answers[2]->id,
            'poll_question_id' => $question->id,
            'text'        => 'new answer text',
        ]);
        $this->seeInDatabase('poll_answers', [
            'poll_question_id' => $question->id,
            'text'        => 'new answer 1',
        ]);
        $this->seeInDatabase('poll_answers', [
            'poll_question_id' => $question->id,
            'text'        => 'new answer 2',
        ]);
    }

    public function testRedirectedAfterUpdate()
    {
        /** @var PollQuestion $question */
        $question = factory(PollQuestion::class)->create(['text' => 'old text']);
        $answers = factory(PollAnswer::class, 3)->make();
        $question->pollAnswers()->saveMany($answers);

        $this->call('PUT', "admin/polls/$question->id", [
            'question'    => 'new text',
            'answers'     => [
                $answers[1]->id => $answers[1]->text,
                $answers[2]->id => 'new answer text',
            ],
            'new_answers' => ['new answer 1', 'new answer 2'],
        ]);

        $this->assertRedirectedToRoute('admin.polls.index');
    }

    public function testDestroy()
    {
        /** @var PollQuestion $question */
        $question = factory(PollQuestion::class)->create();

        $this->makeRequest('DELETE', "admin/polls/$question->id");

        $this->assertNull($question->fresh());
    }
}
