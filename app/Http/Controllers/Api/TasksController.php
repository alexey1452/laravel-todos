<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TasksCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param Task $task
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, Task $task)
    {
        /** @var Task $tasks */
        $tasks = $task->getTasksByCardId($request->only('card_id'));

        return $this->successApiResponse($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TasksCreateRequest $request
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function store(TasksCreateRequest $request, Task $task)
    {
        $task->fill($request->only('title', 'card_id'));

        if ($task->save()) {
            return $this->successApiResponse($task, 'Card successful created');
        }

        return $this->errorApiResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskUpdateRequest $request
     * @param int $id
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskUpdateRequest $request, $id, Task $task)
    {
        /** @var Task $task */
        $currentTask = $task->getTaskById($id);

        if(!$currentTask) {
            return $this->resourceNotFound();
        }

        $currentTask->fill($request->only('title', 'completed'))->save();

        return $this->successApiResponse($currentTask, 'Task success updated');
    }


    /**
     * @param int $id
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Task $task)
    {
        $removableTask = $task->getTaskById($id);

        if(!$removableTask){
            return $this->resourceNotFound();
        }
        $removableTask->delete();

        return $this->successApiResponse();
    }
}
