<?php

namespace App\Traits;

trait ResponseTrait
{
   
    /**
     * Throw the failed validation exception.
     *
     * @param $status String
     * @param $message Any
     * @param $data Array
     * @return Json 
     */
    protected function response_json($data, $status = 'success', $message = 'Berhasil menampilkan data',$code = 200)
    {
        return response()->json([
            'status'        => $status,
            'message'       => $message,
            'data'          => $data
        ],$code);
    }

    
}
