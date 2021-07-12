<?php

namespace App\Imports;

use App\cadre_harmonise;
use Maatwebsite\Excel\Concerns\ToModel;
use Webpatser\Uuid\Uuid;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class cadre_harmonisesImport implements ToModel,WithBatchInserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model (array $row)
    {
        if ($row[0]=="Country") {
            return null;
        }
        
        return new cadre_harmonise([
            'ch_id' => (string)Uuid::generate(),
            'ch_country' => $row[0],
            'ch_adm0_pcode_iso3' => $row[1],
            'ch_admin1_name' => $row[2],
            'ch_admin1_pcode_iso3' => $row[3],
            'ch_admin2_name' => $row[4],
            'ch_admin2_pcode_iso3' => $row[5],
            'ch_ipc_level' => $row[6],
            'ch_phase1' => is_numeric($row[7]) ? floatval($row[7]) : 0 ,
            'ch_phase2' => is_numeric($row[8]) ? floatval($row[8]) : 0 ,
            'ch_phase3' => is_numeric($row[9]) ? floatval($row[9]) : 0 ,
            'ch_phase4' => is_numeric($row[10]) ? floatval($row[10]) : 0 ,
            'ch_phase5' => is_numeric($row[11]) ? floatval($row[12]) : 0 ,
            'ch_phase35' => is_numeric($row[12]) ? floatval($row[12]) : 0 ,
            'ch_exercise_month' => $row[13],
            'ch_exercise_year' => $row[14],
            'ch_situation' => $row[15],
            'ch_date' => gmdate("Y/m/d", ($row[16] - 25569) * 86400),
            'ch_source' => isset($row[17]) ? $row[17] : null,
        ]);
        
        /*
        else{
            if($row[0]!="Country"){
                $UNIX_DATE = ;
                $date = gmdate("Y/m/d", $UNIX_DATE);

                $cadre_harmonise = new cadre_harmonise;
                $cadre_harmonise->ch_id = (string)Uuid::generate();
                $cadre_harmonise->ch_country=$row[0];
                $cadre_harmonise->;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=;
                $cadre_harmonise->=
                $cadre_harmonise->=

                $cadre_harmonise->save();
            }
        }*/
    }

    public function batchSize(): int
    {
        return 100;
    }
}
