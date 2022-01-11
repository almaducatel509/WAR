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
        $sql = "SELECT * FROM professeur WHERE idProfesseur= :idProfesseur";
        $data = ['idProfesseur'=> $modifier];
        $response = $db->query($sql, $data);
        $user = $response[0];
    }

    if(isset($_POST["trash"])) {
        $modifier = $_POST["trash"];
        $sql = "delete FROM professeur WHERE idProfesseur= :idProfesseur";
        $data = ['idProfesseur'=> $modifier];
        $response = $db->query($sql, $data);
    }
        
    $sql = "select * from professeur";
    $response = $db ->query($sql);
    $requestSent = false;
    $isModification = false;
    
    if ($db->error == '') {
        // var_dump( $response);
        if(isset($_POST["firstname"]) &&isset($_POST["lastname"])
         && isset($_POST["sexe"]) && isset($_POST["adresse"]) && isset($_POST["tel"])
         && isset($_POST["lieuNaissance"]) && isset($_POST["dateNaissance"]) 
         && isset($_POST["statutM"]) && isset($_POST["salaire"]) && isset($_POST["email"])
         && isset($_POST["cinNif"]) && isset($_POST["filiere"]) && isset($_POST["niveau"])
         && isset($_POST["niveau"])){
            $requestSent = true;
            $profId= htmlspecialchars(stripslashes(trim($_POST["profId"]))); 
            $nom = slashquote(htmlspecialchars(stripslashes(trim($_POST["firstname"]))));
            $prenom = slashquote(htmlspecialchars(stripslashes(trim ($_POST["lastname"]))));
            $sexe = htmlspecialchars(stripslashes(trim ($_POST["sexe"])));
            $adresse = slashquote(htmlspecialchars(stripslashes(trim ($_POST["adresse"]))));
            $telephone = htmlspecialchars(stripslashes(trim($_POST["tel"])));
            $lieuNaissance = slashquote(htmlspecialchars(stripslashes(trim ($_POST["lieuNaissance"]))));
            $dateNaissance = htmlspecialchars(stripslashes(trim ($_POST["dateNaissance"])));
            $statutM =slashquote(htmlspecialchars(stripslashes(trim($_POST["statutM"]))));
            $salaire = htmlspecialchars(stripslashes(trim ($_POST["salaire"])));
            $email = htmlspecialchars(stripslashes(trim ($_POST["email"])));
            $cinNif = htmlspecialchars(stripslashes(trim($_POST["cinNif"])));
            $filiere = slashquote(htmlspecialchars(stripslashes(trim ($_POST["filiere"]))));
            $niveau = htmlspecialchars(stripslashes(trim ($_POST["niveau"])));
            $memo = htmlspecialchars(stripslashes(trim ($_POST["memo"])));
            $poste = slashquote(htmlspecialchars(stripslashes(trim ($_POST["poste"]))));
            // echo 'Prénom : '.$_POST["firstname"].'<br>';
            // echo 'Email : ' .$_POST["lastname"].'<br>';
            // echo 'Age : ' .$_POST["sexe"].'<br>';
            // echo 'Sexe : ' .$_POST["adresse"].'<br>';
            // echo 'Pays : ' .$_POST["telephone"].'<br>';

            if((!empty($_POST["firstname"])) && (!empty($_POST["lastname"]))
            && (!empty($_POST["sexe"])) && (!empty($_POST["adresse"])) && (!empty($_POST["tel"]))
            && (!empty($_POST["lieuNaissance"])) && (!empty($_POST["dateNaissance"]))
            && (!empty($_POST["statutM"])) &&( !empty($_POST["salaire"])) && (!empty($_POST["email"]))
            && (!empty($_POST["cinNif"])) && (!empty($_POST["filiere"])) && (!empty($_POST["niveau"]))){
            
            $codeProfesseur = getCode($nom, $prenom);
            if(empty($profId)){
                $sql ="INSERT INTO professeur( codeProfesseur, nom, prenom, sexe, adresse, telephone, statut_matrimonial, lieu_naissance, date_naissance, niveau, filiere_affecte, poste, salaire, email, nif_cin, memo)
                    VALUES ('$codeProfesseur','$nom','$prenom', '$sexe','$adresse', '$telephone', '$statutM','$lieuNaissance', '$dateNaissance','$niveau','$filiere','$poste','$salaire','$email', '$cinNif',  '$memo')";
                $response = $db->query($sql);
            }
            else{
                $isModification = true;
                $sql ="UPDATE professeur SET nom='$nom', prenom='$prenom', sexe='$sexe', adresse='$adresse', lieu_naissance='$lieuNaissance', date_naissance='$dateNaissance', 
                telephone='$telephone', email='$email', filiere_affecte='$filiere', niveau='$niveau', poste='$poste', nif_cin= '$cinNif', statut_matrimonial= '$statutM', salaire='$salaire', memo='$memo'
                where idProfesseur=$profId";
                $response = $db->query($sql);
            }
         } else $erreure="Un champ est vide";
        }
    }

?> 
<div class="popup_entry <?= $user != null ? "active" : "" ?>">
    <div class="entry">
        <div class="popup_head">
            <h3>Enregistrer un professeur</h3>
        </div>
        <div class="popup_body">  
            <form action="vue.php?p=professeur" method="post" class="form_entry">
                <input type="hidden" name="profId" value="<?= $user != null ? $user->idProfesseur : "" ?>">
                <div class="avatar" style="">
                    <input class="file" type="file" value="" />
                    <i class="las la-camera"></i>
                </div> 
                <input class="avatar_input" type="hidden" name="avatar" value="" />
                <div class="input">
                    <label for="firstname" class="labels"> Entrez le prenom</label>
                    <input type="text" name="firstname" placeholder="prenom" value="<?= $user != null ? $user->prenom : "" ?>"> 
                </div>
                <div class="input">
                    <label for="lastname" class="labels"> Nom</label>
                   <input type="text" name="lastname" placeholder="nom" value="<?= $user != null ? $user->nom : "" ?>">  
                </div>
                <div class="input">
                    <div class="btn_radio">
                        <input type="radio" name="sexe" value="femme" <?= $user != null && $user->sexe == "femme" ? "checked" : "" ?> required>
                        <label for="femal">Femme</label><br>
                        <input type="radio" name="sexe" value="homme" <?= $user != null && $user->sexe == "homme" ? "checked" : "" ?> required>
                        <label for="mal">Homme</label><br>
                    </div>
                </div>
                <div class="input" >
                    <label for="adresse" class="labels"> Entrez l'adresse</label>
                   <input type="text" name="adresse" placeholder="adresse" value="<?= $user != null ? $user->adresse : "" ?>"> 
                </div>

                <div class="input">
                    <label for="poste"  class="labels"> Entrez le poste</label>
                    <input type="text" name="poste" required value="<?= $user != null ? $user->poste : "" ?>">
                </div>

                <div class="input">
                    <label for="tel"  class="labels">Numero de telephone</label>
                    <input type="text" name="tel" placeholder="---- ----" value="<?= $user != null ? $user->telephone : "" ?>"> 
                </div>
                <div class="input" >
                   <div class="btn_radio">
                        <input type="radio" id="marie" name="statutM" value="marie" <?= $user != null && $user->statut_matrimonial == "marie" ? "checked" : "" ?> required >
                        <label for="marie">Marie(e)</label><br>
                        <input type="radio" id="Celibatair" name="statutM" value="Celibatair" <?= $user != null && $user->statut_matrimonial == "Celibatair" ? "checked" : "" ?> required> 
                        <label for="Celibatair">Celibatair(e)</label><br>
                        <input type="radio" id="uLibre" name="statutM" value="Union_libre" <?= $user != null && $user->statut_matrimonial == "Union_libre" ? "checked" : "" ?> required> 
                        <label for="uLibre">Union libre</label><br>
                    </div>
                </div>
                <div class="input" class="labels">
                    <label for="lieuNaissance" class="labels">Lieu de naissance</label>
                     <input type="text" name="lieuNaissance" placeholder="lieu de naissance" value="<?= $user != null ? $user-> lieu_naissance : "" ?>"> 
                </div>
                <div class="input">
                    <label for="datenaissance" class="labels">Date de naissance</label>
                     <input type="date" name="dateNaissance" placeholder="lieu de naissance" value="<?= $user != null ? $user-> date_naissance : "" ?>" > 
                </div> 
                <div class="input">
                    <label for="niveau"  class="labels"> Seletionnez le niveau</label>
                     <input type="text" name="niveau" value="<?= $user != null ? $user-> niveau : "" ?>"> 
                </div>
                <div class="input" >
                    <label for="salaire"  class="labels">Votre salaire</label>
                    <input type="text" name="salaire" value="<?= $user != null ? $user-> salaire : "" ?>">  
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
                            <option value="<?=$res->nom?>" <?= $user != null && $user->filiere_affecte == $res->nom ? "selected" : "" ?> ><?= $res ->nom ?></option>
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
                    <label for="email"  class="labels">Votre email</label>
                    <input type="text" name="email" placeholder="email" value="<?= $user != null ? $user-> email : "" ?>" >
                </div>
                <div class="input">
                    <label for="cinNif"  class="labels">CIN/NIF</label>
                   <input type="text" name="cinNif" placeholder="cin/nif" value="<?= $user != null ? $user-> nif_cin : "" ?>"> 
                </div> 
                <div class="textarea_style input">
                    <label for="memo" class="labels">Memo</label>
                    <textarea name="memo" class ="memo" rows="4" cols="37" maxlength="100" minlength="3" placeholder="Commentaire..."></textarea>
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
           <h2 class=" title_view">Pofesseurs</h2>
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
            <form action="vue.php?p=professeur" class="rang_entry" method="POST">
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
                        <option value="">Selectioner la session</option>
                        <option value="1">Session 1</option>
                        <option value="2">Session 2</option>
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
            </div>
            
            <div class="forme_print" id="form1">                  
                <div class="colums">
                    <div class="column">Code</div>
                    <div class="column">Profil</div>
                    <div class="column">Nom</div>
                    <div class="column">Prenom</div>
                    <div class="column">Sexe</div>
                    <div class="column">Statut</div>
                    <div class="column">operation</div>
                </div>
                <div class="allrows">
                    <?php
                        $sql = "select * from professeur";
                        $response = $db->query($sql);
                        if ($db->error == '') {
                            // var_dump($response[3]);
                            foreach($response as $res){
                    ?>
                    <div class="rows">
                        <div class="row"> <?= $res-> codeProfesseur ?></div>
                        <div class="row profil_img" style="justify-content: center; background-image: url(<?= $res ->profil === null ? "Dr_dada.png" : $res ->profil?>);"></div> 
                        <div class="row"><?= $res ->nom ?></div>
                        <div class="row"><?= $res-> prenom?></div> 
                        <div class="row"> <?= $res ->sexe ?> </div>
                        <div class="row"> <?= $res ->etat ?> </div>  
                        <div class="row icons_operation"> 
                            <form method="post" action="vue.php?p=professeur">
                                <input type="hidden" name="modifier" value="<?= $res-> idProfesseur?>">
                                <button type="submit" class=" pen las  la-pencil-alt"></button>
                            </form>
                            <form method="post" action="vue.php?p=professeur">
                                 <input type="hidden" name="trash" value="<?= $res-> idProfesseur?>">
                                <button class=" trash las la-trash-alt" type="submit"></button>
                            </form>
                                <i class="las la-angle-down"></i>
                        </div>
                        <div class="row-info">
                        <div class="row profil_img" style="justify-content: center; background-image: url(<?= $res ->profil === null ? "Dr_dada.png" : $res ->profil?>);"></div> 
                            <div>
                                <div class="list_info">
                                    <div class="info code"> 
                                        <span class="span_info">Code:</span>
                                        <span><?= $res ->codeProfesseur ?></span></div>
                                    <div class="info nom">
                                        <span class="span_info">Nom:</span>
                                        <span class="point nom"><?= $res ->nom?></span>
                                    </div>
                                    <div class="info">
                                        <span class="span_info">Prenom:</span>
                                        <span class="point prenom"><?= $res ->prenom ?></span>
                                    </div>
                                    <div class="info sexe">
                                        <span class="span_info">Sexe:</span>
                                        <span><?= $res ->sexe ?></span>
                                    </div>
                                    <div class="info adresse">
                                        <span class="span_info">Adresse:</span>
                                        <span><?= $res -> adresse?></span>
                                    </div>
                                    <div class="info telephone">
                                        <span class="span_info">Tel:</span>
                                        <span><?= $res ->telephone ?></span>
                                    </div>
                                    <div class="info statut_matrimonial">
                                        <span class="span_info">Statut Matrimonial:</span>
                                        <span><?= $res ->statut_matrimonial ?></span>
                                    </div>
                                    <div class="info lieu_naissance">
                                        <span class="span_info">Lieu Naissance:</span>
                                        <span><?= $res-> lieu_naissance ?></span>
                                    </div>
                                    <div class="info date_naissance">
                                        <span class="span_info">Date de naissance:</span>
                                        <span><?= $res -> date_naissance ?></span>
                                    </div>
                                    <div class="info niveau">
                                        <span class="span_info point">Niveau:</span>
                                        <span><?= $res -> niveau ?></span>
                                    </div>
                                    <div class="info">
                                        <span class="span_info">Filiere afectee:</span>
                                        <span class="point filiere"><?= $res ->filiere_affecte ?></span>
                                    </div>
                                    <div class="info poste">
                                        <span class="span_info">Poste:</span>
                                        <span class="point"><?= $res ->poste ?></span>
                                    </div>
                                    <div class="info salaire">
                                        <span class="span_info">Salaire:</span>
                                        <span><?= $res-> salaire ?></span>
                                    </div>
                                    <div class="info email">
                                        <span class="span_info">Email:</span>
                                        <span><?= $res->email?></span>
                                    </div>
                                    <div class="info cin_nif">
                                        <span class="span_info">CIN/NIF: </span>
                                        <span><?= $res->nif_cin?></span>
                                    </div>
                                    <div class="info memo">
                                        <span class="span_info">Memo: </span>
                                        <span><?= $res -> memo ?></span>
                                    </div>
                                    <div class="etats">
                                        <form method="post" action="">
                                            <input type="hidden" name="etat" value="">
                                            <div class="buttons">
                                                <button type="submit" class="etat-btn">A</button>
                                                <button class="etat-btn" type="submit">I</button>
                                                <button class="etat-btn" type="submit"> E</button>
                                                <button class="etat-btn" type="submit">C</button>
                                                <button class="etat-btn active" type="submit">M</button>
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

<?php
    // }else{
    //     echo "Log out Need to implement";   
    // }
?>
<script src="js\zepto.js"></script>
<script src="js\scroll.js"></script>
<script src="js\popup.js"></script>
<script src="js\print.js"></script>
<script src="js\files.js"></script>
<script src="js\note.js"></script>
<style>
    .rows {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        justify-content: space-between;
        align-items: center;
        color: #efefef;
        font-size: 1em;
        padding: 1em;
        border: solid 0.1em #efefef;
        position: relative;
}
</style>