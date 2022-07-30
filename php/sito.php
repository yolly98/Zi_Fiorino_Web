<?php

    session_start();

    if(!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']==NULL)
        echo "<script type='text/javascript'>window.location.href='../index.php';</script>";
  
    if(!isset($_SESSION['sito']))
        echo "<script type='text/javascript'>mioAlert('errore di caricamento');back();</script>";

?>
<!DOCTYPE html>
<html lang='it'>
    
    <head>
        <meta charset='utf8'>
        <title>ZI_Fiorino(Sito)</title>
        <link rel="stylesheet" href="../style/sito.css">
        <link rel="stylesheet" href="../style/util.css">
        <link rel="icon" href="../immagini/zi_icon.ico">
        <script type='text/javascript' src='../util.js'></script>
        <!--<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php echo "<script type='text/javascript'>UTENTE='".$_SESSION['user']."';</script>"; ?>
        
    </head>

    <body onload='carica()'>
        <div id='wrapper'>
            <div id='titleDiv'>
                <label>ZI_FIORINO</label>
            </div>

            <img id='img' src="../immagini/dati.jpg">
            <img id='imgM' src="../immagini/datiM.png">



            <label id='D1' class='D' >SITO:</label>
            <label id='D2' class='D' >USERNAME:</label>
            <label id='D3' class='D' >PASSWORD:</label>
            <label id='D4' class='D' >NOTE:</label>

            <input id='I1' class='I' type='text' readonly maxlength="40">
            <input id='I2' class='I' type='text' onkeydown="keydown(0,event)" onkeyup="borderReset(2)" readonly maxlength="40">
            <input id='I3' class='I' type='password' onkeydown="keydown(1,event)" onkeyup="borderReset(3)"readonly maxlength="40">
            <div id='vediPassw' onclick="viewPassw()">
                <span class="tooltiptext">mostra</span>
            </div>
            <textarea id='I4' class='I' rows="4" cols="50"  onkeydown="keydown(2,event)" readonly></textarea>


            <div id='B_copy' onclick="copiaPassword()" onmouseout="outFunc()">
                <span class="tooltiptext" id='tooltipCopia'>copia password</span>
            </div>

            <button id='B_esci' onclick="esci()">ESCI</button>
            <button id='B_back' onclick="back()">INDIETRO</button>
            <button id='B_aggiorna' onclick="aggiorna()">AGGIORNA</button>
            <button id='B_elimina' onclick="elimina()">ELIMINA</button>
            <button id='B_aggiornaSito' onclick="aggiornaSito()" style='display:none;'>AGGIORNA SITO</button>
            
        
        </div>

    </body>

    <script type='text/javascript'>

        function keydown(index,ev){

            if(ev.keyCode==38){//freccia su
                index=(3+index-1)%3+1;
                document.getElementsByClassName("I")[index].select();
            }
            else if(ev.keyCode==40){//freccia giu
                index=(index+1)%3+1;
                document.getElementsByClassName("I")[index].select();
            }

        }

        sessionCheck();

        var Input=document.getElementsByClassName('I');
       
        function carica(){

            let ajax = new XMLHttpRequest();
            let result;
            ajax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                            
                    result=this.responseText
                    //console.log(result);
                    if(result=='fail'){
                        mioAlert('caricamento fallito o nessun sito salvato');
                        ALERT_INTERVAL=setInterval(function(){
                            if(ALERT==false)
                                return;
                            ALERT=false;
                            clearInterval(ALERT_INTERVAL);
                        },1000);
                    }
                    
                    else{
                        result=JSON.parse(result);
                        Input[0].value=result[0];
                        Input[1].value=result[1];
                        Input[2].value=result[2];
                        Input[3].value=result[3];

                    }
                }
                    
            };
            ajax.open("POST", "./data.php", true);
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.send("tipo=mostraSito");
        }
        /***************************/

        function esci(){
            window.location.href = "../index.php";
        }

        /***************************/

        function back(){
            window.location.href = "./cerca.php";
        }

        /***************************/

        function elimina(){

           mioConfirm('eliminare il sito?');
            

            CONFIRM_INTERVAL=setInterval(function(){

                if(CONFIRM==null)
                    return;
                else if(CONFIRM==false){
                    clearInterval(CONFIRM_INTERVAL);
                    CONFIRM=null;
                    return;
                }


                let ajax = new XMLHttpRequest();
                let result;
                ajax.onreadystatechange = function(){
                    if (this.readyState == 4 && this.status == 200) {
                                    
                        result=this.responseText
                                    
                        if(result!='ok'){
                            mioAlert(result);
                            ALERT_INTERVAL=setInterval(function(){
                                if(ALERT==false)
                                    return;
                                
                                ALERT=false;
                                clearInterval(ALERT_INTERVAL);
                                
                            },1000);
                        }
                            
                        else{
                            mioAlert('salvataggio dati avvenuto con successo');
                        // window.location.reload();
                            ALERT_INTERVAL=setInterval(function(){
                                if(ALERT==false)
                                    return;
                                
                                ALERT=false;
                                clearInterval(ALERT_INTERVAL);
                                window.location.href='./cerca.php';
                            },1000);
                        }
                       
                    }
                };
                ajax.open("POST", "./data.php", true);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send("tipo=elimina&sito="+Input[0].value);

                CONFIRM=null;
                clearInterval(CONFIRM_INTERVAL);


            },1000);

          
                
            
        }

        /***************************/


        function viewPassw(){
            if(Input[2].type=='text'){
                Input[2].type='password';
                document.getElementById("vediPassw").style="border:0vw solid black;";
            }
            else{
                Input[2].type='text';
                document.getElementById("vediPassw").style="border:0.2vw solid black;";
            }

            
        }


         /*******************/

        function borderReset(index){

            if(Input[index-1].value.search(' ')!=-1)
                    Input[index-1].style.borderColor='maroon';
            else
                Input[index-1].style.borderColor='steelblue';
        }

        /****************************/
        function aggiorna(){


            for(let i=1;i<Input.length;i++){
                Input[i].readOnly=false;
                Input[i].style.backgroundColor='white';
              
            }

            document.getElementById('B_aggiorna').style.display='none';
            document.getElementById('B_aggiornaSito').style.display='block';
            document.getElementById('B_elimina').style.display='none';
            document.getElementById('B_back').onclick=function(){location.reload();}
        }

        /***************************/

        function aggiornaSito(){
           
            let I2=Input[1].value;
            let I3=Input[2].value;
            let I4=Input[3].value;

            if(I4==null || I4.length==0)
                I4="inesistente";

          
            if(I2=='' || I3==''){
                mioAlert('dati inseriti non validi');
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);
                if(I2=='')
                    Input[1].style.borderColor='maroon';
                if(I3=='')
                    Input[2].style.borderColor='maroon';
                
                return;
               
            }
            else if(I2.search(' ')!=-1 || I3.search(' ')!=-1 /*|| I4.search(' ')!=-1*/){
                
               mioAlert("Non sono consentiti gli spazi");
               ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);
               
                if(I2.search(' ')!=-1)
                    Input[1].style.borderColor='maroon';
                if(I3.search(' ')!=-1)
                    Input[2].style.borderColor='maroon';
                /*if(I4.search(' ')!=-1)
                    Input[3].style.borderColor='maroon';*/
               return;
            }
            else if(I2.search('&')!=-1 || I2.search("'")!=-1 || I2.search('<')!=-1 || I2.search('>')!=-1 || I2.search('\"')!=-1 ||
                    I3.search('&')!=-1 || I3.search("'")!=-1 || I3.search('<')!=-1 || I3.search('>')!=-1 || I3.search('\"')!=-1){
                mioAlert("i seguenti caratteri non sono consentiti &'<>\"");
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);
                if(I2.search('&')!=-1 || I2.search("'")!=-1 || I2.search('<')!=-1 || I2.search('>')!=-1 || I2.search('\"')!=-1)
                    Input[1].style.borderColor='maroon';
                if(I3.search('&')!=-1 || I3.search("'")!=-1 || I3.search('<')!=-1 || I3.search('>')!=-1 || I3.search('\"')!=-1)
                    Input[2].style.borderColor='maroon';
                /*if(I4.search(' ')!=-1)
                    Input[3].style.borderColor='maroon';*/
               return;
            } 
            else{

                let ajax = new XMLHttpRequest();
                let result;
                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                                
                        result=this.responseText
                                
                        if(result!='ok'){
                            mioAlert(result);
                            ALERT_INTERVAL=setInterval(function(){
                                if(ALERT==false)
                                    return;
                                ALERT=false;
                                clearInterval(ALERT_INTERVAL);
                            },1000);
                        }
                        
                        else{
                            mioAlert('salvataggio dati avvenuto con successo');
                            ALERT_INTERVAL=setInterval(function(){
                                if(ALERT==false)
                                    return;
                                ALERT=false;
                                clearInterval(ALERT_INTERVAL);
                                window.location.reload();
                            },1000);
                        }
                    }
                };
                ajax.open("POST", "./data.php", true);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send("tipo=aggiornaSito&user="+I2+"&passw="+I3+"&note="+I4);
                
            }
            
        }

         /***************************/

         function copiaPassword(){

            let inputPassw=document.getElementById('I3');
            let tooltip=document.getElementById("tooltipCopia");

            inputPassw.type='text';
            inputPassw.select();
            inputPassw.setSelectionRange(0,99999);//serve per mobile
            document.execCommand("copy");
            inputPassw.type='password';

            tooltip.textContent="copiata";
        }

        /***************************/

        function outFunc(){
            let tooltip=document.getElementById("tooltipCopia");
            tooltip.textContent="copia password";
        }
    </script>
</html>