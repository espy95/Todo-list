@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Your projects</div>
                @if (count($projects) > 0)
                <div class="panel panel-default">
                    <div class="panel-body">
                        @foreach ($projects as $project)
                            <table class="table table-condensed project-table">
                                <thead>
                                    <th><a href="/task/{{ $project->id }}">{{ $project->name }}</a></th>
                                    <th data-toggle="tooltip" data-placement="top" title="Number of tasks in project">{{ count($project->tasks) }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($project->tasks as $task)
                                    <tr>
                                        <td class="table-text">{{ $task->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
