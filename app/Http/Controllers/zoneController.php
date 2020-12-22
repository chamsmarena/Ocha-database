<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel;
use App\Imports\Import;
use Illuminate\Support\Facades\DB;
use App\zone as zone;
use App\liste_localite as liste_localite;
use App\keyfigure_caseload as keyfigure_caseload;



class zoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function liste()
    {
        DB::connection('pgsql');
        //$zones = zone::all();
        $zones = DB::table('zones')->orderBy('zone_name', 'asc')->get();
        return view('zone.liste',['datas'=>$zones]);
    }
    public function manageliste()
    {
        DB::connection('pgsql');
        //$zones = zone::all();
        $zones = DB::table('zones')->orderBy('zone_name', 'asc')->get();
        return view('zone.manageliste',['datas'=>$zones]);
    }

    public function show_view_consulter($id)
    {
        DB::connection('pgsql');
        $zone = zone::where('zone_id', $id)->first();
        $liste_localites = liste_localite::where('zone_id', $id)->orderBy('local_name', 'asc')->get();
        $keyfigure_caseloads = keyfigure_caseload::where('zone_id', $id)->get();
        $keyfigure_displacements = DB::table('keyfigure_displacements')->where('zone_id', '=', $id)->where('dis_crise', '=', $zone->zone_code)->get();
        $keyfigure_cadre_harmonises_projected = DB::table('keyfigure_cadre_harmonises')->where('zone_id', '=', $id)->where('ch_situation', '=', 'Projected')->get();
        $keyfigure_cadre_harmonises_current = DB::table('keyfigure_cadre_harmonises')->where('zone_id', '=', $id)->where('ch_situation', '=', 'Current')->get();
        $keyfigure_nutritions = DB::table('keyfigure_nutritions')->where('zone_id', '=', $id)->get();
       // $trend_crisis_caseload_by_years = DB::table('trend_crisis_caseload_by_years')->where('zone_id', '=', $id)->get();
        
        return view('zone.consulter',[
            'datas'=>$zone,
            'liste_localites'=>$liste_localites,
            'keyfigure_caseloads'=>$keyfigure_caseloads,
            'keyfigure_displacements'=>$keyfigure_displacements,
            'keyfigure_cadre_harmonises_projected'=>$keyfigure_cadre_harmonises_projected,
            'keyfigure_cadre_harmonises_current'=>$keyfigure_cadre_harmonises_current,
            'keyfigure_nutritions'=>$keyfigure_nutritions,
            //'trend_crisis_caseload_by_years'=>$trend_crisis_caseload_by_years,
            ]);
    }
    public function show_view_analyser($id)
    {
        DB::connection('pgsql');
        $zone = zone::where('zone_id', $id)->first();
        $keyfigure_caseloads = keyfigure_caseload::where('zone_id', $id)->get();
        $keyfigure_displacements = DB::table('keyfigure_displacements')->where('zone_id', '=', $id)->where('dis_crise', '=', $zone->zone_code)->get();
        $liste_localites = liste_localite::where('zone_id', $id)->orderBy('local_name', 'asc')->get();
        $keyfigure_cadre_harmonises_current = DB::table('keyfigure_cadre_harmonises')->where('zone_id', '=', $id)->where('ch_situation', '=', 'Current')->get();
        $keyfigure_cadre_harmonises_projected = DB::table('keyfigure_cadre_harmonises')->where('zone_id', '=', $id)->where('ch_situation', '=', 'Projected')->get();
        $keyfigure_nutritions = DB::table('keyfigure_nutritions')->where('zone_id', '=', $id)->get();

        return view('zone.analyser',[
            'datas'=>$zone,
            'liste_localites'=>$liste_localites,
            'keyfigure_caseloads'=>$keyfigure_caseloads,
            'keyfigure_displacements'=>$keyfigure_displacements,
            'keyfigure_cadre_harmonises_current'=>$keyfigure_cadre_harmonises_current,
            'keyfigure_cadre_harmonises_projected'=>$keyfigure_cadre_harmonises_projected,
            'keyfigure_nutritions'=>$keyfigure_nutritions,
            ]);
    }
    public function show_view_analyser_avance()
    {
        DB::connection('pgsql');
        $caseloads_by_regions = DB::table('caseloads_by_regions')->get();
        $displacements_by_regions = DB::table('displacements_by_regions')->get();
        $cadre_harmonises_by_regions = DB::table('cadre_harmonises_by_regions')->get();
        $nutrition_by_regions = DB::table('nutrition_by_regions')->get();

        return view('zone.analyseravance',[
            'caseloads_by_regions'=>$caseloads_by_regions,
            'displacements_by_regions'=>$displacements_by_regions,
            'cadre_harmonises_by_regions'=>$cadre_harmonises_by_regions,
            'nutrition_by_regions'=>$nutrition_by_regions,
            ]);
    }

    public function show_view_charts($id)
    {
        DB::connection('pgsql');
        $zone = zone::where('zone_id', $id)->first();
        $trends_by_years = DB::table('trends_by_years')
        ->select(DB::raw('zone_code,zone_name,zone_id,t_category,t_year,SUM(t_value) as t_value'))
        ->where('zone_id', '=', $id)->orderBy('t_year', 'asc')->groupBy("zone_code","zone_name","zone_id","t_category","t_year")->get();

        return view('zone.charts',[
            'datas'=>$zone,
            'trends_by_years'=>$trends_by_years,
            ]);
    }

    public function show_view_manage_consulter($id)
    {
        DB::connection('pgsql');
        $zone = zone::where('zone_id', $id)->first();
        $liste_localites = liste_localite::where('zone_id', $id)->get();
        
        return view('zone.manageconsulter',[
            'datas'=>$zone,
            'liste_localites'=>$liste_localites
            ]);
    }

    public function show_view_ajouter()
    {
        DB::connection('pgsql');
        return view('zone.ajouter');
    }

    public function show_view_modifier($id)
    {
        DB::connection('pgsql');
        $zone = zone::where('zone_id', $id)->first();
        return view('zone.modifier',['datas'=>$zone]);
    }

    public function show_view_delete($id)
    {
        DB::connection('pgsql');
        $zone = zone::where('zone_id', $id)->first();
        return view('zone.deletezone',['datas'=>$zone]);
    }

    public function update()
    {
        DB::connection('pgsql');
        $zone = zone::where('zone_id', $_POST['zone_id'])->first();
        $zone->zone_code = $_POST['zone_code'];
        $zone->zone_name = $_POST['zone_name'];
        $zone->save();
        $zoneUpdated = zone::where('zone_id', $_POST['zone_id'])->first();
        $liste_localites = liste_localite::where('zone_id', $_POST['zone_id'])->get();
        return view('zone.consulter',['datas'=>$zoneUpdated,'liste_localites'=>$liste_localites]);
    }
    
    public function delete()
    {
        if($_POST['delete']=="DELETE"){
            DB::connection('pgsql');
            $zone = zone::where('zone_id', $_POST['zone_id'])->first();
            $zone->forceDelete();
            return redirect('zones');
        }else{
            return back()->with('msg', 'Type DELETE in all caps !');
        }
    }

    public function massdelete()
    {
        if($_POST['delete']=="DELETE multiples"){
            $keys = array_keys($_POST);
            DB::connection('pgsql');
            foreach($keys as $key){
                $key = str_replace("_","",$key);
                if(substr($key,0,8)=="checkbox"){
                    $id = str_replace(' ','',substr($key,8));
                    $zone = zone::where('zone_id', $id)->first();
                    $zone->forceDelete();
                }
            }
            return redirect('zones');
        }else{
            return back()->with('msg', 'Please type exactly <strong>DELETE multiples</strong> !');
        }
    }

    public function add()
    {
        DB::connection('pgsql');
        $zone = new zone;
        $zone->zone_id = self::generateID();
        $zone->zone_code = $_POST['zone_code'];
        $zone->zone_name = $_POST['zone_name'];
        $zone->save();
        return redirect('zone/'.$zone->zone_id);
    }

    private function generateID(){
        $id=date('YmdHis').rand (0, 9999);
        return $id;
    }
}
