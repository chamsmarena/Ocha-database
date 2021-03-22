@extends('layout')
@section('title', 'Database')
@section('content')
    <div class="col">
       
        <div class="row">
            <div class="col d-flex justify-content-center">
                <img src="{{asset('images/chooseLocation.png')}}" style="height:500px;"  alt="logo ocha"/>
            </div>
            <div class="col">
                
                <div class="row pt-5">
                    <div id='page1'>
                        <p class="h1 mb-3">How do you want to explore</p>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="radioByCrisis" >
                            <label class="form-check-label" for="flexRadioDefault1">
                                By Crisis
                            </label>
                            
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="radioByCountry">
                            <label class="form-check-label" for="flexRadioDefault2">
                                By Country
                            </label>
                        </div>
                        <a href="#" class="btn btn-primary" id="buttonShowBlocs"  style="display:none;background-color:#E56A54;border:none;">Continue</a>
                    </div>

                    <div id='page2' style="display:none;">
                        <a href="#" id="buttonGoBack" class="  mb-3" >Go back</a>
                        <div id="blocCrisis" class="mb-3" style="display:none;">
                            <p class="h1 mb-3">Choose crisis</p>
                            @foreach ($datas as $data)
                                <div class="form-check form-switch ">
                                    <input class="form-check-input " type="checkbox" onchange="FilterByCrisis('{{$data->zone_code}}')" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">{{$data->zone_name}}</label>
                                </div>
                            @endforeach
                        </div>
                        <div id="blocCountry" class="mb-3" style="display:none;">
                            <p class="h1 mb-3">Choose countries</p>
                            @foreach ($listepays as $pays)
                                <div class="form-check form-switch ">
                                    <input class="form-check-input " type="checkbox" onchange="FilterByCoutries('{{$pays->local_pcode}}')" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">{{$pays->local_name}}</label>
                                </div>
                            @endforeach
                        </div>
                        
                        <a href="#" class="btn btn-primary" style="background-color:#E56A54;border:none;" id="buttonDone">Done</a>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>

    <script>

        zonesList = "";
        countriesList = "";
        filterBy = "";
        $(document).ready(function () {
            $('#radioByCrisis').change(function () {
                if ($(this).is(':checked')) {
                    $('#blocCountry').hide();
                    $('#blocCrisis').show();
                    $('#buttonShowBlocs').show();
                    filterBy = "crisis";
                }
            });

            $('#radioByCountry').change(function () {
                if ($(this).is(':checked')) {
                    $('#blocCrisis').hide();
                    $('#blocCountry').show();
                    $('#buttonShowBlocs').show();
                    filterBy = "country";
                }
            });
            $('#buttonShowBlocs').click(function () {
                    $('#page1').hide();
                    $('#page2').show();
            });
            $('#buttonGoBack').click(function () {
                    $('#page2').hide();
                    $('#page1').show();
            });
            $('#buttonDone').click(function () {
                if(filterBy == "crisis"){
                    window.location = "/filter/"+filterBy+"/"+zonesList;
                }else{
                    window.location = "/filter/"+filterBy+"/"+countriesList;
                }
            });
        });

        function FilterByCrisis(code_zone){
            search = "_"+code_zone+"_";
            if(zonesList.search(search)==-1){
                zonesList = zonesList + search;
            }else{
                zonesList = zonesList.replace(search, "");
            }
        }

        function FilterByCoutries(pcode){
            search = "_"+pcode+"_";
            if(countriesList.search(search)==-1){
                countriesList = countriesList + search;
            }else{
                countriesList = countriesList.replace(search, "");
            }
            console.log(countriesList);
        }

        
    </script>
@endsection

