<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Throwable;

trait dbTransac
{

     /**
     *
     * @param  \Closure  $callback
     * @param  \Closure|null  $onError
     * @return mixed
     */

    public function dbTransaction(\Closure $callback, \Closure $onError = null)
    {
        try {
            return DB::transaction($callback);
        } catch (Throwable $e) {
            if ($onError) {
                return $onError($e);
            }

            report($e);
            return response()->json([   
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
