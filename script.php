
<?php

$onu_info = new stdClass();

$script = ("cd gpononu
set whitelist phy_addr address [seri5-/al] password null action add slot [portaslotid] link [portaid] onu null type 5506-02-b
cd epononu
cd qinq
set wancfg slot [portaslotid] [portaid] [onuID] index 1 mode internet type route 300 0 nat enable qos disable dsp pppoe proxy disable [usuario] [senha] null auto active enable
set wanbind slot [portaslotid] [portaid] [onuID] index 1 entries 1 fe1
apply wancfg slot [portaslotid] [portaid] [onuID] 
apply wanbind slot 7 9 25");

$rpSerial = "/address [0-9a-zA-z-\/]{1,}/";
$rpSlot = "/slot [0-9a-zA-z]{1,}/";
$rpPorta = "/link [0-9a-zA-z]{1,}/";
$rpOnu = "/onu [0-9a-zA-z]{1,}/";



    $serial = preg_match($rpSerial,$script,$match);
    $serial = explode(" ",$match[0]);
    $onu_info->serial = $serial = $serial[1];

    $slot_id = preg_match($rpSlot,$script,$match);
    $slot_id = explode(" ",$match[0]);
    $onu_info->slot_id = $slot_id = $slot_id[1];

    $porta_id = preg_match($rpPorta,$script,$match);
    $porta_id = explode(" ",$match[0]);
    $onu_info->porta_id = $porta_id = $porta_id[1];

    $onu_id = preg_match($rpOnu,$script,$match);
    $onu_id = explode(" ",$match[0]);
    $onu_info->onu_id = $onu_id = $onu_id[1];

   print_r($serial)

?>

