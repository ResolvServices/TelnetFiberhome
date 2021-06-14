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
$telnet->execute('cd gpononu');
//$telnet->execute('cd qinq');

$cmdResult = $telnet->execute('set whitelist phy_addr address FHTT0017eed0 password null action delete slot null link null onu null type null');


$telnet->disconnect();

//print("\"{$cmdResult}\"\n");

echo $cmdResult;

?>