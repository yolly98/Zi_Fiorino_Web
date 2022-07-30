<?php
    session_start();
    if(!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']==NULL)
        echo "<script type='text/javascript'>window.location.href='../index.php';</script>";
    
?>

<!DOCTYPE html>
<html lang='it'>
    
    <head>
        <meta charset='utf8'>
        <title>ZI_Fiorino(Backup)</title>
        <link rel="stylesheet" href="../style/backup.css">
        <link rel="stylesheet" href="../style/util.css">
        <link rel="icon" href="../immagini/zi_icon.ico">
        <script type='text/javascript' src='../util.js'></script>
        <!--<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php echo "<script type='text/javascript'>UTENTE='".$_SESSION['user']."';</script>"; ?>

    
    </head>


    <body onload='caricaB()'>
        <div id='wrapper'>
            <div id='titleDiv'>
                <label>ZI_FIORINO</label>
            </div>

            <button id='B_esci' onclick="esci()">ESCI</button>
            <button id='B_back' onclick="back()">INDIETRO</button>


            <label id='lT'>BACKUP</label>
            <div id='backupList'>
                <div id='B1' class='backup'>
                    <label class='lB'>VUOTO</label>
                    <button class='add' onclick=add(1)>NUOVO</button>
                    <button class='imp' onclick=importa(1)>IMPORTA</button>
                </div>
                <div id='B2' class='backup'>
                    <label class='lB'>VUOTO</label>
                    <button class='add' onclick=add(2)>NUOVO</button>
                    <button class='imp' onclick=importa(2)>IMPORTA</button>
                </div>
                <div id='B3' class='backup'>
                    <label class='lB'>VUOTO</label>
                    <button class='add' onclick=add(3)>NUOVO</button>
                    <button class='imp' onclick=importa(3)>IMPORTA</button>
                </div>
                <div id='B4' class='backup'>
                    <label class='lB'>VUOTO</label>
                    <button class='add' onclick=add(4)>NUOVO</button>
                    <button class='imp' onclick=importa(4)>IMPORTA</button>
                </div>
                <div id='B5' class='backup'>
                    <label class='lB'>VUOTO</label>
                    <button class='add' onclick=add(5)>NUOVO</button>
                    <button class='imp' onclick=importa(5)>IMPORTA</button>
                </div>
            </div>
        
        </div>

    </body>

    <script type='text/javascript'>

        sessionCheck();

        function esci(){
            window.location.href="../index.php";
        }

        /***************/

        function back(){
            window.location.href="./menu.php";
        }

        /****************/


        function caricaB(){
            let ajax = new XMLHttpRequest();
            let result;
            ajax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                   
                    result=JSON.parse(this.responseText);

                    let BACKUP=document.getElementsByClassName('lB');
                    for(let i=0;i<result.length;i+=2){
                        BACKUP[result[i]-1].textContent=result[i+1];
                    }
                    
                }
            };
            ajax.open("POST", "./data.php", true);
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.send("tipo=mostraBackup");
            return;
        }
        /****************/ 

        function add(n){
            mioConfirm("Vuoi sovrascrivere questo backup con uno nuovo?");
                CONFIRM_INTERVAL=setInterval(function(){
                    if(CONFIRM==null)
                        return;
                    else if(CONFIRM==true)
                    add2(n);

                    CONFIRM=null;
                    clearInterval(CONFIRM_INTERVAL);
                },1000);    
        }
        /*****************/

        function add2(n){

            let ajax = new XMLHttpRequest();
            let result;
            ajax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //mioAlert('salvateggio avvenuto con successo');
                    result=this.responseText
                    
                    if(result=='ok'){
                        mioAlert('creazione backup completata');
                        ALERT_INTERVAL=setInterval(function(){
                            if(ALERT==false)
                                return;
                            ALERT=false;
                            window.location.reload();
                            clearInterval(ALERT_INTERVAL);
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
            ajax.send("tipo=newBackup&idBackup="+n);
            return;
        }

        /******************/

        function importa(n){

            mioConfirm("ATTENZIONE! Tutti i dati saranno sovrascritti dal backup (i backup resteranno inalterati), continuare?");
            CONFIRM_INTERVAL=setInterval(function(){
                if(CONFIRM==null)
                    return;
                else if(CONFIRM==true)
                    importa2(n);

                CONFIRM=null;
                clearInterval(CONFIRM_INTERVAL);
            },1000);
        }


        /********************/

        function importa2(n){

            let ajax = new XMLHttpRequest();
            let result;
            ajax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //mioAlert('salvateggio avvenuto con successo');
                    result=this.responseText
                    
                    if(result=='ok'){
                        mioAlert('caricamento backup completato');
                        ALERT_INTERVAL=setInterval(function(){
                            if(ALERT==false)
                                return;
                            ALERT=false;
                            clearInterval(ALERT_INTERVAL);
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
            ajax.send("tipo=importaBackup&idBackup="+n);
            return;
        }
    </script>
</html>