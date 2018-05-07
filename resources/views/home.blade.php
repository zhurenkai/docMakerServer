@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                @foreach($projects as $project)
                <div class="panel-heading"><a href="doc/markdown?project_id={{$project->id}}">{{$project->name}}</a></div>
                    @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
