<?php
    session_start();
   
    if(!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']==NULL)
        echo "<script type='text/javascript'>window.location.href='../index.php';</script>";

    if(isset($_SESSION['sito']))
        unset($_SESSION['sito']);
?>

<!DOCTYPE html>
<html lang='it'>
    
    <head>
        <meta charset='utf8'>
        <title>ZI_Fiorino(Cerca)</title>
        <link rel="stylesheet" href="../style/cerca.css">
        <link rel="stylesheet" href="../style/util.css">
        <link rel="icon" href="../immagini/zi_icon.ico">
        <script type='text/javascript' src='../util.js'></script>
        <!--<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php echo "<script type='text/javascript'>UTENTE='".$_SESSION['user']."';</script>"; ?>
        
        
    </head>


    <body onload='createList()'>
        <div id='wrapper'>
            <div id='titleDiv'>
                <label>ZI_FIORINO</label>
            </div>

            <img id='img' src="../immagini/indice.jpg">
            

            <label id='h'>INDICE</label>

            <div id='list'>

            </div>

          

            <button id='B_esci' onclick="esci()">ESCI</button>
            <button id='B_back' onclick="back()">INDIETRO</button>
            <button id='B_cerca' disabled>CERCA</button>
            <button id='B_ricarica' onclick="ricarica(null)">RICARICA</button>
            <button id='B_seleziona' onclick="seleziona()">SELEZIONA</button>
            <button id='B_elimina' onclick="elimina()">ELIMINA</button>
            <input id='I_cerca' type='text' onkeyup="ordina()">
            
        
        </div>

    </body>

    <script type='text/javascript'>

        sessionCheck();

        var LIST=new Array();
        var LIST2=new Array();
        /***************************/

        function createList(){

            let L=document.getElementById('list');

            let ajax = new XMLHttpRequest();
            let result;
            ajax.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200) {
                        
                    result=this.responseText
                        
                   if(result=='fail'){
                       mioAlert('lista vuota');
                       ALERT_INTERVAL=setInterval(function(){
                            if(ALERT==false)
                                return;
                            ALERT=false;
                            clearInterval(ALERT_INTERVAL);
                        },1000);
                   }
                    else{
                        
                        result=result.split(' ');  
                        result.pop();
                        result.sort();
                        for(let i=0;i<result.length;i++){
                            let a=document.createElement('button');
                            a.setAttribute('class','passw');
                            a.textContent=String(result[i]);
                            a.onclick=function(){open(a.textContent);};
                            LIST.push(a.textContent);
                            L.appendChild(a);
                        }
                    }

                }
            };
            ajax.open("POST", "./data.php", true);
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.send("tipo=caricaDati");

        }
        /***************************/


        function ricarica(array){

            if(array==null){
                array=LIST;
                document.getElementById('I_cerca').value='';
            }

            document.getElementById('B_seleziona').style.display='block';
            document.getElementById('B_elimina').style.display='none';
            let L=document.getElementById('list');

            while(L.childNodes.length>0)
                L.removeChild(L.firstChild);

            for(let i=0;i<array.length;i++){
                let a=document.createElement('button');
                a.setAttribute('class','passw');
                a.textContent=array[i];
                a.onclick=function(){open(a.textContent);};
                L.appendChild(a);
            }    

            LIST.sort();

        }

        /***************************/

        function seleziona(){

            document.getElementById('B_seleziona').style.display='none';
            document.getElementById('B_elimina').style.display='block';

            let L=document.getElementById('list');

            while(L.childNodes.length>0)
                L.removeChild(L.firstChild);

            for(let i=0;i<LIST.length;i++){
                let a=document.createElement('div');
                let b=document.createElement('input');
                let c=document.createElement('label');
                a.setAttribute('class','passw');
                b.setAttribute('class','passwC');
                c.setAttribute('class','passwL')
                b.setAttribute('type','checkbox');
                b.style.cursor='pointer';
                c.textContent=LIST[i];
                b.setAttribute('id','R_'+i);
                a.appendChild(b);
                a.appendChild(c);
                L.appendChild(a);
            }  
        }

        /***************************/

        function elimina(){

            mioConfirm('vuoi elimiare i siti selezionati?');
            

            CONFIRM_INTERVAL=setInterval(function(){

                if(CONFIRM==null)
                    return;
                else if(CONFIRM==false){
                    clearInterval(CONFIRM_INTERVAL);
                    CONFIRM=null;
                    return;
                }
                    
                document.getElementById('B_seleziona').style.display='block';
                document.getElementById('B_elimina').style.display='none';
                let R=document.getElementsByTagName('input');
                for(let i=0;i<R.length;i++)
                    if(R[i].type=='checkbox' && R[i].checked==true){

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

                                    LIST[i]=null;

                                    for(let i=0;i<LIST.length;i++)
                                        if(LIST[i]==null){
                                            LIST[i]=LIST[0];
                                            LIST.shift();
                                        }

                                    LIST.sort();   

                                    location.reload();
                                }
                                
                            }
                        };
                        ajax.open("POST", "./data.php", true);
                        ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        ajax.send("tipo=elimina&sito="+LIST[i]);

                        CONFIRM=null;
                        clearInterval(CONFIRM_INTERVAL);

                       
                    }

            
            },1000);
                
        }

        /***************************/

        function ordina(){

            LIST2=new Array();
            let regexp=document.getElementById('I_cerca').value;
            regexp=regexp.toUpperCase();
            for(let i=0;i<LIST.length;i++){
                let L=LIST[i].toUpperCase();
                if(L.search(regexp)==0){
                    LIST2.push(LIST[i]);
                }
            }

            LIST2.sort();
            if(LIST2.length>0)
                ricarica(LIST2);
            else 
                ricarica(null);
        }

        /***************************/

        function open(sito){

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
                    else
                        window.location.href='./sito.php';
                            
                }
            };
            ajax.open("POST", "./data.php", true);
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.send("tipo=apri&sito="+sito);

            
        }


        /***************************/
        function esci(){
            window.location.href = "../index.php";
        }

        /***************************/
        
        function back(){
            window.location.href = "./menu.php";
        }

       

     

    </script>
</html>