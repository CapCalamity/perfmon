<!-- resources/views/overview.blade.php -->

@extends('layouts.app')

@section('content')
    @foreach($systems as $system)
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="fa fa-hashtag"></div> {{ $system->uid }}
                        </div>
                    </div>
                </div>
                <table class="table table-condensed">
                    <tr>
                        <td><label>Hostname: </label></td>
                        <td>{{ $system->hostname }}</td>
                    </tr>
                    <tr>
                        <td><label>Uptime:</label></td>
                        <td>{{ date_diff(new DateTime(), DateTime::createFromFormat('U', $system->records()->orderBy('id', 'desc')->first()->boot_time))->format('%D:%H:%i:%S') }}</td>
                    </tr>
                    <tr>
                        <td><label>Boot time:</label></td>
                        <td>{{ date('Y-m-d h:i:s', $system->records()->orderBy('id', 'desc')->first()->boot_time) }}</td>
                    </tr>
                    <tr>
                        <td><label>Entries:</label></td>
                        <td>{{ $system->records()->count() }}</td>
                    </tr>
                    <tr>
                        <td><label>First Entry: </label></td>
                        <td>{{ $system->records()->orderBy('id', 'asc')->first()->created_at }}</td>
                    </tr>
                    <tr>
                        <td><label>Latest Entry: </label></td>
                        <td>{{ $system->records()->orderBy('id', 'desc')->first()->created_at }}</td>
                    </tr>
                </table>
                <div class="panel-footer">
                    <div class="row text-center">
                        <div class="col-sm-6">
                            <div>
                                <a href="/view/system/{{ $system->id }}">View Details</a>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <form action="/system/{{ $system->id }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-danger btn-xs">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection