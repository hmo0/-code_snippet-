<?php
/**
 * Created by PhpStorm.
 * User: hm
 * Date: 2019/1/17
 * Time: 17:00
 */

class XDocService {
    public $url = "http://www.xdocin.com";
    public $key = "6islaawwzvb3ni4x7scaiqtj5u";
    public function run($xdoc, $param, $filename) {
        $param["_key"] = $this->key;
        $param["_xdoc"] = $xdoc;
        $param["_format"] = substr($filename, strrpos($filename, ".") + 1);
        $data = "";
        foreach ($param as $k => $v)
        {
            $data.= "&" . urlencode($k) . "=" . urlencode($v);
        }
        $data = substr($data, 1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . "/xdoc");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($ch);
        curl_close($ch);
        file_put_contents($filename, $data);
    }
}
?>
