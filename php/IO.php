<?php

//session_start();


function creaFileJSON($n){

    $conn=mysqli_connect('localHost','root','');
    if(!$conn){
            echo "connession al server fallita";
            return;
    }

    $sql="USE my_zifiorino;";
    if(!$conn->query($sql)){
        echo "connessione a zi_fiorino fallita";
        return;
    }

    $sql="SELECT * FROM siti WHERE id='".$_SESSION['user']."';";
    $result=$conn->query($sql);
    $array=array();
    while($row=$result->fetch_assoc()){
        array_push($array,
            array($row['nome_sito'],
                  $row['utente_sito'],
                  $row['passw_sito'],
                  $row['id'],
                  $row['note']
            )
        );
        
    }
    
    

    $file=fopen("../BACKUP/".$_SESSION['user']."/b".$n.".json","w") or die("backup fallito");
    fwrite($file,json_encode($array));
    fclose($file);

}


function leggiFileJSON($n){

    $fname="../BACKUP/".$_SESSION['user']."/b".$n.".json";
    $file=fopen($fname,'r') or die("import fallito");
    $data=fread($file,filesize($fname));
    fclose($file);

    return $data;
}



?>