<?php

namespace JeroenNoten\LaravelPoll\Http\Controllers\Admin;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use JeroenNoten\LaravelPoll\Models\PollAnswer;
use JeroenNoten\LaravelPoll\Models\PollQuestion;

class Polls
{
    public function index(Factory $view, Application $app)
    {
        $questions = PollQuestion::all();

        $path = $app->storagePath().DIRECTORY_SEPARATOR.'poll'.DIRECTORY_SEPARATOR.'disabled';
        $disabled = file_exists($path);

        return $view->make('poll::admin.index', compact('questions', 'disabled'));
    }

    public function create(Factory $view)
    {
        return $view->make('poll::admin.create', ['poll' => new PollQuestion]);
    }

    public function store(Request $request, Redirector $redirector)
    {
        $question = PollQuestion::create([
            'text' => $request->input('question'),
        ]);

        $answerCollection = new Collection($request->input('new_answers'));
        $answers = $answerCollection->map(function ($text) {
            return new PollAnswer(compact('text'));
        });

        $question->pollAnswers()->saveMany($answers);

        return $redirector->route('admin.polls.index');
    }

    public function edit(PollQuestion $poll, Factory $view)
    {
        return $view->make('poll::admin.edit', compact('poll'));
    }

    public function update(PollQuestion $poll, Request $request, Redirector $redirector)
    {
        $poll->update(['text' => $request->input('question')]);

        $answers = $request->input('answers');

        $poll->pollAnswers->each(function (PollAnswer $answer) use ($answers) {
            if (isset($answers[$answer->id])) {
                $answer->update(['text' => $answers[$answer->id]]);
            } else {
                $answer->delete();
            }
        });

        $newAnswerCollection = new Collection($request->input('new_answers'));
        $newAnswers = $newAnswerCollection->map(function ($text) {
            return new PollAnswer(compact('text'));
        });

        $poll->pollAnswers()->saveMany($newAnswers);

        return $redirector->route('admin.polls.index');
    }

    public function destroy(PollQuestion $poll, Redirector $redirector)
    {
        $poll->delete();

        return $redirector->route('admin.polls.index');
    }

    public function disable(Redirector $redirector, Application $app)
    {
        $path = $app->storagePath().DIRECTORY_SEPARATOR.'poll'.DIRECTORY_SEPARATOR.'disabled';

        if (! file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        touch($path);

        return $redirector->route('admin.polls.index');
    }

    public function enable(Redirector $redirector, Application $app)
    {
        $path = $app->storagePath().DIRECTORY_SEPARATOR.'poll'.DIRECTORY_SEPARATOR.'disabled';
        @unlink($path);

        return $redirector->route('admin.polls.index');
    }
}
