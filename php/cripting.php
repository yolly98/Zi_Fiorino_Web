<?php
 $N=30;
 settype($N,"int");


function mescola($str_crypt,$chiave){
   
    if($str_crypt==null || $chiave==null){
        
        return false;

    }

    settype($str_crypt,"string");
    settype($chiave,"string");
    
    if(strlen($chiave)>15){
       
        return false;

    }     

    /********ESTENSIONE CHIAVE****************/

    $chiave_ext='';
    settype($chiave_ext,"string");
    for($i=0;$i<($GLOBALS["N"]/15);$i++)
        $chiave_ext.=$chiave;
    

    $chiave=$chiave_ext;
    
     /***********MESCOLAMENTO SIMMETRICO*****/

    /***mescolamento da sx*****/

    for($i=0;$i<strlen($chiave);$i++){

        $c=null;
        settype($c,"string");

        $c=$chiave[$i];
        $x=null;
        $sum=null;
        settype($x,"int");
        settype($sum,"int");

        if($c>='a' && $c<='z'){
            $x=ord($c)-ord('a');
            $sum=$i%(strlen($chiave)/2);//0-14
        }
        else if($c>='A' && $c<='Z'){
            $x=ord($c)-ord('A');
            $sum=$i%(strlen($chiave)/2)+10;//15-29
        }
        else if($c>='0' && $c<='9'){
            $x=ord($c)-ord('0');
            $sum=$i%(strlen($chiave)/2)+20;//30-44
        }
        else
        {
            
            return false;

        }

        $appoggio=null;
        settype($appoggio,"string");
        $appoggio=$str_crypt[$i];
        $str_crypt[$i]=$str_crypt[$x];
        $str_crypt[$x]=chr(ord($appoggio)+$sum);
        
    }

    //cout<<"str mescolata da sx"<<endl<<$str_crypt<<endl<<endl;

    /***mescolamento da dx*****/

    for($i=0;$i<strlen($chiave);$i++){

        $c=$chiave[$i];
        settype($c,"string");
        $x=null;
        settype($x,"int");
        $sum=null;
        settype($sum,"int");

        if($c>='a' && $c<='z'){
            $x=ord($c)-ord('a');
            $sum=$i%(strlen($chiave)/2);
        }
        else if($c>='A' && $c<='Z'){
            $x=ord($c)-ord('A');
            $sum=$i%(strlen($chiave)/2)+10;
        }
        else if($c>='0' && $c<='9'){
            $x=ord($c)-ord('0');
            $sum=$i%(strlen($chiave)/2)+20;
        }
        else
        {
           
            return false;

        }

        $appoggio=null;
        settype($appoggio,"string");
        $appoggio=$str_crypt[strlen($str_crypt)-1-$i];
        $str_crypt[strlen($str_crypt)-1-$i]=$str_crypt[strlen($str_crypt)-1-$x];
        $str_crypt[strlen($str_crypt)-1-$x]=chr(ord($appoggio)+$sum);

    }

    //cout<<"str mescolata da dx"<<endl<<$str_crypt<<endl<<endl;

    return $str_crypt;


}
/*******************************/

function ricomponi( $str_crypt, $chiave){
   


    if($str_crypt==null || $chiave==null){
        //return "errore di input dati";
        return false;
    }


    settype($str_crypt,"string");
    settype($chiave,"string");


    if(strlen($chiave)>15){
        
        return false;

    }     

    /********ESTENSIONE CHIAVE****************/

    $chiave_ext='';
    settype($chiave_ext,"string");

    for($i=0;$i<($GLOBALS["N"]/15);$i++)
        $chiave_ext.=$chiave;
    

    $chiave=$chiave_ext;

    /***********RiCOMPONIMENTO SIMMETRICO*****/

    /*****rimcomponimento da dx****/

    for($i=strlen($chiave)-1;$i>=0;$i--){

        $dec=null;
        settype($dec,"int");
        $c=$chiave[$i];
        settype($c,"string");
        $x=null;
        settype($x,"int");

        if($c>='a' && $c<='z'){
            $x=ord($c)-ord('a');
            $dec=$i%(strlen($chiave)/2);
        }
        else if($c>='A' && $c<='Z'){
            $x=ord($c)-ord('A');
            $dec=$i%(strlen($chiave)/2)+10;
        }
        else if($c>='0' && $c<='9'){
            $x=ord($c)-ord('0');
            $dec=$i%(strlen($chiave)/2)+20;
        }
        else{
            
            return false;

        }

        

        $appoggio=null;
        settype($appoggio,"string");
        $appoggio=$str_crypt[strlen($str_crypt)-1-$x];
        $str_crypt[strlen($str_crypt)-1-$x]=$str_crypt[strlen($str_crypt)-1-$i];
        $str_crypt[strlen($str_crypt)-1-$i]=chr(ord($appoggio)-$dec);

    }

    //cout<<"str ricomposta da dx"<<endl<<str_crypt<<endl<<endl;

    /*****rimcomponimento da sx****/

    for($i=strlen($chiave)-1;$i>=0;$i--){

        $dec=null;
        settype($dec,"int");
        $c=null;
        settype($c,"string");
        $c=$chiave[$i];
        $x=null;
        settype($x,"int");

        if($c>='a' && $c<='z'){
            $x=ord($c)-ord('a');
            $dec=$i%(strlen($chiave)/2);
        }
        else if($c>='A' && $c<='Z'){
            $x=ord($c)-ord('A');
            $dec=$i%(strlen($chiave)/2)+10;
        }
        else if($c>='0' && $c<='9'){
            $x=ord($c)-ord('0');
            $dec=$i%(strlen($chiave)/2)+20;
        }
        else{
            
            return false;

        }

        $appoggio=null;
        settype($appoggio,"string");
        $appoggio=$str_crypt[$x];
        $str_crypt[$x]=$str_crypt[$i];
        $str_crypt[$i]=chr(ord($appoggio)-$dec);


    }

    //cout<<"str ricomposta da sx"<<endl<<str_crypt<<endl<<endl;

    return $str_crypt;



}



/*******************************/

function decripta($str_crypt,$chiave){
    


    if($str_crypt==null || $chiave==null){
        //return "errore di input dati";
        return false;
    }


    settype($str_crypt,"string");
    settype($chiave,"string");

    $str_crypt=ricomponi($str_crypt,$chiave);
    if($str_crypt==false)
        return false;

    $size=null;
    $lung=null;
    $str_mem=null;
    $str_sup=null;

    settype($size,"int");
    settype($lung,"int");
    settype($str_mem,"string");
    settype($$str_sup,"string");

    $size=ord($str_crypt[0])-ord('0');
    $lung=($GLOBALS["N"]*2-$size)/2;
    for($i=0;$i<$GLOBALS["N"];$i++){
        $str_mem.=' ';
        $str_sup.=' ';
    }
    //string str_mem(N,' ');
    //string str_sup(N,' ');

    for($i=0,$j=0;$i<$lung;$i++,$j=$j+2){
        $str_mem[$i]=$str_crypt[2*$lung-$j];
        $str_sup[$i]=$str_crypt[$j+1];
    }

    $str0=null;
    settype($str0,"string");
    for($i=0;$i<$GLOBALS["N"];$i++)
        $str0.=' ';

    //string str0(N,' '); 
    for($i=0;$i<$lung;$i++){
        $shift=null;
        settype($shift,"int");
        $shift=ord($str_mem[$i])-ord('0');
        $str0[$i]=chr(ord($str_sup[$i])-$shift);
    }

    $str0=trim($str0);
   
    return $str0;
}


/**********************************/


function cripta($str,$chiave){
    

    if($str==null || $chiave==null){
        //return "errore di input dati";
        return false;
    }


    settype($str,"string");
    settype($chiave,"string");
    

    $str_crypt=null;
    $str_mem=null;
    $str_sup=null;
    $spia=null;

    settype($str_crypt,"string");
    settype($str_mem,"string");
    settype($str_sup,"string");
    settype($spia,"int");


    
    //string str_crypt(N*2+1,' ');		//20*2 stringa, 1 per l'inizio
    //string str_mem(N,' ');
    //string str_sup(N,' ');
    for($i=0;$i<($GLOBALS["N"]*2+1);$i++)
        $str_crypt.=' ';
    for($i=0;$i<$GLOBALS["N"];$i++)
        $str_mem.=' ';
    for($i=0;$i<$GLOBALS["N"];$i++)
        $str_sup.=' ';
    $spia=0;

    $size=null;
    settype($size,"int");
    $size=($GLOBALS["N"]*2)-2*strlen($str);   //non funziona per str.size()>=N;
    if($size>=0)
        $str_crypt[0]=chr(ord('0')+$size);
    else{
        $spia=1;

        return false;

    }


    if($spia!=1){
        
        for($i=0;$i<strlen($str) && $i<$GLOBALS["N"];$i++){

            $shift=null;
            settype($shift,"int");
            $shift=rand(0,4);
            $str_mem[$i]=chr(ord('0')+$shift);
            $str_sup[$i]=chr(ord($str[$i])+$shift);
           // echo $str_mem[$i]."  ".$str_sup[$i]."<br>";
        }
        

        for($i=0,$j=0;$i<strlen($str) && $i<$GLOBALS["N"];$i++,$j=$j+2){
            $str_crypt[$j+1]=$str_sup[$i];
            $str_crypt[2*strlen($str)-$j]=$str_mem[$i];

        }

       
        $i=null;
        settype($i,"int");
       
        for($i=ord($str_crypt[0])-ord('0')-1;$i>=0;$i--){ 

            $x=null;
            $y=null;
            $k=null;
            settype($k,"string");
            settype($y,"int");
            settype($x,"int");
            $x=rand(0,1);
            
            $k='a';
            switch($x){
                case 0:
                    $y=rand(0,9);
                    $str_crypt[$GLOBALS["N"]*2-$i]=chr(ord('0')+$y);
                    break;
                case 1:
                    $k=chr(ord($k)+rand(0,9));
                    $str_crypt[$GLOBALS["N"]*2-$i]=$k;
                    break;

            }
        }

       
    }
    $finale=null;
    settype($finale,"string");
    $finale=$str_crypt;
    $str_crypt=$finale;

    $str_crypt=mescola($str_crypt,$chiave);
    if($str_crypt==false)
        return false;

    return $str_crypt;


}

/*$ciao=cripta("calogero","ciao");
echo $ciao."<br>";
echo "<pre>";
print_r($ciao);
echo "</pre>";
echo decripta($ciao,"ciao");

/****************************************/
