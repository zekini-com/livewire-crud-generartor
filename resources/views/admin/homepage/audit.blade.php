@extends('zekini/livewire-crud-generator::admin.layout.default')

@section('body')

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Audit Logs
            </div>

            <div class="card-body">
                <table class="table table-hover table-listing table-bordered">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>User Type</th>
                            <th>User ID</th>
                            <th>Audited Type

                            <th>Audited Id</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>IP Address</th>
                            <th>Audit Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(count($logs) > 0)
                        @foreach($logs as $log)
                        <tr>
                            @php
                            $usermodelArr = explode("\\", str_replace("\\", "\\\\", $log->user_type));
                            $userType = $usermodelArr[array_key_last($usermodelArr)];

                            $auditmodelArr = explode("\\", str_replace("\\", "\\\\", $log->auditable_type));
                            $auditType = $auditmodelArr[array_key_last($auditmodelArr)];
                            @endphp
                            <td>{{$loop->index+1}}</td>
                            <td>{{$userType}}</td>
                            <td>{{$log->user_id}}</td>
                            <td>{{$auditType}}</td>
                            <td>{{$log->auditable_id}}</td>
                            <td>{{json_encode($log->old_values)}}</td>
                            <td>{{json_encode($log->new_values)}}</td>
                            <td>{{$log->ip_address}}</td>
                            <td>{{$log->created_at}}</td>
                        </tr>
                        @endforeach
                        @else
                        <div>There is no log available</div>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection