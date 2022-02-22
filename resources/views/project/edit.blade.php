@extends('page')

@section('content')

    <div class="container-fluid">
        <h1>Cập nhật đơn hàng</h1>

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

        <form method="post" action="{{ route('project.update', $project) }}" class="frmProject">
            @csrf
            @method('put')

            <div class="row">
                <div class="col-sm-3">
                    <div class="my-block" id="customer-block">
                        <h3 class="block-title">Thông tin khách hàng</h3>
                        <div class="item">
                            <label>Tên Khách</label>
                            <input type="text" name="name" value="{{ old('name', $project->name) }}" />
                        </div>
                        <div class="item">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $project->email) }}" />
                        </div>
                        <div class="item">
                            <label>Số Điện Thoại</label>
                            <input type="text" name="phone" value="{{ old('phone', $project->phone) }}" />
                        </div>
                        <div class="item">
                            <label>Nguồn Khách</label>
                            <select name="source">
                                @php
                                    $source = old('source', $project->source);
                                @endphp
                                @foreach ($arrSources as $key => $value)
                                    @if ($source == $key)
                                        <option value="{{ $key }}" selected>{{ $value }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="my-block" id="thuchi-block">
                        <h3 class="block-title">Thu Chi</h3>
                        <div id="thuchiTable-region">
                            {!! $thuchitable !!}
                        </div>

                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="my-block" id="project-block">
                        <h3 class="block-title">Thông tin đơn hàng</h3>
                        <div class="item" id="status-region">
                            <label class="w100 b"> <i class="fas fa-question-circle"></i> Status</label>
                            <select name="status" id="status" pid={{ $project->id }}>
                                @php
                                    $status = old('status', $project->status);
                                @endphp
                                @foreach ($arrStatus as $key => $value)
                                    @if ($status == $key)
                                        <option value="{{ $key }}" selected>{{ $value }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div><label><i class="fas fa-book-open"></i> Thông Tin & Quy Cách</label></div>
                                <textarea name="description" rows="12">{{ old('description', $project->description) }}</textarea>
                            </div>
                            <div class="col-sm-6">
                                <div><label><i class="fas fa-book-open"></i> Ghi Chú Chung (Cho Tất Cả)</label></div>
                                <textarea name="note" rows="7">{{ old('note', $project->note) }}</textarea>
                                <div class="item">
                                    <label class="w100">Giá báo khách</label>
                                    <input type="number" name="quotation_price" value="{{ old('quotation_price', $project->quotation_price) }}" />
                                </div>

                                <div class="item">
                                    <label class="w100">Deadline</label>
                                    <input type="text" name="deadline" id="deadline"
                                        value="{{ old('deadline', date('m/d/Y', strtotime($project->deadline))) }}" />
                                </div>

                                <div class="item">
                                    <label class="w100">Độ Ưu Tiên</label>
                                    @php
                                        $priority = old('priority', $project->priority);
                                    @endphp
                                    <select name="priority">
                                        <option value="0" @if ($priority == 0) selected @endif>Bình thường</option>
                                        <option value="1" @if ($priority == 1) selected @endif>Gấp</option>
                                        <option value="2" @if ($priority == 2) selected @endif>Rất Gấp</option>
                                        <option value="3" @if ($priority == 3) selected @endif>Làm Ngay Và Luôn</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div id="admin-area">
                            <div><label><i class="fas fa-book-open"></i> Ghi Chú Riêng (Admin)</label></div>
                            <textarea name="admin_note" rows="5">{{ old('admin_note', $project->admin_note) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="my-block" id="project-block">
                        <h3 class="block-title">Tiến độ thực hiện</h3>
                        @include('project.process-timeline')
                        <div id="project-tasks">
                        </div>

                    </div>
                </div>
            </div>

            <input type="submit" value="Cập nhật" name="save" class="btn btn-primary w-100 myButton" />
        </form>
    </div>

    <script src="{{ asset('js/project-edit.js?') }}{{ time() }}"></script>
@endsection
