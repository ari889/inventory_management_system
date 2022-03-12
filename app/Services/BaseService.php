<?php


namespace App\Services;

use Illuminate\Http\Request;

class BaseService{


    protected function datatable_draw($draw, $recordsToal, $recordsFiltered, $data){
        return array(
            "draw" => $draw,
            "recordsTotal" => $recordsToal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
    }

}
