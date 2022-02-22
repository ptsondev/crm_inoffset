@if (Auth::check())
    <div id="header">
        <div id="main-menu">
            <li><a href="{{ route('project.index') }}"><i class="fas fa-notes-medical"></i> Đơn hàng</a></li>
            <li><a href="{{ route('thuchi') }}"><i class="fas fa-dollar-sign"></i> Thu Chi</a></li>
            <li><a href="{{ route('my-tasks') }}"><i class="fas fa-tasks"></i> Task Của Tôi</a></li>
        </div>
        <div id="user-info">
            Hello, {{ Auth::user()->name }}
            <div id="user-notices">
                @php
                    $new_notice = 0;
                    $html = '';
                    foreach ($notices as $notice) {
                        // render list notification, replace html
                        $class = 'read';
                        if ($notice->read != 1) {
                            $class = 'unread';
                            $new_notice++;
                        }
                        $html .= '<div class="item ' . $class . '">';
                        $html .= $notice->content;
                        $html .= '</div>';
                    }
                    echo $html;
                @endphp
            </div>

            <a href="" id="btnShowNotice"><i class="fas fa-bell"></i><span id="notice-new"> @if ($new_notice > 0) ({{ $new_notice }}) @endif</span></a>
            <a href="javascript:void" onclick="$('#logout-form').submit();">Logout</a>

        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <audio id="notice-sound" controls>
            <source src="{{ asset('images/notice.ogg') }}" type="audio/ogg">
            <source src="{{ asset('images/notice.mp3') }}" type="audio/mpeg">
            <source src="{{ asset('images/notice.wav') }}" type="audio/wav">
        </audio>
    </div>
@endif
