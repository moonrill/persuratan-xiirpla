@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="container">
        <div class="row">
            @if(auth()->user()->role == 'admin')
                <div class="col-3">
                    <a href="{{url('dashboard/user')}}" class="text-decoration-none">
                        <div class="card bg-c-blue ">
                            <div class="card-body text-white">
                                <h1 class="text-right"><i class="bi bi-person-fill"></i><span
                                        class="f-right">{{$user}}</span></h1>
                                <h2>User</h2>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-3">
                    <a href="{{url('dashboard/surat/jenis')}}" class="text-decoration-none">
                        <div class="card bg-c-green">
                            <div class="card-body text-white">
                                <h1 class="text-right"><i class="bi bi-envelope-open-fill"></i><span
                                        class="f-right">{{$jenis_surat}}</span></h1>
                                <h2>Jenis Surat</h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
            <div class="col-3">
                <a href="{{url('dashboard/surat')}}" class="text-decoration-none">
                    <div class="card bg-c-yellow">
                        <div class="card-body text-white">
                            <h1 class="text-right"><i class="bi bi-envelope-fill"></i><span
                                    class="f-right">{{$surat}}</span>
                            </h1>
                            <h2>Surat</h2>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{url('dashboard/log')}}" class="text-decoration-none">
                    <div class="card bg-c-pink">
                        <div class="card-body text-white">
                            <h1 class="text-right"><i class="bi bi-activity"></i><span class="f-right">{{$log}}</span>
                            </h1>
                            <h2>Log</h2>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    <div class="row">
        <div class="col bg-white">
            <div class="card">
                <div class="card-body">
                    {!! $jsChart->container() !!}
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('footer')
    <script src="{{ $jsChart->cdn() }}"></script>

    {{ $jsChart->script() }}
@endsection
