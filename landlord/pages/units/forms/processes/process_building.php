<?php
    //Custom Encrypting Function that will Encrypt data in the database     
    function str_openssl_enc($str, $iv) {
        $key = '123456790jengopay%$$^#$#$$#';
        $chiper = "AES-128-CTR";
        //$ivLen = openssl_cipher_iv_length($chiper);
        $options = 0;
        $str = openssl_encrypt($str, $chiper, $key, $options, $iv);
        return $str;
    }	
?>