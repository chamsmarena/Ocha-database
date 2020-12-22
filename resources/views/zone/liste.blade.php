@extends('layout')
@section('title', 'Crisis zones')
@section('content')
<div class='col'>
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/database">Database</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crisis zones</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class='col'>
            <p><em>Crisis zones in the <strong>West and Central Africa</strong></em></p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>View key figures and charts for the</p>
            @foreach ($datas as $data)
                <a href="/zone/{{ $data->zone_id }}" class="btn btn-light" role="button" aria-pressed="true">{{ $data->zone_name }}</a>
            @endforeach
            <br/>
            <br/>
            or make <em><a href="/adavancedanalysis">Advanced analysis</a></em> of all the data
        </div>

    </div>
</div>
    
@endsection