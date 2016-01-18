<?php

use App\Project;
use App\Task;
use App\User;
use Illuminate\Http\Request;

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', function () {
    	$user = Auth::user();
    	$projects = $user->projects;
    	return view('home', [
    		'projects' => $projects,
    		'user' => $user
    	]);
    });

    /**
     * Mail Reminders
     */
    Route::post('/mail/{project}', function (Request $request, Project $project) {
    	$user = Auth::user();
        $emails = [];
        foreach ($project->members as $member) {
        	$emails[] = $member->email;
    	}
    	Mail::send('emails.reminder', [
    		'project' => $project,
    		'task_count' => $project->tasks()->count(),
    		'user' => $user
    	], function ($m) use ($user, $emails) {
            $m->from($user->email, $user->name)->subject('Your Reminder!');
        	$m->to($emails);
        });
	    return redirect('/task/' . $project->id);
	});

    /**
     * Show Task Dashboard
     */
    Route::get('/task/{project}', function (Project $project) {
	    $tasks = $project->tasks()->orderBy('created_at', 'asc')->get();

	    return view('tasks', [
	        'tasks' => $tasks,
	        'project' => $project
	    ]);
	});

    /**
     * Add New Task
     */
    Route::post('/task/{project}', function (Request $request, Project $project) {
	    $validator = Validator::make($request->all(), [
	        'name' => 'required|max:255',
	    ]);

	    if ($validator->fails()) {
	    	return redirect('/task/' . $project->id)
	            ->withInput()
	            ->withErrors($validator);
	    }

    try{
	    $task = new Task;
	    $task->name = $request->name;
	   	$task->project_id = $project->id;
	    $task->save();
	    return redirect('/task/' . $project->id);
    }
    catch (Illuminate\Database\QueryException $e){
        $error_code = $e->errorInfo[1];
        if($error_code == 1062){
	    	return redirect('/task/' . $project->id);
        }
    }
	});

	/**
	 * Show Projects Dashboard
	 */
    Route::get('/project', function () {
	    $projects = Project::orderBy('created_at', 'asc')->get();

	    return view('projects', [
	        'projects' => $projects
	    ]);
	});
    /**
     * Add Project
     */
    Route::post('/project', function (Request $request) {
	    $validator = Validator::make($request->all(), [
	        'name' => 'required|max:255',
	    ]);

	    if ($validator->fails()) {
	        return redirect('/project')
	            ->withInput()
	            ->withErrors($validator);
	    }

	    $project = new Project;
	    $project->name = $request->name;
	    $project->admin_id = Auth::user()->id;
	    $project->save();

	    Auth::user()->projects()->save($project);
	    return redirect('/project');
	});
    /**
     * Join Project
     */
    Route::post('/project/{project}', function (Project $project) {
    	if ($project->hasMember(Auth::user()->id)) {
    		$project->members()->detach(Auth::user()->id);
    	} else $project->members()->attach(Auth::user()->id);
	    return redirect('/project');
	});

    /**
     * Delete Task
     */
    Route::delete('/task/{task}', function (Task $task) {
    	$project_id = $task->project_id;
	    $task->delete();

	    return redirect('/task/' . $project_id);
	});

    /**
     * Delete Project
     */
    Route::delete('/project/{project}', function (Project $project) {
    	$project->members()->detach();
	    $project->delete();

	    return redirect('/project');
	});

});