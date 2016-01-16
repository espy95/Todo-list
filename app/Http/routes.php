<?php

use App\Task;
use Illuminate\Http\Request;

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', 'HomeController@index');

    /**
     * Show Task Dashboard
     */
    Route::get('/task', function () {
	    $tasks = Task::orderBy('created_at', 'asc')->get();

	    return view('tasks', [
	        'tasks' => $tasks
	    ]);
	});
    /**
     * Add New Task
     */
    Route::post('/task', function (Request $request) {
	    $validator = Validator::make($request->all(), [
	        'name' => 'required|max:255',
	    ]);

	    if ($validator->fails()) {
	        return redirect('/task')
	            ->withInput()
	            ->withErrors($validator);
	    }

	    $task = new Task;
	    $task->name = $request->name;
	    $task->save();
	    return redirect('/task');
	});

    /**
     * Delete Task
     */
    Route::delete('/task/{task}', function (Task $task) {
	    $task->delete();

	    return redirect('/task');
	});

});