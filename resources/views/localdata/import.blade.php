@extends('layout')
@section('title', 'Import screen')
@section('content')
    <div class="col">
        
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item " aria-current="page"><a href="/database">Database</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Import</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <blockquote class="blockquote">
                <p class="mb-0">Imports data stored in Excel files to the server 10.29.55.40.</p>
            </blockquote>
        </div>
        <div class="row">
            @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Well done!</strong> Successfull import.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
        </div>
        <div class="row">
                <div class="card float-left m-1 shadow-sm mb-5 bg-white rounded" style="width: 15rem;">
                    <div class="card-body">
                        <h5 class="card-title">Cadre harmonisé</h5>
                        <p class="card-text">Analysis and identification of risk areas and food and nutritionally insecure populations</p>
                        <a href="/confirmimport/ch" class="btn btn-primary" style="background-color:#418fde;border:none;">Launch import</a>
                    </div>
                </div>
                <div class="card float-left m-1 shadow-sm mb-5 bg-white rounded" style="width: 15rem;">
                    <div class="card-body">
                        <h5 class="card-title">Caseloads</h5>
                        <p class="card-text">Total population, people affected, people in need and people reach</p>
                        <a href="/confirmimport/caseloads" class="btn btn-primary" style="background-color:#418fde;border:none;">Launch import</a>
                    </div>
                </div>
                <div class="card float-left m-1 shadow-sm  mb-5 bg-white rounded" style="width: 15rem;">
                    <div class="card-body">
                        <h5 class="card-title">Inform sahel</h5>
                        <p class="card-text">...</p>
                        <a href="/confirmimport/informSahel" class="btn btn-primary " style="background-color:#418fde;border:none;">Launch import</a>
                    </div>
                </div>
                <div class="card float-left m-1 shadow-sm  mb-5 bg-white rounded" style="width: 15rem;">
                    <div class="card-body">
                        <h5 class="card-title">Displacements</h5>
                        <p class="card-text">...</p>
                        <a href="/confirmimport/disp" class="btn btn-primary" style="background-color:#418fde;border:none;">Launch import</a>
                    </div>
                </div>
                <div class="card float-left m-1 shadow-sm p-3 mb-5 bg-white rounded" style="width: 15rem;">
                    <div class="card-body">
                        <h5 class="card-title">Nutrition</h5>
                        <p class="card-text">SAM, MAM and GAM</p>
                        <a href="/confirmimport/nutrition" class="btn btn-primary" style="background-color:#418fde;border:none;">Launch import</a>
                    </div>
                </div>
        </div>
    </div>
@endsection