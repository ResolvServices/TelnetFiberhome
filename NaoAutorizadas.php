<?php

include_once ('TelnetClient.php');

//$telnet = new TelnetClient('10.0.2.2', 23);
$telnet->connect();
$telnet->setPrompt('>'); //setRegexPrompt() to use a regex
$telnet->login('GEPON','GEPON');
$telnet->execute('terminal length 0');
$telnet->setPrompt(':');
$telnet->execute('enable');
$telnet->setPrompt('#');
$telnet->execute('GEPON');
$telnet->execute('cd gpononu');

$cmdResult = $telnet->execute('show unauth_discovery');

$telnet->disconnect();

$rp = '/-----  ONU Unauth Table ,SLOT=[0-9]{1,} PON=[0-9]{1,} ,ITEM=0-----/';

print($cmdResult);

$cmdResult = preg_replace($rp,"",$cmdResult);
$cmdResult= str_replace("\n", "", $cmdResult);
$cmdResult= str_replace("\r", "", $cmdResult);
$cmdResult= str_replace(" ", "\n", $cmdResult);
$cmdResult = implode("\n", array_filter(explode("\n", $cmdResult)));
$cmdResult = str_replace(',SLOT=','',$cmdResult);
$cmdResult = str_replace('PON=','',$cmdResult);

print($cmdResult);
//$cmdResult = explode("\n",$cmdResult);

//$list  = preg_grep('/[A-Z]{4}[a-z0-9]{8}/', $cmdResult);
$list1 = array_keys($list);

//print_r($list);

for($i=0;$i<count($list1);$i++){
    $onu[$i] = array(
        'slot' =>  $cmdResult[$list1[$i]-15],
        'porta' => $cmdResult[$list1[$i]-14],
        'serial' =>$cmdResult[$list1[$i]],
        'modelo' =>$cmdResult[$list1[$i]-1]
    );
}



foreach ($onu as $lista){
    $lista = $onu;
}

print_r($onu);

//$cmdResult = str_replace(" ",",",$cmdResult);
//$cmdResult = str_replace("  ","",$cmdResult);
//$cmd = explode(",",$cmdResult);
//$cmd = array_filter($cmd);

//var_dump($cmd);

//exit;

$startPos = strpos($cmdResult,'1 ,');
$endPos = strpos($cmdResult,'TOTAL');
$endPos -= $startPos;
$slotPort = substr($cmdResult,$startPos,$endPos);
$slotPort = explode(' ',$slotPort);


$slotPort = implode('',$slotPort);
$slotPort = explode(',',$slotPort);


$list  = preg_grep('/[A-Z]{4}/', $slotPort);

//print_r($list);

$list1 = array_keys($list);

//print_r($list1);
//echo count($list);
for($i=0;$i < count($list);$i++){
    $lista[$i] = array(

     'serial' => $slotPort[$list1[$i]],
     'slot'  => $slotPort[$list1[$i]+4],
    'porta' => $slotPort[$list1[$i]+5]
    );
}

//$lista = explode(',',$lista);

print_r($lista);  

?>
