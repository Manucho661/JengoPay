<?php
function getDarajaAccessToken() {
    $consumerKey = 'SjTrAk1B7iHo8yH1DZKsyzG9HKDLRlvwKGR3tBJniWEtkkNc';      // Replace with your app's consumer key
    $consumerSecret = 'aibliGXFpGjaZblaZTT7J5608uHSGFGZz4vAiDMxwv3rTyFKAynI5f9lMxAJtyq9'; // Replace with your app's consumer secret

    $credentials = base64_encode("$consumerKey:$consumerSecret");

    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic $credentials"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response);
    return $result->access_token ?? null;
}
