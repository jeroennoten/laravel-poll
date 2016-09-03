<?php

namespace JeroenNoten\LaravelPoll\Http\Controllers;

use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use JeroenNoten\LaravelPoll\Models\PollAnswer;

class Votes
{
    public function store(PollAnswer $answer, Request $request, Redirector $redirector, CookieJar $cookies)
    {

        $pollCookie = $request->cookie('poll');
        $votedPolls = $pollCookie ? explode(',', $pollCookie) : [];

        if (!in_array($answer->pollQuestion->id, $votedPolls)) {
            $answer->increment('votes');
            $votedPolls[] = $answer->pollQuestion->id;
        }

        return $redirector->back()->withCookie($cookies->forever('poll', implode(',', $votedPolls)));
    }
}