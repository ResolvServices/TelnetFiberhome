<?php
//namespace Telnet;
include ('TelnetClient.php');
include ('NaoAutorizadas.php');

//$telnet = new TelnetClient('10.0.2.2', 23);
//$telnet->connect();
//$telnet->setPrompt('>'); //setRegexPrompt() to use a regex
//$telnet->setPruneCtrlSeq(true); //Enable this to filter out ANSI control/escape sequences
//$telnet->login('GEPON','GEPON');
//$telnet->execute('terminal length 0');
//$telnet->setPrompt(':');
//$telnet->execute('enable');
//$telnet->setPrompt('#');
//$telnet->execute('GEPON');

//$telnet->execute('cd gpononu');



//$cmdResult = $telnet->execute('show optic_module slot 1 link 2 onu 1');

echo $slotPort[5];

//$telnet->disconnect();

//print("\"{$cmdResult}\"\n");

?>


