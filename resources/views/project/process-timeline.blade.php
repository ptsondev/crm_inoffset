@php
$i = 1;
@endphp
@foreach ($tasks as $task)
    @php
        $class = '';
        if ($task->status == 2) {
            $class = 'finish';
        } elseif ($task->status == 1) {
            $class = 'ongoing';
        }
    @endphp
    <div class="process row {{ $class }}">
        <div class="col-2">
            <div class="num">{{ $i++ }}</div>
        </div>
        <div class="col-10">
            <label>{{ $task->task }}</label>
            @if ($class == 'finish')
                <br>
                Start date - End date
            @else
                <select name="assign_{{ $task->id }}">
                    <option value="0">-- Bỏ qua --</option>
                    @foreach ($users as $user)
                        <option @if ($task->uid == $user->id) selected @endif value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <br>

                <input type="text" name="note_{{ $task->id }}" value="{{ $task->note_for_me }}" placeholder="Ghi chú cho thiết kế" />
            @endif
        </div>
    </div>
@endforeach

@if (!$tasks->isEmpty())
    <input type="hidden" name="update-process" value="1" />
@endif
