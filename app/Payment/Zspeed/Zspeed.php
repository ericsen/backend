<?php

namespace App\Payment\Zspeed;

use Illuminate\Support\Facades\DB;
use App\Models\Api\GameUser;

class Zspeed
{
    function __construct(string $sort, string $site) {
        $this->sort = $sort;
        $this->site = $site;
    }

    public function getDateType(){
        $today = date("Ymd");
        return $today;
    }
}
