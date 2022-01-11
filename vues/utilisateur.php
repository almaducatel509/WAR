<?php

require_once ('dbutils/PDO.php');
    if(isset($_SESSION['dba'])) {
        $db =$_SESSION['dba'];
    }else{
        $db = new DBA();
        // $db = dbConnect();
    }
    $user= null;
    if(isset($_POST["modifier"])) {
        $modifier = $_POST["modifier"];
        $sql = "SELECT * FROM utilisateur WHERE idUtilisateur= :idUtilisateur";
        $data = ['idUtilisateur'=> $modifier];
        $response = $db->query($sql, $data);
        $user = $response[0];
    }

    if(isset($_POST["trash"])) {
        $modifier = $_POST["trash"];
        $sql = "DELETE FROM utilisateur WHERE idUtilisateur= :idUtilisateur";
        $data = ['idUtilisateur'=> $modifier];
        $response = $db->query($sql, $data);
    }

$sql = "select * from utilisateur";
    $response = $db ->query($sql);
    $requestSent = false;
    $isModification = false;
    
    if ($db->error == '') {
        // var_dump( $response);
        if(isset($_POST["firstname"]) &&isset($_POST["lastname"])&& isset($_POST["pseudo"])
         && isset($_POST["poste"]) && isset($_POST["pass"]) && isset($_POST["privileges"])){
            $utiId= htmlspecialchars(stripslashes(trim($_POST["utiId"]))); 
            $prenom= slashquote(htmlspecialchars(stripslashes(trim($_POST["firstname"]))));
            $nom = slashquote(htmlspecialchars(stripslashes(trim ($_POST["lastname"]))));
            $pseudo = htmlspecialchars(stripslashes(trim ($_POST["pseudo"])));
            $poste = slashquote(htmlspecialchars(stripslashes(trim ($_POST["poste"]))));
            $pass = htmlspecialchars(stripslashes(trim($_POST["pass"])));
            $privileges = htmlspecialchars(stripslashes(trim ($_POST["privileges"])));
//            $trash = htmlspecialchars(stripslashes(trim ($_POST["trash"])));
            // $personneRef = slashquote(htmlspecialchars(stripslashes(trim($_POST["personneRef"]))));
            // echo 'Prénom : '.$_POST["firstname"].'<br>';
            // echo 'Email : ' .$_POST["lastname"].'<br>';
            // echo 'Age : ' .$_POST["sexe"].'<br>';
            // echo 'Sexe : ' .$_POST["adresse"].'<br>';
            // echo 'Pays : ' .$_POST["telephone"].'<br>';

            if((!empty($_POST["firstname"])) && (!empty($_POST["lastname"]))
            && (!empty($_POST["pseudo"])) && (!empty($_POST["poste"])) && (!empty($_POST["pass"]))
            && (!empty($_POST["privileges"]))){

            $requestSent = true;

            $codeUtilisateur = getCode($nom, $prenom);
            if(empty($utiId)){
                $sql ="INSERT INTO utilisateur(nomUser, prenomUser, poste, pseudo, pass, privilege, etat)
                    VALUES ('$nom','$prenom','$poste', '$pseudo','$pass', '$privileges', 'Actif')";
                $response = $db->query($sql);
            }
            else{
                $isModification = true;
                $sql ="UPDATE utilisateur SET nomUser='$nom', prenomUser='$prenom', poste='$poste', pseudo='$pseudo', pass='$pass', privilege = '$privileges'
                where idUtilisateur=$utiId";
                $response = $db->query($sql);
            }
         } else $erreure="Un champ est vide";
        }
    }
?>

<div class="popup_entry <?= $user != null ? "active" : "" ?>">
    <div class="entry">
        <div class="popup_head">
            <h3>Enregistrer un utilisateur</h3>
        </div>
        <div class="popup_body">  
        <form action="vue.php?p=utilisateur" method="post" class="form_entry" name="formulaire">
            <input type="hidden" name="utiId" value="<?= $user == null ? "" : $user -> idUtilisateur ?>">
            <div class="avatar">
                <input class="file" type="file" value="" />
                <i class="las la-camera"></i>
            </div> 
            <input class="avatar_input" type="hidden" name="avatar" value="" />
            
            <div class="input">
                <label for="lastname" class="labels">Entrez le nom</label>
                <input type="text" name="lastname" placeholder="Nom" value="<?= $user == null ? "" : $user -> nomUser ?>">
            </div>
            <div class="input">
                <label for="firstname" class="labels">Entrez le prenom</label>
                <input type="text" name="firstname" placeholder="Prenom"  value="<?= $user == null ? "" : $user -> prenomUser?>">
            </div>
            <div class="input">
                <label for="pseudo" class="labels">Entrez le pseudo</label>
                <input type="text" name="pseudo" placeholder="Pseudo" value="<?= $user == null ? "" : $user -> pseudo ?>">
            </div>
            <div class="input">
                <label for="nomPoste" class="labels">Entrez le poste</label>
                <input type="text" name="poste" placeholder="Poste" value="<?= $user == null ? "" : $user -> poste ?>">
            </div>
            <div class="privileges">
                <label for="pass" class="labels">Choisissez les privileges</label>
                <input type="hidden" name="privileges">
                <?php
                    if($user != null){
                        $privileges = explode(",",$user->privilege);
                    }
                ?>
                <?php foreach(allPrivileges() as $privilege){ ?>
                    <div class="check">
                        <input type="checkbox" <?= $user == null || !in_array($privilege, $privileges) ? "" : "checked"  ?> value="<?= $privilege ?>" id="<?= $privilege ?>">
                        <label for="<?= $privilege ?>"><?= $privilege ?></label>
                    </div>
                <?php } ?>
            </div>
            <div class="input">
                <label for="pass" class="labels">Entrez le mot de passe</label>
                <input type="password" name="pass" placeholder="Mot de passe"  value="<?= $user == null ? "" : $user ->pass ?>">
            </div>
            <div class="input">
                <label for="passconfirm" class="labels">Confirmez le mot de passe</label>
                <input type="password" name="passconfirm" placeholder="Mot de passe" value="<?= $user == null ? "" : $user ->pass ?>">
            </div>
            <div class="button_input">
                <button type="submit" value="Submit" name="submit" id="submit">Ajouter</button>
                <button type="reset" value="Clear" name="clear" id="quit" class="quit">Quiter</button>
            </div>
        </form>
        </div>
        
        
    </div>
</div>

<div class="head">
    <div class="view">
        <span class=" las  la-chalkboard-teacher"></span>
        <h2 class=" title_view">Utilisateurs</h2>
    </div>
    <div class="AcadY">
        <span class="iconY las  la-calendar-check"></span>
        <span class="ActualY"><?= $AYBanner ?></span>
    </div>
</div>

<?php if($requestSent){ ?>
    <div class="boxAlert"><?= $isModification != null ? "Modification reussie ! " : "Enregistrement reussi!" ?></div>
    <script>
        setTimeout(function(){
            document.querySelector(".boxAlert").style.display="none";
        }, 5000);
    </script>
<?php } ?>

<div class="lastBolock">
    <div class="see">
        <div class="button_head">
            <button class="print" onclick="printEorm()">
                <span class="print_icon las  la-print"></span>
                <span class="print_span"></span> 
            </button>
            <button class="print">
                <span class="print_icon las  la-plus-square"></span>
                <span class="add_span"></span> 
            </button>
        </div>
        
            <div class="colums">
                <div class="column">Profil</div>
                <div class="column">Nom</div>
                <div class="column">Prenom</div>
                <div class="column"> Pseudo</div>
                <div class="column"> Poste</div>
                <div class="column"> Statut</div>
                <div class="column">Operation</div>

            </div>
            <div class="allrows">
            <?php
                $sql = "select * from utilisateur";
                $response = $db->query($sql);
                if ($db->error == '') {
                    // var_dump($response);
                    foreach($response as $res){
            ?>
            <div class="rows">
                <div class="row profil_img" style="justify-content: center; background-image: url(<?= $res ->profil === null ? "Dr_dada.png" : $res->profil ?>);"></div>
                <div class="row point "><?= $res ->nomUser ?></div>
                <div class="row point "><?= $res-> prenomUser?></div>
                <div class="row"><?= $res ->pseudo ?></div> 
                <div class="row"><?= $res->poste ?></div>   
                <div class="row"><?= $res->etat?></div>
                <div class="row icons_operation">
                    <form method="post" action="vue.php?p=utilisateur">
                        <input type="hidden" name="modifier"  value="<?= $res ->idUtilisateur?>">
                        <button type="submit" class=" pen las  la-pencil-alt"></button>
                    </form>
                    <form method="post" action="vue.php?p=utilisateur">
                        <input type="hidden" name="trash" value="<?= $res -> idUtilisateur ?>">
                        <button class=" trash las la-trash-alt" type="submit"></button>
                    </form>
                </div>
                    
            </div>


            <?php
            }
                } else {
                    echo 'Error encountered ' . $db->error;
                }
                ?>
<?php
    // }else{
    //     echo "Log out Need to implement";   
    // }
?>

<script>
    var privileges = '';
    function collect(){
        privileges = '';
        $('.privileges input').each(function(){
            if(this.checked){
                privileges += (privileges.length ? ',' : '')+$(this).val();
            }
        });
        $('.privileges [type="hidden"]').val(privileges);
    }
    $('.privileges').on('change', 'input', function(){
        collect();
    });
    collect();
</script>
<script src="js\zepto.js"></script>
<script src="js\scroll.js"></script>
<script src="js\popup.js"></script>
<script src="js\print.js"></script>
<style>
    
.colums{ 
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    color: #131D28;
    background: #efefef;
    font-weight: bold;
    justify-content: space-between;
    font-size: 1em;
    padding: 1em;
}
</style>