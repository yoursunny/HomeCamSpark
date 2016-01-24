<?php
$accesstoken = trim(file_get_contents('/home/sunny/code/HomeCamSpark/accesstoken.txt'));

$j = json_decode(file_get_contents('php://input'));
$id = $j->data->id;

$context = stream_context_create(array(
  'http'=>array(
    'method'=>'GET',
    'header'=>'Authorization: Bearer '.$accesstoken."\r\n"
  )
));
$json = file_get_contents('https://api.ciscospark.com/v1/messages/'.$id, false, $context);
$j = json_decode($json);
if (!isset($j->text)) {
  die;
}
$text = $j->text;
$p = popen('festival --tts', 'w');
fwrite($p, $text);
fclose($p);
?>
