<?php

require "./cripting.php";
require "./IO.php";
session_start();

$user=$passw="";
$userErr=$passwErr="";

/*DB configuration*/
$addressDB="172.17.0.3";    //altervista "localHost"
$userDB="root";             //altervista "root"
$passwDB="password";        //altervista ""
$nameDB="zi_fiorinoDB";     //altervista "my_zifiorino"


/****************REGISTRAZIONE*****************/
if($_POST['tipo']=='reg'){

    $user=test($_POST['user']);
    $passw=test($_POST['passw']);


    $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "connessione fallita:".$conn->connect_error;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "connessione non riuscita a zi_fiorino";
    }

    $sql="SELECT*FROM utenti WHERE utente LIKE BINARY ?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$user);
    $stmt->execute();
    $result=$stmt->get_result();
   
    if($result->num_rows==0){

        $sql="INSERT into utenti(utente,passw,ultimaPassw) values(?,?,CURRENT_DATE)";
        $stmt=$conn->prepare($sql);
        $passw=mescola(md5($passw),$passw);
        if($passw==false){
            echo "errore generico, riprovare";
            return;
        }
        $passw=stringToBinary($passw);
        $stmt->bind_param("ss",$user,$passw);
        $stmt->execute();

        echo 'ok';
        mkdir("../BACKUP/".$user);  //creo cartella dei backup
    }
    else{
        echo "nome utente non disponibile";
    }


    $conn->close();


}
/*********************LOGIN**********************/
else if($_POST['tipo']=='login'){

    $user=test($_POST['user']);
    $passw=test($_POST['passw']);

   $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "connessione fallita:".$conn->connect_error;
            return;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "connessione non riuscita a zi_fiorino";
        return;
    }

    $passwMD5=mescola(md5($passw),$passw);
    if($passwMD5==false){
        echo "errore generico, riprovare";
        return;
    }

    $sql="SELECT*FROM utenti WHERE utente LIKE BINARY ? and passw LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $sendPassw=stringToBinary($passwMD5);
    $stmt->bind_param("ss",$user,$sendPassw);
    $stmt->execute();
    $result=$stmt->get_result();
   
    if($result->num_rows>0){

        $_SESSION['user']=$user;
        $c_value = $passw;
        $chiave='';
        for($j=0;$j<15;$j++){
            $charORint=rand(0,1);
            if($charORint==0){
                $num=rand(0,9);
                $chiave.=chr(ord('0')+$num);
            }
            else{
                $lettera=rand(0,25);
                $chiave.=chr(ord('a')+$lettera);

            }

        }
        $passwCrypted=cripta($c_value,$chiave);
        if($passwCrypted==false){
            echo "errore generico, riprovare";
            return;
        }
        

        $_SESSION["passwAccesso"]=stringToBinary($passwCrypted);
        $_SESSION["chiave"]=stringToBinary($chiave);
        $_SESSION["sessione"]=time();
        
        echo 'user:'.$user;
       
    }
    else{
         echo "nome utente e password inesisitenti";
    }

    $conn->close();


}
/*************CAMBIARE PASSW**************************/
else if($_POST['tipo']=='newPassw'){

    if(!isset($_SESSION['user'])){
        echo 'sessione persa';
        return;
    }

    $user=$_SESSION['user'];
    $passw=test($_POST['newPassw']);
    

    $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "connessione fallita:".$conn->connect_error;
            return;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "connessione non riuscita a zi_fiorino";
        return;
    }

    $passwMD5=mescola(md5($passw),$passw);
    if($passwMD5==false){
        echo "errore generico, riprovare";
        return;
    }

    $sql="UPDATE utenti set passw=?,ultimaPassw=CURRENT_DATE WHERE utente LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $sendPassw=stringToBinary($passwMD5);
    $stmt->bind_param("ss",$sendPassw,$user);
    $stmt->execute();
    $result=$stmt->get_result();

    $oldPassw=decripta(binaryToString($_SESSION['passwAccesso']),binaryToString($_SESSION["chiave"]));
    
    if($oldPassw==false){
        echo "errore generico, riprovare";
        return;
    }

    $c_value = $passw;
    $chiave='';
    for($j=0;$j<15;$j++){
        $charORint=rand(0,1);
        if($charORint==0){
            $num=rand(0,9);
            $chiave.=chr(ord('0')+$num);
        }
        else{
            $lettera=rand(0,25);
            $chiave.=chr(ord('a')+$lettera);

        }

    }
    $strCrypted=cripta($c_value,$chiave);
    if($strCrypted==false){
        echo "errore generico, riprovare";
        return;
    }
    

    $_SESSION["passwAccesso"]=stringToBinary($strCrypted);
    $_SESSION["chiave"]=stringToBinary($chiave);
    $_SESSION["sessione"]=0;

    //devo aggiornare tutti i siti perchè ho cambiato la chiave di cifratura

    $sql="SELECT* FROM siti WHERE id LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$user);
    $stmt->execute();
    $result=$stmt->get_result();
    
    if($result->num_rows>0)
        while($row=$result->fetch_assoc()){
             $sito=$row['nome_sito'];
             $utente_sito=decripta(binaryToString($row['utente_sito']),$oldPassw);//oldPssw
             $passw_sito=decripta(binaryToString($row['passw_sito']),$oldPassw);
             //$note=decripta(binaryToString($row['note']),$oldPassw);
             
            $userCrypted=cripta($utente_sito,$passw);
            $passwCrypted=cripta( $passw_sito,$passw);
            if($userCrypted==false ||  $passwCrypted==false || $utente_sito==false || $passw_sito==false){
                echo "errore generico, riprovare";
                return;
            }
             $utente_sito=stringToBinary($userCrypted);
             $passw_sito=stringToBinary($passwCrypted);
             //$note=stringToBinary(cripta($note,$passw));

             
            $sql1="UPDATE siti 
                set utente_sito=?,
                    passw_sito=?
                where id LIKE BINARY ? and nome_sito LIKE BINARY ?;";
            $stmt=$conn->prepare($sql1);
            $stmt->bind_param("ssss",$utente_sito,$passw_sito,$user,$sito);
            $stmt->execute();
            $result1=$stmt->get_result();
            
        }

    //devo aggiorna tutti i beckup dell'utente perchè ho cambiato la passw di accesso

    $sql="SELECT* FROM backupDati WHERE utente LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$user);
    $stmt->execute();
    $result=$stmt->get_result();
    
    while($row=$result->fetch_assoc()){
        $Bpassw=decripta(binaryToString($row['PBackup']),$oldPassw);
        $Bpassw=stringToBinary(cripta($Bpassw,$passw));
        $sql="UPDATE backupDati SET PBackup=? WHERE utente LIKE BINARY ? and idBackup=?;";
        $stmt=$conn->prepare($sql);
        $sendIdBackup=$row['idBackup'];
        $stmt->bind_param("ssi",$Bpassw,$user,$sendIdBackup);
        $stmt->execute();
        
    }
        
  
   echo 'ok';

    $conn->close();

}
/***************AGGIUNTA SITO*************************/
else if($_POST['tipo']=='newSito'){

    if(!isset($_SESSION['user'])){
        echo 'sessione persa';
        return;
    }

    $sito=test($_POST["sito"]);
    $user=test($_POST["utente_sito"]);
    $passw=test($_POST["passw_sito"]);
    $note=test($_POST["note"]);

   // $sito=$_POST['sito'];
    $passwAccesso=decripta(binaryToString($_SESSION['passwAccesso']),binaryToString($_SESSION["chiave"]));
    $userCrypted=cripta($user,$passwAccesso);
    $passwCrypted=cripta($passw,$passwAccesso);
    if($passwAccesso==false || $userCrypted==false ||  $passwCrypted==false){
        echo "errore generico, riprovare";
        return;
    }
    $user=stringToBinary($userCrypted);
    $passw=stringToBinary($passwCrypted);
   // $note=stringToBinary($note);
    $id=$_SESSION['user'];
    

    $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "connessione fallita:".$conn->connect_error;
            return;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "connessione non riuscita a zi_fiorino";
        return;
    }

    $sql="SELECT nome_sito FROM siti WHERE id LIKE BINARY ? and nome_sito LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ss",$id,$user);
    $stmt->execute();
    $test=$stmt->get_result();
    
    if($test->num_rows>0){
        echo "sito già esistente";
        return;
    }

    

    $sql="INSERT into siti(nome_sito,utente_sito,passw_sito,note,id) value(?,?,?,?,?)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssss',$sito,$user,$passw,$note,$id);
    $stmt->execute();
    
    
    echo 'ok';
    

    $conn->close();

}

/********************CARICA DATI DA DB***************************/
else if($_POST['tipo']=='caricaDati'){

    if(!isset($_SESSION['user'])){
        echo 'fail';
        return;
    }

    $user=$_SESSION['user'];

    $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "fail";
            return;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "fail";
        return;
    }

    $sql="SELECT nome_sito FROM siti where id LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$user);
    $stmt->execute();
    $result=$stmt->get_result();
    
    if($result->num_rows==0){
         echo "fail";
         return;
    }
    else{
        
        while($row=$result->fetch_assoc()){
            echo $row['nome_sito'].' ';
        }

        
    }

    
    

    $conn->close();

}
/********************ELIMINA SITO***********************/
else if($_POST['tipo']=='elimina'){


    if(!isset($_SESSION['user'])){
        echo 'sessione persa';
        return;
    }

    $sito=$_POST['sito'];
    $user=$_SESSION['user'];

    $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "connessione al server fallita";
            return;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "connessione a zi_fiorino fallita";
        return;
    }

    $sql="DELETE from siti where id LIKE BINARY ? and nome_sito LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ss",$user,$sito);
    $stmt->execute();
    $result=$stmt->get_result();
    
    echo 'ok';

    $conn->close();
    
}
/***************APRI SITO SELEZIONATO*************/
else if($_POST['tipo']=='apri'){

    if(!isset($_SESSION['user'])){
        echo 'sessione persa';
        return;
    }

    $_SESSION['sito']=$_POST['sito'];
    echo 'ok';    

}
/***************CARICA DATI IN php/sito.php******************/
else if($_POST['tipo']=='mostraSito'){

    if(!isset($_SESSION['user']) || !isset($_SESSION['sito'])){
        echo 'fail';
        return;
    }

    $user=$_SESSION['user'];
    $sito=$_SESSION['sito'];

    $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "fail";
            return;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "fail";
        return;
    }

    $sql="SELECT* FROM siti where id LIKE BINARY ? and nome_sito LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ss",$user,$sito);
    $stmt->execute();
    $result=$stmt->get_result();
    
    if($result->num_rows==0){
         echo "fail";
         return;
    }
    else{
        
        $arrayData=array();
        $passwAccesso=decripta(binaryToString($_SESSION['passwAccesso']),binaryToString($_SESSION["chiave"]));

       if($passwAccesso==false){
            echo "fail";
            return;
        }
        
        while($row=$result->fetch_assoc()){
            $nome_sito=$row['nome_sito'];
            $utente_sito=decripta(binaryToString($row['utente_sito']),$passwAccesso);
            $passw_sito=decripta(binaryToString($row['passw_sito']),$passwAccesso);
            $note=$row['note'];

           if($passw_sito==false || $utente_sito==false){
                echo "fail";
                return;
            }

            array_push($arrayData,$nome_sito,$utente_sito,$passw_sito,$note);
            echo json_encode($arrayData);
           
        }

        
    }
   
}
/**************AGGIORNA SITO**************/
else if($_POST['tipo']=='aggiornaSito'){

    if(!isset($_SESSION['user']) || !isset($_SESSION['sito'])){
        echo 'sessione persa';
        return;
    }

    $user=$_SESSION['user'];
    $sito=$_SESSION['sito'];
    $utente_sito=test($_POST['user']);
    $passw_sito=test($_POST['passw']);
    $note=test($_POST['note']); 

    $passwAccesso=decripta(binaryToString($_SESSION['passwAccesso']),binaryToString($_SESSION["chiave"]));
    $userCrypted=cripta($utente_sito,$passwAccesso);
    $passwCrypted=cripta($passw_sito,$passwAccesso);
    if($passwAccesso==false || $userCrypted==false ||  $passwCrypted==false){
        echo "errore generico, riprovare";
        return;
    }

    $utente_sito=stringToBinary($userCrypted);
    $passw_sito=stringToBinary($passwCrypted);
    //$note=stringToBinary($note);
    

    $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "connession al server fallita";
            return;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "connessione a zi_fiorino fallita";
        return;
    }

    $sql="UPDATE siti 
        set utente_sito=?,
            passw_sito=?,
            note=?
        where id LIKE BINARY ? and nome_sito LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssss',$utente_sito,$passw_sito,$note,$user,$sito);
    $stmt->execute();
    $result=$stmt->get_result();

    
   echo 'ok';
}
/**************passw troppo vecchia***********/
else if($_POST['tipo']=='passwUpdater'){

    $user=$_SESSION['user'];

    $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "connession al server fallita";
            return;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "connessione a zi_fiorino fallita";
        return;
    }

    $sql="SELECT floor(datediff(CURRENT_DATE,ultimaPassw)/30) as d FROM utenti WHERE utente LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$user);
    $stmt->execute();
    $result=$stmt->get_result();
    
    $result=$result->fetch_assoc();
    if($result['d']<6){

        echo "passwOk";
        return;
    }
    
    $sql="UPDATE utenti SET ultimaPassw=ultimaPassw+interval 3 month WHERE utente LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$user);
    $stmt->execute();
    $result=$stmt->get_result();
    echo "passwVecchia";
    return;

}
/***************CANCELLA SESSIONE**************/
else if($_POST['tipo']=='cancellaSessione'){

    if(isset($_SESSION['sessione']) && time()<($_SESSION['sessione']+60*15)){ //la session dura 15 minuti
    	echo "ok";
        return;
    }
    unset($_SESSION['passwAccesso']);
    unset($_SESSION['chiave']);
    unset($_SESSION["sessione"]);
    echo "sessioneScaduta";
}
/************CREAZIONE BACKUP*****************/
else if($_POST['tipo']=='newBackup'){

    if(!isset($_SESSION['user'])){
        echo 'sessione persa';
        return;
    }
    
    $user=$_SESSION['user'];
    $idB=$_POST['idBackup'];

    $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "connession al server fallita";
            return;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "connessione a zi_fiorino fallita";
        return;
    }


    $sql="SELECT * FROM backupDati WHERE utente LIKE BINARY ? and idBackup=?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("si",$user,$idB);
    $stmt->execute();
    $result=$stmt->get_result();
    $passw=decripta(binaryToString($_SESSION['passwAccesso']),binaryToString($_SESSION['chiave']));
    $passw=stringToBinary(cripta($passw,$passw));

    if($result->fetch_assoc()){
        unlink("../BACKUP/".$user."/b".$idB.".json");
        $sql="UPDATE backupDati SET dateBackup=CURRENT_TIMESTAMP,PBackup=? WHERE utente LIKE BINARY ? and idBackup=?;";
        $stmt=$conn->prepare($sql);
        $stmt->bind_param("ssi",$passw,$user,$idB);
        $stmt->execute();
    }
    else{
        $sql="INSERT INTO backupDati(utente,idBackup,PBackup,datebackup) VALUES(?,".$idB.",?,CURRENT_TIMESTAMP);";
        $stmt=$conn->prepare($sql);
        $stmt->bind_param('ss',$user,$passw);
        $stmt->execute();

    }

    creaFileJSON($idB);
    echo 'ok';
    $conn->close();
        
    
}

/***************MOSTRA BACKUP********************/

else if($_POST['tipo']=='mostraBackup'){

    $user=$_SESSION['user'];
    

    $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "connession al server fallita";
            return;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "connessione a zi_fiorino fallita";
        return;
    }

    $sql="SELECT dateBackup as db, idBackup as ib FROM backupDati WHERE utente LIKE BINARY ? order by idBackup;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$user);
    $stmt->execute();
    $result=$stmt->get_result();
    $b=array();
    while($row=$result->fetch_assoc()){
        array_push($b,$row['ib']);
    array_push($b,date_format(date_create($row['db']),'d/m/Y H:i:s'));
    }

    $myJSON = json_encode($b);
    echo $myJSON;

}

/****************IMPORTA BACKUP*****************/

else if($_POST['tipo']=='importaBackup'){

    $user=$_SESSION['user'];
    $idB=$_POST['idBackup'];
    $passw=decripta(binaryToString($_SESSION['passwAccesso']),binaryToString($_SESSION['chiave']));

    $data=leggiFileJSON($idB);
    $data=json_decode($data);

    $conn=mysqli_connect($addressDB,$userDB,$passwDB);
    if(!$conn){
            echo "connessione al server fallita";
            return;
    }

    $sql="USE ".$nameDB.";";
    if(!$conn->query($sql)){
        echo "connessione a zi_fiorino fallita";
        return;
    }

    $sql="DELETE FROM siti WHERE id LIKE BINARY ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$user);
    $stmt->execute();
    
    $sql="SELECT PBackup FROM backupDati WHERE utente LIKE BINARY ? and idBackup=?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("si",$user,$idB);
    $stmt->execute();
    $Bpassw=$stmt->get_result();
    $Bpassw=$Bpassw->fetch_assoc();
    $Bpassw=decripta(binaryToString($Bpassw["PBackup"]),$passw);
    
    for($i=0;$i<count($data);$i++){

        $nome_sito=$data[$i][0];
        $utente_sito=decripta(binaryToString($data[$i][1]),$Bpassw);
        $passw_sito=decripta(binaryToString($data[$i][2]),$Bpassw);
        $utente_sito=stringToBinary(cripta($utente_sito,$passw));
        $passw_sito=stringToBinary(cripta($passw_sito,$passw));
        $id=$data[$i][3];
        $note=$data[$i][4];

        $sql="INSERT into siti(nome_sito,utente_sito,passw_sito,id,note) value(?,?,?,?,?)";
        $stmt=$conn->prepare($sql);
        $stmt->bind_param('sssss',$nome_sito,$utente_sito,$passw_sito,$id,$note);
        $stmt->execute();

    }

    echo 'ok';

}

/**********************************/
else
    echo 'connessione fallita';




/*************************************/
function test($data){

    $data=trim($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return $data;
}


/************************************/

function stringToBinary($string)
{
    $characters = str_split($string);
 
    $binary = [];
    foreach ($characters as $character) {
        $data = unpack('H*', $character);
        $binary[] = base_convert($data[1], 16, 2);
    }
 
    return implode(' ', $binary);    
}
 
function binaryToString($binary)
{
    $binaries = explode(' ', $binary);
 
    $string = null;
    foreach ($binaries as $binary) {
        $string .= pack('H*', dechex(bindec($binary)));
    }
 
    return $string;    
}



?>