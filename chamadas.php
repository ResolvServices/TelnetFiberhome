<?php
 
 include('ClassTelnetFH.php');

    $cmd = new TelnetFH('10.0.2.2',23,'GEPON','GEPON','GEPON');

    //$cmd->conexao('10.0.2.2',23,'GEPON','GEPON','GEPON');
    //print_r($lista = $cmd->listaNaoAutorizadas());
    //$cmd->provisionarManual('FHTT0017eed0');
    //$cmd->desprovisionar('FHTT0017eed0');
     $sinal = $cmd->nivelDeSinal('FHTT0017eed0');

     echo $sinal['Rx Power'];
    //print_r ($cmd->idOnu('HWTC1abb168d'));
    //$cmd->wancfgPPPOE('FHTT0017eed0',300,'TesteWanCFG','123456',1,'fe1');
   
    $script = ("cd gpononu
set whitelist phy_addr address FHTT0017eed0 password null action add slot 7 link 9 onu null type 5506-02-b
cd epononu
cd qinq
set wancfg slot    index 1 mode internet type route 300 0 nat enable qos disable dsp pppoe proxy disable testeTelnet01 teste null auto active enable
set wanbind slot 7 9  index 1 entries 1 fe1
apply wancfg slot 7 9  
apply wanbind slot 7 9  
cd gpononu
set web_cfg_mng slot  link  onu  index 1 web_user admin web_password 123456 group admin");


   //$script = explode("\n",$script);
    
    //$cmd->provisionar($script);

    $cmd->disconnect(); 

?>