
<?php

include_once ('TelnetClient.php');

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

$cmdResult = $telnet->execute('show whitelist phy_addr select address FHTT0017eed0');


$telnet->disconnect();

$pos = "/  [0-9]{1,}/";
echo $cmdResult;
$pos = preg_match($pos,$cmdResult,$match);
$pos = $match[0];
$pos = strpos($cmdResult,$pos);
echo $cmdResult = substr($cmdResult,$pos);
$cmdResult = str_replace(' ',',',$cmdResult);
//echo $cmdResult;
$onu_id = explode(',',$cmdResult);
$onu_id = array_filter($onu_id);
//print_r($onu_id);
$onu_id1 = array_keys($onu_id);
//$onu_id = $onu_id[11];
//print_r($onu_id1);

/*$onu_id[0] = $onu_id[$onu_id1[0]];
$onu_id[1] = $onu_id[$onu_id1[1]];
$onu_id[2] = $onu_id[$onu_id1[2]];*/


$onu_id = [
    0 => $onu_id[0] = $onu_id[$onu_id1[0]],
    1 => $onu_id[1] = $onu_id[$onu_id1[1]],
    2 => $onu_id[2] = $onu_id[$onu_id1[2]],
    3 => $onu_id[3] = $onu_id[$onu_id1[5]],
];

print_r($onu_id);

//echo $onu_id[1];

?>

