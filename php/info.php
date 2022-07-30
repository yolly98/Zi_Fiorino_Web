<?php 
    session_start();
    if(!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']==NULL)
        echo "<script type='text/javascript'>window.location.href='../index.php';</script>";

 ?>

<!DOCTYPE html>
<html lang='it'>
    
    <head>
        <meta charset='utf8'>
        <title>ZI_Fiorino(Info)</title>
        <link rel="stylesheet" href="../style/info.css">
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

            <img id='img' src="../immagini/inform.jpg">
            <img id='imgM' src="../immagini/informM.png">

            <label id='h'>INFORMAZIONI</label>

            <label id='D1' class='D' >Nome Applicazione:</label>
            <label id='D2' class='D' >Autore:</label>
            <label id='D3' class='D' >Contatti:</label>

            <label id='R1' class='R' >Zi_Fiorino</label>
            <label id='R2' class='R' >Gianluca Gemini</label>
            <label id='R3' class='R' >gianlucagemini98@gmail.com</label>

            <button id='B_esci' onclick="esci()">ESCI</button>
            <button id='B_back' onclick="back()">INDIETRO</button>
            
        
        </div>

    </body>

    <script type='text/javascript'>

        sessionCheck();

        function esci(){
            window.location.href = "../index.php";
        }

        function back(){
            window.location.href = "./menu.php";
        }

    </script>
</html>