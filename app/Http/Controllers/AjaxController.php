<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Models\ThuChi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Auth;

use function Ramsey\Uuid\v1;

class AjaxController extends Controller
{
    //
    public function show_process(Request $request)
    {
        // kiểm tra project này đã tạo task chưa, nếu chưa tạo mặc định và load lên
        $tasks = Task::where('pid', $request['project_id'])->get();
        if ($tasks->isEmpty()) {
            $task = new Task();
            $task->pid = $request['project_id'];
            $task->uid = 0; /// tmp
            $task->status = 0;
            $task->task = 'Thiết Kế';
            $task->save();

            $task = new Task();
            $task->pid = $request['project_id'];
            $task->uid = 1; /// tmp
            $task->status = 0;
            $task->task = 'Bình File';
            $task->save();

            $task = new Task();
            $task->pid = $request['project_id'];
            $task->uid = 1; /// tmp
            $task->status = 0;
            $task->task = 'In Ấn';
            $task->save();

            $task = new Task();
            $task->pid = $request['project_id'];
            $task->uid = 1; /// tmp
            $task->status = 0;
            $task->task = 'Gia Công';
            $task->save();

            $task = new Task();
            $task->pid = $request['project_id'];
            $task->uid = 1; /// tmp
            $task->status = 0;
            $task->task = 'Giao Hàng';
            $task->save();

            $tasks = Task::where('pid', $request['project_id'])->get();
        }
        $users = User::all();
        $html = view('project.process-timeline', compact('users', 'tasks'))->render();
        return response()->json(['html' => $html]);
    }

    public function reload_notification(Request $request)
    {
        $uid = Auth::id();
        $notices = Notification::where('uid', $uid)->distinct()->orderByDesc('id')->get();
        $html = '';
        foreach ($notices as $notice) {
            // render list notification, replace html
            $class = ($notice->read == 1) ? 'read' : 'unread';
            $html .= '<div class="item ' . $class . '">';
            $html .= $notice->content;
            $html .= '</div>';
        }
        $unread = Notification::where(['uid' => $uid, 'read' => 0])->get();
        $new = $unread->count();
        return response()->json(['html' => $html, 'new' => $new]);
    }

    public function read_notification(Request $request)
    {
        $uid = Auth::id();
        $now = date('Y-m-d H:i:s', time() - 10);
        $affected = DB::table('notifications')
            ->where('uid', '=', $uid)
            ->where('created_at', '<=', $now)
            ->update(['read' => 1]);

        Log::debug($now);
    }

    public function task_finish(Request $request)
    {
        $task = Task::find($request['tid']);
        $task->finish_at = date('Y-m-d H:i:s');
        $task->status = 2;
        $task->update();



        $newTask = Task::assisgn_next_task($task->pid);

        $project = Project::find($task->pid);
        if ($task->task == 'Thiết Kế') {
            $project->status = 5;
        } else if ($task->task == 'Bình File') {
            $project->status = 6;
        } else if ($task->task == 'In Ấn') {
            $project->status = 7;
        } else if ($task->task == 'Gia Công') {
            $project->status = 8;
        } else if ($task->task == 'Giao Hàng') {
            $project->status = 9;
        }
        $project->assigned = $newTask->uid;
        $project->update();

        return response()->json(['result' => 1]);
    }

    public function task_reply(Request $request)
    {
        $notice = new Notification();
        $notice->uid = $request['uid'];
        $notice->content = 'Phản hồi đơn hàng ' . $request['pid'] . ' --- ' . $request['content'];
        $notice->save();
    }

    public function add_thuchi_to_project(Request $request)
    {

        ThuChi::create($request->all());
        return response()->json(['thuchiTable' => ThuChi::displayThuChiByProject($request['pid'])]);
    }
}
