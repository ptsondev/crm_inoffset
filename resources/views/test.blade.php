@extends('layouts.app')

@section('content')
    <div class="wrapper">

        <header>
            <h1>Web dictaphone</h1>
        </header>

        <section class="main-controls">
            <canvas class="visualizer" height="60px"></canvas>
            <div id="buttons">
                <button class="record">Record</button>
                <button class="stop">Stop</button>
            </div>
        </section>

        <section class="sound-clips">


        </section>

    </div>

    <label for="toggle">❔</label>
    <input type="checkbox" id="toggle">
    <aside>
        <h2>Information</h2>

        <p>Web dictaphone is built using <a href="https://developer.mozilla.org/en-US/docs/Web/API/Navigator.getUserMedia">getUserMedia</a> and the <a
                href="https://developer.mozilla.org/en-US/docs/Web/API/MediaRecorder_API">MediaRecorder API</a>, which provides an easier way to capture
            Media streams.</p>

        <p>Icon courtesy of <a href="http://findicons.com/search/microphone">Find Icons</a>. Thanks to <a href="http://soledadpenades.com/">Sole</a> for
            the Oscilloscope code!</p>
    </aside>
@endsection
<script src="{{ asset('js/record-sound/scripts/app.js?') }}" defer></script>
<link href="{{ asset('js/record-sound/styles/app.css') }}" rel="stylesheet">
