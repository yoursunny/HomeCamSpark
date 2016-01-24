<?php
$ngrokjson = file_get_contents('http://127.0.0.1:4040/api/tunnels');
$ngrokj = json_decode($ngrokjson);
$tunnel = $ngrokj->tunnels[0]->public_url;
$baseuri = str_replace('tcp://', 'http://', $tunnel);

$accesstoken = trim(file_get_contents('accesstoken.txt'));
$room = trim(file_get_contents('room.txt'));

$context = stream_context_create(array(
  'http'=>array(
    'method'=>'GET',
    'header'=>'Authorization: Bearer '.$accesstoken."\r\n"
  )
));
$json = file_get_contents('https://api.ciscospark.com/v1/webhooks', false, $context);
$j = json_decode($json);
foreach ($j->items as $webhook) {
  if ($webhook->name != 'HomeCamSpark') {
    continue;
  }
  $context = stream_context_create(array(
    'http'=>array(
      'method'=>'DELETE',
      'header'=>'Authorization: Bearer '.$accesstoken."\r\n"
    )
  ));
  file_get_contents('https://api.ciscospark.com/v1/webhooks/'.$webhook->id, false, $context);
}


$j = array(
  'name'=>'HomeCamSpark',
  'targetUrl'=>$baseuri.'/webhook.php',
  'resource'=>'messages',
  'event'=>'created',
  'filter'=>'roomId='.$room
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
$resp = file_get_contents('https://api.ciscospark.com/v1/webhooks', false, $context);
$respj = json_decode($resp);
echo $respj->id."\n";
?>
