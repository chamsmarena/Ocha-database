@extends('layout')
@section('title', $datas->local_name)
@section('content')
    <div class="col">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/database">Database</a></li>
                        <li class="breadcrumb-item"><a href="/managezones">Manage crisis zones</a></li>
                        <li class="breadcrumb-item"><a href="/managezone/{{ $zone[0]->zone_id }}">{{ $zone[0]->zone_name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $datas->local_name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h3>{{ $datas->local_name }}</h3>
                <p>{{ $datas->local_pcode }} (admin {{ $datas->local_admin_level }})</p>
                <p><em><a href="/edit/localite/{{ $datas->local_id }}">Edit</a> or <a href="/delete/localite/{{ $datas->local_id }}">Delete</a> the locality</em></p>
            </div>
        </div>
    </div>
@endsection           