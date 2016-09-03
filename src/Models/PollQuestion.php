<?php

namespace JeroenNoten\LaravelPoll\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static PollQuestion first
 * @method static Collection latest
 * @property mixed      id
 * @property mixed      text
 * @property Collection pollAnswers
 * @property mixed      votes
 */
class PollQuestion extends Model
{
    protected $fillable = ['text'];

    public function pollAnswers()
    {
        return $this->hasMany(PollAnswer::class);
    }

    public function getVotesAttribute()
    {
        return $this->pollAnswers()->getBaseQuery()->sum('votes');
    }
}
