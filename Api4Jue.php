<?php
/**
 * Created by PhpStorm.
 * User: aric
 * Date: 2019-06-22
 * Time: 00:02
 */
class Api4Jue {
    public static function upload($filepath) {
        $name = basename($filepath);
        preg_match('/(.*?)\\.[A-Za-z0-9]+/', $name,$m);
        $name=$m[0];
        //$type = mime_content_type($filepath);
        $bits = file_get_contents($filepath);
        $target_url = "https://api.jue.sh/img/upload";
        $data = array();
        $mimeBoundary = uniqid();
        array_push($data, "--" . $mimeBoundary);
        $mimeType = empty($type) ? 'application/octet-stream' : $type;
        array_push($data, "Content-Disposition: form-data; name=\"file\"; filename=\"$name\"");
        //array_push($data, "Content-Type: $mimeType");
        array_push($data, '');
        array_push($data, $bits);
        array_push($data, '');
        array_push($data, "--" . $mimeBoundary . "--");
        $post_data = implode("\r\n", $data);
        $length = strlen($post_data);
        $headers = array();
        array_push($headers, "Content-Type: multipart/form-data; boundary=$mimeBoundary");
        array_push($headers, "Content-Length: {$length}");
        array_push($headers, "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        return json_decode($result);
    }
}
