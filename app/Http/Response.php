<?php


namespace App\Http;
trait Response {
    public function error($message, $code = "ERROR", $errorCode = 400) {
        return response()->json([
            'code' => $code,
            'message' => $message
        ], $errorCode);
    }

    public function errorData($message, $error = "ERROR", $code = "ERROR", $errorCode = 400) {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'error' => $error
        ], $errorCode);
    }

    public function success($data, $message = "success", $code = "SUCCESS", $errorCode = 200) {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $errorCode);
    }

    public function successPaginate($data, $message = "success", $code = 200) {
        $mData = $this->paginateEncode($data);
        $data->code = $code;
        $data->message = $message;
        return response()->json($mData, $code);
    }

    public function paginateEncode($data) {
        return json_decode(json_encode($data));
    }
}
