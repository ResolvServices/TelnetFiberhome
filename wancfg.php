<?php

include ('TelnetClient.php');


$telnet = new TelnetClient('10.0.2.2', 23);
$telnet->connect();
$telnet->setPrompt('>'); //setRegexPrompt() to use a regex
//$telnet->setPruneCtrlSeq(true); //Enable this to filter out ANSI control/escape sequences
$telnet->login('GEPON','GEPON');
$telnet->execute('terminal length 0');
$telnet->setPrompt(':');
$telnet->execute('enable');
$telnet->setPrompt('#');
$telnet->execute('GEPON');
$telnet->execute('cd epononu');
$telnet->execute('cd qinq');
echo $cmdResult = $telnet->execute('delete wancfg slot 7 9 25 index 0');
echo $cmdResult = $telnet->execute('apply wancfg slot 7 9 25');
echo $cmdResult = $telnet->execute('set wancfg slot 7 9 25 index 1 mode internet type route 300 0 nat enable qos disable dsp pppoe proxy disable testeTelnet01 teste null auto  active enable');
echo $cmdResult = $telnet->execute('set wanbind slot 7 9 25 index 1 entries 1 fe1');
echo $cmdResult = $telnet->execute('apply wancfg slot 7 9 25');
echo $cmdResult = $telnet->execute('apply wanbind slot 7 9 25');

//$cmdResult = $telnet->execute('set whitelist phy_addr address FHTT0017eed0 password null action add slot 7 link 9 onu 65 type 5506-02-b');



$telnet->disconnect();

//print("\"{$cmdResult}\"\n");

//echo $cmdResult;

?>