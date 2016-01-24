<?php
$ngrokjson = file_get_contents('http://127.0.0.1:4040/api/tunnels');
$ngrokj = json_decode($ngrokjson);
$tunnel = $ngrokj->tunnels[0]->public_url;
$baseuri = str_replace('tcp://', 'http://', $tunnel);
$filename = basename($argv[1]);

$accesstoken = trim(file_get_contents('accesstoken.txt'));
$room = trim(file_get_contents('room.txt'));

$j = array(
  'roomId'=>$room,
  'files'=>array($baseuri.'/'.$filename)
);
$json = json_encode($j);

$context = stream_context_create(array(
  'http'=>array(
    'method'=>'POST',
    'header'=>'Authorization: Bearer '.$accesstoken."\r\n".
              'Content-Type: application/json'."\r\n",
    'content'=>$json
  )
));
$resp = file_get_contents('https://api.ciscospark.com/v1/messages', false, $context);
$respj = json_decode($resp);
echo $respj->id."\n";
?>
