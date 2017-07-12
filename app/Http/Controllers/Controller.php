<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array $results
     * @return array $data
     */
    protected function logResponse(array $results)
    {
        DB::table('tb_log_api')->insert($results);

        $result                          = json_decode($results['result']);

        $data['status']                 = $results['status'];
        $data['message']                = $results['message'];

        foreach ($result as $key => $value)
        {
            $data[$key] = $value;
        }

        // $data['results']['time']        = $results['time'];
        // $data['results']['url']         = $results['url'];
        // $data['results']['method']      = $results['method'];
        // $data['results']['action']      = $results['action'];
        // $data['results']['parameter']   = json_decode($results['parameter']);

        return $data;
    }
}
