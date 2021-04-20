@extends('filterV2')
@section('title', 'Crisis zones')
@section('content')

<?php
    function convertToUnit($val,$decimal){
        $result = "";
        if($val<1000){
            $result = $val;
        }else{
            if($val<1000000){
                $result = round($val/1000)."K";
            }else{
                if($val<1000000000){
                    $result = round($val/1000000,$decimal)."M";
                }else{
                    $result = round($val/1000000000,$decimal)."B";
                }
            }
        }
        return $result;
    }

    function getTrendDataOld($datas){
        $dates = array();
        $locations = array();
        $trendsData = array();
        $arrayModel = array("year"=>0);

        foreach ($datas as $data){
            $year = intval(substr($data["date"],0,4));

            if (!in_array($year, $dates) && $year!="")
            {
                array_push($dates,$year);
            }

            if (!in_array($data["adminName"], $locations) && $data["adminName"]!="")
            {
                array_push($locations,$data["adminName"]);
                $arrayModel = array_merge($arrayModel, array($data["adminName"]=>0));
            }
        }
        
        
        foreach ($dates as $date){
            
            $arrayTemp = $arrayModel;
            if ($arrayTemp["year"]==0) {
                $arrayTemp["year"] = $date;
            }
            foreach ($locations as $location){
                $lastDate = "1900-01-01";
                $lastValue = 0;
                foreach ($datas as $data){

                    $year = intval(substr($data["date"],0,4));

                    if($date==$year && $location==$data["adminName"] && $data["value"]!=0 && $data["value"]!=""){

                        if($data["date"]==$lastDate){
                            $lastValue = $lastValue + $data["value"];
                        }else{
                            if($data["date"] > $lastDate){
                                $lastDate = $data["date"];
                                $lastValue = $data["value"];
                            }
                        }
                        
                    }
                }
                $arrayTemp[$location] = $lastValue;
            }
            
            array_push($trendsData,$arrayTemp);
        }
        return $trendsData;
    }

    function getTrendData($datas){
        $locations = array();
        $trendsData = array();
        $dates = array();

        
        foreach ($datas as $data){
            $year = intval(substr($data["date"],0,4));

            //recuperation des années
            if (!in_array($year, $dates) && $year!="")
            {
                array_push($dates,$year);
            }

            //recuperation des localites
            if (!in_array($data["adminName"], $locations) && $data["adminName"]!="")
            {
                array_push($locations,$data["adminName"]);
            }
        }


        foreach ($locations as $location){
            $dataTemp = array();
            foreach ($dates as $date){
                $lastDate = "1900-01-01";
                $lastValue = 0;
                

                foreach ($datas as $data){
                    $year = intval(substr($data["date"],0,4));
                    if($date==$year && $location==$data["adminName"] && $data["value"]!=0 && $data["value"]!=""){
                        
                        if($data["date"]==$lastDate){
                            $lastValue = $lastValue + $data["value"];
                        }else{
                            if($data["date"] > $lastDate){
                                $lastDate = $data["date"];
                                $lastValue = $data["value"];
                            }
                        }
                    }
                }
                array_push($dataTemp,array($date,$lastValue));
            }
            array_push($trendsData,array("name"=>$location,"data"=>$dataTemp));
            
        }
        return $trendsData;
    }

    function getMapDataOld($datas,$dataFieldName){
        $mapData = array();
        foreach ($datas as $data){
            array_push($mapData,array("adminName"=>$data['adminName'], "value"=>$data[$dataFieldName]));
        }
        return $mapData;
    }

    function getMapData($datas,$dataFieldName){
        $mapData = array();
        foreach ($datas as $data){
            array_push($mapData,array($data['adminName'], $data[$dataFieldName]));
        }
        return $mapData;
    }



    function array_push_assoc($array, $key, $value){
        $array[$key] = $value;
        return $array;
    }
    
    $zone=$datas[0]["zone"];


    //caseloads
    $caseloads=$datas[0]["caseloads"];
    $KeyFigureCaseLoadsByAdmin = array();
    $TrendsCaseLoadsByAdmin = array();
    $adminName = "";
    $KeyFigureCaseLoads = array("pin"=>0,"pt"=>0,"pr"=>0);

    $trendCaseloads_PIN_Raw = array();
    $trendDisplacement_IDP_Raw = array();
    

    $trendCaseloads_PIN = array();

    $trendNutrition_SAM_Raw = array();
    $trendNutrition_SAM = array();
    $nutritionColumns = array();

    $trendCh_Current_Raw = array();
    $trendCh_Current = array();
    $ch_CurrentColumns = array();

    $trendCh_Projected_Raw = array();
    $trendCh_Projected = array();
    $ch_ProjectedColumns = array();

    $caseloadColumns = array();
    
    $displacementColumns = array();


    foreach ($caseloads as $caseload){
       

        array_push($TrendsCaseLoadsByAdmin, array("adminName"=>$adminName,"date"=>$caseload->caseload_date, "pin"=>$caseload->caseload_people_in_need,  "pt"=>$caseload->caseload_people_targeted, "pr"=>$caseload->caseload_people_reached));
        

        //traitement key figure
        if ($adminLevel=="admin0") {
            $adminName = $caseload->caseload_country;
        } else {
            $adminName = $caseload->caseload_admin1_name;
        }

         //traitement trend
         array_push($trendCaseloads_PIN_Raw, array("adminName"=>$adminName,"date"=>$caseload->caseload_date, "value"=>$caseload->caseload_people_in_need));
         
         if (!in_array($adminName, $caseloadColumns) && $adminName!="")
         {
             array_push($caseloadColumns,$adminName);
         }

         
         //traitement trend fin
        
        if(array_key_exists($adminName,$KeyFigureCaseLoadsByAdmin)){
            if ($KeyFigureCaseLoadsByAdmin[$adminName]["date"]==$caseload->caseload_date) {
                $KeyFigureCaseLoadsByAdmin[$adminName] = array( 
                    "adminName"=>$adminName,
                    "date"=>$caseload->caseload_date, 
                    "pin"=>($caseload->caseload_people_in_need + $KeyFigureCaseLoadsByAdmin[$adminName]["pin"]),  
                    "pt"=>($caseload->caseload_people_targeted + $KeyFigureCaseLoadsByAdmin[$adminName]["pt"]), 
                    "pr"=>($caseload->caseload_people_reached + $KeyFigureCaseLoadsByAdmin[$adminName]["pr"])
                );
            }else{
                if ($KeyFigureCaseLoadsByAdmin[$adminName]["date"]<$caseload->caseload_date) {
                    $KeyFigureCaseLoadsByAdmin[$adminName] = array( "adminName"=>$adminName,"date"=>$caseload->caseload_date, "pin"=>$caseload->caseload_people_in_need,  "pt"=>$caseload->caseload_people_targeted, "pr"=>$caseload->caseload_people_reached);
                }
            }
        }else{
            $KeyFigureCaseLoadsByAdmin = array_push_assoc( $KeyFigureCaseLoadsByAdmin,  $adminName,  array("adminName"=>$adminName, "date"=>$caseload->caseload_date, "pin"=>$caseload->caseload_people_in_need, "pt"=>$caseload->caseload_people_targeted, "pr"=>$caseload->caseload_people_reached ));
        }
    }

    $trendCaseloads_PIN = getTrendData($trendCaseloads_PIN_Raw);
    $mapCaseloads_PIN = getMapData($KeyFigureCaseLoadsByAdmin,"pin");


    foreach ($KeyFigureCaseLoadsByAdmin as $KeyFigure){
        $KeyFigureCaseLoads["pin"] = $KeyFigureCaseLoads["pin"] + $KeyFigure["pin"];
        $KeyFigureCaseLoads["pt"] = $KeyFigureCaseLoads["pt"] + $KeyFigure["pt"];
        $KeyFigureCaseLoads["pr"] = $KeyFigureCaseLoads["pr"] + $KeyFigure["pr"];
    }

 

    //cadre harmonisé
    $cadre_harmonises=$datas[0]["cadre_harmonises"];
    $KeyFigureCHByAdminCurrent = array();
    $KeyFigureCHByAdminProjeted = array();
    $adminName = "";
    $KeyFigureCHCurrent = array("month"=>"","ch_phase1"=>0,"ch_phase2"=>0,"ch_phase3"=>0,"ch_phase35"=>0,"ch_phase4"=>0,"ch_phase5"=>0);
    $KeyFigureCHProjeted = array("month"=>"","ch_phase1"=>0,"ch_phase2"=>0,"ch_phase3"=>0,"ch_phase35"=>0,"ch_phase4"=>0,"ch_phase5"=>0);

    foreach ($cadre_harmonises as $ch){
        if ($adminLevel=="admin0") {
            $adminName = $ch->ch_country;
        } else {
            $adminName = $ch->ch_admin1_name;
        }

        

        if ($ch->ch_situation=="Current") {
            //traitement trend
            array_push($trendCh_Current_Raw, array("adminName"=>$adminName,"date"=>$ch->ch_date, "value"=>$ch->ch_phase35));
            
            if (!in_array($adminName, $ch_CurrentColumns) && $adminName!="")
            {
                array_push($ch_CurrentColumns,$adminName);
            }
            //traitement trend fin 

            //current
            if(array_key_exists($adminName,$KeyFigureCHByAdminCurrent)){
                if ($KeyFigureCHByAdminCurrent[$adminName]["date"]==$ch->ch_date) {
                    $KeyFigureCHByAdminCurrent[$adminName] = array( 
                        "adminName"=>$adminName,
                        "month"=>$ch->ch_exercise_month, 
                        "year"=>$ch->ch_exercise_year, 
                        "date"=>$ch->ch_date, 
                        "ch_phase1"=>($ch->ch_phase1 + $KeyFigureCHByAdminCurrent[$adminName]["ch_phase1"]),  
                        "ch_phase2"=>($ch->ch_phase2 + $KeyFigureCHByAdminCurrent[$adminName]["ch_phase2"]), 
                        "ch_phase3"=>($ch->ch_phase3 + $KeyFigureCHByAdminCurrent[$adminName]["ch_phase3"]),
                        "ch_phase35"=>($ch->ch_phase35 + $KeyFigureCHByAdminCurrent[$adminName]["ch_phase35"]),
                        "ch_phase4"=>($ch->ch_phase4 + $KeyFigureCHByAdminCurrent[$adminName]["ch_phase4"]),
                        "ch_phase5"=>($ch->ch_phase5 + $KeyFigureCHByAdminCurrent[$adminName]["ch_phase5"]),
                    );
                }else{
                    if ($KeyFigureCHByAdminCurrent[$adminName]["date"]<$ch->ch_date) {
                        $KeyFigureCHByAdminCurrent[$adminName] = array("adminName"=>$adminName, "month"=>$ch->ch_exercise_month,"year"=>$ch->ch_exercise_year,"date"=>$ch->ch_date, "ch_phase1"=>$ch->ch_phase1, "ch_phase2"=>$ch->ch_phase2, "ch_phase3"=>$ch->ch_phase3,"ch_phase35"=>$ch->ch_phase35,"ch_phase4"=>$ch->ch_phase4,"ch_phase5"=>$ch->ch_phase5);
                    }
                }
            }else{
                $KeyFigureCHByAdminCurrent = array_push_assoc($KeyFigureCHByAdminCurrent, $adminName, array("adminName"=>$adminName, "month"=>$ch->ch_exercise_month,"year"=>$ch->ch_exercise_year,"date"=>$ch->ch_date,  "ch_phase1"=>$ch->ch_phase1,  "ch_phase2"=>$ch->ch_phase2, "ch_phase3"=>$ch->ch_phase3, "ch_phase35"=>$ch->ch_phase35, "ch_phase4"=>$ch->ch_phase4, "ch_phase5"=>$ch->ch_phase5));
            }
        } else {
            //projected

            //traitement trend
            array_push($trendCh_Projected_Raw, array("adminName"=>$adminName,"date"=>$ch->ch_date, "value"=>$ch->ch_phase35));
            
            if (!in_array($adminName, $ch_ProjectedColumns) && $adminName!="")
            {
                array_push($ch_ProjectedColumns,$adminName);
            }
            //traitement trend fin

            if(array_key_exists($adminName,$KeyFigureCHByAdminProjeted)){
                if ($KeyFigureCHByAdminProjeted[$adminName]["date"]==$ch->ch_date) {
                    $KeyFigureCHByAdminProjeted[$adminName] = array( 
                        "adminName"=>$adminName,
                        "month"=>$ch->ch_exercise_month, 
                        "year"=>$ch->ch_exercise_year,
                        "date"=>$ch->ch_date, 
                        "ch_phase1"=>($ch->ch_phase1 + $KeyFigureCHByAdminProjeted[$adminName]["ch_phase1"]),  
                        "ch_phase2"=>($ch->ch_phase2 + $KeyFigureCHByAdminProjeted[$adminName]["ch_phase2"]), 
                        "ch_phase3"=>($ch->ch_phase3 + $KeyFigureCHByAdminProjeted[$adminName]["ch_phase3"]),
                        "ch_phase35"=>($ch->ch_phase35 + $KeyFigureCHByAdminProjeted[$adminName]["ch_phase35"]),
                        "ch_phase4"=>($ch->ch_phase4 + $KeyFigureCHByAdminProjeted[$adminName]["ch_phase4"]),
                        "ch_phase5"=>($ch->ch_phase5 + $KeyFigureCHByAdminProjeted[$adminName]["ch_phase5"]),
                    );
                }else{
                    if ($KeyFigureCHByAdminProjeted[$adminName]["date"]<$ch->ch_date) {
                        $KeyFigureCHByAdminProjeted[$adminName] = array("adminName"=>$adminName, "month"=>$ch->ch_exercise_month,"year"=>$ch->ch_exercise_year,"date"=>$ch->ch_date, "ch_phase1"=>$ch->ch_phase1, "ch_phase2"=>$ch->ch_phase2, "ch_phase3"=>$ch->ch_phase3,"ch_phase35"=>$ch->ch_phase35,"ch_phase4"=>$ch->ch_phase4,"ch_phase5"=>$ch->ch_phase5);
                    }
                }
            }else{
                $KeyFigureCHByAdminProjeted = array_push_assoc($KeyFigureCHByAdminProjeted, $adminName, array("adminName"=>$adminName, "month"=>$ch->ch_exercise_month,"year"=>$ch->ch_exercise_year,"date"=>$ch->ch_date,  "ch_phase1"=>$ch->ch_phase1,  "ch_phase2"=>$ch->ch_phase2, "ch_phase3"=>$ch->ch_phase3, "ch_phase35"=>$ch->ch_phase35, "ch_phase4"=>$ch->ch_phase4, "ch_phase5"=>$ch->ch_phase5));
            }
        }
    }

    $trendCh_Current = getTrendData($trendCh_Current_Raw);
    $trendCh_Projected = getTrendData($trendCh_Projected_Raw);
	
    foreach ($KeyFigureCHByAdminCurrent as $KeyFigure){
        $KeyFigureCHCurrent["ch_phase1"] = $KeyFigureCHCurrent["ch_phase1"] + $KeyFigure["ch_phase1"];
        $KeyFigureCHCurrent["ch_phase2"] = $KeyFigureCHCurrent["ch_phase2"] + $KeyFigure["ch_phase2"];
        $KeyFigureCHCurrent["ch_phase3"] = $KeyFigureCHCurrent["ch_phase3"] + $KeyFigure["ch_phase3"];
        $KeyFigureCHCurrent["ch_phase35"] = $KeyFigureCHCurrent["ch_phase35"] + $KeyFigure["ch_phase35"];
        $KeyFigureCHCurrent["ch_phase4"] = $KeyFigureCHCurrent["ch_phase4"] + $KeyFigure["ch_phase4"];
        $KeyFigureCHCurrent["ch_phase5"] = $KeyFigureCHCurrent["ch_phase5"] + $KeyFigure["ch_phase5"];
    }

    foreach ($KeyFigureCHByAdminProjeted as $KeyFigure){
        $KeyFigureCHProjeted["ch_phase1"] = $KeyFigureCHProjeted["ch_phase1"] + $KeyFigure["ch_phase1"];
        $KeyFigureCHProjeted["ch_phase2"] = $KeyFigureCHProjeted["ch_phase2"] + $KeyFigure["ch_phase2"];
        $KeyFigureCHProjeted["ch_phase3"] = $KeyFigureCHProjeted["ch_phase3"] + $KeyFigure["ch_phase3"];
        $KeyFigureCHProjeted["ch_phase35"] = $KeyFigureCHProjeted["ch_phase35"] + $KeyFigure["ch_phase35"];
        $KeyFigureCHProjeted["ch_phase4"] = $KeyFigureCHProjeted["ch_phase4"] + $KeyFigure["ch_phase4"];
        $KeyFigureCHProjeted["ch_phase5"] = $KeyFigureCHProjeted["ch_phase5"] + $KeyFigure["ch_phase5"];
    }



    //nutrition
    $nutritions=$datas[0]["nutrition"];
  
    
    $KeyFigurenutritionsByAdmin = array();
    $adminName = "";
    $KeyFigurenutritions = array("sam"=>0,"mam"=>0,"gam"=>0);

    foreach ($nutritions as $nutrition){
        if ($adminLevel=="admin0") {
            $adminName = $nutrition->nut_country;
        } else {
            $adminName = $nutrition->nut_admin1;
        }

        //traitement trend
        array_push($trendNutrition_SAM_Raw, array("adminName"=>$adminName,"date"=>$nutrition->nut_date, "value"=>$nutrition->nut_sam));
         
        if (!in_array($adminName, $nutritionColumns) && $adminName!="")
        {
            array_push($nutritionColumns,$adminName);
        }
        
        if(array_key_exists($adminName,$KeyFigurenutritionsByAdmin)){
            if ($KeyFigurenutritionsByAdmin[$adminName]["date"]==$nutrition->nut_date) {
                $KeyFigurenutritionsByAdmin[$adminName] = array( 
                    "adminName"=>$adminName,
                    "date"=>$nutrition->nut_date, 
                    "sam"=>($nutrition->nut_sam + $KeyFigurenutritionsByAdmin[$adminName]["sam"]),  
                    "mam"=>($nutrition->nut_gam + $KeyFigurenutritionsByAdmin[$adminName]["mam"]), 
                    "gam"=>($nutrition->nut_mam + $KeyFigurenutritionsByAdmin[$adminName]["gam"])
                );
            }else{
                if ($KeyFigurenutritionsByAdmin[$adminName]["date"]<$nutrition->nut_date) {
                    $KeyFigurenutritionsByAdmin[$adminName] = array( "adminName"=>$adminName, "date"=>$nutrition->nut_date, "sam"=>$nutrition->nut_sam,  "mam"=>$nutrition->nut_gam, "gam"=>$nutrition->nut_mam);
                }
            }
        }else{
            $KeyFigurenutritionsByAdmin = array_push_assoc( $KeyFigurenutritionsByAdmin,  $adminName,  array("adminName"=>$adminName, "date"=>$nutrition->nut_date, "sam"=>$nutrition->nut_sam, "mam"=>$nutrition->nut_gam, "gam"=>$nutrition->nut_mam ));
        }
    }

    foreach ($KeyFigurenutritionsByAdmin as $KeyFigure){
        $KeyFigurenutritions["sam"] = $KeyFigurenutritions["sam"] + $KeyFigure["sam"];
        $KeyFigurenutritions["mam"] = $KeyFigurenutritions["mam"] + $KeyFigure["mam"];
        $KeyFigurenutritions["gam"] = $KeyFigurenutritions["gam"] + $KeyFigure["gam"];
    }

    $trendNutrition_SAM = getTrendData($trendNutrition_SAM_Raw);
    $mapNutrition_SAM = getMapData($KeyFigurenutritionsByAdmin,"sam");


    //displacements
    $displacements=$datas[0]["displacements"];

    $KeyFigureDisplacementsByAdmin = array();
    $adminName = "";
    $KeyFigureDisplacements = array("idp"=>0, "refugees"=>0, "returnees"=>0);

    foreach ($displacements as $displacement){
        if ($adminLevel=="admin0") {
            $adminName = $displacement->dis_country;
        } else {
            $adminName = $displacement->dis_admin1_name;
        }

       
        
        if(array_key_exists($adminName,$KeyFigureDisplacementsByAdmin)){
            $temp = $KeyFigureDisplacementsByAdmin[$adminName];

            switch ($displacement->dis_type) {
                case 'IDP':
                     //traitement trend
                    array_push($trendDisplacement_IDP_Raw, array("adminName"=>$adminName,"date"=>$displacement->dis_date, "value"=>$displacement->dis_value));
         
                    if (!in_array($adminName, $displacementColumns) && $adminName!="")
                    {
                        array_push($displacementColumns,$adminName);
                    }

                    if ($KeyFigureDisplacementsByAdmin[$adminName]["idp_date"]==$displacement->dis_date) {
                        $KeyFigureDisplacementsByAdmin[$adminName] = array(
                            "adminName"=>$adminName,
                            "idp"=>($displacement->dis_value + $KeyFigureDisplacementsByAdmin[$adminName]["idp"]),
                            "idp_date"=>$displacement->dis_date,  
                            "refugees"=>$temp["refugees"], 
                            "refugees_date"=>$temp["refugees_date"],
                            "returnees"=>$temp["returnees"],
                            "returnees_date"=>$temp["returnees_date"],
                        );
                    }else{
                        if ($KeyFigureDisplacementsByAdmin[$adminName]["idp_date"]<$displacement->dis_date) {
                            $KeyFigureDisplacementsByAdmin[$adminName] = array(
                                "adminName"=>$adminName,
                                "idp"=>$displacement->dis_value,
                                "idp_date"=>$displacement->dis_date,  
                                "refugees"=>$temp["refugees"], 
                                "refugees_date"=>$temp["refugees_date"],
                                "returnees"=>$temp["returnees"],
                                "returnees_date"=>$temp["returnees_date"],
                            );
                        }
                    }
                    break;
                case 'Refugee':
                    if ($KeyFigureDisplacementsByAdmin[$adminName]["refugees_date"]==$displacement->dis_date) {
                        $KeyFigureDisplacementsByAdmin[$adminName] = array(
                            "adminName"=>$adminName,
                            "idp"=>$temp["idp"], 
                            "idp_date"=>$temp["idp_date"],
                            "refugees"=>($displacement->dis_value + $KeyFigureDisplacementsByAdmin[$adminName]["refugees"]),
                            "refugees_date"=>$displacement->dis_date,  
                            "returnees"=>$temp["returnees"],
                            "returnees_date"=>$temp["returnees_date"],
                        );
                    }else{
                        if ($KeyFigureDisplacementsByAdmin[$adminName]["refugees_date"]<$displacement->dis_date) {
                            $KeyFigureDisplacementsByAdmin[$adminName] = array(
                                "adminName"=>$adminName,
                                "idp"=>$temp["idp"], 
                                "idp_date"=>$temp["idp_date"],
                                "refugees"=>$displacement->dis_value,
                                "refugees_date"=>$displacement->dis_date,  
                                "returnees"=>$temp["returnees"],
                                "returnees_date"=>$temp["returnees_date"],
                            );
                        }
                    }
                    break;
                case 'Returnee':
                    if ($KeyFigureDisplacementsByAdmin[$adminName]["returnees_date"]==$displacement->dis_date) {
                        $KeyFigureDisplacementsByAdmin[$adminName] = array(
                            "adminName"=>$adminName,
                            "idp"=>$temp["idp"], 
                            "idp_date"=>$temp["idp_date"],
                            "refugees"=>$temp["refugees"],
                            "refugees_date"=>$temp["refugees_date"],
                            "returnees"=>($displacement->dis_value + $KeyFigureDisplacementsByAdmin[$adminName]["returnees"]),
                            "returnees_date"=>$displacement->dis_date
                        );
                    }else{
                        if ($KeyFigureDisplacementsByAdmin[$adminName]["returnees_date"]<$displacement->dis_date) {
                            $KeyFigureDisplacementsByAdmin[$adminName] = array(
                                "adminName"=>$adminName,
                                "idp"=>$temp["idp"],
                                "idp_date"=>$temp["idp_date"], 
                                "refugees"=>$temp["refugees"], 
                                "refugees_date"=>$temp["refugees_date"],
                                "returnees"=>$displacement->dis_value,
                                "returnees_date"=>$displacement->dis_date,
                            );
                        }
                    }
                    break;
            }
        }else{
            switch ($displacement->dis_type) {
                case 'IDP':
                    $KeyFigureDisplacementsByAdmin[$adminName] = array(
                            "adminName"=>$adminName,
                            "idp"=>$displacement->dis_value,
                            "idp_date"=>$displacement->dis_date,  
                            "refugees"=>0, 
                            "refugees_date"=>"",
                            "returnees"=>0,
                            "returnees_date"=>"",
                        );
                    break;
                case 'Refugee':
                    $KeyFigureDisplacementsByAdmin[$adminName] = array(
                            "adminName"=>$adminName,
                            "idp"=>0, 
                            "idp_date"=>"",
                            "refugees"=>$displacement->dis_value,
                            "refugees_date"=>$displacement->dis_date,  
                            "returnees"=>0,
                            "returnees_date"=>"",
                        );
                    break;
                case 'Returnee':
                    $KeyFigureDisplacementsByAdmin[$adminName] = array(
                            "adminName"=>$adminName,
                            "idp"=>0,
                            "idp_date"=>"", 
                            "refugees"=>0, 
                            "refugees_date"=>"",
                            "returnees"=>$displacement->dis_value,
                            "returnees_date"=>$displacement->dis_date,
                        );
                    break;
            }
        }
    }

    foreach ($KeyFigureDisplacementsByAdmin as $KeyFigure){
        $KeyFigureDisplacements["idp"] = $KeyFigureDisplacements["idp"] + $KeyFigure["idp"];
        $KeyFigureDisplacements["refugees"] = $KeyFigureDisplacements["refugees"] + $KeyFigure["refugees"];
        $KeyFigureDisplacements["returnees"] = $KeyFigureDisplacements["returnees"] + $KeyFigure["returnees"];
    }

    $trendDisplacement_IDP = getTrendData($trendDisplacement_IDP_Raw);
    $mapDisplacement_IDP = getMapData($KeyFigureDisplacementsByAdmin,"idp");

  

?>

<div class='col-12 pt-3'>
    <p>
        Datas for the <strong>{{$zone->zone_name}}</strong>, <em>by {{$adminLevel}} from {{$periodFrom}} to {{$periodTo}}</em><br>
        <a href="#" class="btn-link" onclick="ExportPowerPoint()"><em>Exporter une présentation</em></a>
                    
    </p>
    <div class="row">
        <div class="col-3 keyFigure-card cards-selected rounded me-1 ms-1" id="keyFigure-caseloads" onclick="showData('caseloads')">
            <p>Caseloads</p>
            <div class="row">
                <div class='col' >
                    <div>
                        <img src="{{asset('images/People-in-need.svg')}}" style="height:26px;"  alt="..."/> 
                        <span class="keyfigure">{{convertToUnit($KeyFigureCaseLoads["pin"],1)}}</span>
                    </div>
                    <p class="labelkeyfigure lh-sm mt-2">People in need</p>
                </div>
                <div class='col'>
                    <div>
                        <img src="{{asset('images/People-targeted.svg')}}" style="height:26px;"  alt="..."/> 
                        <span class="keyfigure">{{convertToUnit($KeyFigureCaseLoads["pt"],1)}}</span>
                    </div>
                    <p class="labelkeyfigure lh-sm mt-2">People targeted</p>
                </div>
                <div class='col'>
                    <div>
                        <img src="{{asset('images/Person-2.svg')}}" style="height:26px;"  alt="..."/> 
                        <span class="keyfigure">{{convertToUnit($KeyFigureCaseLoads["pr"],1)}}</span>
                    </div>
                    <p class="labelkeyfigure lh-sm mt-2">People reached</p>
                </div>
            </div>
        </div>

        <div class="col-3 keyFigure-card cards rounded me-1 ms-1"  id="keyFigure-disp" onclick="showData('disp')">
            <p>Displacements</p>
            <div class="row">
                <div class='col'>
                    <div>
                        <img src="{{asset('images/Internally-displaced.svg')}}" style="height:26px;"  alt="..."/> 
                        <span class="keyfigure">{{convertToUnit($KeyFigureDisplacements["idp"],1)}}</span>
                    </div>
                    <p class="labelkeyfigure lh-sm mt-2">Internally displaced persons</p>
                </div>
                <div class='col'>
                    <div>
                        <img src="{{asset('images/Refugee.svg')}}" style="height:26px;"  alt="..."/> 
                        <span class="keyfigure">{{convertToUnit($KeyFigureDisplacements["refugees"],1)}}</span>
                    </div>
                    <p class="labelkeyfigure lh-sm mt-2">Refugees</p>
                </div>
                <div class='col'>
                    <div>
                        <img src="{{asset('images/Population-return.svg')}}" style="height:26px;"  alt="..."/> 
                        <span class="keyfigure">{{convertToUnit($KeyFigureDisplacements["returnees"],1)}}</span>
                    </div>
                    <p class="labelkeyfigure lh-sm mt-2">Returnees</p>
                </div>
            </div>
        </div>

        <div class="col-1 keyFigure-card cards rounded me-1 ms-1"  id="keyFigure-nutrition" onclick="showData('nutrition')">
            <p>Nutrition</p>
            <div class="row">
                <div class='col'>
                    <div>
                        <img src="{{asset('images/Nutrition.svg')}}" style="height:26px;"  alt="..."/> 
                        <span class="keyfigure">{{convertToUnit($KeyFigurenutritions["sam"],1)}}</span>
                    </div>
                    <p class="labelkeyfigure lh-sm mt-2">Severe Accure Malnourished</p>
                </div>
            </div>
        </div>

        <div class="col-2 keyFigure-card cards rounded me-1 ms-1" id="keyFigure-foodSecurity"  onclick="showData('foodSecurity')">
            <p>Food security</p>
            <div class="row">
                <div class='col'>
                    <div>
                        <img src="{{asset('images/Food-Security.svg')}}" style="height:20px;"  alt="..."/> 
                        <span class="keyfigure">{{convertToUnit($KeyFigureCHCurrent["ch_phase35"],1)}}</span>
                    </div>
                    <p class="labelkeyfigure lh-sm mt-2">Current Food Insecure</p>
                </div>
                <div class='col'>
                    <div>
                        <img src="{{asset('images/Food-Security.svg')}}" style="height:20px;"  alt="..."/> 
                        <span class="keyfigure">{{convertToUnit($KeyFigureCHProjeted["ch_phase35"],1)}}</span>
                    </div>
                    <p class="labelkeyfigure lh-sm mt-2">Projected Food Insecure</p>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12 bloc-data" id="bloc-data-caseloads" style = "displayf:none;">
            <div class="row">
                <div class="col-8">
                    
                    <a href="#" class="btn-link" onclick="downloadMap('caseloads')"><em>download map</em></a>
                    <div class="map-caseloads" id="map-caseloads">
                    </div>
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 white-blocs rounded m-1">
                            <p>Trend by year</p>
                            <!--a href="#" class="btn-link" onclick="downloadTrend('caseloads')"><em>image</em></a-->
                            <div class="trend-caseloads" id="trend-caseloads">
                            </div>

                            <table class="table" id="trend-data-caseloads" style="display:none;">
                                <thead>
                                    <tr>
                                        <th scope="col">Date</th>
                                        <th scope="col">AdminName</th>
                                        <th scope="col">AdminName</th>
                                        <th scope="col">In need</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($TrendsCaseLoadsByAdmin as $caseload)
                                        <tr>
                                            <th >{{$caseload["date"]}}</th>
                                            <th >{{$caseload["adminName"]}}</th>
                                            <th >{{$adminLevel}}</th>
                                            <td>{{$caseload["pin"]}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 white-blocs rounded m-1">
                            <p>Key figures by country</p>
                            <a href="#" class="btn-link" onclick="downloadData('caseloads')"><em>excel</em></a>
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">Country</th>
                                    <th scope="col">In need</th>
                                    <th scope="col">Targeted</th>
                                    <th scope="col">Reached</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($KeyFigureCaseLoadsByAdmin as $caseload)
                                        <tr>
                                            <th scope="row">{{$caseload["adminName"]}}</th>
                                            <td>{{convertToUnit($caseload["pin"],1)}}</td>
                                            <td>{{convertToUnit($caseload["pt"],1)}}</td>
                                            <td>{{convertToUnit($caseload["pr"],1)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <table class="table" id="keyFigure-data-caseloads" style="display:none;">
                                <thead>
                                    <tr>
                                    <th scope="col">Country</th>
                                    <th scope="col">In need</th>
                                    <th scope="col">Targeted</th>
                                    <th scope="col">Reached</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($KeyFigureCaseLoadsByAdmin as $caseload)
                                        <tr>
                                            <th scope="row">{{$caseload["adminName"]}}</th>
                                            <td>{{$caseload["pin"]}}</td>
                                            <td>{{$caseload["pt"]}}</td>
                                            <td>{{$caseload["pr"]}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 bloc-data" id="bloc-data-disp" style = "display:none;">
            <div class="row">
                <div class="col-8">
                    <a href="#" class="btn-link" onclick="downloadMap('displacements')"><em>download map</em></a>
                    <div class="map-displacements" id="map-displacements">
                    </div>
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 white-blocs rounded m-1">
                            <p>Trend by year</p>
                            <!--a href="#" class="btn-link" onclick="downloadTrend('displacements')"><em>image</em></a-->
                            <div class="trend-displacements" id="trend-displacements">
                            </div>

                        </div>
                        <div class="col-12 white-blocs rounded m-1">
                            <p>Key figures by country</p>
                            <a href="#" class="btn-link" onclick="downloadData('displacements')"><em>Excel</em></a>
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">Country</th>
                                    <th scope="col">IDPs</th>
                                    <th scope="col">Refugees</th>
                                    <th scope="col">Returnees</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($KeyFigureDisplacementsByAdmin as $displacement)
                                        <tr>
                                            <th scope="row">{{$displacement["adminName"]}}</th>
                                            <td>{{convertToUnit($displacement["idp"],1)}}</td>
                                            <td>{{convertToUnit($displacement["refugees"],1)}}</td>
                                            <td>{{convertToUnit($displacement["returnees"],1)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <table class="table" id="keyFigure-data-displacements" style="display:none;">
                                <thead>
                                    <tr>
                                        <th scope="col">Country</th>
                                        <th scope="col">IDPs</th>
                                        <th scope="col">Refugees</th>
                                        <th scope="col">Returnees</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($KeyFigureDisplacementsByAdmin as $displacement)
                                        <tr>
                                            <th scope="row">{{$displacement["adminName"]}}</th>
                                            <td>{{$displacement["idp"]}}</td>
                                            <td>{{$displacement["refugees"]}}</td>
                                            <td>{{$displacement["returnees"]}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 bloc-data" id="bloc-data-nutrition" style = "display:none;">
            <div class="row">
                <div class="col-8">
                    <a href="#" class="btn-link" onclick="downloadMap('nutrition')"><em>download map</em></a>
                    <div class="map-nutrition" id="map-nutrition">
                    </div>
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 white-blocs rounded m-1">
                            <p>Trend by year</p>
                            <!--a href="#" class="btn-link" onclick="downloadTrend('nutrition')"><em>image</em></a-->
                            <div class="trend-nutrition" id="trend-nutrition">
                            </div>
                        </div>
                        <div class="col-12 white-blocs rounded m-1">
                            <p>Key figures by country</p>
                            <a href="#" class="btn-link" onclick="downloadData('nutrition')"><em>excel</em></a>
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">Country</th>
                                    <th scope="col">GAM</th>
                                    <th scope="col">MAM</th>
                                    <th scope="col">SAM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($KeyFigurenutritionsByAdmin as $nutrition)
                                        <tr>
                                            <th scope="row">{{$nutrition["adminName"]}}</th>
                                            <td>{{convertToUnit($nutrition["gam"],1)}}</td>
                                            <td>{{convertToUnit($nutrition["mam"],1)}}</td>
                                            <td>{{convertToUnit($nutrition["sam"],1)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <table class="table" id="keyFigure-data-nutrition" style="display:none;">
                                <thead>
                                    <tr>
                                    <th scope="col">Country</th>
                                    <th scope="col">GAM</th>
                                    <th scope="col">MAM</th>
                                    <th scope="col">SAM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($KeyFigurenutritionsByAdmin as $nutrition)
                                        <tr>
                                            <th scope="row">{{$nutrition["adminName"]}}</th>
                                            <td>{{$nutrition["gam"]}}</td>
                                            <td>{{$nutrition["mam"]}}</td>
                                            <td>{{$nutrition["sam"]}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 bloc-data" id="bloc-data-foodSecurity" style = "display:none;">
            <!-- CURRENT -->
            <div class="row">
                <h5>Current</h5>
                <div class="col">
                    <div class="map-ch" id="map-ch-current"></div>
                </div>
                <div class="col  white-blocs rounded m-1">
                    <p>Trend by year</p>
                    <!--a href="#" class="btn-link" onclick="downloadTrend('ch-current')"><em>image</em></a-->
                    <div class="trend-ch" id="trend-ch-current"></div>
                </div>
                <div class="col white-blocs rounded m-1">
                    <p>Key figures by country</p>
                    <a href="#" class="btn-link" onclick="downloadData('ch-current')"><em>excel</em></a>
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Country</th>
                            <th scope="col">Year</th>
                            <th scope="col">Month</th>
                            <th scope="col">Phase 1</th>
                            <th scope="col">Phase 2</th>
                            <th scope="col">Phase 3</th>
                            <th scope="col">Phase 3+</th>
                            <th scope="col">Phase 4</th>
                            <th scope="col">Phase 5</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($KeyFigureCHByAdminCurrent as $foodSec)
                                <tr>
                                    <th scope="row">{{$foodSec["adminName"]}}</th>
                                    <td>{{$foodSec["year"]}}</td>
                                    <td>{{$foodSec["month"]}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase1"],1)}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase2"],1)}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase3"],1)}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase35"],1)}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase4"],1)}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase5"],1)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="table" id="keyFigure-data-ch-current"  style="display:none;">
                        <thead>
                            <tr>
                            <th scope="col">Country</th>
                            <th scope="col">Year</th>
                            <th scope="col">Month</th>
                            <th scope="col">Phase_1 </th>
                            <th scope="col">Phase_2 </th>
                            <th scope="col">Phase_3 </th>
                            <th scope="col">Phase_3+</th>
                            <th scope="col">Phase_4 </th>
                            <th scope="col">Phase_5 </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($KeyFigureCHByAdminCurrent as $foodSec)
                                <tr>
                                    <th scope="row">{{$foodSec["adminName"]}}</th>
                                    <td>{{$foodSec["year"]}}</td>
                                    <td>{{$foodSec["month"]}}</td>
                                    <td>{{$foodSec["ch_phase1"]}}</td>
                                    <td>{{$foodSec["ch_phase2"]}}</td>
                                    <td>{{$foodSec["ch_phase3"]}}</td>
                                    <td>{{$foodSec["ch_phase35"]}}</td>
                                    <td>{{$foodSec["ch_phase4"]}}</td>
                                    <td>{{$foodSec["ch_phase5"]}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PROJECTED -->
            <div class="row">
                <h5>Projected</h5>
                <div class="col">
                    <div class="map-ch" id="map-ch-projected"></div>
                </div>
                <div class="col  white-blocs rounded m-1">
                    <p>Trend by year</p>
                    <!--a href="#" class="btn-link" onclick="downloadTrend('ch-projected')"><em>image</em></a-->
                    <div class="trend-ch" id="trend-ch-projected"></div>
                </div>
                <div class="col white-blocs rounded m-1">
                    <p>Key figures by country</p>
                    <a href="#" class="btn-link" onclick="downloadData('ch-projected')"><em>excel</em></a>
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Country</th>
                            <th scope="col">Year</th>
                            <th scope="col">Month</th>
                            <th scope="col">Phase 1</th>
                            <th scope="col">Phase 2</th>
                            <th scope="col">Phase 3</th>
                            <th scope="col">Phase 3+</th>
                            <th scope="col">Phase 4</th>
                            <th scope="col">Phase 5</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($KeyFigureCHByAdminProjeted as $foodSec)
                                <tr>
                                    <th scope="row">{{$foodSec["adminName"]}}</th>
                                    <td>{{$foodSec["year"]}}</td>
                                    <td>{{$foodSec["month"]}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase1"],1)}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase2"],1)}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase3"],1)}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase35"],1)}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase4"],1)}}</td>
                                    <td>{{convertToUnit($foodSec["ch_phase5"],1)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="table" id="keyFigure-data-ch-projected"  style="display:none;">
                        <thead>
                            <tr>
                            <th scope="col">Country</th>
                            <th scope="col">Year</th>
                            <th scope="col">Month</th>
                            <th scope="col">Phase_1 </th>
                            <th scope="col">Phase_2 </th>
                            <th scope="col">Phase_3 </th>
                            <th scope="col">Phase_3+</th>
                            <th scope="col">Phase_4 </th>
                            <th scope="col">Phase_5 </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($KeyFigureCHByAdminProjeted as $foodSec)
                                <tr>
                                    <th scope="row">{{$foodSec["adminName"]}}</th>
                                    <td>{{$foodSec["year"]}}</td>
                                    <td>{{$foodSec["month"]}}</td>
                                    <td>{{$foodSec["ch_phase1"]}}</td>
                                    <td>{{$foodSec["ch_phase2"]}}</td>
                                    <td>{{$foodSec["ch_phase3"]}}</td>
                                    <td>{{$foodSec["ch_phase35"]}}</td>
                                    <td>{{$foodSec["ch_phase4"]}}</td>
                                    <td>{{$foodSec["ch_phase5"]}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id='chart'>ygfhgf</div>

<script>
$(document).ready(function(){
    
    adminLevel = {!! json_encode($adminLevel) !!};
    zoneCode = {!! json_encode($zone->zone_code) !!};
    zoneName = {!! json_encode($zone->zone_name) !!};
    //trend data
    trendCaseloads_pin = {!! json_encode($trendCaseloads_PIN) !!};
    caseloadColumns = {!! json_encode($caseloadColumns) !!};
    KeyFigureCaseLoads = {!! json_encode($KeyFigureCaseLoads) !!};

    trendDisplacement_IDP = {!! json_encode($trendDisplacement_IDP) !!};
    displacementColumns = {!! json_encode($displacementColumns) !!};
    KeyFigureDisplacements = {!! json_encode($KeyFigureDisplacements) !!};

    trendNutrition_SAM = {!! json_encode($trendNutrition_SAM) !!};
    nutritionColumns = {!! json_encode($nutritionColumns) !!};

    trendCh_Current = {!! json_encode($trendCh_Current) !!};
    ch_CurrentColumns = {!! json_encode($ch_CurrentColumns) !!};

    trendCh_Projected = {!! json_encode($trendCh_Projected) !!};
    ch_ProjectedColumns = {!! json_encode($ch_ProjectedColumns) !!};

    //maps data
    mapCaseloads_PIN = {!! json_encode($mapCaseloads_PIN) !!};
    mapDisplacement_IDP = {!! json_encode($mapDisplacement_IDP) !!};
    mapNutrition_SAM = {!! json_encode($mapNutrition_SAM) !!};

    //trends call functions
    AddChart(trendCaseloads_pin,"trend-caseloads",zoneName+": People in need")
    AddChart(trendDisplacement_IDP,"trend-displacements",zoneName+": Internally displaced persons")
    AddChart(trendNutrition_SAM,"trend-nutrition",zoneName+": Severe Acuted Malnourished")
    AddChart(trendCh_Current,"trend-ch-current",zoneName+": Current food insecure")
    AddChart(trendCh_Projected,"trend-ch-projected",zoneName+": Projected food insecure")

    //console.log(trendNutrition_SAM);


    //map call functions
   // AddCaseloadPinMap(mapCaseloads_PIN)

    addTestMap("map-caseloads",zoneCode,adminLevel,mapCaseloads_PIN,"People in need")
    addTestMap("map-displacements",zoneCode,adminLevel,mapDisplacement_IDP,"Internally displaced persons")
    addTestMap("map-nutrition",zoneCode,adminLevel,mapNutrition_SAM,"Save Acute Malnourished")

  
    image1= 0;
    console.log(KeyFigureDisplacements);
});








function ExportPowerPoint(){
    $(".bloc-data").show();
    umg = "";
    html2canvas(document.querySelector("#trend-caseloads")).then(function(caseloads) {
        html2canvas(document.querySelector("#trend-displacements")).then(function(displacements) {
            html2canvas(document.querySelector("#trend-nutrition")).then(function(nutrition) {
                html2canvas(document.querySelector("#trend-ch-current")).then(function(chCurrent) {
                    html2canvas(document.querySelector("#trend-ch-projected")).then(function(chProjected) {
                        caseloadImage = caseloads.toDataURL()
                        displacementsImage = displacements.toDataURL()
                        nutritionImage = nutrition.toDataURL()
                        chCurrentImage = chCurrent.toDataURL()
                        chProjectedImage = chProjected.toDataURL()

                        var pptx = new PptxGenJS();

                        // STEP 2: Add a new Slide to the Presentation
                        var slide = pptx.addSlide();
                        var slide_caseLoad = pptx.addSlide();
                        var slide_disp = pptx.addSlide();
                        var slide_nutrition = pptx.addSlide();
                        var slide_foodSec = pptx.addSlide();

                        slide.addText('Presentation for', { x:3.8, y:1.44, fontSize:18, color:'418fde' });
                        slide.addText(zoneName, { x:3.8, y:1.86, fontSize:18, color:'418fde' });

                        //CASELOADS
                        slide_caseLoad.addText('Caseloads', { x:0.47, y:0.42, fontSize:18, color:'418fde' });
                        slide_caseLoad.addText('People in need', { x:0.47,y:1.25, fontSize:11, color:'999999', w: 1.30});
                        slide_caseLoad.addText(convertToUnit(KeyFigureCaseLoads.pin,1), { x:0.95,y:0.98, fontSize:14, color:'418fde', w: 1.30});
                        slide_caseLoad.addText('People targeted', { x:2.12, y:1.25, fontSize:11, color:'999999', w: 1.30 });
                        slide_caseLoad.addText(convertToUnit(KeyFigureCaseLoads.pt,1), { x:2.52, y:0.98, fontSize:14, color:'418fde', w: 1.30 });
                        slide_caseLoad.addText('People reached', { x:3.76,y:1.25, fontSize:11, color:'999999', w: 1.30 });
                        slide_caseLoad.addText(convertToUnit(KeyFigureCaseLoads.pr,1), { x:4.07,y:0.98, fontSize:14, color:'418fde', w: 1.30 });
                        slide_caseLoad.addImage({ path: "/images/People-in-need.svg", x: 0.58,y: 0.86,  w: 0.37, h: 0.26 });
                        slide_caseLoad.addImage({ path: "/images/People-targeted.svg", y: 0.86,x: 2.26,  w: 0.26, h: 0.26 });
                        slide_caseLoad.addImage({ path: "/images/Person-2.svg", y: 0.86,x: 3.93,  w: 0.14, h: 0.26 });
                        slide_caseLoad.addImage({ data: caseloadImage,x: 5.17,y: 1.65,  w: 4.69, h: 3.00 });


                        //DISPLACEMENTS
                        slide_disp.addText('Displacements', { x:0.47, y:0.42, fontSize:18, color:'418fde' });
                        slide_disp.addText('IDPs', { x:0.47,y:1.25, fontSize:11, color:'999999', w: 1.30});
                        slide_disp.addText(convertToUnit(KeyFigureDisplacements.idp,1), { x:0.95,y:0.98, fontSize:14, color:'418fde', w: 1.30});
                        slide_disp.addText('Refugees', { x:2.12, y:1.25, fontSize:11, color:'999999', w: 1.30 });
                        slide_disp.addText(convertToUnit(KeyFigureDisplacements.refugees,1), { x:2.52, y:0.98, fontSize:14, color:'418fde', w: 1.30 });
                        slide_disp.addText('Returnees', { x:3.76,y:1.25, fontSize:11, color:'999999', w: 1.30 });
                        slide_disp.addText(convertToUnit(KeyFigureDisplacements.returnees,1), { x:4.07,y:0.98, fontSize:14, color:'418fde', w: 1.30 });
                        slide_disp.addImage({ path: "/images/Internally-displaced.svg", x: 0.58,y: 0.86,  w: 0.37, h: 0.26 });
                        slide_disp.addImage({ path: "/images/Refugee.svg", y: 0.86,x: 2.26,  w: 0.26, h: 0.26 });
                        slide_disp.addImage({ path: "/images/Population-return.svg", y: 0.86,x: 3.93,  w: 0.14, h: 0.26 });
                        slide_disp.addImage({ data: displacementsImage,x: 5.17,y: 1.65,  w: 4.69, h: 3.00 });


              
                        slide.addImage({ data: nutritionImage });
                        slide.addImage({ data: chCurrentImage });
                        slide.addImage({ data: chProjectedImage });

                        // STEP 4: Send the PPTX Presentation to the user, using your choice of file name
                        pptx.writeFile('PptxGenJs-Basic-Slide-Demo');
                        $(".bloc-data").hide();
                        showData("caseloads");

                    });
                });
            });
        });
    });

    




    
        
       


       //saveAs(canvas.toDataURL(), 'file-name.png');
  


   
}

function showData(bloc) {
    $(".bloc-data").hide();
    blocName = "#bloc-data-"+bloc;
    cardId = "#keyFigure-"+bloc;
    
    $(".keyFigure-card").removeClass("cards-selected");
    $(".keyFigure-card").removeClass("cards");
    $(".keyFigure-card").addClass("cards");
    $(cardId).removeClass("cards");
    $(cardId).addClass("cards-selected");
    $(blocName).show();
}

function downloadMap(categ){
    mapName = "#map-"+categ
    html2canvas(document.querySelector(mapName)).then(function(canvas) {
        saveAs(canvas.toDataURL(), 'file-name.png');
    });
}

function downloadTrend(categ){
    mapName = "#trend-"+categ
    html2canvas(document.querySelector(mapName)).then(function(canvas) {
        saveAs(canvas.toDataURL(), 'file-name.png');
    });
}

function saveAs(uri, filename) {

    var link = document.createElement('a');

    if (typeof link.download === 'string') {

        link.href = uri;
        link.download = filename;

        //Firefox requires the link to be in the body
        document.body.appendChild(link);

        //simulate click
        link.click();

        //remove the link when done
        document.body.removeChild(link);

    } else {

        window.open(uri);

    }
}

function convertToUnit(val,decimal){
        result = "";
        if(val<1000){
            result = val;
        }else{
            if(val<1000000){
                calc = val/1000
                result = calc.toFixed(decimal)+"K";
            }else{
                if(val<1000000000){
                    calc = val/1000000
                    result = calc.toFixed(decimal)+"M";
                }else{
                    calc = val/1000000000
                    result = calc.toFixed(decimal)+"B";
                }
            }
        }
        return result;
    }

function AddChart(series,element,title){
    array_color = d3.schemeBlues[4]

    var options = {
        zoom: {
            enabled: false,
        },
        title: {
            text: title,
            align: 'left',
            margin: 10,
            offsetX: 0,
            offsetY: 0,
            floating: false,
            style: {
            fontSize:  '14px',
            fontWeight:  'bold',
            fontFamily:  undefined,
            color:  '#263238'
            },
        },
          series: series,
          chart: {
          type: 'area',
          height: 350,
          stacked: true,
          events: {
            selection: function (chart, e) {
              console.log(new Date(e.xaxis.min))
            }
          },
        },
        colors: array_color,
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        fill: {
          type: 'solid',
          opacity: 1,
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left'
        },
        xaxis: {
          type: 'category'
        },
        yaxis: {
            show: true,
            labels: {
                formatter: (value) => { return convertToUnit(value,0) },
            },
        },
    };

    blocId = "#"+element;
    var chart = new ApexCharts(document.querySelector(blocId), options);
    chart.render();
}

function addTestMap(bloc,layerName,adminLevel,mapCaseloads_PIN ,title) {


    d3.json("/maps/"+layerName+"_"+adminLevel+".json").then(function(us){

        console.log(us)
    states = new Map(us.objects.admin.geometries.map(d => [d.properties.OBJECTID, d.properties]))

    data = Object.assign(new Map(mapCaseloads_PIN), {title: title})
    color = d3.scaleQuantize([d3.min(data, d => d[1]), d3.max(data, d => d[1])], d3.schemeBlues[9])

    path = d3.geoPath()

    format = d => `${d}`

    svg = d3.select('#'+bloc).append("svg")
    .attr("viewBox", [0, 0, 975, 610]);

    svg.append("g")
        .attr("transform", "translate(610,20)")
        .append(() => legend({color, title: data.title, width: 260,tickFormat: d3.format(".0s")}));


    svg.append("g")
        .selectAll("path")
        .data(topojson.feature(us, us.objects.admin).features)
        .join("path")
        .attr("fill", d => color(data.get(d.properties.adminName)))
        .attr("d", path)
        .append("title")
        .text(d => `${d.properties.adminName}, ${states.get(d.properties.OBJECTID).adminName}
    ${format(data.get(d.properties.adminName))}`);

    svg.append("path")
        .datum(topojson.mesh(us, us.objects.admin, (a, b) => a !== b))
        .attr("fill", "none")
        .attr("stroke", "white")
        .attr("stroke-linejoin", "round")
        .attr("d", path);
    })
}


function AddChartOld(data,Columns,element,title){

    //caseloads
    data = Object.assign(data, {y: title})
    series = d3.stack().keys(Columns)(data)

    height = 300
    width = 500
    //margin = ({top: 20, right: 30, bottom: 30, left: 50})
    margin = ({top: 20, right: 30, bottom: 30, left: 60})

    area = d3.area()
    .x(d => x(d.data.year))
    .y0(d => y(d[0]))
    .y1(d => y(d[1]))

    color = d3.scaleOrdinal(d3.schemeBlues[6])




    x = d3.scaleTime().domain(d3.extent(data, d => d.year)).range([margin.left, width - margin.right])
    y = d3.scaleLinear().domain([0, d3.max(series, d => d3.max(d, d => d[1]))]).nice().range([height - margin.bottom, margin.top])


    xAxis = g => g
    .attr("transform", `translate(0,${height - margin.bottom})`)
    .call(d3.axisBottom(x).ticks(width / 80).tickSizeOuter(0))

    yAxis = g => g
    .attr("transform", `translate(${margin.left},0)`)
    .call(d3.axisLeft(y))
    .call(g => g.select(".domain").remove())
    .call(g => g.select(".tick:last-of-type text").clone()
        .attr("x", 3)
        .attr("text-anchor", "start")
        .attr("font-weight", "bold")
        .text(data.y))



    const svg = d3.select("#"+element).append("svg")
        .attr("viewBox", [0, 0, width, height]);

    svg.append("g")
        .selectAll("path")
        .data(series)
        .join("path")
        .attr("fill", ({key}) => color(key))
        .attr("d", area)
        .append("title")
        .text(({key}) => key);

    svg.append("g").call(xAxis);
    svg.append("g").call(yAxis);
}


function AddCaseloadPinMapold2(KeyFigureDisplacementsByAdmin){

    //caseloads
    data = Object.assign(KeyFigureDisplacementsByAdmin, {y: "People in need"})
    //series = d3.stack().keys(caseloadColumns)(data)
    //Width and height
    var w = 500;
    var h = 500;

    var margin = {
        top: 60,
        bottom: 40,
        left: 70,
        right: 40
    };

    var width = w - margin.left - margin.right;
    var height = h - margin.top - margin.bottom;

      
    // define map projection
    var projection = d3.geoAlbersUsa()
        .translate([w/2, h/2])
        .scale([500]);

    //Define default path generator
    var path = d3.geoPath()
        .projection(projection);

    var svg = d3.select("#map-caseloads")
        .append("svg")
        .attr("id", "chart")
        .attr("width", w)
        .attr("height", h)
        .append("g")
        .attr("tranform", "translate(0" + margin.left + "," + margin.top + ")");

        var color = d3.scaleQuantile()
          .range(["rgb(237, 248, 233)", "rgb(186, 228, 179)", "rgb(116,196,118)", "rgb(49,163,84)", "rgb(0,109,44)"]);


          
        color.domain([ d3.min(data, function(d){ return d.pin; }),
          d3.max(data, function(d){ return d.pin; })
        ]);


   // $.getJSON("/maps/lcb_admbnda_adm1_ocha_database.json", function(json){
    d3.json("/maps/lcb_admbnda_adm1_ocha_database.json").then(function(json){ });
    d3.json("/maps/lcb_admbnda_adm1_ocha_database.json").then(function(json){    
        //Merge the agriculture and GeoJSON data
        //Loop through once for each agriculture data value

        mapData.features.forEach(function(feature) {
            json.geometry = turf.rewind(feature.geometry, {reverse:true});
        })

        for(var i = 0; i < data.length; i++){
          // grab state name
          var dataState = data[i].adminName;

          //grab data value, and convert from string to float
          var dataValue = parseFloat(data[i].pin);

          //find the corresponding state inside the GeoJSON
          for(var n = 0; n < json.features.length; n++){

            // properties name gets the states name
            var jsonState = json.features[n].properties.adminName;
            // if statment to merge by name of state
            if(dataState == jsonState){
              //Copy the data value into the JSON
              // basically creating a new value column in JSON data
              json.features[n].properties.value = dataValue;

              //stop looking through the JSON
              break;
            }
          }
        }


        //console.log(json)
        
        svg.selectAll("path")
          .data(json.features)
          .enter()
          .append("path")
          .attr("d", path)
          .style("fill", function(d){
            //get the data value
            var value = d.properties.value;
            
            if(value){
              //If value exists
              //console.log(color(value))
              return color(value);
            } else {
              // If value is undefined
              //we do this because alaska and hawaii are not in dataset we are using but still in projections
              return "#ccc"
            }

          });

        });




    


}

function AddCaseloadPinMap(data) {

    d3.json("/maps/lcb_admbnda_adm1_ocha_database.json").then(function(mapData){ 

    //reverse coodinates
    mapData.features.forEach(function(feature) {
        feature.geometry = turf.rewind(feature.geometry, {reverse:true});
    })

    for(var i = 0; i < data.length; i++){
        var dataState = data[i].adminName;
        var dataValue = parseFloat(data[i].value);

        //find the corresponding state inside the GeoJSON
        for(var n = 0; n < mapData.features.length; n++){

            // properties name gets the states name
            var jsonState = mapData.features[n].properties.adminName;
            // if statment to merge by name of state
            if(dataState == jsonState){
                //Copy the data value into the JSON
                // basically creating a new value column in JSON data
                mapData.features[n].properties.value = dataValue;
                break;
            }
        }
    }


    const countriesData = data;
    const mapContainer = d3.select('#map-caseloads');

    const mapRatio = 0.4;
    // The plus turns it into a number
    const width = 400;
    const height = 600;

    // Map and projection
    const projection = d3.geoMercator()
        .scale(200)
        .translate([width / 2, height / 2])
        .center([0, 0]);
  
    const pathBuilder = d3.geoPath(projection);

    var color = d3.scaleQuantile()
        .range(["rgb(212, 229, 247)", "rgb(130, 181, 233)", "rgb(65, 143, 222)", "rgb(31, 105, 179)", "rgb(20, 67, 114)"]);
   
    color.domain([ d3.min(data, function(d){ return d.value; }),
        d3.max(data, function(d){ return d.value; })
    ]);

  // The Tooltip
  const Tooltip = d3.select('body')
    .append('div')
    .attr('class', 'map-tooltip')
    .style('visibility', 'hidden')
    .style('background-color', 'white')
    .style('border', 'solid')
    .style('border-width', '1px')
    .style('border-radius', '1px')
    .style('padding', '5px')
    .style('position', 'absolute')
    .on('mouseover', (event) => {
      // A bug where if the user's cursor gets on top of the Tooltip, it flashes infinitely until the user's cursor moves
      // Very distracting and this gets rid of it completely. Besides, the cursor should never be over the Tooltip anyway
      Tooltip.style('visibility', 'hidden');
    });

  const zoom = d3.zoom()
    .on('zoom', (event) => {
      map.attr('transform', event.transform);
    })
    .scaleExtent([1, 40]);

    // The Map
    const map = mapContainer
        .append('svg')
        .attr('padding', 'none')
        .attr('height', height)
        .attr('width', width)
        .attr('border', '1px solid black')
        .attr('margin-left', '16px')
        .attr('preserveAspectRatio', 'xMinYMin meet')
        // This is for when you zoom on the background, it will zoom
        .call(zoom)
        // This is going to be the country group
        .append('g');

    map
        .selectAll('path')
        .data(mapData.features)
        .enter()
        // This will be the country appended
        .append('path')
        // Used for clearing out styling later
        .classed('country', true)
        // Used for selecting specific countries for styling
        .attr('id', (feature) => {
            return 'country' + feature.properties.OBJECTID;
        })
        // Simple stylings
        .attr('opacity', '.7')
        .attr('stroke', 'black')
        .attr('stroke-width', '.1px')
        .attr('d', (feature) => {
        // Using the projection, create the polygon for the country
        return pathBuilder(feature);
        })
        .attr('fill', (feature) => {
            return color(feature.properties.value);
        })
        // Events are given the event object and the feature object (AKA datum AKA d as it is usually shown in documentation)
        .on('mouseover', (event, feature) => {
        // This adds the styling to show the user they are hovering over the country
        d3.select('#country' + feature.properties.value)
            .transition()
            .duration(200)
            .attr('opacity', '1')
            .attr('stroke-width', '1px');
        // Show the Tooltip
        Tooltip.style('visibility', 'visible');
        })
    .on('mouseleave', (event, feature) => {
      // This clears out the remaining styles on all other countries not currently being hovered
      d3.selectAll('.country')
        .transition()
        .duration(200)
        .attr('opacity', '0.7')
        .attr('stroke-width', '.1px');
      // Hide the tooltip
      Tooltip.style('visibility', 'hidden');
    })
    .on('mousemove', (event, feature) => {
        Tooltip
          .html(feature.properties.adminName + '<br>' + 'Count: ' + feature.properties.value)
          .style('left', (event.x + 10) + 'px')
          .style('top', (event.y + 10) + 'px');
    
    });






    });
  
}

 //test

 function legend({
  color,
  title,
  tickSize = 6,
  width = 320, 
  height = 44 + tickSize,
  marginTop = 18,
  marginRight = 0,
  marginBottom = 16 + tickSize,
  marginLeft = 0,
  ticks = width / 64,
  tickFormat,
  tickValues
} = {}) {

  const svg = d3.create("svg")
      .attr("width", width)
      .attr("height", height)
      .attr("viewBox", [0, 0, width, height])
      .style("overflow", "visible")
      .style("display", "block");

  let tickAdjust = g => g.selectAll(".tick line").attr("y1", marginTop + marginBottom - height);
  let x;

  // Continuous
  if (color.interpolate) {
    const n = Math.min(color.domain().length, color.range().length);

    x = color.copy().rangeRound(d3.quantize(d3.interpolate(marginLeft, width - marginRight), n));

    svg.append("image")
        .attr("x", marginLeft)
        .attr("y", marginTop)
        .attr("width", width - marginLeft - marginRight)
        .attr("height", height - marginTop - marginBottom)
        .attr("preserveAspectRatio", "none")
        .attr("xlink:href", ramp(color.copy().domain(d3.quantize(d3.interpolate(0, 1), n))).toDataURL());
  }

  // Sequential
  else if (color.interpolator) {
    x = Object.assign(color.copy()
        .interpolator(d3.interpolateRound(marginLeft, width - marginRight)),
        {range() { return [marginLeft, width - marginRight]; }});

    svg.append("image")
        .attr("x", marginLeft)
        .attr("y", marginTop)
        .attr("width", width - marginLeft - marginRight)
        .attr("height", height - marginTop - marginBottom)
        .attr("preserveAspectRatio", "none")
        .attr("xlink:href", ramp(color.interpolator()).toDataURL());

    // scaleSequentialQuantile doesn’t implement ticks or tickFormat.
    if (!x.ticks) {
      if (tickValues === undefined) {
        const n = Math.round(ticks + 1);
        tickValues = d3.range(n).map(i => d3.quantile(color.domain(), i / (n - 1)));
      }
      if (typeof tickFormat !== "function") {
        tickFormat = d3.format(tickFormat === undefined ? ",f" : tickFormat);
      }
    }
  }

  // Threshold
  else if (color.invertExtent) {
    const thresholds
        = color.thresholds ? color.thresholds() // scaleQuantize
        : color.quantiles ? color.quantiles() // scaleQuantile
        : color.domain(); // scaleThreshold

    const thresholdFormat
        = tickFormat === undefined ? d => d
        : typeof tickFormat === "string" ? d3.format(tickFormat)
        : tickFormat;

    x = d3.scaleLinear()
        .domain([-1, color.range().length - 1])
        .rangeRound([marginLeft, width - marginRight]);

    svg.append("g")
      .selectAll("rect")
      .data(color.range())
      .join("rect")
        .attr("x", (d, i) => x(i - 1))
        .attr("y", marginTop)
        .attr("width", (d, i) => x(i) - x(i - 1))
        .attr("height", height - marginTop - marginBottom)
        .attr("fill", d => d);

    tickValues = d3.range(thresholds.length);
    tickFormat = i => thresholdFormat(thresholds[i], i);
  }

  // Ordinal
  else {
    x = d3.scaleBand()
        .domain(color.domain())
        .rangeRound([marginLeft, width - marginRight]);

    svg.append("g")
      .selectAll("rect")
      .data(color.domain())
      .join("rect")
        .attr("x", x)
        .attr("y", marginTop)
        .attr("width", Math.max(0, x.bandwidth() - 1))
        .attr("height", height - marginTop - marginBottom)
        .attr("fill", color);

    tickAdjust = () => {};
  }

  svg.append("g")
      .attr("transform", `translate(0,${height - marginBottom})`)
      .call(d3.axisBottom(x)
        .ticks(ticks, typeof tickFormat === "string" ? tickFormat : undefined)
        .tickFormat(typeof tickFormat === "function" ? tickFormat : undefined)
        .tickSize(tickSize)
        .tickValues(tickValues))
      .call(tickAdjust)
      .call(g => g.select(".domain").remove())
      .call(g => g.append("text")
        .attr("x", marginLeft)
        .attr("y", marginTop + marginBottom - height - 6)
        .attr("fill", "currentColor")
        .attr("text-anchor", "start")
        .attr("font-weight", "bold")
        .attr("class", "title")
        .text(title));

  return svg.node();
}

function swatches({
  color,
  columns = null,
  format = x => x,
  swatchSize = 15,
  swatchWidth = swatchSize,
  swatchHeight = swatchSize,
  marginLeft = 0
}) {
  const id = DOM.uid().id;

  if (columns !== null) return html`<div style="display: flex; align-items: center; margin-left: ${+marginLeft}px; min-height: 33px; font: 10px sans-serif;">
  <style>

.${id}-item {
  break-inside: avoid;
  display: flex;
  align-items: center;
  padding-bottom: 1px;
}

.${id}-label {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: calc(100% - ${+swatchWidth}px - 0.5em);
}

.${id}-swatch {
  width: ${+swatchWidth}px;
  height: ${+swatchHeight}px;
  margin: 0 0.5em 0 0;
}

  </style>
  <div style="width: 100%; columns: ${columns};">${color.domain().map(value => {
    const label = format(value);
    return html`<div class="${id}-item">
      <div class="${id}-swatch" style="background:${color(value)};"></div>
      <div class="${id}-label" title="${label.replace(/["&]/g, entity)}">${document.createTextNode(label)}</div>
    </div>`;
  })}
  </div>
</div>`;

  return html`<div style="display: flex; align-items: center; min-height: 33px; margin-left: ${+marginLeft}px; font: 10px sans-serif;">
  <style>

.${id} {
  display: inline-flex;
  align-items: center;
  margin-right: 1em;
}

.${id}::before {
  content: "";
  width: ${+swatchWidth}px;
  height: ${+swatchHeight}px;
  margin-right: 0.5em;
  background: var(--color);
}

  </style>
  <div>${color.domain().map(value => html`<span class="${id}" style="--color: ${color(value)}">${document.createTextNode(format(value))}</span>`)}</div>`;
}

function entity(character) {
  return `&#${character.charCodeAt(0).toString()};`;
}

function ramp(color, n = 256) {
  const canvas = DOM.canvas(n, 1);
  const context = canvas.getContext("2d");
  for (let i = 0; i < n; ++i) {
    context.fillStyle = color(i / (n - 1));
    context.fillRect(i, 0, 1, 1);
  }
  return canvas;
}

function downloadData(typeData){
    tableName = "keyFigure-data-"+typeData
    var tableToExport = TableExport(document.getElementById(tableName));

    var exportData = tableToExport.getExportData(); 
    console.log(exportData);
    var xlsxData = exportData[tableName].xlsx; 
    console.log(xlsxData);
    tableToExport.export2file(xlsxData.data, xlsxData.mimeType, typeData, xlsxData.fileExtension, xlsxData.merges, xlsxData.RTL, typeData)

}

</script>
    
@endsection