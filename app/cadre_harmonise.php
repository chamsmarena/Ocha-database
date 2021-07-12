<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cadre_harmonise extends Model
{
    protected $primaryKey = 'ch_id';
    protected $fillable = ['ch_id','ch_country', 'ch_adm0_pcode_iso3', 'ch_admin1_name', 'ch_admin1_pcode_iso3', 'ch_admin2_name', 'ch_admin2_pcode_iso3', 'ch_ipc_level', 'ch_phase1', 'ch_phase2', 'ch_phase3', 'ch_phase4', 'ch_phase5', 'ch_phase35', 'ch_exercise_month', 'ch_exercise_year', 'ch_situation', 'ch_date', 'ch_source'];
    public $incrementing = false;
}
