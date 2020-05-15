@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tournament {{ $tournament->id }}</div>
                <div class="card-body">

                    <a href="{{ url('/admin/tournament') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                    <a href="{{ url('/admin/tournament/' . $tournament->id . '/edit') }}" title="Edit Tournament"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                    {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['admin/tournament', $tournament->id],
                    'style' => 'display:inline'
                    ]) !!}
                    {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                    'type' => 'submit',
                    'class' => 'btn btn-danger btn-sm',
                    'title' => 'Delete Tournament',
                    'onclick'=>'return confirm("Confirm delete?")'
                    ))!!}
                    {!! Form::close() !!}
                    <br/>
                    <br/>

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>ID</th><td>{{ $tournament->id }}</td>
                                </tr>
                                <tr><th> Name </th><td> {{ $tournament->name }} </td></tr><tr><th> Type </th><td> {{ $tournament->type }} </td></tr><tr><th> Number Of Players </th><td> {{ $tournament->number_of_players }} </td></tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <?php
            $playersTeams = DB::table('tournament_player_teams')->where('tournament_id', $tournament->id)->get();
            ?>        
            <div class="card">
                <div class="card-header">Tournament Players & Teams</div>
                <div class="card-body">

                    <br/>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>#</th><th>Player Name</th><th>Team</th><th>Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($playersTeams as $item)
                                <tr>
                                    <td>{{$item->id }}</td>
                                    <td>
                                        <?php
                                        $user = DB::table('users')->where('id', $item->player_id)->first();
                                        echo $user->username;
                                        ?> 
                                    </td>
                                    <td>{{ $item->team_id }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
