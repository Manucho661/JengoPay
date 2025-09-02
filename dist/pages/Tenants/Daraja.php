<?php
class Daraja {
    private $consumerKey;
    private $consumerSecret;
    private $businessShortcode;
    private $passkey;
    private $callbackUrl;
    private $accessToken;

    public function __construct($config) {
        $this->consumerKey = $config['consumer_key'];
        $this->consumerSecret = $config['consumer_secret'];
        $this->businessShortcode = $config['business_shortcode'];
        $this->passkey = $config['lipa_na_mpesa_passkey'];
        $this->callbackUrl = $config['callback_url'];
        $this->accessToken = $this->generateAccessToken();
    }

    private function generateAccessToken() {
      $credentials = base64_encode($this->consumerKey . ':' . $this->consumerSecret);
      $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
      $headers = ["Authorization: Basic $credentials"];

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);
      $error = curl_error($ch);
      curl_close($ch);

      if ($error) {
          throw new Exception("cURL Error: $error");
      }

      $result = json_decode($response, true);

      if (!isset($result['access_token'])) {
          throw new Exception("Access token not returned: " . json_encode($result));
      }

      return $result['access_token'];
  }


    public function stkPush($phone, $amount, $accountReference, $transactionDesc) {
        $timestamp = date('YmdHis');
        $password = base64_encode($this->businessShortcode . $this->passkey . $timestamp);

        $payload = [
            'BusinessShortCode' => $this->businessShortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $this->businessShortcode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => $accountReference,
            'TransactionDesc' => $transactionDesc
        ];

        $ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->accessToken
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $response;
    }
}
?>
