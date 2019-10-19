<?php

include "configuration/env.php";

class FlipHelper
{
    private $request = "/disburse";
    private $status = "/disburse\/";

    public function __construct()
    {
        $this->host = getenv('BASE_FLIP');
        $this->token = getenv('TOKEN');
    }

    public function postRequest($data)
    {
        $data['token'] = $this->token;
        return $this->curlRequest($this->host . $this->request);
    }

    public function getStatus($id)
    {
        return $this->curlRequest($this->host . $this->status . $id);
    }


    private function curlRequest($url, $postData = '', $postFiles = array())
    {
        $agent = 'Mozilla/5.0 (X11; AccessApi; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        if ($postData) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_POST, true);
            if (isset($postData['token'])) {
                $header = array(
                    "Content-Type: application/json",
                    "Authorization: Bearer " . $postData['token']
                );
                if (count($postFiles) > 0) {
                    $return = $this->curl_custom_postfields($postData, $postFiles);
                    $header = array(
                        "Content-Type: multipart/form-data; boundary={$return['header']}",
                        "Authorization: Bearer " . $postData['token']
                    );
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $return['body']);
                }
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            }
        }
        $result['header'] = curl_getinfo($ch);
        $result['errno'] = curl_errno($ch);
        $result['errmsg'] = curl_error($ch);
        $result['content'] = curl_exec($ch);
        if ($this->json_validate($result['content'])) {
            $result['content'] = json_decode($result['content']);
        }
        curl_close($ch);
        return $result['content'];
    }

    private function json_validate($string)
    {
        // decode the JSON data
        $result = json_decode($string);

        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
                // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
                // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
                // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }

        if ($error !== '') {
            return 0;
        }

        return 1;
    }
}
