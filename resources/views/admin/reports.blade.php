@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2 col-md-2 col-lg-1 col-xl-1">
                <nav class="nav navbar-light navbar-toggleable-sm">
                    <div class="flex-column mt-md-0 mt-4 pt-md-0 pt-4" id="navbarWEX">
                        <a class="nav-link" href="{{url('admin')}}"><span class="fa fa-home"></span>Dashboard</a>
                        <a href="{{url('admin/members')}}" class="nav-link">Members</a>
                        <a href="{{url('admin/conductors')}}" class="nav-link">Conductors</a>
                        <a href="{{url('admin/conductor-reports')}}" class="nav-link navbar-brand active">Conductor Reports</a>
                        <a href="{{url('admin/loans')}}" class="nav-link">Loans</a>
                    </div>
                </nav>
            </div>
            <div class="col-sm-10 col-md-10 col-lg-11 col-xl-11">
                @if(session('info'))
                    <div class="row justify-content-center">
                        <div class="alert alert-success">
                            {{session('info')}}
                        </div>
                    </div>
                @endif

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Recorded Trips</div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <form action="" method="get" id="parameterForm">
                                        <div class="row justify-content-center">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label for="conductor" class="text-md-right">Conductor</label>
                                                    <select name="conductor_id" id="conductor" class="form-control">
                                                        @foreach($conductors as $conductor)
                                                            <option value="{{$conductor->id}}" {{$selected_conductor == $conductor->id ? 'selected':''}}>{{$conductor->first_name}} {{$conductor->last_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="show">Show</label>
                                                <select name="show" id="show" class="form-control" onchange="$('#parameterForm').submit()">
                                                    <option value="5" {{$show == 5 ? 'selected':''}}>5</option>
                                                    <option value="10" {{$show == 10 ? 'selected':''}}>10</option>
                                                    <option value="25" {{$show == 25 ? 'selected':''}}>25</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label for="start_date" class="text-md-right">{{ __('Start Date') }}</label>

                                                    <input id="start_date" type="text" class="form-control{{ $errors->has('start_date') ? ' is-invalid' : '' }}" name="start_date" value="{{ (new DateTime($start_date))->format('d/m/Y') }}"
                                                           required autofocus>

                                                    @if ($errors->has('start_date'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('start_date') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label for="end_date" class="text-md-right">{{ __('End Date') }}</label>

                                                    <input id="end_date" type="text" class="form-control{{ $errors->has('end_date') ? ' is-invalid' : '' }}" name="end_date" value="{{ (new DateTime($end_date))->format('d/m/Y') }}" required autofocus>

                                                    @if ($errors->has('end_date'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('end_date') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-md-1">
                                                <button class="btn btn-primary">Get</button>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-primary" onclick="printReport()">Print</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="row justify-content-center" style="margin-top: 20px;">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Car registration</th>
                                            <th>Amount collected</th>
                                            <th>SACCO Charge</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($trips as $trip)
                                            <tr>
                                                <td>{{(new DateTime($trip->created_at))->format('d/m/Y')}}</td>
                                                <td>{{(new DateTime($trip->created_at))->format('H:i')}}</td>
                                                <td>{{$trip->vehicle->registration}}</td>
                                                <td>{{$trip->total_amount}}</td>
                                                <td>{{$trip->sacco_charge}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <div class="row justify-content-center">
                            {{ $trips->appends(['conductor_id'=> $selected_conductor,'start_date'=> $start_date, 'end_date'=> $end_date, 'show'=> $show])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('jscontent')
    <script>
        function printReport(){
            var form = $('#parameterForm');
            form.append("<input type='hidden' id='printField' name='print' value='true'>");

            window.open(window.location.protocol + "//"+window.location.host + window.location.pathname + "?" + form.serialize(), "Print Report");

            $('#printField').remove();
        }

        $(document).ready(function(){
            $('#start_date').datepicker({
                dateFormat: "dd/mm/yy",
                altFormat: "yy-mm-dd"
            });

            $('#end_date').datepicker({
                dateFormat: "dd/mm/yy",
                altFormat: "yy-mm-dd"
            });
        });
    </script>
@endsection