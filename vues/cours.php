<?php

require_once ('dbutils/PDO.php');

    if(isset($_SESSION['dba'])) {
        $db =$_SESSION['dba'];
    }else{
        $db = new DBA();
        // $db = dbConnect();
    }
    $user= null;
    script_filiere();
    if(isset($_POST["trash"])) {
        $modifier = $_POST["trash"];
        $sql = "DELETE FROM cours WHERE codeCours= :codeCours";
        $data = ['codeCours'=> $modifier];
        $response = $db->query($sql, $data);
    }
    if(isset($_POST["modifier"])) {
        $modifier = $_POST["modifier"];
        $sql = "SELECT * FROM cours WHERE codeCours= :codeCours";
        $data = ['codeCours'=> $modifier];
        $response = $db->query($sql, $data);
        $user = $response[0];
    }

    $sql = "select * from cours";
    $response = $db ->query($sql);
    $requestSent = false;
    $isModification = false;
    if ($db->error == '') {
        // var_dump( $response);
        if(isset($_POST["nomCours"]) &&isset($_POST["filiere"]) && isset($_POST["niveau"]) 
            && isset($_POST["professeur"]) && isset($_POST["coefficient"])
            && isset($_POST["session"])  && isset($_POST["nomCours"]) ){
            $codeCours= htmlspecialchars(stripslashes(trim($_POST["codeCours"]))); 
            $filiere = slashquote(htmlspecialchars(stripslashes(trim($_POST["filiere"]))));
            $niveau = htmlspecialchars(stripslashes(trim ($_POST["niveau"])));
            $professeurTitulair = slashquote(htmlspecialchars(stripslashes(trim ($_POST["professeur"]))));
            $professeursupleant = htmlspecialchars(stripslashes(trim($_POST["professeur_sup"])));
            $coefficient = htmlspecialchars(stripslashes(trim ($_POST["coefficient"])));
            $session = htmlspecialchars(stripslashes(trim ($_POST["session"])));
            $nomCours = htmlspecialchars(stripslashes(trim ($_POST["nomCours"])));
//            var_dump($_POST);
            // echo 'Prénom : '.$_POST["firstname"].'<br>';
            // echo 'Email : ' .$_POST["lastname"].'<br>';
            // echo 'Age : ' .$_POST["sexe"].'<br>';
            // echo 'Sexe : ' .$_POST["adresse"].'<br>';
            // echo 'Pays : ' .$_POST["telephone"].'<br>';

            if(!empty($_POST["nomCours"]) && !empty($_POST["filiere"]) && !empty($_POST["niveau"]) &&
                !empty($_POST["professeur"]) && !empty($_POST["coefficient"]) && !empty($_POST["session"]) ){

                $requestSent = true;

                if(empty($codeCours)){
                    $sql ="INSERT INTO cours (nomCours, niveau, session, coefficient, professeur_titulaire, professeur_supleant,filiere)
                        VALUES ('$nomCours','$niveau', '$session','$coefficient','$professeurTitulair', '$professeursupleant', '$filiere')";
                    $response = $db->query($sql);
                }
                else{
                    $isModification = true;
                    $sql =" UPDATE cours SET nomCours ='$nomCours', niveau ='$niveau', session='$session', coefficient='$coefficient', 
                    professeur_titulaire='$professeurTitulair', professeur_supleant='$professeursupleant', filiere='$filiere' WHERE codeCours= $codeCours";
                    $response = $db->query($sql);
                }
            }else $erreure="Un champ est vide";
        }
    }

?>
<div class="popup_entry <?= $user != null ? "active" : "" ?>">
        <div class="entry">
            <div class="popup_head">
                <h3>Enregistrer un cours</h3>
            </div>
            <div class="popup_body">  
            <form action="vue.php?p=cours" method="post" class="form_entry" name="formulaire">
                    <input type="hidden" name="codeCours" value="<?= $user == null ? "" : $user -> codeCours ?>">
                    <div class="input">
                        <label for="nomCours" class="labels">Entrez la matiere</label>
                        <input type="text" name="nomCours" required placeholder="Matiere" value="<?= $user == null ? "" : $user -> nomCours ?>" />
                    </div>
                    <div class="input">
                    <?php
                        $sql = "select * from filiere"; 
                        $response = $db->query($sql); 
                        if ($db->error == '') {
                            // var_dump($response);
                    ?>
                    <label for="filiere" class="labels">List de filiere</label>
                    <select name="filiere" required id="list_filiere">
                        <option value="">Selectionnez la filiere</option>
                        <?php foreach($response as $res){?>
                            <option value="<?=$res->nom?>" <?= $user != null && $user->filiere == $res->nom ? "selected" : "" ?>><?= $res ->nom ?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="input">
                    <label for="niveau" class="labels">List de niveau</label>
                    <select name="niveau" required  id="liste_niveau" >
                        <option value="<?= $user != null ? $user->niveau : "" ?>">Sélectionnez la niveau</option>
                    </select>     
                </div>
                <div class="input">
                    <?php
                        $sql = "select * from professeur"; 
                        $response = $db->query($sql); 
                        if ($db->error == '') {
                            // var_dump($response);
                    ?>
                    <label for="professeur" class="labels">List des professeurs</label>
                    <select name="professeur" required id="list_professeur">
                        <option value="">Selectionnez le professeur titulair</option>
                        <?php foreach($response as $res){?>
                            <option value="<?=$res->idProfesseur?>" <?= $user != null && $user->professeur_titulaire == $res->idProfesseur ? "selected" : "" ?>><?= $res->prenom. " " . $res ->nom ?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="input">
                    <?php
                        $sql = "select * from professeur"; 
                        $response = $db->query($sql);
                    ?>
                    <label for="professeur" class="labels">List des professeurs</label>
                    <select name="professeur_sup"  id="list_professeur">
                        <option value="">Selectionnez le professeur supleant</option>
                        <?php foreach($response as $res){ ?>
                            <option value="<?=$res->idProfesseur?>" <?= $user != null && $user->professeur_supleant == $res->idProfesseur ? "selected" : "" ?>><?= $res->prenom. " " . $res ->nom ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="input">
                    <label for="coefficient" class="labels">Entrez le coefficient</label>
                    <input type="text" name="coefficient" required placeholder="Coeficient" value="<?=  $user != null ? $user -> coefficient : "" ?> ">
                </div>
                <div class="input">
                    <label for="session" class="labels">choisissez la session</label>
                    <select name="session" required>
                        <option value="1" <?= $user != null && $user-> session == 1 ? "selected" : "" ?> >session 1</option>
                        <option value="2" <?= $user != null && $user-> session == 2 ? "selected" : "" ?> >session 2</option>
                    </select>
                </div> 
               
                <div class="button_input">
            <button type="submit" value="Submit" name="submit" id="submit">Ajouter</button>
            <button type="reset" value="Clear" name="clear" id="quit" class="quit">Quiter</button>
        </div>
    </form>
</div>
            
        </div>   
    </div>  

<div class="main-content">
    
    <div class="head">
        <div class="view">
            <span class=" las  la-chalkboard-teacher"></span>
            <h2 class=" title_view">Cours</h2>
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
        <div class="rang">
            <?php
                $sql = "select * from filiere"; 
                $response = $db->query($sql); 
                if ($db->error == '') {
                    // var_dump($response);
            ?> 
            <form action="rang_entry" method="POST">
                <div class="rang_input">
                    <select name="filiere">
                        <option value=''>Choisissez la filiere</option>
                        <?php foreach($response as $res){?>
                        <option value="<?= $res ->nom ?>"><?= $res ->nom ?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="rang_input">
                    <select name="niveau">
                        <option value="">Selectionez le niveau</option>
                    </select>
                </div>
                <div class="rang_input">
                    <select name="session">
                        <option value="">Selectioner la session</option>
                        <option value="1">Session 1</option>
                        <option value="2">Session 2</option>
                    </select>
                </div>
               
           </form>
        </div>
        <div class="see">
            <div class="button_head">
                <button class="print">
                    <span class="print_icon las  la-plus-square"></span>
                    <span class="add_span"></span> 
                </button>
            </div>
                
            <div class="forme_print" id="form1">                 
                <div class="colums">
                    <div class="column">Matiere</div>
                    <div class="column">Filiiere</div>
                    <div class="column">Niveau</div>
                    <div class="column">Session</div>
                    <div class="column">Professeur titulair</div>
                    <div class="column"> Professeur supleant </div>
                    <div class="column"> Coefficient </div>
                    <div class="column"> Etat</div>
                    <div class="column">operation</div>
                </div>
                <div class="allrows">
                    <?php
                        $sql = "select c.*, 
                                  (select concat(prenom, ' ', nom) from professeur where idProfesseur= c.professeur_titulaire) as titulaire,
                                  (select concat(prenom, ' ', nom) from professeur where idProfesseur = c.professeur_supleant) as suppleant
                                from cours c";
                        $response = $db->query($sql);
                        if ($db->error == '') {
                            // var_dump($response);
                            foreach($response as $res){
                        ?>
                    <div class="rows">
                        <div class="row point nom">
                            <?= $res -> nomCours ?>
                        </div>
                        <div class="row point filiere">
                            <?=$res-> filiere ?>
                        </div>
                        <div class="row point niveau">
                            <?=$res -> niveau ?>
                        </div>
                        <div class="row">
                            <?= $res ->session ?>
                        </div>
                        <div class="row">
                            <?= $res-> titulaire ?>
                            <!--  titulaire -->
                        </div> 
                        <div class="row"> 
                            <!-- suppleant-->
                            <?= $res-> suppleant ?> &nbsp;
                        </div>
                        <div class="row">
                            <?= $res ->coefficient?>
                        </div>
                        <div class="row">
                            <?= $res ->etat?>
                        </div>   
                        <div class="row icons_operation"> 
                            <form method="post" action="vue.php?p=cours">
                            <input type="hidden" name="modifier" value="<?= $res->codeCours?>">
                                <button type="submit" class=" pen las  la-pencil-alt"></button>
                            </form>                    
                            <form method="post" action="vue.php?p=cours">
                                <input type="hidden" name="trash"value="<?= $res->codeCours?>"> 
                                <button href="#"class=" trash las la-trash-alt" type="submit"></button>
                            </form>
                        </div>
                    </div>
                    <?php
                    }
                    } else {
                        echo 'Error encountered ' . $db->error;
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
<?php
    // }else{
    //     echo "Log out Need to implement";   
    // }
?>

<style>
    .rows {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    justify-content: space-between;
    align-items: center;
    color: #efefef;
    font-size: 1em;
    padding: 1em;
    border: solid 0.1em #efefef;
    position: relative;
}
</style>