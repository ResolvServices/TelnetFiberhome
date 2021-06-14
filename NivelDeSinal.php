<?php

include ('TelnetClient.php');


$telnet = new TelnetClient('10.0.2.2', 23);
$telnet->connect();
$telnet->setPrompt('>'); //setRegexPrompt() to use a regex
$telnet->login('GEPON','GEPON');
$telnet->execute('terminal length 0');
$telnet->setPrompt(':');
$telnet->execute('enable');
$telnet->setPrompt('#');
$telnet->execute('GEPON');
$telnet->execute('cd gpononu');


$cmdResult = $telnet->execute('show optic_module slot 7 link 9 onu 11');

$telnet->disconnect();

echo $cmdResult;

$startPos = strpos($cmdResult,'TYPE');
$endPos = strripos($cmdResult,')');
$endPos -= $startPos;
$cmdResult = substr($cmdResult,$startPos,$endPos+1);
$sinal = str_replace(' ',',',$cmdResult);
$sinal = explode(',',$sinal);

echo $rxPower = "Rx Power = ".preg_replace("/[^0-9.-]/","",$sinal[35])."\n";
echo $txPower = "Tx Power = ".preg_replace("/[^0-9.-]/","",$sinal[30]);


?>