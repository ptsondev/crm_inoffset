<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;

class Task extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $guarded = ['id']; // other fields is fillablle

    public static function assisgn_next_task($project_id)
    {
        $tasks = Task::where('pid', $project_id)->get();
        $task = 0;
        foreach ($tasks as $task) {
            if ($task->status == 0) {
                break;
            }
        }
        if ($task->status != 2) { // check cho case giao hàng finish
            $task->begin_at = date('Y-m-d H:i:s');
            $task->status = 1;
            $task->update();

            // gửi notification thông báo task cho user tương ứng
            $notice = new Notification();
            $notice->uid = $task->uid;
            $notice->content = '<a href="/task/my-tasks/?focus=' . $task->id . '">' . $task->task . ' - Đơn hàng ' . $task->pid . '</a>';
            $notice->save();
        } else {
        }
        return $task;
    }
}
