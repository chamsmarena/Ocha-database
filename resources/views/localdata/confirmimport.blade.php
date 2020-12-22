
@extends('layout')
@section('title', 'Confirm data import')
@section('content')
    <div class="col">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item " aria-current="page"><a href="/database">Database</a></li>
                        <li class="breadcrumb-item " aria-current="page"><a href="/import">Import screen</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Confirm data import</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col">
                Are you sure you want to import <strong>{{ $elementName }} </strong> data? old data will be replaced.

                    @if (Session::has('msg'))
                        <div class="alert alert-danger" role="alert">
                        {!! Session::has('msg') ? Session::get("msg") : '' !!}
                    </div>
                    @endif

            
                <form action="/database/guide_import" method="POST">
                @csrf
                    <div class="form-group">
                        <label for="import">Type "IMPORT" in all caps to confirm</label>
                        <input type="text" class="form-control w-25" id="import"  name="import" autocomplete="off">

                    </div>
                    <input type="text" hidden name="element" value="{{ $element }}">
                    <button type="submit" class="btn btn-primary" style="background-color:#418fde;border:none;">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection