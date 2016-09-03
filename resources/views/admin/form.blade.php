<div class="form-group @if($errors->has('question')) has-error @endif">
    <label class="control-label" for="questionField">Vraag</label>
    <input type="text" name="question" value="{{ old('question', $poll->text) }}" class="form-control"
           id="questionField">
    @if($errors->has('question'))
        <span class="help-block">{{ $errors->first('question') }}</span>
    @endif
</div>
<div class="form-group">
    <label class="control-label">Antwoorden</label>
    <div id="answerFields">
        @if($poll->exists)
            @foreach($poll->pollAnswers as $answer)
                <p style="display: flex;">
                    <input type="text"
                           name="answers[{{ $answer->id }}]"
                           value="{{ $answer->text }}"
                           class="form-control"
                    >
                    <button type="button"
                            class="btn btn-xs btn-danger"
                            onclick="deleteAnswer(this.parentNode)"
                            style="margin-left: 10px;"
                    ><i class="fa fa-trash"></i></button>
                </p>
            @endforeach
        @else
            <p style="display: flex;">
                <input type="text"
                       name="new_answers[]"
                       class="form-control"
                >
                <button type="button"
                        class="btn btn-xs btn-danger"
                        onclick="deleteAnswer(this.parentNode)"
                        style="margin-left: 10px;"
                ><i class="fa fa-trash"></i></button>
            </p>
            <p style="display: flex;">
                <input type="text"
                       name="new_answers[]"
                       class="form-control"
                >
                <button type="button"
                        class="btn btn-xs btn-danger"
                        onclick="deleteAnswer(this.parentNode)"
                        style="margin-left: 10px;"
                ><i class="fa fa-trash"></i></button>
            </p>
        @endif
    </div>
    <a href="#" onclick="addAnswer(event);"><i class="fa fa-plus"></i> Nog een antwoord</a>
</div>

<script>
    function addAnswer(event) {
        event.preventDefault();
        document.getElementById('answerFields').insertAdjacentHTML('beforeend', '<p style="display: flex;"><input type="text" name="new_answers[]" class="form-control"><button type="button" class="btn btn-xs btn-danger" onclick="deleteAnswer(this.parentNode)" style="margin-left: 10px;"><i class="fa fa-trash"></i></button></p>');
    }

    function deleteAnswer(field) {
        field.parentNode.removeChild(field);
    }
</script>
