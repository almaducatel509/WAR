<?php

require_once ('dbutils/PDO.php');
if(isset($_SESSION['dba'])) {
    $db =$_SESSION['dba'];
}else{
    $db = new DBA();
    // $db = dbConnect();
}
$liste_filiere = script_filiere();
$user= null;
$requestSent = false; $message = "";
if(isset($_POST["modifier"])) {
    $modifier = $_POST["modifier"];
    $sql = " SELECT n.*, c.niveau, c.session, c.filiere  FROM notes n, cours c WHERE id_note = $modifier and n.codeCours = c.codeCours";
    $response = $db->query($sql);
    $user = $response[0];
}


if(isset($_POST["trash"])) {
    $id = $_POST['trash'];
    $sql = " Delete  FROM notes WHERE id_note = $id";
    $response = $db->query($sql);
    $requestSent = true;
    $message = "Suppression réussie !";
}

if(isset($_POST["etudiant"]) && isset($_POST["cours"]) && isset($_POST["note"])){
    $requestSent = true;
    $idN= htmlspecialchars(stripslashes(trim($_POST["noteId"])));
    $etudiant = slashquote(htmlspecialchars(stripslashes(trim ($_POST["etudiant"]))));
    $cours = htmlspecialchars(stripslashes(trim($_POST["cours"])));
    $noteSur100 = htmlspecialchars(stripslashes(trim($_POST["note"])));

    if(!empty($_POST["etudiant"]) && !empty($_POST["cours"]) && !empty($_POST["note"])){
        if(empty($idN)){
            $sql ="INSERT INTO notes(idEtudiant, codeCours, noteSur100, annee_academique)
                    VALUES ('$etudiant', '$cours','$noteSur100', (select id from annee_academique where etat='O'))";
            $response = $db->query($sql);
            $message = "Enregistrement réussi !";
        }
        else{
            $isModification = true;
            $sql ="UPDATE notes SET noteSur100=$noteSur100 WHERE id_note = $idN";
            $response = $db->query($sql);
            $message = "Modification réussie !";
        }
    }else $message = "Un champ est vide";
}

$sql = "select * from notes";
$response = $db ->query($sql);

?>

<script>
    var dataset = {
        cours : {},
        etudiant: {},
        professeur: {}
    };
    <?php 
     $k = 0;
     $listC = selectCours();
     $listP = selectProfesseur();
     $listE = selectEtudiant();
    foreach($listC as $cours){ ?>
        dataset.cours[<?= $k ?>] = {
            "id" : "<?= $cours->codeCours ?>",
            "nom" : "<?= $cours->nomCours ?>",
            "coefficient" : <?= $cours->coefficient ?>,
            "niveau" : "<?= $cours->niveau ?>",
            "filiere" : "<?= $cours->filiere ?>",
            "session" : <?= $cours->session ?>,
            "titulaire" : "<?= $cours->professeur_titulaire ?>",
            "suppleant" : <?= $cours->professeur_supleant == null ? "null" : '"'.$cours->professeur_supleant.'"' ?>
        }
    <?php
        $k++;
    } 
        $k = 0;
    ?>
    <?php 
    foreach($listP as $prof){ ?>
        dataset.professeur[<?= $k ?>] = {
            "id" : "<?= $prof->idProfesseur ?>",
            "nom" : "<?= $prof->nom ?>",
            "filiere" : "<?= $prof->filiere_affecte ?>"
        }
    <?php 
         $k++;
    } 
    $k = 0;
    ?>
    <?php 
    foreach($listE as $etu){ ?>
        dataset.etudiant[<?= $k ?>] = {
            "id" : "<?= $etu->idEtudiant ?>",
            "nom" : "<?= $etu->nomEtudiant ?>",
            "code" : "<?= $etu->codeEtudiant ?>",
            "filiere" : "<?= $etu->filiere ?>",
            "niveau" : "<?= $etu->niveau ?>"
        }
    <?php
        $k++;
    } 
    $k = 0;
    ?>
</script> 
<div class="popup_entry <?= $user != null ? "active" : "" ?>">
    <div class="entry">
        <div class="popup_head"> 
            <h3> Enregistrer une note</h3>
        </div>
        <div class="popup_body">
            <form action="vue.php?p=notes" method="POST" class="form_entry">
                <input type="hidden" name="noteId" value=" <?= $user == null ? "" : $user->id_note?>" >
                <div class="input">
                    <?php
                        $sql = "select * from filiere";
                        $response = $db->query($sql);
                    ?>
                    <select name="filiere" <?= $user == null ? "" : "disabled" ?>>
                        <option value=''>Choisissez la filiere</option>
                        <?php foreach($response as $res){?>
                        <option value="<?= $res ->nom ?>" <?= $user == null || $user->filiere != $res->nom ? "" : "selected" ?> ><?= $res ->nom ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="input">
                     <select name="niveau" <?= $user == null ? "" : "disabled" ?>>
                        <option>Choisissez le niveau</option>
                        <?php
                            if($user != null){
                                foreach (explode(",", $liste_filiere[$user->filiere]) as $niveau){
                                    ?>
                                    <option value="<?= $niveau ?>" <?= $user == null || $user->niveau != $niveau ? "" : "selected" ?> ><?= $niveau ?></option>
                                    <?php
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="input">
                    <select name="session" <?= $user == null ? "" : "disabled" ?> >
                        <option value="">Selectionnez la session</option>
                        <option value="1" <?= $user == null || $user->session != 1 ? "" : "selected" ?> >Session 1</option>
                        <option value="2" <?= $user == null || $user->session != 2 ? "" : "selected" ?> >Session 2</option>
                    </select>
                </div>
                <div class="input">
                    <select name="etudiant" <?= $user == null ? "" : "disabled" ?>>
                        <option>Choisissez l'etudiant</option>
                        <?php
                        if($user != null){
                            foreach(selectEtudiant($user->filiere, $user->niveau) as $etu){
                        ?>
                         <option value="<?= $etu->idEtudiant ?>" <?= $user == null || $user->idEtudiant != $etu->idEtudiant ? "" : "selected" ?> ><?= $etu->prenom . " " . $etu->nom ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="input">
                    <select name="cours" <?= $user == null ? "" : "disabled" ?>>
                    <option>Selectionnez le cours</option>
                    <?php if($user != null){
                        foreach(selectCours($user->filiere, $user->niveau) as $cours ){
                    ?>
                        <option value="<?= $cours->codeCours ?>" <?= $user == null || $user->codeCours != $cours->codeCours ? "" : "selected" ?> ><?= $cours->nomCours ?></option>
                    <?php  }
                       }
                    ?>
                    </select>
                </div>
                <div class="input">
                     <input type="number" name="note" min="0" max="100" placeholder="Entrez la note sur 100" required name="noteSur100" value="<?= $user != null  ? $user-> noteSur100 : "" ?>">
                </div>
                <div class="button_input">
                    <button type="submit" value="submit" name="submit" id="submit" style="cursor:pointer;">Ajouter</button>
                    <button type="reset" value="clear" name="clear" id="quit" class="quit" style="cursor:pointer;">Quiter</button>
                </div>  
            </form>
        </div>
                    
    </div>
</div>
<div class="main-content">
    <div class="head">
       <div class="view">
           <span class=" las  la-chalkboard-teacher"></span>
           <h2 class=" title_view">Notes</h2>
       </div>
       <div class="AcadY">
           <span class="iconY las  la-calendar-check"></span>
           <span class="ActualY"><?= $AYBanner ?></span>
       </div>
    </div>

    <?php if($requestSent && !empty($message)){ ?>
        <div class="boxAlert"><?= $message ?></div>
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
            <form action="vue.php?p=notes" class="rang_entry" method="POST">
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
                        <option value="niveau">Choisissez le niveau</option>
                        <option></option>
                    </select>
                </div>
                <div class="rang_input">
                    <select name="session">
                        <option value="">choisissez la session</option>
                        <option value="1" <?= $user != null && $user-> session == $user->session ? "selected" : "" ?> >session 1</option> 
                        <option value="1" <?= $user != null && $user-> session == $user->session ? "selected" : "" ?> >session 2</option> 
                    </select>
                </div> 
            </form>
        </div>
        <div class="see" id="notes">
            <div class="button_head">
                <button class="print">
                    <span class="print_icon las  la-plus-square"></span>
                    <span class="add_span"></span> 
                </button>
            </div>
            <div class="forme_print" id="form1">
                <div class="colums">
                    <div class="column">Code</div>
                    <div class="column">Nom</div>
                    <div class="column">Prenom</div>
                    <div class="column">Filiere</div>
                    <div class="column">Niveau</div>
                    <div class="column">Session</div>
                    <div class="column">Matiere</div>
                    <div class="column">Note total</div>
                    <div class="column">operation</div>
                </div> 
                <div class="allrows">
                    <?php
                    //    $sql="select * from notes";
                    $sql = "SELECT n.*, e.codeEtudiant, e.nomEtudiant, e.prenomEtudiant, c.nomCours, c.niveau, c.session, c.filiere FROM notes n, etudiant e, cours c WHERE e.idEtudiant = n.idEtudiant AND c.codeCours = n.codeCours";

                        $response = $db->query($sql);               

                        if ($db->error == '') {
                            // var_dump($response);
                            foreach($response as $res){
                        ?>

                    <div class="rows">           
                        <div class="row point code"><?= $res->codeEtudiant ?></div>
                        <div class="row point "><?= $res ->nomEtudiant?></div><!--<%= data.get("nomEtudiant") %></div> -->
                        <div class="row point "><?= $res ->prenomEtudiant?> </div> <!-- <%= data.get("prenomEtudiant") %></div> -->
                        <div class="row point filiere"><?= $res ->filiere?></div><!--<%= data.get("filiere") %></div> -->
                        <div class="row point " style="display: none"> <?= $res ->niveau?></div><!-- <%= data.get("niveau") %></div> -->
                        <div class="row point " style="display: none"><?= $res ->session?></div><!--<%= data.get("session") %></div> -->
                        <div class="row"><?= $res ->nomCours?></div> <!--<%= data.get("nomCours") %></div> -->
                        <div class="row"><?= $res ->noteSur100?></div><!--<%= data.get("noteSur100") %></div> -->
                        <div class="row icons_operation"> 
                            <form method="post"  action=""><!-- action="./panel?res=note"> -->
                                <input type="hidden" name="modifier" value="<?= $res ->id_note?>">
                                <button type="submit" class=" pen las  la-pencil-alt"></button>
                            </form>
                            <form method="post" action=""> <!-- action="./panel?res=note"> -->
                                <input type="hidden" name="trash" value="<?= $res ->id_note?>">
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
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/note.js"></script>

<style>
    .rows {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(9, 1fr);
        justify-content: space-between;
        align-items: center;
        color: #efefef;
        font-size: 1em;
        padding: 1em;
        border: solid 0.1em #efefef;
        position: relative;
    }
</style>