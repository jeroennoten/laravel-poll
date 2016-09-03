@extends('adminlte::page')

@section('content')
    <form action="{{ route('admin.polls.store') }}" method="post">
        {{ csrf_field() }}
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Nieuwe poll</h3>
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
