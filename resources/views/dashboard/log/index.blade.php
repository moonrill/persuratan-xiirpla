@extends('layouts.app')
@section('title', 'Log Activity')
@section('content')
    <div class="row">
        <div class="col d-flex justify-content-between mb-2">
            <a class="btn btn-primary" href="{{url('/dashboard')}}"><i class="bi-arrow-left-circle"></i>
                Kembali</a>
        </div>
    </div>
    <div class="row justify-content-center ">
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hovered DataTable">
                        <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Log</th>
                            <th>Created at</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 1;
                        ?>
                        @foreach($logs as $log)
                        @php
                            $type = "";
                            switch($log->action) {
                                case "UPDATE":
                                    $type = "warning";
                                    break;
                                case "INSERT":
                                    $type = "success";
                                    break;
                                case "DELETE":
                                    $type = "danger";
                                    break;
                            }
                        @endphp
                            <tr>
                                <td class="text-center">{{$no++}}</td>
                                <td>{{$log->username}}</td>
                                <td class="text-center">
                                    <div @class(["d-flex", "justify-content-center", "badge", "text-center", "text-bg-$type", "p-2"])>{{$log->action}}</div>
                                </td>
                                <td>{{$log->log}}</td>
                                <td class="col-2">{{$log->created_at}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('footer')
    <script type="module">
        $('.table').DataTable();
    </script>
@endsection
