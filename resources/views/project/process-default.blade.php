<div class="process row">
    <div class="col-2">
        <div class="num">1</div>
    </div>
    <div class="col-10">
        <label>Thiết Kế</label>
        <select name="step_1">
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <br>
        <input type="text" name="note_1" placeholder="Ghi chú cho thiết kế" />
    </div>
</div>

<div class="process row">
    <div class="col-2">
        <div class="num">2</div>
    </div>
    <div class="col-10">
        <label>Bình File</label>
        <select name="step_2">
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <br>
        <input type="text" name="note_2" placeholder="Ghi chú cho bình file" />
    </div>
</div>



<div class="process row">
    <div class="col-2">
        <div class="num">3</div>
    </div>
    <div class="col-10">
        <label>In Ấn</label>
        <select name="step_3">
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <br>
        <input type="text" name="note_3" placeholder="Ghi chú cho in ấn" />
    </div>
</div>


<div class="process row">
    <div class="col-2">
        <div class="num">4</div>
    </div>
    <div class="col-10">
        <label>Gia Công</label>
        <select name="step_4">
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <br>
        <input type="text" name="note_4" placeholder="Ghi chú cho gia công" />
    </div>
</div>


<div class="process row">
    <div class="col-2">
        <div class="num">5</div>
    </div>
    <div class="col-10">
        <label>Giao hàng</label>
        <select name="step_5">
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <br>
        <input type="text" name="note_5" placeholder="Ghi chú cho giao hàng" />
    </div>
</div>
