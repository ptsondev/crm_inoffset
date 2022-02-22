@extends('page')

@section('content')
    <div class="container">
        <h1>Tạo Project mới</h1>

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

        <form method="post" action="{{ route('project.store') }}" class="frmProject">
            @csrf

            <div class="row">
                <div class="col-sm-4">
                    <div class="my-block" id="customer-block">
                        <h3>Thông tin khách hàng</h3>
                        <div class="item">
                            <label>Tên Khách</label>
                            <input type="text" name="name" />
                        </div>
                        <div class="item">
                            <label>Email</label>
                            <input type="email" name="email" />
                        </div>
                        <div class="item">
                            <label>Số Điện Thoại</label>
                            <input type="text" name="phone" />
                        </div>
                        <div class="item">
                            <label>Nguồn Khách</label>
                            <select name="source">
                                @foreach ($arrSources as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-sm-8">
                    <div class="my-block" id="project-block">
                        <h3>Thông tin đơn hàng</h3>
                        <div class="row">
                            <div class="col-sm-6">
                                <div><label><i class="fas fa-book-open"></i> Thông Tin & Quy Cách</label></div>
                                <textarea name="description" rows="12"></textarea>
                            </div>
                            <div class="col-sm-6">
                                <div><label><i class="fas fa-book-open"></i> Ghi Chú Chung (Cho Tất Cả)</label></div>
                                <textarea name="note" rows="7"></textarea>

                                <div class="item">
                                    <label class="w100">Giá báo khách</label>
                                    <input type="number" name="quotation_price" />
                                </div>

                                <div class="item">
                                    <label class="w100">Deadline</label>
                                    <input type="datetime-local" name="deadline" />
                                </div>

                                <div class="item">
                                    <label class="w100">Độ Ưu Tiên</label>
                                    <select name="priority">
                                        <option value="0">Bình thường</option>
                                        <option value="1">Gấp</option>
                                        <option value="2">Rất Gấp</option>
                                        <option value="3">Làm Ngay Và Luôn</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div id="admin-area">
                            <div><label><i class="fas fa-book-open"></i> Ghi Chú Riêng (Admin)</label></div>
                            <textarea name="note" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <input type="submit" value="Save" name="save" class="btn btn-primary w-100 myButton" />
        </form>
    </div>

@endsection
