<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\ThuChi;
use App\Models\Notification;
use Auth;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arrSources = Project::getListSource();
        $arrStatus = Project::getListStatus();
        $users = User::get();
        $arrStaff  = array();
        foreach ($users as $u) {
            $arrStaff[$u->id] = $u->name;
        }
        return view('project.index', compact('arrSources', 'arrStatus', 'arrStaff'));
    }

    public function thuchi()
    {
        $arrThuChi = 3;
        return view('project.thuchi', compact('arrThuChi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arrSources = Project::getListSource();
        $arrStatus = Project::getListStatus();
        return view('project.create', compact('arrSources', 'arrStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['assigned'] = 1; // tmp
        $project = Project::create($data);
        return redirect('/project');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $arrSources = Project::getListSource();
        $arrStatus = Project::getListStatus();
        $project = Project::find($id);
        $users = User::all();
        $tasks = Task::where('pid', $id)->get();
        $thuchitable = ThuChi::displayThuChiByProject($id);

        return view('project.edit', compact('arrSources', 'arrStatus', 'project', 'users', 'tasks', 'thuchitable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['deadline'] = date("Y-m-d H:i:s", strtotime($data['deadline']));
        $project = Project::find($id);
        $project->update($data);

        if (isset($data['update-process'])) {
            $tasks = Task::where('pid', $id)->get();
            foreach ($tasks as $task) {
                if (isset($data['assign_' . $task->id])) {
                    $task->uid = $data['assign_' . $task->id];
                    $task->note_for_me = $data['note_' . $task->id];
                    if ($task->uid == 0) { // bỏ qua công đoạn này
                        $task->status = 2;

                        $task->begin_at = date("d/m/y H:i");
                        $task->finish_at = date("d/m/y H:i");
                    }
                    $task->update();
                }
            }
            Task::assisgn_next_task($id);
        }

        return redirect()->back()->with('success', 'Đã cập nhật');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function my_tasks()
    {
        $users = User::all();

          $tasks = Task::where('uid', Auth::id())
            ->where('tasks.status', 1)
            ->groupby('tasks.pid')
            ->join('projects', 'projects.id', 'tasks.pid')
            ->orderby('projects.priority', 'DESC')
            ->orderby('projects.deadline', 'ASC')
            ->orderby('tasks.id', 'ASC')
            ->get(['tasks.*', 'projects.name']);
        return view('task.my-tasks', compact('tasks', 'users'));
    }
}
