<?php

include_once ('TelnetClient.php');
include_once ('NaoAutorizadas.php');

$fhtt = array_search('FHTT0017eed0',$slotPort);
echo $serial = $slotPort[$fhtt];
echo $slot = $slotPort[$fhtt+4];
echo $pon = $slotPort[$fhtt+5];;

$telnet = new TelnetClient('10.0.2.2', 23);
$telnet->connect();
$telnet->setPrompt('>');
$telnet->login('GEPON','GEPON');
$telnet->execute('terminal length 0');
$telnet->setPrompt(':');
$telnet->execute('enable');
$telnet->setPrompt('#');
$telnet->execute('GEPON');
$telnet->execute('cd gpononu');


//$telnet->execute("set authorization slot $slotPort[5] link $slotPort[6] type 5506-02-b onuid 65 phy_id $slotPort[1]");
$cmdResult = $telnet->execute("set whitelist phy_addr address FHTT0017eed0 password null action add slot null link null onu null type null");

$telnet->disconnect();

echo $cmdResult;

?>