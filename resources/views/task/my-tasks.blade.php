@extends('page')

@section('content')
    <div class="container">
        <h1>Task của tôi</h1>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{!! $message !!}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif

        <table class="myTable" id="tbTasks">
            <tr class="first-row">
                <th>PID</th>
                <th>Khách</th>
                <th class="status">Tình Trạng</th>
                <th class="work">Công việc</th>
                <th class="note">Ghi Chú</th>
                <th>Thao Tác</th>
                <th>Phản Hồi</th>
            </tr>
            @php
                $arrStatus = [
                    0 => 'Chưa bắt đầu',
                    1 => 'Đã Bắt đầu',
                    2 => 'Đã hoàn thành',
                ];
            @endphp
            @foreach ($tasks as $task)
                @php
                    $class = '';
                    if (isset($_REQUEST['focus']) && $_REQUEST['focus'] == $task->id) {
                        $class = 'focus';
                    }
                @endphp
                <tr tid="{{ $task->id }}" class="{{ $class }}">
                    <td><a href="/project/{{ $task->pid }}/edit">{{ $task->pid }}</a></td>
                    <td>{{ $task->name }}</td>
                    <td>{{ $arrStatus[$task->status] }}</td>
                    <td>{{ $task->task }}</td>
                    <td>{{ $task->note_for_me }}</td>
                    <td>
                        <input type="button" class="btnFinish" value="Hoàn Thành" tid="{{ $task->id }}" />
                    </td>
                    <td>
                        <input type="button" class="btnSendFeedback" value="Phản hồi" tid="{{ $task->id }}" pid="{{ $task->pid }}" />
                    </td>
                </tr>
            @endforeach
        </table>

        <div id="feedback-area">
            <h3>Gửi phản hồi cho đơn hàng [<span id="project-id"></span>]</h3>
            <input type="hidden" id="reply-pid" />

            Gửi cho
            <select id="reply-to">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @if ($user->id == 2) selected @endif>{{ $user->name }}</option>
                @endforeach
            </select>
            <br>
            <textarea id="reply-content" placeholder="Nội dung" rows="5" cols="50"></textarea>
            <br>
            <input type="button" id="btnSendReply" value="Gửi" />
        </div>
    </div>

    <script src="{{ asset('js/my-tasks.js?') }}{{ time() }}"></script>
@endsection
