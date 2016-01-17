Hello!
<br><br>
You have <b>{{ $task_count }}</b> due tasks in <a href="{{ url('/task/' . $project->id) }}"><b>{{ $project->name }}</b></a>.
<br><br>
Please try to finish them.
<br><br>
Sincerely, <b><i>{{ $user->name }}</i></b>