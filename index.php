<?php
    require_once ('dbutils/PDO.php');
    // session_abort(); 
    if(session_id() == '') {
        session_start();
    }                   
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css/ionicons/css/ionicons.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="css\utils.css">
        <title>Connection</title>
    </head>
    <body>
        
  <div class="body_form">
            <div class="login-bcg">
                
            </div>
            <div class="login">
                <form action="index.php" method="post">
                    <div title><h1>Conection</h1></div>
                    <?php

                    $db = new DBA();

                    $isSubmitted =  ($_SERVER["REQUEST_METHOD"] == "POST") ? 1 : 0;
                    if($isSubmitted == 1){
                        $username = isset($_POST['pseudo']) ? $_POST['pseudo'] : null;
                        $password = isset($_POST['pass']) ? $_POST['pass'] : null;
                    
                        $sql = "select * from utilisateur where pseudo= :pseudoID and pass= :passID";
                        $data = ['pseudoID'=> $username, 'passID'=> $password];
                        $response = $db->query($sql, $data);
                        $user = null;
                        if ($db->error == '') {
                            if($response!=null){
                                $user = $response[0];
                            // var_dump($response[0]); 
                                $_SESSION['user'] = $user;
                                $_SESSION['dba'] = $db;
                                header("Location: http://localhost/WebApplication1/vue.php", TRUE, 301);
                                exit();              
                            } 
                            // else {
                                // echo 'Error encountered ' . $db->error;
                            // }
                        }

                        if($username == null || $password == null || $user == null){
                            ?>
                            <div class="boxAlert bad">Nom d'utilisateur ou mot de passe incorrect !</div>
                            <script>
                                setTimeout(function(){
                                    document.querySelector(".boxAlert").style.display="none";
                                }, 5000);
                            </script>
                             <?php
                        }
                        
                    }                   
                    
                    ?> 
                    <input type="text" placeholder=" Pseudo" name="pseudo"><br><br>
                    <input type="password" placeholder="Mot de passe" name="pass"><br><br>
                    <div class="button">
                        <input type="submit" value="register" name="submit" id="submit" />
                    </div>
                </form>
           </div>   
    </div>
       
    </body>
</html>

 