<?php 
    session_start();
    if(!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']==NULL)
        echo "<script type='text/javascript'>window.location.href='../index.php';</script>";
   
?>

<!DOCTYPE html>
<html lang='it'>
    
    <head>
        <meta charset='utf8'>
        <title>ZI_Fiorino(Aggiungi)</title>
        <link rel="stylesheet" href="../style/aggiungi.css">
        <link rel="stylesheet" href="../style/util.css">
        <link rel="icon" href="../immagini/zi_icon.ico">
        <script type='text/javascript' src='../util.js'></script>
        <!--<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php echo "<script type='text/javascript'>UTENTE='".$_SESSION['user']."';</script>"; ?>
        
        
    </head>

    
    <body>
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

            <input id='I1' class='I'  type='text' onkeydown="keydown(0,event)" onkeyup="borderReset(1)"  maxlength="40">
            <input id='I2' class='I'  type='text' onkeydown="keydown(1,event)" onkeyup="borderReset(2)"  maxlength="40">
            <input id='I3' class='I'  type='password' onkeydown="keydown(2,event)" onkeyup="borderReset(3)"  maxlength="40">
            <div id='vediPassw' onclick="viewPassw()" >
                <span class="tooltiptext">mostra</span>
            </div>
            <textarea id='I4' class='I' rows="4" cols="50" maxlength="200" onkeydown="keydown(3,event)"></textarea>


            <label  id='R1' class='R' >(*)</label>
            <label  id='R2' class='R' >(*)</label>
            <label  id='R3' class='R' >(*)</label>

            <button id='B_esci' onclick="esci()">ESCI</button>
            <button id='B_back' onclick="back()">INDIETRO</button>
            <button id='B_aggiungi' onclick="aggiungi()">AGGIUNGI</button>
            
        
        </div>

    </body>

    <script type='text/javascript'>

        function keydown(index,ev){

            if(ev.keyCode==38){//freccia su
                index=(4+index-1)%4;
                document.getElementsByClassName("I")[index].select();
            }
            else if(ev.keyCode==40){//freccia giu
                index=(index+1)%4;
                document.getElementsByClassName("I")[index].select();
            }


        }

        sessionCheck();

        var Input=document.getElementsByClassName('I');

        /***************************/

        function esci(){
            window.location.href = "../index.php";
        }

        /***************************/

        function back(){
            window.location.href = "./menu.php";
        }

        /*******************/

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

        /***************************/

        function borderReset(index){
           
            if(Input[index-1].value.search(' ')!=-1)
                    Input[index-1].style.borderColor='maroon';
            else
                Input[index-1].style.borderColor='steelblue';
          
        }

        /******************************/

        function aggiungi(){
            let I1=Input[0].value;
            let I2=Input[1].value;
            let I3=Input[2].value;
            let I4=Input[3].value;
           
            if(I4==null || I4.length==0)
                I4="inesistente";

          
            if(I1=='' || I2=='' || I3==''){
                mioAlert('dati inseriti non validi');
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);
                if(I1=='')
                    Input[0].style.borderColor='maroon';
                if(I2=='')
                    Input[1].style.borderColor='maroon';
                if(I3=='')
                    Input[2].style.borderColor='maroon';
                    
                return;
               
            }
            else if(I1.search(' ')!=-1 || I2.search(' ')!=-1 || I3.search(' ')!=-1 /*|| I4.search(' ')!=-1*/){
                
               mioAlert("Non sono consentiti gli spazi");
               ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);
               if(I1.search(' ')!=-1)
                    Input[0].style.borderColor='maroon';
                if(I2.search(' ')!=-1)
                    Input[1].style.borderColor='maroon';
                if(I3.search(' ')!=-1)
                    Input[2].style.borderColor='maroon';
                /*if(I4.search(' ')!=-1)
                    Input[3].style.borderColor='maroon';*/
               return;
            }
            else if(I1.search('&')!=-1 || I1.search("'")!=-1 || I1.search('<')!=-1 || I1.search('>')!=-1 || I1.search('\"')!=-1 ||
                    I2.search('&')!=-1 || I2.search("'")!=-1 || I2.search('<')!=-1 || I2.search('>')!=-1 || I2.search('\"')!=-1 ||
                    I3.search('&')!=-1 || I3.search("'")!=-1 || I3.search('<')!=-1 || I3.search('>')!=-1 || I3.search('\"')!=-1){
                mioAlert("i seguenti caratteri non sono consentiti &'<>\"");
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);
                if(I1.search('&')!=-1 || I1.search("'")!=-1 || I1.search('<')!=-1 || I1.search('>')!=-1 || I1.search('\"')!=-1)
                    Input[0].style.borderColor='maroon';
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
                        //mioAlert('salvateggio avvenuto con successo');
                        result=this.responseText
                        
                        if(result=='ok'){
                            mioAlert('salvataggio dati avvenuto con successo');
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
                ajax.send("tipo=newSito&sito="+I1+"&utente_sito="+I2+"&passw_sito="+I3+"&note="+I4);
                
                for(let i=0;i<Input.length;i++)
                    Input[i].value='';
               
            }
            
        }

        /***************************/

    </script>
</html>