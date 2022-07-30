<?php


    session_start();
    if(isset($_SESSION['user']))
        unset($_SESSION['user']);


    if(isset($_SESSION['passwAccesso'])){
        unset($_SESSION['passwAccesso']);
        
    }
    if(isset($_SESSION['chiave'])){
        unset($_SESSION['chiave']);
    }
    if(isset($_SESSION['sessione'])){
        unset($_SESSION['sessione']);
    }
        
?>



<!DOCTYPE html>
<html lang='it'>
    <head>
        <meta charset='utf8'>
        <title>Zi_Fiorino(Login)</title>
        <link rel="icon" href="./immagini/zi_icon.ico">
        <link rel="stylesheet" href="./style/index.css">
        <link rel="stylesheet" href="./style/util.css">
        <script type='text/javascript' src='./util.js'></script>
        <!--<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
    </head>
    
    <body >
        <div id='wrapper'>
            <div id='titleDiv'>
                <label>ZI_FIORINO</label>
            </div>

            <div id='login' style='display:block'>
                <label id='labelUser'>Utente</label>
                <input class='IL' id='user' type='text' onkeydown="keydownInput('log',0,event)">
                <label id='labelPassw'>Password</label>
                <input class='IL' id='passw' type='password' maxlength="15" onkeydown="keydownInput('log',1,event)" onkeyup="count('login',null)"><label class='countPassw'>15</label>
                <button id='B_login' onclick=login()>Login</button>
                <button id='B_reg' onclick=registrati()>Registrati</button>
            </div>

            <div id='reg' style='display:none'>
                <label id='labelUserR'>Utente</label>
                <input class='IR' id='userR' type='text' onkeydown="keydownInput('reg',0,event)" onkeyup="controlloSpazi(0)">
                <label id='labelPasswR'>Password</label>
                <input class='IR' id='passwR' type='password' maxlength="15" onkeydown="keydownInput('reg',1,event)" onkeyup="count('reg1',1)"><label class='countPasswR1'>15</label>
                <label id='labelPasswRR'>Ripeti password</label>
                <input class='IR' id='passwRR' type='password' maxlength="15" onkeydown="keydownInput('reg',2,event)" onkeyup="count('reg2',2)"><label class='countPasswR2'>15</label>
                <button id='B_ok' onclick=reg()>OK</button>
                <button id='B_back' onclick=backLogin()>Indietro</button>
            </div>

        </div>
    </body>

    <script type='text/javascript'>


       
        /*********************************/

        document.addEventListener('keydown',this.keydown.bind(this),false);
        var User=document.getElementById('user');
        var Passw=document.getElementById('passw');
        var UserR=document.getElementById('userR');
        var PasswR=document.getElementById('passwR');
        var PasswRR=document.getElementById('passwRR');
        var Login=document.getElementById('login');
        var Reg=document.getElementById('reg');

        /***************************/

        function keydown(ev){
            if(ev.keyCode==13 && Login.style.display=='block' && document.getElementsByClassName("blockDiv").length==0){
                login();
            }
            else if(ev.keyCode==13 && Reg.style.display=='block' && document.getElementsByClassName("blockDiv").length==0){
                reg();
            }

        }

        /*************************/
        function keydownInput(type,index,ev){

            if(ev.keyCode==38){//freccia su
                if(type=='log'){
                    index=(2+index-1)%2;
                    document.getElementsByClassName("IL")[index].select();
                }
                else if(type=='reg'){
                    index=(3+index-1)%3;
                    document.getElementsByClassName("IR")[index].select();
                }
            }
            else if(ev.keyCode==40){//freccia giu
                if(type=='log'){
                    index=(index+1)%2;
                    document.getElementsByClassName("IL")[index].select();
                }
                else if(type=='reg'){
                    index=(index+1)%3;
                    document.getElementsByClassName("IR")[index].select();
                }
            }

        }

        /***************************/

        function login(){
            
            let _user=User.value;
            let _passw=Passw.value;

            if(_user==null || _user=='' || _passw==null || _passw==''){
                mioAlert('dati inseriti insufficienti');
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);

                Passw.value='';
                User.value='';
                document.getElementsByClassName("countPassw")[0].textContent="15";
            }
            else if(_user.search(' ')!=-1 || _passw.search(' ')!=-1){
                mioAlert('gli spazi non sono consentiti');
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);

            } 
            else if(_user.search('&')!=-1 || _user.search("'")!=-1 || _user.search('<')!=-1 || _user.search('>')!=-1 || _user.search('\"')!=-1 ||
                    _passw.search('&')!=-1 || _passw.search("'")!=-1 || _passw.search('<')!=-1 || _passw.search('>')!=-1 || _passw.search('\"')!=-1){
                mioAlert("i seguenti caratteri non sono consentiti &'<>\"");
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);
            } 
            else{

                let ajax = new XMLHttpRequest();
                let result;
                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                       
                        result=this.responseText;
                        
                        if(result.search('user:')==0){
                            User.value='';
                            Passw.value='';
                            UTENTE=result.substring(5,result.length);
                            window.location.href = "./php/menu.php";
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
                ajax.open("POST", "./php/data.php", true);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send("tipo=login&user="+_user+"&passw="+_passw);
            }
        }

        /***************************/

        function registrati(){

            Login.style.display='none';
            Reg.style.display='block';
            document.getElementsByClassName("countPassw")[0].textContent="15";
        }

        /***************************/

        function reg(){
            let _user=UserR.value;
            let _passw=PasswR.value;
            let _passwR=PasswRR.value;

            if(_user==null || _user=='' || _passw==null || _passw=='' || _passwR==null || _passwR==''){
                mioAlert('dati inseriti insufficienti o non validi');
                PasswR.value='';
                PasswRR.value='';
                UserR.value='';
                document.getElementsByClassName("countPasswR1")[0].textContent="15";
                document.getElementsByClassName("countPasswR2")[0].textContent="15";
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);
            }
            else if(_user.search(' ')!=-1 || _passw.search(' ')!=-1 || _passwR.search(' ')!=-1){
                mioAlert('gli spazi non sono consentiti');
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);

            } 
            else if(_user.search('&')!=-1 || _user.search("'")!=-1 || _user.search('<')!=-1 || _user.search('>')!=-1 || _user.search('\"')!=-1 ||
                    _passw.search('&')!=-1 || _passw.search("'")!=-1 || _passw.search('<')!=-1 || _passw.search('>')!=-1 || _passw.search('\"')!=-1 ||
                    _passwR.search('&')!=-1 || _passwR.search("'")!=-1 || _passwR.search('<')!=-1 || _passwR.search('>')!=-1 || _passwR.search('\"')!=-1){
                mioAlert("i seguenti caratteri non sono consentiti &'<>\"");
                ALERT_INTERVAL=setInterval(function(){
                    if(ALERT==false)
                        return;
                    ALERT=false;
                    clearInterval(ALERT_INTERVAL);
                },1000);

            } 
            else{

                if(_passw!=_passwR){

                    mioAlert('le password non coincidono');
                    PasswR.value='';
                    PasswRR.value='';
                    document.getElementsByClassName("countPasswR1")[0].textContent="15";
                    document.getElementsByClassName("countPasswR2")[0].textContent="15";
                    ALERT_INTERVAL=setInterval(function(){
                        if(ALERT==false)
                            return;
                        ALERT=false;
                        clearInterval(ALERT_INTERVAL);
                    },1000);
                    return;
                }

                let ajax = new XMLHttpRequest();
                let result;
                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        //mioAlert('salvateggio avvenuto con successo');
                        result=this.responseText
                        
                        if(result=='ok'){
                            mioAlert('registrazione avvenuta con successo');

                            ALERT_INTERVAL=setInterval(function(){
                                if(ALERT==false)
                                    return;
                                ALERT=false;
                                clearInterval(ALERT_INTERVAL);
                                backLogin();
                                mioAlert('IMPORTANTE!\rPer una questione di sicurezza non sarà possibile recuperare la password di accesso al sito,\r perciò è opportuno tenerla bene a mente.\rInoltre è opportuno eseguire dei backup dei propri dati tramite il portale apposito');
                                ALERT_INTERVAL=setInterval(function(){
                                    if(ALERT==false)
                                        return;
                                    ALERT=false;
                                    clearInterval(ALERT_INTERVAL);
                                },1000);
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
                ajax.open("POST", "./php/data.php", true);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send("tipo=reg&user="+_user+"&passw="+_passw)
                
            }
        }

        /***************************/

        function backLogin(){
            User.value='';
            Passw.value='';
            UserR.value='';
            PasswR.value='';
            PasswRR.value='';
            Login.style.display='block';
            Reg.style.display='none';
            document.getElementsByClassName("countPasswR1")[0].textContent="15";
            document.getElementsByClassName("countPasswR2")[0].textContent="15";
        }

        /***************************/

        function count(tipo,index){

            if(tipo=='login'){
                let val=document.getElementById("passw").value;
                let counter=document.getElementsByClassName("countPassw")[0];
                counter.textContent=15-val.length;

            }
            else if(tipo=='reg1'){
                let val=document.getElementById("passwR").value;
                let counter=document.getElementsByClassName("countPasswR1")[0];
                counter.textContent=15-val.length;

            }
            else if(tipo=='reg2'){
                let val=document.getElementById("passwRR").value;
                let counter=document.getElementsByClassName("countPasswR2")[0];
                counter.textContent=15-val.length;

            }

            controlloSpazi(index);

        }

        /********************/

        function controlloSpazi(index){

            let Input;
            if(index==0)
                Input=UserR;
            else if(index==1)
                Input=PasswR;
            else if(index==2)
                Input=PasswRR;
            else
                return;

            if(Input.value.search(' ')!=-1)
                Input.style.borderColor='maroon';
            else
                Input.style.borderColor='steelblue';

        }
  
        
    </script>

</html>