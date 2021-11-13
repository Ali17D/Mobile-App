<?php
    header('Access-Control-Allow-origin: *');
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: PUT, GET, POST, DELET, OPTIONS");
    header("Access-Control-Allow-Headers:Origin, Content-Type, Authorization, Accept, X-Requested-With, x-xsrf-token");
    header("Content-Type: application/json; charset=utf-8");

    define('dataBaseLocalhost', 'localhost');
    define('dataBaseUser', 'root');
    define('dataBasePassword', '');
    define('dataBaseName', 'doyourbest');

    $mysqli = mysqli_connect(dataBaseLocalhost, dataBaseUser, dataBasePassword, dataBaseName);
    $postjson=array();
    //Les compteurs
    $i=0; $y=0; $z=0;$x=0;$v=0;$t=0;$c=0;$n=0;$m=0;
    if((json_decode( file_get_contents("php://input"),true))){
        $postjson=json_decode( file_get_contents("php://input"),true);
        $container=$postjson['container'];
        if ($container=="register") {
            $Email=$postjson['Email'];  $lastname=$postjson['Lastname']; $firstname=$postjson['Firstname'];
            $motDePasse= $postjson['yourPassword'];
            if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$Email)==false){
                $resultOne =json_encode( array('ok' => false, 'msg' => 'Email disabled'));
            }else{
                $select="SELECT Email FROM `member` WHERE Email='$Email'"; $recupeEmail=mysqli_num_rows(mysqli_query($mysqli,$select));
                if($recupeEmail==1){
                    $resultOne =json_encode( array('ok' => false, 'msg' => 'Email is already used'));  
                }elseif(ctype_alpha($firstname)==false ||ctype_alpha($firstname)==false){
                    $resultOne =json_encode( array('ok' => false, 'msg' => 'your firstname,lastname must only be alphabetical.'));
                }elseif(strlen($firstname)>15||strlen($lastname)>15){
                    $resultOne =json_encode( array('ok' => false, 'msg' => 'your Lastname and firstname must not exceed 15 characters'));  
                }elseif(strlen($motDePasse)>8){
                    $resultOne =json_encode( array('ok' => false, 'msg' => 'your passeword must not exceed 8 characters'));}
                else{
                    $password = md5($motDePasse);
                    $insert =  "INSERT INTO `member` SET Firstname='$postjson[Firstname]', 
                    Lastname='$postjson[Lastname]',Email='$postjson[Email]',Password='$password'";
                    $anwser=mysqli_query($mysqli,$insert);
                    if ($anwser) {
                        $resultOne =json_encode( array('ok' => true, 'msg' => 'Register successfuly'));   
                    }else{
                    $resultOne =json_encode( array('ok' => false, 'msg' => 'Register failed'));}
                }         
            }echo $resultOne;   
        }
        elseif($postjson['container']=="connexion"){ 
		//$password = md5($postjson['yourPassword']); 
          //  $Email=$postjson['Email'];$loginTable=array();
            //$loginData="SELECT * FROM member WHERE Email='$email' AND Password='$password'";
            //$valeursLogin=mysqli_num_rows(mysqli_query($mysqli,$loginData));
            //$recupeValeur=mysqli_fetch_array(mysqli_query($mysqli,$loginData));
			//$resultLogin =json_encode( array('ok' => false, 'message' => 'Slau'));
	$resultLogin='ok';
            /*if($valeursLogin==0){
                $resultLogin =json_encode( array('ok' => false, 'message' => $resultLogin)); 
            }else{
                $loginTable=array('id'=>$recupeValeur['id'],'Firstname'=>$recupeValeur['Firstname'],'Lastname'=>$recupeValeur['Lastname'],'Email'=>$recupeValeur['Email']);
                if ($recupeValeur){
                    $resultLogin =json_encode( array('ok' => true, 'message' => $loginTable));   
                }else{
                    $resultLogin =json_encode( array('ok' => false));
                }    
            }*/
              echo ($resultLogin);

        }
        elseif($postjson['container']=="réinitialisation"){
            $Email=$postjson['Email'];
            $select="SELECT Email FROM `member` WHERE Email='$Email'";
                $recupeEmail=mysqli_num_rows(mysqli_query($mysqli,$select));
                if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$Email)==false){
                    $resultOne =json_encode( array('ok' => false, 'msg' => 'Email disabled'));
                }
                elseif($recupeEmail==0){
                    $resultOne =json_encode( array('ok' => false, 'msg' => 'Sorry! Email not found'));  
                }else{
                    $p="0123456789abdcefghijklmsrntulopqrstvwxyopmz";
                    $v=str_shuffle($p);
                    $v=substr($v,0,6); 
                    $newpasseword=md5($v);
                    $subject="reset passeword";
                    $message="this is your new passeword:".$v;
                    $header="from:tossiantiochus@gmail.com \r\n";
                    $header.="MINE-version:1.0"."\r\n";
                    $header.="Content-type:text/html; charset=utf-8"."\r\n";
                     mail($Email,$subject,$message,$header);
                    $insert =  "UPDATE `member` SET `Password`='$newpasseword' WHERE Email='$Email'";
                    $anwser=mysqli_query($mysqli,$insert);
                    if ($insert) {
                        $resultOne =json_encode( array('ok' => true, 'msg' => 'Passeword reset successfuly, please check your email'));   
                    }else{
                        $resultOne =json_encode( array('ok' => false, 'msg' => 'reset passseword failed'));
                    }
                }  
                    echo $resultOne;     
        }
        elseif($postjson['container']=="message"){
            $Email=$postjson['Email']; $recupeMessage=$postjson['msg'];
            $select="SELECT * FROM `member` WHERE Email='$Email'";
            $recupeFirstname=mysqli_fetch_array(mysqli_query($mysqli,$select));
                $recupeEmail=mysqli_num_rows(mysqli_query($mysqli,$select));
                if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$Email)==false){
                    $resultOne =json_encode( array('ok' => false, 'msg' => 'Email disabled'));
                }
                elseif($recupeEmail==0){
                    $resultOne =json_encode( array('ok' => false, 'msg' => 'Sorry! Email or Firstname not found'));  
                }else{
                    $subject=$recupeFirstname['Firstname']."\r\n"."vous Contacte";
                    $message=$recupeMessage."\r\n";
                    $header="from:".$Email."\r\n";
                    $header.="MINE-version:1.0"."\r\n";
                    $header.="Content-type:text/html; charset=utf-8"."\r\n";
                     mail('tossiantiochus@gmail.com',$subject,$message,$header);
                     $sender=mail('tossiantiochus@gmail.com',$subject,$message,$header);
                    if ($sender) {
                        $resultOne =json_encode( array('ok' => true, 'msg' => 'Thank you to contact us, we will reply you soon'));   
                    }else{
                        $resultOne =json_encode( array('ok' => false, 'msg' => 'contact failed'));
                    }
                }  
                    echo $resultOne;     
        }
        elseif($postjson['container']=="information"){
            $table=array(); $table1=array(); $table=$postjson['focalesMotrice'];
           $table1=$postjson['generalesMotrices']; $table2=$postjson['generalesNonMotrices'];
           $table3=$postjson['inconnuesMotrices']; $table4=$postjson['neonatale'];
           $table5=$postjson['nourrisson'];$table6=$postjson['enfant'];
           $table7=$postjson['adolescentAdulte']; $loginTable=array();
            //Pour la crise focale motrice
            while($i<sizeof($table)){
                if($table[$i]=="Myoclonique"){
                      
                $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%' OR Abreviation LIKE'%CLB%'
                OR Abreviation LIKE'%LVT%' OR Abreviation LIKE'%CZP%'OR Abreviation LIKE'%DZP%'
                OR Abreviation LIKE'%LZP%'OR Abreviation LIKE'%PER%'OR Abreviation LIKE'%Piracetam%'"; 
                $data=mysqli_query($mysqli,$loginData);
                while($recupeValeur=mysqli_fetch_array($data)){
                    $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                    'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                    'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                    'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                    'Amm'=>$recupeValeur['Amm']);
                }
                if ($data) {
                    $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                }else{
                    $resultLogin=json_encode( array('ok' => false));
                } 
                
                }elseif($table[$i]=="Tonique"){
                    $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%PHT%'OR Abreviation LIKE'%CBZ%'
                    "; 
                    $data=mysqli_query($mysqli,$loginData);
                    while($recupeValeur=mysqli_fetch_array($data)){
                        $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                        'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                        'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                        'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                        'Amm'=>$recupeValeur['Amm']);
                    }
                    if ($data) {
                        $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                    }else{
                        $resultLogin=json_encode( array('ok' => false));
                    }  
                }elseif($table[$i]=="Spasmes"){
                    $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%VGB%' OR Abreviation LIKE '%ACHT%' OR Abreviation LIKE '%prednisone%'
                        OR Abreviation LIKE '%TPM%'OR Abreviation LIKE '%Pyridoxine%' OR Abreviation LIKE '%PB%'
                        OR Abreviation LIKE '%Hydrocortisone%'";  
                    $data=mysqli_query($mysqli,$loginData);
                    while($recupeValeur=mysqli_fetch_array($data)){
                        $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                        'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                        'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                        'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                        'Amm'=>$recupeValeur['Amm']);
                    }
                    if ($data) {
                        $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                    }else{
                        $resultLogin=json_encode( array('ok' => false));
                    }   
                }elseif($table[$i]=="Hyperkinetique"){
                    $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%CBZ%'
                    OR Abreviation LIKE '%OXC%'"; 
                    $data=mysqli_query($mysqli,$loginData);
                    while($recupeValeur=mysqli_fetch_array($data)){
                        $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                        'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                        'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                        'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                        'Amm'=>$recupeValeur['Amm']);
                    }
                    if ($data) {
                        $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                    }else{
                        $resultLogin=json_encode( array('ok' => false));
                    }  
                }elseif($table[$i]=="Atonique"){
                    $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%LTG%'"; 
                    $data=mysqli_query($mysqli,$loginData);
                    while($recupeValeur=mysqli_fetch_array($data)){
                        $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                        'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                        'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                        'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                        'Amm'=>$recupeValeur['Amm']);
                    }
                    if ($data) {
                        $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                    }else{
                        $resultLogin=json_encode( array('ok' => false));
                    } 
                }elseif($table[$i]=="Automatisme"){
                    $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%CBZ%'
                    OR Abreviation LIKE '%LTG%' OR Abreviation LIKE '%OXC%'OR Abreviation LIKE '%LEV%'"; 
                    $data=mysqli_query($mysqli,$loginData);
                    while($recupeValeur=mysqli_fetch_array($data)){
                        $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                        'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                        'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                        'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                        'Amm'=>$recupeValeur['Amm']);
                    }
                    if ($data) {
                        $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                    }else{
                        $resultLogin=json_encode( array('ok' => false));
                    }    
                } $i++;
                //Pour la crise généralisée motrcie
                while($y<sizeof($table1)){
                    if($table1[$y]=="Myoclonique"){   
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%' OR Abreviation LIKE'%CLB%'
                    OR Abreviation LIKE'%LVT%' OR Abreviation LIKE'%CZP%'OR Abreviation LIKE'%DZP%'
                    OR Abreviation LIKE'%LZP%'OR Abreviation LIKE'%PER%'OR Abreviation LIKE'%Piracetam%'";  
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if($data){
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        }    
                    }elseif($table1[$y]=="Tonique"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%PHT%'OR Abreviation LIKE'%CBZ%'
                        OR Abreviation LIKE'%TPM%'OR Abreviation LIKE'%OXC%'OR Abreviation LIKE'%RFM%'"; 
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        }  
                    }elseif($table1[$y]=="Spasmes"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%VGB%' OR Abreviation LIKE '%ACHT%' OR Abreviation LIKE '%prednisone%'
                        OR Abreviation LIKE '%TPM%'OR Abreviation LIKE '%Pyridoxine%' OR Abreviation LIKE '%PB%'
                        OR Abreviation LIKE '%Hydrocortisone%'";  
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }elseif($table1[$y]=="Tonique clonique"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'OR Abreviation LIKE'%CBZ%'
                        OR Abreviation LIKE '%ESM%' OR Abreviation LIKE '%FBM%' OR Abreviation LIKE '%TPM%'
                        OR Abreviation LIKE '%PB%' OR Abreviation LIKE '%LTG%' OR Abreviation LIKE '%OXC%'
                        OR Abreviation LIKE '%FBM%'OR Abreviation LIKE '%LCM%'OR Abreviation LIKE '%PER%'
                        OR Abreviation LIKE '%ESL%'";  
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        }  
                    }elseif($table1[$y]=="Atonique"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%LTG%'"; 
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        }  
                    }elseif($table1[$y]=="Myoclonique-tonique clonique"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'OR Abreviation LIKE'%CLB%'"; 
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        }   
                    } $y++;
                } //pour la crise généralisée non motrice
                while($z<sizeof($table2)){
                    if($table2[$z]=="Myoclonique"){   
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%' OR Abreviation LIKE'%CLB%'
                    OR Abreviation LIKE'%LVT%' OR Abreviation LIKE'%CZP%'OR Abreviation LIKE'%DZP%'
                    OR Abreviation LIKE'%LZP%'OR Abreviation LIKE'%PER%'OR Abreviation LIKE'%Piracetam%'";  
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        }   
                    }elseif($table2[$z]=="Absence"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%CLB%' OR Abreviation LIKE '%LTG%' OR Abreviation LIKE '%ESM%'"; 
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }$z++;
                }//Pour la crise innconue motrice
                 while($x<sizeof($table3)){
                    if($table3[$x]=="Tonique clonique"){   
                     $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'OR Abreviation LIKE'%CBZ%'
                        OR Abreviation LIKE '%ESM%' OR Abreviation LIKE '%FBM%' OR Abreviation LIKE '%TPM%'
                        OR Abreviation LIKE '%PB%' OR Abreviation LIKE '%LTG%'OR Abreviation LIKE '%OXC%'"; 
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        }   
                    }elseif($table3[$x]=="Spasmes"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%VGB%' OR Abreviation LIKE '%ACHT%' OR Abreviation LIKE '%prednisone%'
                        OR Abreviation LIKE '%TPM%'OR Abreviation LIKE '%Pyridoxine%' OR Abreviation LIKE '%PB%'
                        OR Abreviation LIKE '%Hydrocortisone%'";  
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    } $x++;
                }//Pour le symdrome : période néonatale.
                while($c<sizeof($table4)){
                    if($table4[$c]=="Myoclonique précoce"){   
                     $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'OR Abreviation LIKE'%CLB%'"; 
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        }   
                    } $c++;
                }//Pour le syndrome: nourrisson
                while($v<sizeof($table5)){
                    if($table5[$v]=="Crise fébrile et Fc+"){   
                     $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'OR Abreviation LIKE'%PB%'
                     OR Abreviation LIKE'%CBZ%' OR Abreviation LIKE'%LTG%'"; 
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        }   
                    }elseif($table5[$v]=="Syndrome de West"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%VGB%' OR Abreviation LIKE '%ACHT%' OR Abreviation LIKE '%prednisone%'
                        OR Abreviation LIKE '%TPM%'OR Abreviation LIKE '%VitB6%' OR Abreviation LIKE '%PB%'
                        OR Abreviation LIKE '%Hydrocortisone%' OR Abreviation LIKE '%LVT%' 
                        OR Abreviation LIKE '%Tetracosactide%' OR Abreviation LIKE '%EPM%'";  
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }elseif($table5[$v]=="Syndrome de Dravet"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%CLB%'OR Abreviation LIKE '%TPM%'OR Abreviation LIKE '%CDB%'
                        OR Abreviation LIKE '%FFA%' OR Abreviation LIKE '%STP%'OR Abreviation LIKE '%lev%'
                        OR Abreviation LIKE '%Stiripentol%'";  
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }elseif($table5[$v]=="Epilepsie myoclonique nourrisson"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%CLB%'";
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }$v++;
                }//syndrome concernant les enfants
                while($t<sizeof($table6)){
                    if($table6[$t]=="Crise fébrile et Fc+"){   
                     $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'OR Abreviation LIKE'%PB%'
                     OR Abreviation LIKE'%CBZ%' OR Abreviation LIKE'%LTG%'"; 
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        }   
                    }elseif($table6[$t]=="Syndrome de Landau kleffner"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%' OR Abreviation LIKE '%ESM%' OR Abreviation LIKE '%LEV%'
                        OR Abreviation LIKE '%TPM%'OR Abreviation LIKE '%DZP%' OR Abreviation LIKE '%Stéroide%'";  
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }elseif($table6[$t]=="Encéphalopathie avec pointes ondes continues dans le sommeil"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%BZD%'OR Abreviation LIKE '%steroid%'";  
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }elseif($table6[$t]=="Syndrome de Lennox Gastaut"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%ETH%' OR Abreviation LIKE '%LTG%'OR Abreviation LIKE '%TPM%'
                        OR Abreviation LIKE '%BZD%'OR Abreviation LIKE '%FBM%'OR Abreviation LIKE '%RFM%'";
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }elseif($table6[$t]=="Epilepsie avec absences myocloniques"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'";
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }elseif($table6[$t]=="Epilepsie occipitale à début précoce(Syndrome de panayiotopoulos)"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%CBZ%'";
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }elseif($table6[$t]=="Epilepsie à pointes centro-temporale"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%CLB%'OR Abreviation LIKE '%CBZ%'OR Abreviation LIKE '%ESM%'
                        OR Abreviation LIKE '%GBP%'OR Abreviation LIKE '%STM%'OR Abreviation LIKE '%OXC%'
                        OR Abreviation LIKE '%LEV%'";
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }elseif($table6[$t]=="Epilepsie avec des crises myclonique-atomique"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%LTG%'OR Abreviation LIKE '%TPM%'OR Abreviation LIKE '%LVT%'";
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }$t++;
                }//syndrome les adolescentsadultes adolescentAdulte
                while($n<sizeof($table7)){
                    if($table6[$n]=="Absence juvéniles"){   
                     $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'OR Abreviation LIKE'%FBM%'
                     OR Abreviation LIKE'%ESM%' OR Abreviation LIKE'%LTG%'"; 
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        }   
                    }elseif($table7[$n]=="Epilepsie myoclonique juvénile(EMJ)"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%CLB%'OR Abreviation LIKE '%LTG%' OR Abreviation LIKE '%TPM%'
                        OR Abreviation LIKE '%LVT%'OR Abreviation LIKE '%BZD%'";  
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }elseif($table7[$n]=="Epilepsie avec crise TC généralisé"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'
                        OR Abreviation LIKE '%OXC%' OR Abreviation LIKE '%CBZ%'OR Abreviation LIKE '%PB%'
                        OR Abreviation LIKE '%ESM%'OR Abreviation LIKE '%TPM%'OR Abreviation LIKE '%FBM%'
                        OR Abreviation LIKE '%LTG%'";  
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }elseif($table7[$n]=="Autres épilepsies temporales familiales"){
                        $loginData="SELECT * FROM `gestionmedicamento` WHERE Abreviation LIKE'%VPA%'";
                        $data=mysqli_query($mysqli,$loginData);
                        while($recupeValeur=mysqli_fetch_array($data)){
                            $loginTable[]=array('id'=>$recupeValeur['id'],'Designation'=>$recupeValeur['Designation'],'Efficacite'=>$recupeValeur['Efficacite'],
                            'effectSecondaire'=>$recupeValeur['effectSecondaire'],'Formes'=>$recupeValeur['Formes'],'Prix'=>$recupeValeur['Prix'],
                            'Enfant'=>$recupeValeur['Enfant'],'Grossesse'=>$recupeValeur['Grossesse'],'Dose'=>$recupeValeur['Dose'],
                            'Interaction'=>$recupeValeur['Interaction'],'Disponibilite'=>$recupeValeur['Disponibilite'],'Abreviation'=>$recupeValeur['Abreviation'],
                            'Amm'=>$recupeValeur['Amm']);
                        }
                        if ($data) {
                            $resultLogin=json_encode( array('ok' => true, 'message' => $loginTable));   
                        }else{
                            $resultLogin=json_encode( array('ok' => false));
                        } 
                    }$n++;
                }
            }
                echo ($resultLogin);
        }
    }
?>
