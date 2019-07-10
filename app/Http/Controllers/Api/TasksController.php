<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TasksRequest;
use App\Models\Tasks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $cardId = $request->card_id;
        $query = Tasks::query()->where('card_id', '=', $cardId);
        $tasks = $query->get()->toArray();

        return $this->successApiResponse($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TasksRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TasksRequest $request)
    {
        $data = $request->all();
        $data['complete'] = false;
        $task = new Tasks();
        $task->fill($data);
        $task->save();

        return $this->successApiResponse($task, 'Task success created');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Tasks::where('id', '=', $id)->first();

        if(!$task){
            return $this->resourceNotFound();
        }

        $task->fill($request->all());
        $task->update();

        return $this->successApiResponse($task, 'Task success updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Tasks::find($id);

        if(!$task) {
            return $this->resourceNotFound();
        }

        $task->delete();

        return $this->successApiResponse();
    }
}
