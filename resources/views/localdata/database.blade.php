@extends('layout')
@section('title', 'Database')
@section('content')
    <div class="col pt-5">
        <div class="row" >
            <div class="col" id='buttonOk'>
                <a href="#" class="btn btn-primary" style="background-color:#E56A54;border:none;" id="buttonDone">Done</a>
            </div>
        </div>
        <div class="row" >
            <div class="col" id='page1'>
                <p class="h4 mb-3">How do you want to explore</p>
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
                <!--a href="#" class="btn btn-primary" id="buttonShowBlocs"  style="display:none;background-color:#E56A54;border:none;">Continue</a-->
            </div>

            <div class="col" id='page2' style="display:none;">
                <!--a href="#" id="buttonGoBack" class="  mb-3" >Go back</a-->
                <div id="blocCrisis" class="mb-3" style="display:none;">
                    <p class="h4 mb-3">Choose crisis</p>
                    @foreach ($datas as $data)
                        <div class="form-check form-switch ">
                            <input class="form-check-input " type="checkbox" onchange="FilterByCrisis('{{$data->zone_code}}')" id="flexSwitchCheckDefault">
                            <label class="form-check-label" for="flexSwitchCheckDefault">{{$data->zone_name}}</label>
                        </div>
                    @endforeach
                </div>
                <div id="blocCountry" class="mb-3" style="display:none;">
                    <p class="h4 mb-3">Choose countries</p>
                    @foreach ($listepays as $pays)
                        <div class="form-check form-switch ">
                            <input class="form-check-input " type="checkbox" onchange="FilterByCoutries('{{$pays->local_pcode}}')" id="flexSwitchCheckDefault">
                            <label class="form-check-label" for="flexSwitchCheckDefault">{{$pays->local_name}}</label>
                        </div>
                    @endforeach
                </div>
                
            </div>

            <div class="col" id='page3' style="displayw:none;">
                <!--a href="#" id="buttonGoBack" class="  mb-3" >Go back</a-->
                <div id="blocPeriod" class="mb-3" style="displayw:none;">
                    <p class="h4 mb-3">Choose period</p>
                    <div class="input-group">
                        <span class="input-group-text">From to</span>
                        <select class="form-select" id='period_from' aria-label="Disabled select example" >
                            <option selected>From</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                        </select>
                        <select class="form-select" id='period_to' aria-label="Disabled select example" >
                            <option selected>To</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                        </select>
                    </div>
                </div>
                
            </div>

            <div class="col" id='page3' style="displayw:none;">
                <!--a href="#" id="buttonGoBack" class="  mb-3" >Go back</a-->
                <div id="blocPeriod" class="mb-3" style="displayw:none;">
                    <p class="h4 mb-3">Desired administrative division </p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefaultzz" id="radioAdmin0">
                        <label class="form-check-label" for="flexRadioDefault3">
                            Admin 0
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefaultzz" id="radioAdmin1" checked>
                        <label class="form-check-label" for="flexRadioDefault4">
                            Admin 1
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        zonesList = "";
        countriesList = "";
        adminLevel = "";
        
        filterBy = "";
        $(document).ready(function () {
            $('#radioByCrisis').change(function () {
                if ($(this).is(':checked')) {
                    $('#page2').hide();

                    $('#blocCountry').hide();
                    $('#blocCrisis').show();
                    $('#buttonShowBlocs').show();
                    filterBy = "crisis";

                    $('#page2').show();
                }
            });

            $('#radioAdmin0').change(function () {
                if ($(this).is(':checked')) {
                    adminLevel = "admin0";
                }
            });

            $('#radioAdmin1').change(function () {
                if ($(this).is(':checked')) {
                    adminLevel = "admin1";
                }
            });

            $('#radioByCountry').change(function () {
                if ($(this).is(':checked')) {
                    $('#page2').hide();

                    $('#blocCrisis').hide();
                    $('#blocCountry').show();
                    $('#buttonShowBlocs').show();
                    filterBy = "country";

                    
                    $('#page2').show();
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
                periodFrom = $('#period_from').val();
                periodTo = $('#period_to').val();

                if(filterBy == "crisis"){
                    window.location = "/filterV2/"+filterBy+"/"+zonesList+"/"+periodFrom+"/"+periodTo+"/"+adminLevel;
                }else{
                    window.location = "/filterV2/"+filterBy+"/"+countriesList+"/"+periodFrom+"/"+periodTo+"/"+adminLevel;
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

