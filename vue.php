<?php
    $script = file_get_contents('js/utils.js');
    require_once ('./dbutils/side_menu.php');
    
?>
<?php

require_once ('dbutils/PDO.php');
require_once ('dbutils/Utils.php');

if(session_id() == '') {
    session_start();
}
    $AYBanner = getAYBanner();
    $user = $_SESSION['user'];

    if(isset($_SESSION['dba'])) {
        $db =$_SESSION['dba'];
    }else{
        $db = new DBA();
        // $db = dbConnect();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="fonts.css" rel="stylesheet" />
    <link href="css\ionicons\css\ionicons.css" rel="stylesheet"/>
    <link href="css\line-awesome-1.3.0\css\line-awesome.css" rel="stylesheet" />
    <link href="style.css" rel="stylesheet" />
    <link rel="stylesheet" href="css\academique.css">
     <link rel="stylesheet" href="css\etudiant.css">
    <link rel="stylesheet" href="css\horaire.css"> 
    <link rel="stylesheet" href="css\notes.css">
     <link rel="stylesheet" href="css\professeur.css">
    <link rel="stylesheet" href="css\utilisateur.css"> 
    <link rel="stylesheet" href="css\utils.css"> 
    <title>CHCL</title>
    <script src="js/zepto.js" type="text/javascript"></script>
</head>
<body> 
    <?php 
        // if(isset($_SESSION['user'])) {
            // $user =$_SESSION['user'];
        ?>
    <div class="app">
        <div class="sidebar">
            <div class="brand">
                <span class="las la-code"></span>
                <h3>SmartHead</h3>
                <span class="las la-code"></span>
            </div>
            <div class="sidemenu">
           
                <div class="side-user">
                    <div class="side-img" style="background-image: url(images.png);"></div>
                    <div class="user">
                        <small>
                            <!-- my error -->
                        <?=
                         $user -> nomUser .' '. $user -> prenomUser;
                         ?>
                        </small>
                        <p>
                            <?= $user -> poste?> 
                        </p>
                        <div class="separator"></div>
                    </div>
                </div>
                
                <ul class="my_menu">                    
                    <?= nav_menu()  ?>
                                                       
                    <script>
                        // if (document.links.length <= 0) {
                        //     document.links[0].className = 'active';
                        // }
                        // else
                            for (var i = 0; i < document.links.length; i++) {
                                if (document.links[i].href == document.URL) {
                                    document.links[i].className = 'active';
                                }
                            }
                    </script>
                </ul>                
            </div>
        </div>
        <div class="main-content">
            <header>
                <div class="search-content">
                    <div class="search">
                        <input type="text" onekeyup="mySearch()"  placeholder="Recherchez...">
                        <icon class="ion-ios-search-strong"></icon>
                    </div>
                </div>
                <div class="content-las">
                    <form action="index.php" method="post" class="form-header">
                        <input type="hidden" name="signout">
                        <button type="submit" class="las  la-sign-out-alt" id="signout"></button>
                    </form>
                </div>
            </header>
            <main>
            
                <?php
                $page = isset($_GET['p']) ? $_GET['p'] : 'dashboard';
    
                switch($page){
                    case 'dashboard' : require_once('vues/dashboard.php'); break;
                    case 'academique' : require_once('vues/academique.php');break;
                    case 'etudiants' : require_once('vues/etudiants.php');break;
                    case 'professeur' : require_once('vues/professeur.php');break;
                    case 'filiere' : require_once('vues/filiere.php');break;
                    case 'cours' : require_once('vues/cours.php');break;
                    case 'horaire' : require_once('vues/horaire.php');break;
                    case 'utilisateur' : require_once('vues/utilisateur.php');break;
                    case 'notes' : require_once('vues/notes.php');break;
                    default : require_once('vues/error.php');
                }
                ?>
                
            </main>
        </div>
    </div>

<?php
    // }else{
        // echo "Log out Need to implement";   
    // }
?>
    <script src="js/scroll.js" type="text/javascript"></script>
    <script src="js/popup.js"></script>
    <script src="js/print.js"></script>
    <script src="js/search.js"></script>
</body>
</html>