@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    New Project
                </div>

                <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('errors')

                    <!-- New Project Form -->
                    <form action="/project" method="POST" class="form-horizontal">
                        {{ csrf_field() }}

                        <!-- Project Name -->
                        <div class="form-group">
                            <label for="project-name" class="col-sm-3 control-label">Project</label>

                            <div class="col-sm-6">
                                <input type="text" name="name" id="project-name" class="form-control" value="{{ old('project') }}">
                            </div>
                        </div>

                        <!-- Add Project Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-plus"></i> Add Project
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Projects -->
            @if (count($projects) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Current Projects
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped project-table">
                            <thead>
                                <th>Project</th>
                                <th>&nbsp;</th>
                            </thead>
                            <tbody>
                                @foreach ($projects as $project)
                                    <tr>
                                        <td class="table-text"><div>{{ $project->name }}</div></td>

                                        @if ($project->hasMember(Auth::user()->id))
                                        <!-- Project Leave Button -->
                                        <td>
                                            <form action="/project/{{ $project->id }}" method="POST">
                                                {{ csrf_field() }}

                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-minus"></i> Leave
                                                </button>
                                            </form>
                                        </td>
                                        @if ($project->isAdmin(Auth::user()->id))
                                        <!-- Project Delete Button -->
                                        <td>
                                            <form action="/project/{{ $project->id }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}

                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                        @endif
                                        @else
                                        <!-- Project Join Button -->
                                        <td>
                                            <form action="/project/{{ $project->id }}" method="POST">
                                                {{ csrf_field() }}

                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-plus"></i> Join
                                                </button>
                                            </form>
                                        </td>
                                        <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection