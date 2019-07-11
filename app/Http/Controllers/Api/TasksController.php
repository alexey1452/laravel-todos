<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TasksRequest;
use App\Models\Task;
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
        $tasks = Task::query()
                        ->where('card_id',  $cardId)
                        ->get()
                        ->toArray();

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
        $task = Task::create($data);

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
        /** @var Task $task */
        $task = Task::where('id', $id)->firstOrFail();
        $task->update($request->all());

        return $this->successApiResponse($task, 'Task success updated');
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        /** @var Task $task */
        $task = Task::findOrFail($id);
        $task->delete();

        return $this->successApiResponse();
    }
}
