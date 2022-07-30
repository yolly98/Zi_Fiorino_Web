<?php
    session_start();
    if(!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']==NULL)
        echo "<script type='text/javascript'>window.location.href='../index.php';</script>";
?>

<!DOCTYPE html>
<html lang='it'>
    
    <head>
        <meta charset='utf8'>
        <title>ZI_Fiorino(Menu)</title>
        <link rel="stylesheet" href="../style/menu.css">
        <link rel="stylesheet" href="../style/util.css">
        <link rel="icon" href="../immagini/zi_icon.ico">
        <script type='text/javascript' src='../util.js'></script>
        <!--<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php echo "<script type='text/javascript'>UTENTE='".$_SESSION['user']."';</script>"; ?>
        
    </head>

    
    
    <body onload='passwUpdater()'>
        <div id='wrapper'>
            <div id='titleDiv'>
                <label>ZI_FIORINO</label>
            </div>

            <button id='B_esci' onclick="esci()">ESCI</button>
            <button id='B_cambiaPassw' onclick="cambiaPassw()">CAMBIA PASSWORD</button>
            <button id='B_cerca' onclick="cerca()">CERCA</button>
            <button id='B_aggiungi' onclick="aggiungi()">AGGIUNGI</button>
            <button id='B_info' onclick="info()">INFO</button>
            <button id='B_backup' onclick="backup()">BACKUP</button>

            <img id='imgLente' src="../immagini/cerca.png">
            <img id='imgLibro' src="../immagini/aggiungi.png">

            <div id='D_cambiaPassw' style='display:none'>
                <p style='width:100%; height:1vw;'></p>
                <label>Nuova Password</label>
                <p style='width:100%; height:1vw;'></p>
                <input class='CP' id='P1' type='password' maxlength="15" onkeydown="keydown(0,event)">
                <p style='width:100%; height:1.5vw;'></p>
                <label>Ripeti Password</label>
                <p style='width:100%; height:1vw;'></p>
                <input class='CP' id='P2' type='password' maxlength="15" onkeydown="keydown(1,event)">
                <p style='width:100%; height:1.5vw;'></p>
                <button onclick='backPassw()'>Indietro</button>
                <button onclick='newPassw()'>Ok</button>
            </div>
        
        </div>

    </body>

    <script type='text/javascript'>

        sessionCheck();

        function keydown(index,ev){

            if(ev.keyCode==38){//freccia su
                index=(2+index-1)%2;
                document.getElementsByClassName("CP")[index].select();
            }
            else if(ev.keyCode==40){//freccia giu
                index=(index+1)%2;
                document.getElementsByClassName("CP")[index].select();
            }

        }

        /***************************/
        
        function esci(){
            window.location.href = "../index.php";
        }

        /**************************/

        function backup(){
            window.location.href = "./backup.php";
        }
        
        /***************************/

        function cambiaPassw(){
            document.getElementById('D_cambiaPassw').style.display='block';
            let b=document.getElementsByTagName('button');
            for(let i=0;i<b.length-2;i++)
                b[i].disabled=true;

        
        }

        /***************************/

        function newPassw(){
            let P1=document.getElementById('P1');
            let P2=document.getElementById('P2');
            if(P1.value!=P2.value || P1.value=='' || P1.value==''){
                mioAlert('dati inseriti incompatibili');
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);
                P1.value='';
                P2.value='';
            }
            else if(P1.value.search(' ')!=-1 || P2.value.search(' ')!=-1){
                mioAlert('gli spazi non sono consentiti');
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);
                P1.value='';
                P2.value='';
            } 
            else if(P1.value.search('&')!=-1 || P1.value.search("'")!=-1 || P1.value.search('<')!=-1 || P1.value.search('>')!=-1 || P1.value.search('\"')!=-1 ||
                P2.value.search('&')!=-1 || P2.value.search("'")!=-1 || P2.value.search('<')!=-1 || P2.value.search('>')!=-1 || P2.value.search('\"')!=-1){
                mioAlert("i seguenti caratteri non sono consentiti &'<>\"");
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);
                P1.value='';
                P2.value='';

            } 
            else if(P1.value==P1.value){

                let ajax = new XMLHttpRequest();
                let result;
                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        //mioAlert('salvateggio avvenuto con successo');
                        result=this.responseText
                        P1.value='';
                        P2.value='';
                        if(result=='ok'){
                            mioAlert('modifica avvenuta con successo');
                            ALERT_INTERVAL=setInterval(function(){
                                if(ALERT==false)
                                    return;

                                ALERT=false;
                                clearInterval(ALERT_INTERVAL);
                                backPassw();
                            },1000);
                        }
                        else{
                           mioAlert(result);
                           ALERT_INTERVAL=setInterval(function(){
                                if(ALERT==false)
                                    return;
                                ALERT=false;
                                clearInterval(ALERT_INTERVAL);
                            },1000);
                        }
                    }
                };
                ajax.open("POST", "./data.php", true);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send("tipo=newPassw&newPassw="+P1.value);
                
            }

        }

        /***************************/

        function backPassw(){
            document.getElementById('P1').value=='';
            document.getElementById('P2').value=='';
            document.getElementById('D_cambiaPassw').style.display='none';
            let b=document.getElementsByTagName('button');
            for(let i=0;i<b.length-2;i++)
                b[i].disabled=false;
        }

        /***************************/

        function cerca(){
            window.location.href = "./cerca.php";
        }

        /***************************/

        function aggiungi(){
            window.location.href = "./aggiungi.php";
        }

        /***************************/
        
        function info(){
            window.location.href = "./info.php";
        }

        /***************/

        function passwUpdater(){

            let ajax = new XMLHttpRequest();
            let result;
            ajax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    
                    result=this.responseText;

                    if(result!="passwOk"){
                        mioAlert("Per una maggiore sicurezza consigliamo di cambiare la password di accsso almeno ogni 6 mesi");
                        ALERT_INTERVAL=setInterval(function(){
                            if(ALERT==false)
                                return;
                            ALERT=false;
                            clearInterval(ALERT_INTERVAL);
                        },1000);
                                }
                }
            };
            ajax.open("POST", "./data.php", true);
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.send("tipo=passwUpdater");


        }
    </script>
</html>