<?php

namespace JeroenNoten\LaravelPoll\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use JeroenNoten\LaravelPoll\Models\PollQuestion;

class Poll
{
    private $app;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    public function compose(View $view)
    {
        $path = $this->app->storagePath().DIRECTORY_SEPARATOR.'poll'.DIRECTORY_SEPARATOR.'disabled';

        $question = PollQuestion::latest()->first();

        $pollCookie = $this->request->cookie('poll');
        $votedPolls = $pollCookie ? explode(',', $pollCookie) : [];

        $view->with([
            'voted' => $question && in_array($question->id, $votedPolls),
            'pollDisabled' => file_exists($path),
            'question' => $question,
        ]);
    }
}