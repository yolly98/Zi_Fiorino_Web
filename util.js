
var UTENTE='';
var SESSION_INTERVAL=null;
var CONFIRM_INTERVAL=null;
var CONFIRM=null;
var ALERT_INTERVAL=null;
var ALERT=false;





/************/

function mioAlert(text){

    let alert=document.createElement('div');
    let alertText=document.createElement('label');
    let alertButton=document.createElement('button');
    let blockDiv=document.createElement('div');
    let spazio1=document.createElement('p');
    

    alert.setAttribute('class','alert');
    alertText.setAttribute('class','alertText');
    alertButton.setAttribute('class','alertButton');
    blockDiv.setAttribute('class','blockDiv');
    spazio1.style="width:100%; height:1vw; position:relative; float:left;";
    

    alertButton.textContent='OK';

    alertText.textContent=text;
    alertButton.onclick=function(){


        ALERT=true;
        alert.parentNode.removeChild(alert);
        blockDiv.parentNode.removeChild(blockDiv);
    }

    alert.appendChild(alertText);
    alert.appendChild(spazio1);
    alert.appendChild(alertButton);
    document.body.appendChild(alert);
    document.body.appendChild(blockDiv);

    /*ALERT_INTERVAL=setInterval(function(){
        if(ALERT==false)
            return;
        ALERT=false;
        clearInterval(ALERT_INTERVAL);
    },1000);*/


}

/***********/

function mioPrompt(text){


    let prompt=document.createElement('div');
    let promptText=document.createElement('label');
    let inputPrompt=document.createElement('input');
    let okButton=document.createElement('button');
    let blockDiv=document.createElement('div');
    let spazio1=document.createElement('p');
    let spazio2=document.createElement('p');
    let passw;
   

    prompt.setAttribute('class','prompt');
    promptText.setAttribute('class','promptText');
    inputPrompt.setAttribute('class','inputPrompt');
    okButton.setAttribute('class','okButton');
    blockDiv.setAttribute('class','blockDiv');
    spazio1.style="width:100%; height:1vw; position:relative; float:left;";
    spazio2.style="width:100%; height:1vw; position:relative; float:left;";
    okButton.textContent='Conferma';
    inputPrompt.type='password';
    inputPrompt.maxLength='15';

    promptText.textContent=text;
    okButton.onclick=function(){

        passw=inputPrompt.value;

        let ajax = new XMLHttpRequest();
        let result;
        ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
               
                result=this.responseText;
                
                if(result.search('user:')==0){
                    
                    UTENTE=result.substring(5,result.length);
                    prompt.parentNode.removeChild(prompt);
                    blockDiv.parentNode.removeChild(blockDiv);
                     sessionCheck();
                }
                else{
                    //mioAlert(result);
                    mioAlert("password errata");
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
        ajax.send("tipo=login&user="+UTENTE+"&passw="+passw);
        
    }


    prompt.appendChild(promptText);
    prompt.appendChild(spazio1);
    prompt.appendChild(inputPrompt);
    prompt.appendChild(spazio2);
    prompt.appendChild(okButton);
    document.body.appendChild(prompt);
    document.body.appendChild(blockDiv);   
    

}

/******************/



function mioConfirm(text){

    let confirm=document.createElement('div');
    let confirmText=document.createElement('label');
    let okButton=document.createElement('button');
    let backButton=document.createElement('button');
    let blockDiv=document.createElement('div');
    let spazio1=document.createElement('p');
   

    confirm.setAttribute('class','confirm');
    confirmText.setAttribute('class','confirmText');
    okButton.setAttribute('class','confirmButton');
    backButton.setAttribute('class','backButton');
    blockDiv.setAttribute('class','blockDiv');
    spazio1.style="width:100%; height:1vw; position:relative; float:left;";
    okButton.textContent='Conferma';
    backButton.textContent='Annulla';

    confirmText.textContent=text;
    okButton.onclick=function(){

        confirm.parentNode.removeChild(confirm);
        blockDiv.parentNode.removeChild(blockDiv);
        CONFIRM=true;
    }

    backButton.onclick=function(){

        confirm.parentNode.removeChild(confirm);
        blockDiv.parentNode.removeChild(blockDiv);
        CONFIRM=false;
    }

    confirm.appendChild(confirmText);
    confirm.appendChild(spazio1);
    confirm.appendChild(okButton);
    confirm.appendChild(backButton);
    document.body.appendChild(confirm);
    document.body.appendChild(blockDiv);   
    

}

/******************/

function sessionCheck(){

    SESSION_INTERVAL=setInterval(function() {
        checkS();
        
      },3000)
}

/*****************/

function checkS(){

    let ajax = new XMLHttpRequest();
    let result='';
    ajax.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            

            result=this.responseText;
           
            if(result!='ok'){
                mioPrompt("Sessione scaduta, reinserire password di accesso");
                clearInterval(SESSION_INTERVAL);
                
            }
        }
    };
    ajax.open("POST", "./data.php", true);//la funzione viene importata dalle pagine che stanno nella cartella php
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send("tipo=cancellaSessione");

}
