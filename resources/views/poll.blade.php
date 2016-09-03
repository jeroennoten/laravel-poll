@unless($pollDisabled || !$question)
    <h1>Poll</h1>
    <p>{{ $question->text }}</p>
    @if($voted)
        @foreach ($question->pollAnswers as $answer)
            <div class="progress">
                <div class="progress-bar" style="width: {{ $answer->percentage }}%; text-align: left;">
                    <span style="white-space: nowrap; background: rgba(0,0,0,.4); line-height: 20px; display: inline-block; padding: 0 10px; min-width: 100%;">{{ $answer->text }} ({{ $answer->percentage }}%)</span>
                </div>
            </div>
        @endforeach
    @else
        <form method="post">
            {{ csrf_field() }}
            @foreach ($question->pollAnswers as $answer)
                <p>
                    <button type="submit"
                            formaction="{{ route('polls.answers.votes.store', $answer) }}"
                            class="btn btn-default btn-block"
                    >{{ $answer->text }}</button>
                </p>
            @endforeach
        </form>
    @endif
@endunless
