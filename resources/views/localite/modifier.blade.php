@extends('layout')
@section('title', 'Edit '.$datas->local_name)
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
                        <li class="breadcrumb-item active" aria-current="page">Edit {{ $datas->local_name }}</li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="row">
            <div class="col">
                <form action="/update/localite" method="POST">
                @csrf
                    <div class="form-group">
                        <label for="zone_code">Name</label>
                        <input type="text" class="form-control" id="local_name"  name="local_name" aria-describedby="zone_codeHelp" value="{{ $datas->local_name }}" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="zone_name">Pcode</label>
                        <input type="text" class="form-control" id="local_pcode" name="local_pcode" aria-describedby="zone_nameHelp"  value="{{ $datas->local_pcode}}" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="local_admin_level">Admin level</label>
                        <select class="form-control" id="local_admin_level" name="local_admin_level">
                            <option value="0" <?php echo ($datas->local_admin_level==0) ? "selected='selected'" : ""; ?>>0</option>
                            <option value="1" <?php echo ($datas->local_admin_level==1) ? "selected='selected'" : ""; ?>>1</option>
                            <option value="2" <?php echo ($datas->local_admin_level==2) ? "selected='selected'" : ""; ?>>2</option>
                            <option value="3" <?php echo ($datas->local_admin_level==3) ? "selected='selected'" : ""; ?>>3</option>
                        </select>
                    </div>
                    <input type="text" hidden name="local_id" value="{{ $datas->local_id }}">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

            </div>
        </div>

        
    </div>
@endsection  