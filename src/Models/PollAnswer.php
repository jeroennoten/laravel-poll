<?php

namespace JeroenNoten\LaravelPoll\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed        id
 * @property PollQuestion pollQuestion
 * @property mixed        votes
 * @method increment($field)
 */
class PollAnswer extends Model
{
    protected $fillable = ['text', 'votes'];

    public function pollQuestion()
    {
        return $this->belongsTo(PollQuestion::class);
    }

    public function getPercentageAttribute()
    {
        return round($this->votes / $this->pollQuestion->votes * 100);
    }
}
