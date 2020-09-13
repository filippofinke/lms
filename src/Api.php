<?php

namespace FilippoFinke\LMS;

class Api {

    const BASE_URL = "https://www.lmsvbs.admin.ch/gtservices/suisvc/v1";

    private $tokens = null;

    private function request($path, $headers) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, self::BASE_URL . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function login($avs, $password) {
        $token = base64_encode("LIVE:$avs:$password");

        $headers[] = "Authorization: Basic ".$token;
        $headers[] = "Refreshtoken: ";

        $result = $this->request("/auth?language=it-CH", $headers);
        $object = json_decode($result, true);

        if(isset($object["jwtSecurityToken"])) {
            $this->tokens = $object;
            return true;
        } 
        return false;
    }

    public function getMe() {
        if(isset($this->tokens)) {

            $userId = json_decode(base64_decode(explode(".",$this->tokens["jwtSecurityToken"])[1]), true)["gt-user-id"];

            $headers[] = 'Authorization: Bearer '.$this->tokens["jwtSecurityToken"];

            $result = $this->request("/user/$userId?language=it-CH", $headers);
            $object = json_decode($result, true);

            return $object;
        }

        return false;
    }

    public function getPreferences() {
        if(isset($this->tokens)) {

            $headers[] = 'Authorization: Bearer '.$this->tokens["jwtSecurityToken"];

            $result = $this->request("/userpreferences?language=it-CH", $headers);
            $object = json_decode($result, true);

            return $object;
        }

        return false;
    }

    public function getBossRelations() {
        if(isset($this->tokens)) {

            $headers[] = 'Authorization: Bearer '.$this->tokens["jwtSecurityToken"];

            $result = $this->request("/user/bossRelations?language=it-CH", $headers);
            $object = json_decode($result, true);

            return $object;
        }

        return false;
    }

    public function getFavorites() {
        if(isset($this->tokens)) {

            $headers[] = 'Authorization: Bearer '.$this->tokens["jwtSecurityToken"];

            $result = $this->request("/favorites/getFavoritesByUser?language=it-CH", $headers);
            $object = json_decode($result, true);

            return $object;
        }

        return false;
    }

}