@extends('adminlte::page')

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Polls</h3>
        </div>
        <div class="box-body">
            <p><a href="{{ route('admin.polls.create') }}" class="btn btn-success">Nieuwe poll</a></p>
            @if($disabled)
                <form method="POST" action="{{ route('admin.polls.enable') }}">
                    {{ csrf_field() }}
                    Het pollsysteem is momenteel uitgeschakeld.
                    <button type="submit" class="btn btn-primary btn-xs">Inschakelen</button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.polls.disable') }}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary btn-xs">Pollsysteem uitschakelen</button>
                </form>
            @endif
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                @foreach($questions as $question)
                    <tr onclick="location.href = '{{ route('admin.polls.edit', $question) }}'" style="cursor: pointer;">
                        <td>{{ $question->text }}</td>
                        <td>
                            <form method="post"
                                  action="{{ route('admin.polls.destroy', $question) }}"
                                  style="display: inline"
                            >
                                {{ method_field('delete') }}
                                {{ csrf_field() }}
                                <button id="deleteQuestion{{ $question->id }}Button"
                                        class="btn btn-danger btn-xs"
                                        type="submit"
                                        onclick="return confirmDeleteQuestion(event)"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function confirmDeleteQuestion(event) {
            event.stopPropagation();
            return confirm('Weet je zeker dat je deze poll wilt verwijderen? Deze actie kan niet ongedaan gemaakt worden.');
        }
    </script>
@endsection
