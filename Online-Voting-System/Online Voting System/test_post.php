<?php
// test_post.php - quick POST to api/login.php using stream context
$url = 'http://localhost/Online%20Voting%20System/api/login.php';
$data = http_build_query(['mobile'=>'9800000000','password'=>'testpass','role'=>1]);
$options = ['http' => ['method' => 'POST',
                      'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                                  "Content-Length: ".strlen($data)."\r\n",
                      'content' => $data]];
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo "<pre>$result</pre>";
