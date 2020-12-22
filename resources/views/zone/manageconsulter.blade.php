@extends('layout')
@section('title', $datas->zone_name)
@section('content')

    <div class="col">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/database">Database</a></li>
                        <li class="breadcrumb-item"><a href="/managezones">Manage crisis zones</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $datas->zone_name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <div class="col">
            <p><em>Liste des localités dans le <strong>{{ $datas->zone_name }}</strong></em></p>
            </div>
        </div>
        <div class="row">
            <div class="col">
            <p><em><a href="/edit/zone/{{ $datas->zone_id }}">Editer</a> ou <a href="/add/localite/{{ $datas->zone_id }}">Supprimer la zone</a>, <a href="/add/localite/{{ $datas->zone_id }}">Ajouter localité dans la zone</a></em></p>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col">
                @foreach ($liste_localites as $localite)
                    <a href="/managelocalite/{{ $localite->local_id }}" class="btn btn-light" role="button" aria-pressed="true">{{ $localite->local_name }} ({{ $localite->local_pcode }}, admin {{ $localite->local_admin_level }})</a>
                @endforeach
            </div>
        </div>
    </div>
@endsection           