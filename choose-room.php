<?php
$accesstoken = trim(file_get_contents('accesstoken.txt'));

$context = stream_context_create(array(
  'http'=>array(
    'method'=>'GET',
    'header'=>'Authorization: Bearer '.$accesstoken."\r\n"
  )
));
$json = file_get_contents('https://api.ciscospark.com/v1/rooms', false, $context);
$j = json_decode($json);

echo "Available Rooms:\n";
for ($i = 0; $i < count($j->items); ++$i) {
  printf("%d. %s\n", $i, $j->items[$i]->title);
}
echo "\n";

$sel = -1;
$stdin = fopen('php://stdin', 'r');
while ($sel < 0 || $sel >= count($j->items)) {
  printf("Choose a room [0-%d]: ", count($j->items) - 1);
  $line = trim(fgets($stdin));
  $sel = intval($line);
  if (strval($sel) != $line) {
    $sel = -1;
  }
}
fclose($stdin);

$f = fopen('room.txt', 'w');
fwrite($f, $j->items[$sel]->id);
fclose($f);
?>
