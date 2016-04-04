<!-- resources/views/overview.blade.php -->

@extends('layouts.app')

@section('content')
    @foreach($systems as $system)
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="fa fa-hashtag"></div> {{ $system->uid }}
                        </div>
                        <div class="col-sm-3">
                            <div class="fa fa-clock-o"></div>
                            {{ date_diff(new DateTime(), DateTime::createFromFormat('U', $system->records()->orderBy('id', 'desc')->first()->boot_time))->format('%D:%H:%i:%S') }}
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-sm-12">
                        <label>
                            Hostame: {{ $system->hostname }}
                        </label>
                    </div>
                    <div class="col-sm-12">
                        <label>
                            Boot Time:
                        </label>
                        {{ date('Y-m-d h:i:s', $system->records()->orderBy('id', 'desc')->first()->boot_time) }}
                    </div>
                    <div class="col-sm-12">
                        <label>
                            Entries:
                        </label>
                        {{ $system->records()->count() }}
                    </div>
                    <div class="col-sm-12">
                        {{ $system->records()->orderBy('id', 'asc')->first()->created_at }}
                        <div class="fa fa-arrow-right"></div>
                        {{ $system->records()->orderBy('id', 'desc')->first()->created_at }}
                    </div>
                    <div class="col-sm-12">
                        <hr/>
                    </div>
                    <div class="row text-center">
                        <div class="col-sm-6">
                            <a href="/view/system/{{ $system->id }}">View Details</a>
                        </div>
                        <div class="col-sm-6">
                            <form action="/system/{{ $system->id }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-danger">
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