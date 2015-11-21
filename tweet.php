<?php
  include param.php
  $twitter_version = '1.0';
  $sign_method = 'HMAC-SHA1';
  $status=$argv[1];
  $url = 'https://api.twitter.com/1.1/statuses/update.json';
  $param_string = 'oauth_consumer_key=' . $twitter_consumer_key .
        '&oauth_nonce=' . time() .
        '&oauth_signature_method=' . $sign_method .
        '&oauth_timestamp=' . time() .
        '&oauth_token=' . $twitter_access_token .
        '&oauth_version=' . $twitter_version .
        '&status=' . rawurlencode($status);

  //Generate a signature base string for POST
  $base_string = 'POST&' . rawurlencode($url) . '&' . rawurlencode($param_string);
  $sign_key = rawurlencode($twitter_consumer_secret) . '&' . rawurlencode($twitter_access_token_secret);

  //Generate a unique signature
  $signature = base64_encode(hash_hmac('sha1', $base_string, $sign_key, true));
  $curl_header = 'OAuth oauth_consumer_key=' . rawurlencode($twitter_consumer_key) . ',' .
      'oauth_nonce=' . rawurlencode(time()) . ',' .
      'oauth_signature=' . rawurlencode($signature) . ',' .
      'oauth_signature_method=' . $sign_method . ',' .
      'oauth_timestamp=' . rawurlencode(time()) . ',' .
      'oauth_token=' . rawurlencode($twitter_access_token) . ',' .         'oauth_version=' . $twitter_version;

  $ch = curl_init();
  //Twitter post
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $curl_header));
  curl_setopt($ch, CURLOPT_VERBOSE, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, 'status=' . rawurlencode($status));

  $twitter_post = json_decode(curl_exec($ch));
  curl_close($ch);
  print_r($twitter_post);
?>