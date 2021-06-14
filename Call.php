<?php

include('ClassTelnetFH.php');

$cmd = new TelnetFH('10.0.2.2',23,'GEPON','GEPON','GEPON');

$onu_info = new stdClass();

$script = ("cd gpononu
set whitelist phy_addr address HWTC1abb168d password null action add slot  link  onu  type 5506-02-b
cd epononu
cd qinq
set wancfg slot    index 1 mode internet type route 300 0 nat enable qos disable dsp pppoe proxy disable [usuario] [senha] null auto active enable
set wanbind slot    index 1 entries 1 fe1
apply wancfg slot    
apply wanbind slot   
cd gpononu
set web_cfg_mng slot  link  onu  index 1 web_user admin web_password 123456 group admin");

    $rpSerial = "/address [0-9a-zA-z]{1,}/";
    $rpSlot = "/slot [0-9]{1,}/";
    $rpPorta = "/link [0-9a-zA-z]{1,}/";
    $rpOnu = "/onu [0-9a-zA-z]{1,}/";

    $serial = preg_match($rpSerial,$script,$match);
    $serial = explode(" ",$match[0]);
    $serial = $serial = $serial[1];

    $slot_id = preg_match($rpSlot,$script,$match);
    $slot_id = explode(" ",$match[0]);
    $slot_id = $slot_id = $slot_id[1];

    $porta_id = preg_match($rpPorta,$script,$match);
    $porta_id = explode(" ",$match[0]);
    $porta_id = $porta_id = $porta_id[1];

    $onu_id = preg_match($rpOnu,$script,$match);
    $onu_id = explode(" ",$match[0]);
    $onu_id = $onu_id = $onu_id[1];

   //print_r($onu_info);
   
    $rpSlot = "/slot  link/";
    if($slot_id == ""){
       $slot_id = "null";
       $script = preg_replace($rpSlot,"slot $slot_id link",$script);
        }else{
            $script = preg_replace($rpSlot,"slot $slot_id link",$script);
        }
    
    $rpPorta = "/link  /";
    if($porta_id == ""){
       $porta_id = "null";
       $script = preg_replace($rpPorta,"link $porta_id ",$script);
        }else{
            $script = preg_replace($rpPorta,"link $porta_id ",$script);
        }

    $rpOnu = "/onu /";
    if($onu_id == ""){
        $onu_id = "null";
        $script = preg_replace($rpOnu,"onu $onu_id",$script);
        }else{
            $script = preg_replace($rpOnu,"onu $onu_id ",$script);
        }



        $script1 = explode("\n",$script);

        
    for($i=0;$i<2;$i++){
        $comandos = $script1[$i];
        //echo $comandos."\n";
        $onu = $cmd->provisionar($comandos);
        
    }


    $slotportid = $onu['slot'];
    $portaid = $onu['porta'];
    $onuid = $onu['onu'];

    //print_r($script);

    $script1 = implode("\n",$script1);

    $script1 = str_replace("slot   ","slot $slotportid $portaid $onuid",$script1);
    $script1 = str_replace("slot null","slot $slotportid",$script1);
    $script1 = str_replace("link null","link $portaid",$script1);
    $script1 = str_replace("onu null","onu $onuid",$script1);

    $script1 = explode("\n",$script1);

    for($i=$i;$i<=count($script1);$i++){
        $comandos = $script1[$i];
        //echo $comandos."\n";
        $onu = $cmd->provisionar($comandos);
        
    }

    $cmd->disconnect();     
    
    //print_r($onu);
   //print_r($onu_info);
   //print_r ($script);

?>