@extends('layout')
@section('title', 'Manage crisis zones')
@section('content')
<div class='col'>
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/database">Database</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage crisis zones</li>
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
            <p><em><a href="/add/zone">Add</a> a new zone</em></p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @foreach ($datas as $data)
                <a href="/managezone/{{ $data->zone_id }}" class="btn btn-light" role="button" aria-pressed="true">{{ $data->zone_name }}</a>
            @endforeach
        </div>
    </div>



    <!--div class="row">
        @if (Session::has('msg'))
        <div class="alert alert-danger" role="alert">
            {!! Session::has('msg') ? Session::get("msg") : '' !!}
        </div>
        @endif
    
        <form action="/massdelete/zone" method="POST">
        @csrf
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">Delete?</th>
                        <th scope="col">Code</th>
                        <th scope="col">Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                    <tr>
                        <td><input type="checkbox" name="checkbox{{ $data->zone_id }}" id="checkbox{{ $data->zone_id }}"></td>
                        <td><a href='/managezone/{{ $data->zone_id }}'>{{ $data->zone_code }}</a></td>
                        <td><a href='/managezone/{{ $data->zone_id }}'>{{ $data->zone_name }}</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="form-group">
                <label for="delete">Type exactly <strong>DELETE multiples</strong> to confirm</label>
                <input type="text" class="form-control" id="delete"  name="delete" >
            </div>
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>
    </div-->
</div>
    
@endsection