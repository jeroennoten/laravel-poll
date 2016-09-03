@extends('adminlte::page')

@section('content')
    <form action="{{ route('admin.polls.update', $poll) }}" method="post">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Poll aanpassen</h3>
            </div>
            <div class="box-body">
                @include('poll::admin.form')
            </div>
            <div class="box-footer">
                <a href="{{ route('admin.polls.index') }}" class="btn btn-danger">Annuleren</a>
                <button type="submit" class="btn btn-success">Opslaan</button>
            </div>
        </div>
    </form>
@endsection
