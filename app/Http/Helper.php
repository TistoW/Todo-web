<?php


namespace App\Http;

use App\Models\PersonalToken;
use Illuminate\Http\Request;

trait Helper {

    function getUserId(Request $request) {
        $user = $this->getUser($request);
        if ($user) {
            return $user->id;
        } else {
            return null;
        }
    }

    function getUser(Request $request) {
        $token = $request->header('token');
        $personalToken = PersonalToken::where("token", $token)->with('user')->first();
        return $personalToken->user;
    }

    function random() {
        $chars = '0123456789ABCDEFGHAIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($chars), 0, 10);
    }

    function createToken($length = 25) {
        $chars = '0123456789ABCDEFGHAIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($chars), 0, $length);
    }

    function saveImage($request, $location, $default = '') {
        $fileName = $default;
        $image = $request->image;
        if ($image->getClientOriginalName()) {
            $file = str_replace(' ', '', $image->getClientOriginalName());
            $ext = "";
            if (str_contains($file, '.jpg')) {
                $ext = '.jpg';
            } else if (str_contains($file, '.png')) {
                $ext = '.png';
            } else if (str_contains($file, '.jpeg')) {
                $ext = '.jpeg';
            }
            $fileName = $location . '_' . date('mdHs') . rand(1, 999) . $ext;
            $image->storeAs('public/' . $location, $fileName);
        }
        return $fileName;
    }

    public function pushNotif($title, $message, $mFcm) {
        // fmc harus dalam bentuk Array
        $mData = [
            'title' => $title,
            'body' => $message
        ];

        $payload = [
            'registration_ids' => $mFcm,
            'notification' => $mData
        ];

        $this->logs(json_encode($payload));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Content-type: application/json",
                "Authorization: key=AAAA_ex6ZY4:APA91bFBmVO_4C-KF8qpMLHGeJVKePEn3-v_w67-B-7wXf6scj-YTEqOD67jLwxntshU3VMHEOSF7wXjN1b-8eqgsJt_qKzsRvL6MNGN0AlUB0Pvfj3Y2LZUj1di7DDFqqZATKRwbU5s"
            ),
        ));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($curl);
        curl_close($curl);

        $data = [
            'success' => 1,
            'message' => "Push notif success",
            'data' => $mData,
            'firebase_response' => json_decode($response)
        ];
        $this->logs(json_encode($data));
        return $data;
    }

    function logs($message = "message") {
        error_log($message);
    }
}
