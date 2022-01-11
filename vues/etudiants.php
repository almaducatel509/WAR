<?php
// "<?php echo $dnn['id']; 
require_once ('dbutils/PDO.php');

    if(isset($_SESSION['dba'])) {
        $db =$_SESSION['dba'];
    }else{
        $db = new DBA();
        // $db = dbConnect();
    }
    $filieres = script_filiere();
    $user= null;
    if(isset($_POST["modifier"])) {
        $modifier = $_POST["modifier"];
        $sql = "SELECT * FROM etudiant WHERE idEtudiant= :idEtudiant";
        $data = ['idEtudiant'=> $modifier];
        $response = $db->query($sql, $data);
        $user = $response[0];
    }

    if(isset($_POST["trash"])) {
        $modifier = $_POST["trash"];
        $sql = "delete FROM etudiant WHERE idEtudiant= :idEtudiant";
        $data = ['idEtudiant'=> $modifier];
        $response = $db->query($sql, $data);
    }


$sql = "select * from etudiant";
    $response = $db ->query($sql);
    $requestSent = false;
    $isModification = false;
    
    if ($db->error == '') {
        // var_dump( $response);
        if(isset($_POST["firstname"]) &&isset($_POST["lastname"])
         && isset($_POST["sexe"]) && isset($_POST["adresse"]) && isset($_POST["telephone"])
         && isset($_POST["lieuNaissance"]) && isset($_POST["dateNaissance"]) 
         && isset($_POST["personneRef"]) && isset($_POST["telRef"]) && isset($_POST["email"])
         && isset($_POST["cinNif"]) && isset($_POST["filiere"]) && isset($_POST["niveau"])){
            $requestSent = true;
            $etuId= htmlspecialchars(stripslashes(trim($_POST["etuId"]))); 
            $nom = slashquote(htmlspecialchars(stripslashes(trim($_POST["firstname"]))));
            $prenom = slashquote(htmlspecialchars(stripslashes(trim ($_POST["lastname"]))));
            $sexe = htmlspecialchars(stripslashes(trim ($_POST["sexe"])));
            $adresse = slashquote(htmlspecialchars(stripslashes(trim ($_POST["adresse"]))));
            $telephone = htmlspecialchars(stripslashes(trim($_POST["telephone"])));
            $lieuNaissance = htmlspecialchars(stripslashes(trim ($_POST["lieuNaissance"])));
            $dateNaissance = htmlspecialchars(stripslashes(trim ($_POST["dateNaissance"])));
            $personneRef = slashquote(htmlspecialchars(stripslashes(trim($_POST["personneRef"]))));
            $telRef = htmlspecialchars(stripslashes(trim ($_POST["telRef"])));
            $email = htmlspecialchars(stripslashes(trim ($_POST["email"])));
            $cinNif = htmlspecialchars(stripslashes(trim($_POST["cinNif"])));
            $filiere = slashquote(htmlspecialchars(stripslashes(trim ($_POST["filiere"]))));
            $niveau = htmlspecialchars(stripslashes(trim ($_POST["niveau"])));
            $memo = htmlspecialchars(stripslashes(trim ($_POST["memo"])));
            
            // echo 'Prénom : '.$_POST["firstname"].'<br>';
            // echo 'Email : ' .$_POST["lastname"].'<br>';
            // echo 'Age : ' .$_POST["sexe"].'<br>';
            // echo 'Sexe : ' .$_POST["adresse"].'<br>';
            // echo 'Pays : ' .$_POST["telephone"].'<br>';

            if((!empty($_POST["firstname"])) && (!empty($_POST["lastname"]))
            && (!empty($_POST["sexe"])) && (!empty($_POST["adresse"])) && (!empty($_POST["telephone"]))
            && (!empty($_POST["lieuNaissance"])) && (!empty($_POST["dateNaissance"]))
            && (!empty($_POST["personneRef"])) &&( !empty($_POST["telRef"])) && (!empty($_POST["email"]))
            && (!empty($_POST["cinNif"])) && (!empty($_POST["filiere"])) && (!empty($_POST["niveau"]))){
            
            $codeEtudiant = getCode($nom, $prenom);
            if(empty($etuId)){
                $sql ="INSERT INTO etudiant(codeEtudiant, nomEtudiant, prenomEtudiant, sexe, adresse, lieu_naissance, date_naissance, telephone, email, filiere, niveau, nif_cin, personne_ref, tel_ref, memo, annee_academique)
                    VALUES ('$codeEtudiant','$nom','$prenom', '$sexe','$adresse','$lieuNaissance', '$dateNaissance', '$telephone','$email', '$filiere','$niveau', '$cinNif', '$personneRef', '$telRef', '$memo',(select id from annee_academique where etat = 'O' limit 1))";
                $response = $db->query($sql);
            }
            else{
                $isModification = true;
                $sql ="UPDATE etudiant SET nomEtudiant='$nom', prenomEtudiant='$prenom', sexe='$sexe', adresse='$adresse', lieu_naissance='$lieuNaissance', date_naissance='$dateNaissance', 
                telephone='$telephone', email='$email', filiere='$filiere', niveau='$niveau', nif_cin= '$cinNif', personne_ref= '$personneRef', tel_ref='$telRef', memo='$memo'
                where idEtudiant=$etuId";
                $response = $db->query($sql);
            }
         } else $erreure="Un champ est vide";
        }
    }

?> 
<div class="popup_entry <?= $user != null ? "active" : "" ?>">
    <div class="entry ">
        <div class="popup_head">
            <h3>Enregistrer un(e) etudiant(e)</h3>
        </div>
        <div class="popup_body">
            <form action="vue.php?p=etudiants" method="post" class="form_entry" name="formulaire">
                <input type="hidden" name="etuId" value="<?= $user != null ? $user->idEtudiant : "" ?>">
                <div class="avatar" style="">
                    <input class="file" type="file" value="" />
                    <i class="las la-camera"></i>
                </div> 
                <input class="avatar_input" type="hidden" name="avatar" value="" />
                <div class="input">
                    <label for="firstname" class="labels">Entrez le prenom</label>
                    <input type="text" name="firstname" value="<?= $user != null ? $user->prenomEtudiant : "" ?>" required>
                </div>
                <div class="input">
                    <label for="lastname" class="labels">Entrez le nom </label>
                    <input type="text" name="lastname" value="<?= $user != null ? $user->nomEtudiant : "" ?>" required>
                </div>
                <div class="input">
                    <div class="btn_radio">
                        <input type="radio" name="sexe" value="femme" <?= $user != null && $user->sexe == "femme" ? "checked" : "" ?> required>
                        <label for="femal">Femme</label><br>
                        <input type="radio" name="sexe" value="homme" <?= $user != null && $user->sexe == "homme" ? "checked" : "" ?> required>
                        <label for="mal">Homme</label><br>
                    </div>
                </div>
                <div class="input">
                    <label for="adresse" class="labels">Entrez l'adresse</label>
                    <input type="text" name="adresse" value="<?= $user != null ? $user->adresse : "" ?>" required/> 
                </div>
                <div class="input" class="labels">
                    <label for="tel">Entrez le numero de telephone </label>
                    <input type="text" name="telephone" required  placeholder="---- ----" value="<?= $user != null ? $user->telephone : "" ?>">
                </div>
                <div class="input">
                    <label for="lieuNaissance" class="labels">Entrez lieu de naissance </label>
                    <input type="text" name="lieuNaissance" required placeholder="Lieu de naissance" value="<?= $user != null ? $user->lieu_naissance : "" ?>">
                </div>
                <div class="input">
                    <label for="datenaissance" class="labels">Entrez la date de naissance </label>
                    <input type="date" name="dateNaissance" required placeholder="Date" value="<?= $user != null ? $user->date_naissance : "" ?>">             
                </div> 
                <div class="input">
                    <label for="personneRef" class="labels">Entrez la personne de reference</label>
                    <input type="text" name="personneRef" required placeholder="Nom et prenom" value="<?= $user != null ? $user->personne_ref : "" ?>">
                </div> 
                 <div class="input">
                    <label for="telRef" class="labels">Entrez le telephone reference</label>
                    <input type="tel" id="phone" name="telRef" required value="<?= $user != null ? $user->tel_ref : "" ?>">
                </div> 
                <div class="input">
                    <label for="email" class="labels">Entrez l'email</label>
                    <input type="text" name="email" value="<?= $user != null ? $user->email : "" ?>" required placeholder="Email"> 
                </div>
                <div class="input">
                    <label for="cinNif" class="labels">Entrez le CIN/NIF </label>
                    <input type="text" name="cinNif" value="<?= $user != null ? $user->nif_cin : "" ?>" required placeholder="cin/nif" value="">
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
                        <option value="">Sélectionnez le niveau</option>
                        <?php
                            if($user != null){
                                foreach(explode(",",$filieres[$user->filiere]) as $niveau){
                                    ?>
                                    <option value="<?= $niveau ?>" <?= $niveau == $user->niveau ? "selected" : ""?> ><?= $niveau ?></option>
                                    <?php
                                }
                            }
                        ?>
                    </select>     
                </div>
                <div class="textarea_style">
                    <label for="memo" class="labels">Memo
                    <textarea name="memo" class ="memo" rows="4" cols="37" maxlength="100" minlength="3" placeholder="Laissez un commentaire ici..."><?= $user != null ? $user->memo : "" ?></textarea></label>
                </div> 
                <div class="button_input">
                    <button type="submit" value="Submit" name="submit" id="submit"  style="cursor:pointer;">Ajouter</button>
                    <button type="reset" value="Clear" name="clear" id="quit" class="quit"  style="cursor:pointer;">Quiter</button>
                </div>
            </form>
        </div>
       
    </div>
</div>     

<div class="main-content">
    <div class="head">
       <div class="view">
           <span class=" las  la-chalkboard-teacher"></span>
           <h2 class=" title_view">Etudiants</h2>
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
                <form action="vue.php?p=etudiants" class="rang_entry" method="POST">
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
                            <option></option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="see">
                <div class="button_head">
                    <button class="print" onclick="printForm('form1')">
                        <span class="print_icon las  la-print"></span>
                        <span class="print_span"></span> 
                    </button>
                    <button class="print">
                        <span class="print_icon las  la-plus-square"></span>
                        <span class="add_span"></span> 
                    </button>
<!--                    <form method="post" action="etudiant.php" style="display: inline-block">-->
<!--                        <input type="hidden" name="excel">-->
<!--                        <input class="file import-file" type="file" value="" />-->
<!--                        <button class="import print">-->
<!--                            <span class="print_icon las la-file-upload"></span>-->
<!--                            <span class="file_span"></span> -->
<!--                        </button>-->
<!--                    </form>-->
                </div>
                
                <div class="forme_print" id="form1">                 
                    <div class="colums">
                        <div class="column"> Code</div>
                        <div class="column">Profil</div>
                        <div class="column">Nom</div>
                        <div class="column">Prenom</div>
                        <div class="column">Filiere</div>
                        <div class="column"> Niveau</div>
                        <div class="column"> Statut</div>
                        <div class="column">operation</div>

                    </div>
                    <div class="allrows">
                    <?php
                        $sql = "select * from etudiant";
                        $response = $db->query($sql);
                        if ($db->error == '') {
                            // var_dump($response);
                            foreach($response as $res){
                        ?>
                            <div class="rows">
                                <div class="row"><?= $res ->codeEtudiant?></div>
                                <div class="row profil_img" style="justify-content: center; background-image: url(<?= $res ->profil === null ? "Dr_dada.png" : $res ->profil?>);"></div> 
                                <div class="row point nom"><?= $res ->nomEtudiant ?></div> 
                                <div class="row point prenom"><?= $res ->prenomEtudiant?></div> 
                                <div class="row point filiere"><?= $res ->filiere ?></div>
                                <div class="row point niveau"><?= $res ->niveau ?></div> 
                                <div class="row"> <?= $res ->etat ?></div>
                                <div class="row icons_operation"> 
                                    <form method="post" action="vue.php?p=etudiants">
                                            <input type="hidden" name="modifier" value="<?= $res ->idEtudiant ?>">
                                            <button type="submit" class=" pen las  la-pencil-alt"></button>
                                    </form>                         
                                    <form method="post" action="vue.php?p=etudiants">
                                        <input type="hidden" name="trash" value="<?= $res -> idEtudiant?>">
                                        <button href="#"class=" trash las la-trash-alt" type="submit"></button> 
                                    </form> 
                                    <i class="las la-angle-down"></i> 
                                </div>
                                    <div class="row-info"> 
                                    <div class="row Iprofil_img" style="justify-content: center; background-image: url(<?= $res ->profil == null ? "Dr_dada.png" : $res -> profil ?>);"></div> 
                                    <div>
                                    <div class="list_info">
                                        <div class="info code"> 
                                            <span class="span_info">Code:</span>
                                            <span><?= $res ->codeEtudiant?></span> 
                                        </div>
                                        <div class="info nom">
                                            <span class="span_info">Nom:</span>
                                            <span> <?= $res ->nomEtudiant?></span> 
                                        </div> 
                                        <div class="info prenom"> -->
                                            <span class="span_info">Prenom:</span>
                                            <span><?= $res -> prenomEtudiant ?></span>
                                        </div>
                                        <div class="info sexe">
                                            <span class="span_info">Sexe:</span>
                                            <span><?= $res ->sexe ?></span> 
                                        </div>
                                        <div class="info adresse">
                                            <span class="span_info">Adress:</span>
                                            <span><?= $res ->adresse ?></span>
                                        </div>
                                        <div class="info telephone">
                                            <span class="span_info">Tel:</span>
                                            <span><?= $res -> telephone ?></span>
                                        </div>
                                        <div class="info email">
                                                <span class="span_info">Email:</span>
                                                <span><?= $res ->email ?></span>
                                        </div>
                                        <div class="info lieu_naissance">
                                            <span class="span_info">Lieu Naissance:</span>
                                            <span><?= $res ->lieu_naissance ?></span>
                                        </div>
                                        <div class="info date_naissance">
                                            <span class="span_info">Date de naissance:</span>
                                            <span><?= $res ->date_naissance ?></span>
                                        </div>
                                        <div class="info personneRef">
                                            <span class="span_info">Personne de reference:</span>
                                            <span><?= $res -> personne_ref ?></span>
                                        </div>
                                        <div class="info tel_ref">
                                            <span class="span_info"> Tel personne de reference:</span>
                                            <span><?= $res -> tel_ref ?></span>
                                        </div>
                                        <div class="info niveau">
                                            <span class="span_info"> Niveau:</span>
                                            <span><?= $res -> niveau ?></span>
                                        </div>
                                        <div class="info filiere">
                                            <span class="span_info">Filiere:</span>
                                            <span><?= $res -> filiere ?></span>
                                        </div>
                                        <div class="info cin_nif">
                                            <span class="span_info">CIN/NIF: </span>
                                            <span><?=$res-> nif_cin ?></span>
                                        </div>

                                        <div class="info memo">
                                            <span class="span_info">Memo: </span>
                                            <span> <?= $res -> memo ?></span> 
                                        </div>
                                        <div class="etats">
                                            <form method="post" action="">
                                                <div class="etat">
                                                    <input type="hidden" name="etat" value="">
                                                    <button type="submit" class="etat-btn">A</button>
                                                </div>
                                                <div>
                                                    <input type="hidden" name="etat" value="">
                                                    <button class="etat-btn" type="submit">E</button>
                                                </div>
                                                <div>
                                                    <input type="hidden" name="etat" value=""> -->
                                                    <button class="etat-btn" type="submit">D</button> -->
                                                </div> 
                                                <div>
                                                        <input type="hidden" name="etat" value=""> 
                                                        <button class="etat-btn" type="submit">T</button> 
                                                    </div> 

                                                </form>
                                            </div>
                                    </div> 
                                    </div>
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
<script src="js\zepto.js"></script>
<script src="js\scroll.js"></script>
<script src="js\popup.js"></script>
<script src="js\print.js"></script>
<!--<script src="js\files.js"></script>-->

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

