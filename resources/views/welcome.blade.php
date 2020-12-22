@extends('layout')
@section('title', 'Welcome to IMU')
@section('content')
    <div class="col">
        <div class="row">
            <div class="col-3">
                <div class="card shadow-sm mb-5 bg-white rounded" style="width: 15rem;">
                    <div class="card-body">
                        <h5 class="card-title">OCHA database</h5>
                        <p class="card-text">Development of a tool for the periodic (semi-automatic) collection and storage of data related to humanitarian activities in the Central and West African Region (WCA).</p>
                        <a href="/database" class="btn btn-primary" style="background-color:#418fde;border:none;">Go to</a>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card shadow-sm mb-5 bg-white rounded" style="width: 15rem;">
                    <div class="card-body">
                        <h5 class="card-title">BI products catalogue</h5>
                        <p class="card-text">...</p>
                        <a href="https://docs.google.com/spreadsheets/d/13kH3BlJABxCbRId9CQ8Uvj5vukqmkkrIMa7v-Nmy-Aw/edit#gid=0" class="btn btn-primary" style="background-color:#418fde;border:none;">Go to</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

