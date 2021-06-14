<?php

include ('TelnetClient.php');

Class TelnetFH{


    private $conn;
    private $lista;



    public function __construct($host,$port,$user,$password,$enPassword) {

        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->enPassword = $enPassword;

        $this->conn = new TelnetClient($host,$port);
        $this->conn->connect();
        $this->conn->setPrompt('>'); 
        $this->conn->login($user,$password);
        $this->conn->execute('terminal length 0');
        $this->conn->setPrompt(':');
        $this->conn->execute('enable');
        $this->conn->setPrompt('#');
        $this->conn->execute($enPassword);
    
    
    }


    /*public function provisionar($script){


        $rpSerial = "/address [0-9a-zA-z]{1,}/";
        $serial = preg_match($rpSerial,$script,$match);
        $serial = explode(" ",$match[0]);
        $serial = $serial[1];

        //echo $serial;

        if($serial != ""){
        $this->desprovisionar($serial);
        $this->conn->execute("cd gepon");
        echo $this->conn->execute($script);

        sleep(15);
        $onuinfo = $this->idOnu($serial);
        }else{
            return;
        }
        //echo $this->conn->execute($script);

        //$script = explode("\n",$script);

        //$key = array_search('cd qinq',$script);
        //$script = preg_replace("/onu [\s]/","onu null ",$script);
      
        //for ($i=0;$i<=$key;$i++){
        //    echo $cmdresult = $this->conn->execute($script[$i]);
        //}

        //sleep(10);

        //$onuinfo = $this->idOnu($serial);

        //$script = preg_replace('/slot ([\s])([\s])([\s])/',"slot $onuinfo[0] $onuinfo[1] $onuinfo[2] ",$script);
        //$script = preg_replace('/slot ([0-9]{1,}) ([0-9]{1,}) ([\s])/',"slot $onuinfo[0] $onuinfo[1] $onuinfo[2] ",$script);
        //
        //echo $cmdresult = $this->conn->execute("cd epononu");
        //echo $cmdresult = $this->conn->execute("cd qinq");

        //for ($i=$i;$i<=count($script);$i++){
        //    echo $cmdresult = $this->conn->execute($script[$i]);
        //}
        ////$this->conn->execute($script);
        //

        ////print_r ($onuinfo);
        return $onuinfo; 
    }*/

    public function provisionar($script){

        $rpSerial = "/address [0-9a-zA-z]{1,}/";
        $rpSlot = "/slot [0-9]{1,}/";
        $rpPorta = "/link [0-9a-zA-z]{1,}/";
        $rpOnu = "/onu [0-9a-zA-z]{1,}/";
    
        $serial = preg_match($rpSerial,$script,$match);
        $serial = explode(" ",$match[0]);
        $serial = $serial[1];

        $slot_id = preg_match($rpSlot,$script,$match);
        $slot_id = explode(" ",$match[0]);
        $slot_id = $slot_id[1];
    
        $porta_id = preg_match($rpPorta,$script,$match);
        $porta_id = explode(" ",$match[0]);
        $porta_id = $porta_id[1];
    
        $onu_id = preg_match($rpOnu,$script,$match);
        $onu_id = explode(" ",$match[0]);
        $onu_id = $onu_id[1];

        if($serial == ""){

            //echo "Entro aqui";
            $rpSerial = "/logic_sn sn [0-9a-zA-z]{1,}/";
            $serial = preg_match($rpSerial,$script,$match);
            $serial = explode(" ",$match[0]);
            $serial = $serial[2];
            echo $serial;
        }
    
       //print_r($onu_id);
       
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
    
        $rpOnu = "/onu  /";
        if($onu_id == ""){
            $onu_id = "null";
            $script = preg_replace($rpOnu,"onu $onu_id ",$script);
            }else{
                $script = preg_replace($rpOnu,"onu $onu_id ",$script);
            }
            
            

            $this->desprovisionar($serial);

            $script1 = explode("\n",$script);

        
    
            
        for($i=0;$i<2;$i++){
            $comandos = $script1[$i];
            //echo $comandos."\n";
            echo $this->conn->execute($comandos);
            
        }

        sleep(20);

        $onu_info = $this->idOnu($serial);
    
    
        $slotportid = $onu_info['slot'];
        $portaid = $onu_info['porta'];
        $onuid = $onu_info['onu'];
    
        //print_r($onu);
    
        $script1 = implode("\n",$script1);
    
        $script1 = str_replace("slot   ","slot $slotportid $portaid $onuid",$script1);
        $rpOnu = '/slot ([0-9]{1,2}) ([0-9]{1,2})  /';
        $script1 = preg_replace($rpOnu,"slot $slotportid $portaid $onuid ",$script1);
        $script1 = str_replace("slot null","slot $slotportid",$script1);
        $script1 = str_replace("link null","link $portaid",$script1);
        $script1 = str_replace("onu null","onu $onuid",$script1);
    
        $script1 = explode("\n",$script1);
    
        for($i=$i;$i<=count($script1);$i++){
            $comandos = $script1[$i];
            //echo $comandos."\n";
            echo $this->conn->execute($comandos);
            
        }

        return $onu_info;

    }
    
    public function listaNaoAutorizadas(){

        $this->conn->execute('cd gpononu');

        $cmdResult = $this->conn->execute('show unauth list');

        //echo $cmdResult;
        //exit;

        //$this->conn->disconnect();

        $startPos = strpos($cmdResult,'1 ,');
        $endPos = strpos($cmdResult,'TOTAL');
        $endPos -= $startPos;
        $slotPort = substr($cmdResult,$startPos,$endPos);
        $slotPort = explode(' ',$slotPort);


        $slotPort = implode('',$slotPort);
        $slotPort = explode(',',$slotPort);
        //print_r($slotPort);

        $list  = preg_grep('/[A-Z]/', $slotPort);
        //print_r($list);
        $list1 = array_keys($list);
        
        //var_dump($list1);

        for($i=0;$i < count($list);$i++){
            $this->lista[$i] = array (
                
            'serial' => $slotPort[$list1[$i]],
            'slot' => $slotPort[$list1[$i]+4],
            'port' => $slotPort[$list1[$i]+5],
            );
        }

        
        return $this->lista;
    }

    public function provisionarManual($fhtt){

       // $this->desprovisionar($fhtt);
       //echo $fhtt;

        $list = $this->listaNaoAutorizadas();

        //$fhtt = array_search($fhtt,$list);
        $serial = $this->lista[$fhtt];
        $slot =   $this->lista[$fhtt+1];
        $pon =    $this->lista[$fhtt+2];;

        $this->conn->execute('cd gpononu');
        //$conn->execute("set authorization slot $slotPort[5] link $slotPort[6] type 5506-02-b onuid 65 phy_id $slotPort[1]");
        $cmdResult = $this->conn->execute("set whitelist phy_addr address $fhtt password null action add slot 7 link 9 onu null type null");

        echo $cmdResult;

    }

    public function desprovisionar($fhtt){

        //echo $fhtt."\n";
        $onuid = $this->idOnu($fhtt);
        //print_r($onuid);


        $cmdresult = $this->conn->execute("cd epononu");
        echo $cmdresult = $this->conn->execute("cd qinq");
        echo $cmdresult = $this->conn->execute("delete wancfg slot $onuid[slot] $onuid[porta] $onuid[onu] index 0");
        echo $cmdresult = $this->conn->execute("apply wancfg slot $onuid[slot] $onuid[porta] $onuid[onu]");
        $this->conn->execute('cd gpononu');
        $regexFHTT = '/([A-Z]){4}([0-9a-z]){1,}/';
        $match = preg_match($regexFHTT,$fhtt);
        if($match == 1){
        $cmdResult = $this->conn->execute("set whitelist phy_addr address $fhtt password null action delete");
        }else{
            $cmdResult = $this->conn->execute("set whitelist logic_sn sn $fhtt password null action delete");  
        }
        echo $cmdResult;
    }

    public function nivelDeSinal($fhtt){

        $onu_id = $this->idOnu($fhtt);
        //print_r($onu_id);

        $cmdResult = $this->conn->execute("show optic_module slot $onu_id[slot] link $onu_id[porta] onu $onu_id[onu]");

        //echo $cmdResult;

        $startPos = strpos($cmdResult,'TYPE');
        $endPos = strripos($cmdResult,')');
        $endPos -= $startPos;
        $cmdResult = substr($cmdResult,$startPos,$endPos+1);
        $sinal = str_replace(' ',',',$cmdResult);
        $sinal = explode(',',$sinal);

        //print_r($sinal);

        $sinal = [

        "Rx Power" => $rxPower = preg_replace("/[^0-9.-]/","",$sinal[35]),
        "Tx Power" => $txPower = preg_replace("/[^0-9.-]/","",$sinal[30]),

        ];

        return $sinal;
    }

    public function idOnu($fhtt){


        $this->conn->execute('cd gpononu');
        $regexFHTT = '/([A-Z]){4}([0-9a-z]){1,}/';
        $match = preg_match($regexFHTT,$fhtt);
        if($match == 1){
        $cmdResult = $this->conn->execute("show whitelist phy_addr select address $fhtt");
        }else{
            echo $cmdResult = $this->conn->execute("show whitelist logic_sn select sn $fhtt password null");
        }
        $pos = "/  [0-9]{1,}/";
        $pos = preg_match($pos,$cmdResult,$match);
        $pos = $match[0];
        $pos = strpos($cmdResult,$pos);
        $cmdResult = substr($cmdResult,$pos);
        $cmdResult = str_replace(' ',',',$cmdResult);
        $cmdResult = str_replace("\n",',',$cmdResult);
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
            "slot" => $onu_id[0] = $onu_id[$onu_id1[0]],
            "porta" => $onu_id[1] = $onu_id[$onu_id1[1]],
            "onu" => $onu_id[2] = $onu_id[$onu_id1[2]],
            "serial" => $onu_id[3] = $onu_id[$onu_id1[5]],
        ];
        //print_r($onu_id);

        return $onu_id;
    }

    public function wancfgPPPOE($fhtt,$vlan,$pppoeUser,$pppoePassword,$numPorts,$ports){
        /*
        $numPorts = numero de portas a serem liberadas , 1 porta lan = 1 , 1 porta lan e 1 WIFI = 2
        $ports = portas a serem liberadas , dor apenas porta 1 'fe1' , se for 1 e 2 'fe1,fe2' // nomes das portas ('fe1,fe2,fe3,fe4,ssid1,ssid2,ssid3,ssid4' )
        
        */ 
        $onuid = $this->idOnu($fhtt);

        $ports = str_replace(',',' ',$ports);

        //echo $ports;

        $this->conn->execute('cd epononu');
        $this->conn->execute('cd qinq');
        $this->conn->execute("delete wancfg slot $onuid[0] $onuid[1] $onuid[2] index 0");
        $this->conn->execute("apply wancfg slot $onuid[0] $onuid[1] $onuid[2]");
        $this->conn->execute("set wancfg slot $onuid[0] $onuid[1] $onuid[2] index 1 mode internet type route $vlan 0 nat enable qos disable dsp pppoe proxy disable $pppoeUser $pppoePassword null auto  active enable");
        $this->conn->execute("set wanbind slot $onuid[0] $onuid[1] $onuid[2] index 1 entries $numPorts $ports");
        $this->conn->execute("apply wancfg slot $onuid[0] $onuid[1] $onuid[2]");
        $this->conn->execute("apply wanbind slot $onuid[0] $onuid[1] $onuid[2]");


    }

    public function wancfgBRIDGE($fhtt,$vlan,$numPorts,$ports){

        $onuid = $this->idOnu($fhtt);

        $ports = str_replace(',',' ',$ports);

        //echo $ports;

        $this->conn->execute('cd epononu');
        $this->conn->execute('cd qinq');
        $this->conn->execute("delete wancfg slot $onuid[0] $onuid[1] $onuid[2] index 0");
        $this->conn->execute("apply wancfg slot $onuid[0] $onuid[1] $onuid[2]");
        $this->conn->execute("set wancfg slot $onuid[0] $onuid[1] $onuid[2] index 1 mode internet type route $vlan 0 nat enable qos disable dsp dhcp active disable");
        $this->conn->execute("set wanbind slot $onuid[0] $onuid[1] $onuid[2] index 1 entries $numPorts $ports");
        $this->conn->execute("apply wancfg slot $onuid[0] $onuid[1] $onuid[2]");
        $this->conn->execute("apply wanbind slot $onuid[0] $onuid[1] $onuid[2]");


    }

    public function disconnect(){

        $this->conn->disconnect();

    }
}
