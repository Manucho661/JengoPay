<?php
    /*Custom Encrypting Function that will Encrypt data in the database     
    function str_openssl_enc($str, $iv) {
        $key = '123456790jengopay%$$^#$#$$#';
        $chiper = "AES-128-CTR";
        //$ivLen = openssl_cipher_iv_length($chiper);
        $options = 0;
        $str = openssl_encrypt($str, $chiper, $key, $options, $iv);
        return $str;
    }*/	

    /*Custom Decrypting Function that will Decrypt data on the Client Side
    function str_openssl_dec($str, $iv) {
        $key = '123456790jengopay%$$^#$#$$#';
        $chiper = "AES-128-CTR";
        //$ivLen = openssl_cipher_iv_length($chiper);
        $options = 0;
        $str = openssl_decrypt($str, $chiper, $key, $options, $iv);
        return $str;
    }*/  

    //Custom Function to Encrypt URL when doing CRUD Operations
    function encryptor($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";

        $secret_key = "28383433QW4-121AHSHDH292-SJ27830129W-283910W3SSH";
        $secret_iv = 'jengopay';

        $key = hash('sha256', $secret_key);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    } 
?>