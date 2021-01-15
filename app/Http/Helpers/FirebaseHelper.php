<?php


namespace App\Http\Helpers;


class FirebaseHelper
{
    public static function sendFcmNotificationMessage($PlayerIDs, $Data, $subtitle)
    {

        $server_key = config('fcmsettings.token');

        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: key='.$server_key
        ];


        $msg = ['title' => 'تراشیپ', 'sub_title' => $subtitle, 'activitydata' => $Data];
        $notificationBody = ['subtitle' => $subtitle];
        $notification = ['title' => 'تراشیپ', 'body' => $subtitle];

        $fields = [
            "content_available" => true,
            "priority" => "high",
            'registration_ids' => $PlayerIDs,
            'notification' => $notification,
            'data' => $msg
        ];

        $fields = json_encode($fields);
        $url = 'https://fcm.googleapis.com/fcm/send';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
}
