<?php

require_once ('dbutils/PDO.php');
    if(isset($_SESSION['dba'])) {
        $db =$_SESSION['dba'];
    }else{
        $db = new DBA();
    }
   
    $sql = "select * from annee_academique";
    $response = $db->query($sql);
    $user = isset($response[0]) ? $response[0] : null;
    $requestSent = false;
    $message = "";
    $modifier = null;
    $AY = null;
    if(isset($_POST["pass"])){
        $passer = $_POST['pass'];
        $requestSent = true;
        $result = passerEtudiants($passer);
        if($result[0] == $result[1]){
            $next = anneeSuivant($passer);
            if($next > 0){
                $db->query("update annee_academique set etat = 'F' where id=".$passer);
                $db->query("update annee_academique set etat = 'O' where id=".$next);
            }
            else{
                $result[1] = 0;
            }
        }
        $message = $result[0] != $result[1] || $result[1] == 0 ? $result[1] == 0 ? "La passation n'est pas encore prête !" : ($result[1] - $result[0])." étudiant(s) sur ".$result[1]." n'a/ont pas subi certaine(s) épreuve(s)" : "Passation des étudiants réussie !";
    }
    if(isset($_POST["modifier"])) {
        $modifier = $_POST["modifier"];
//        $passer = $_POST["pass"];
        $sql = "SELECT * FROM annee_academique WHERE id = :id";
        $data = ['id'=> $modifier];
        $AY = $db->query($sql, $data);
        if(count($AY)) $AY = $AY[0];

        if ($db->error == '') {
            if($AY == null){
                exit();
            }
        }
        else{
            var_dump($db->error);
        }
    }
//     $db->executeTransaction($sql, $data);
    if(isset($_POST["dateDebut"]) && isset($_POST["dateFin"])){
        $debut = htmlentities($_POST["dateDebut"]);
        $fin = htmlentities($_POST["dateFin"]);
        $ayId = isset($_POST["aid"]) ? $_POST["aid"] : "";
        $data = ["pDebut"=>$debut, "pFin"=>$fin];
        $requestSent = true;
        $isModification = false;
        if(!estUneAnneeAcademique($debut, $fin)){
            $message = "Les dates sont invalides !";
        }
        else if(!empty($debut) && !empty($fin)){
            if(empty($ayId)){
                $last = $db->query("select * from annee_academique where etat='O'");
                $last = count($last) > 0;
                $data["etat"] = $last ? 'F' : 'O';
                $sql = "insert into annee_academique(date_debut, date_fin, annee_debut, annee_fin, academicY,etat) VALUES(:pDebut, :pFin, year(:pDebut), year(:pFin), CONCAT(year(:pDebut), '-', year(:pFin)), :etat )";
                $response = $db->query($sql,$data);
            }else{
                $isModification = true;
                $data["pidA"] = $ayId;
                $sql="UPDATE annee_academique SET date_debut=:pDebut,date_fin=:pFin,annee_debut=year(:pDebut),annee_fin=year(:pFin), academicY=CONCAT( year(:pDebut), '-', year(:pFin)) WHERE id=:pidA";
                $response = $db->query($sql,$data);
            }
            if ($db->error == '') {
                $message = $isModification ? "Enregistrement réussi !" : "Modification réussie !";
            } else {
                $message = 'Error encountered [ '. $isModification . " ] " . $db->error;
            }
        }
    }

    
?>
 <div class="popup_entry  <?= $AY != null ? "active" : "" ?>">
    <div class="entry ">
        <div class="popup_head">
            <h3>Nouvelle annee academique</h3>
        </div>
        <div class="popup_body">
            <form action="vue.php?p=academique" method="post" class="form_entry">
                <input type="hidden" name="aid" value="<?= $AY != null ? $AY->id : "" ?>">
                <div class="input">
                    <label for="dateDebut" class="labels">Entrez la date du debut</label>
                    <input type="date" name="dateDebut" value="<?= $AY == null ? "" : $AY->date_debut ?>" >
                </div>
                <div class="input">
                    <label for="dateFIN" class="labels">Entrez la date de la fin</label>
                    <input type="date" name="dateFin" value="<?= $AY == null ? "" : $AY->date_fin ?>" >
                </div> 
               
            </form>
        </div>
        <div class="button_input">
            <button type="submit" value="Submit" name="submit" id="submit">Ajouter</button>
            <button type="reset" value="Clear" name="clear" id="quit" class="quit">Quiter</button>
        </div>
    </div>
</div>

<div class="head">
    <div class="view">
        <span class="las la-chalkboard-teacher"></span>
        <h2 class=" title_view">Annee Academique</h2>
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
    <div class="see">
       
        <div class="two_part"> 
            <div class="button_head">
                <button class="print">
                    <span class="print_icon las  la-plus-square"></span>
                    <span class="add_span"></span> 
                </button>
            </div>   
            <div class="colums">
                <div class="column">Annee Debut</div>
                <div class="column">Annee Fin</div> 
                <div class="column">Date debut</div>
                <div class="column">Date fin</div>
                <div class="column">operation</div>
            </div>
            <div class="allrows">
                <?php
                   $sql = "select * from annee_academique";
                    $response = $db->query($sql);
                    if ($db->error == '') {
                        // var_dump($response);
                        foreach($response as $res){
                    ?>
            <div class="rows">
                <div class="row point nom">
                    <?= $res ->annee_debut?>
                </div>
                <div class="row point nom">
                    <?= $res ->annee_fin?>
                </div>
                <div class="row">                                                
                    <?= $res ->date_debut?>
                </div>
                <div class="row">        
                    <?= $res ->date_fin?>
                </div>
                <div class="row icons_operation">
                    <form method="post" action="vue.php?p=academique">
                        <input type="hidden" name="modifier" value="<?= $res ->id?>"> 
                        <button type="submit" class=" pen las  la-pencil-alt"></button>
                    </form>   &nbsp; &nbsp; &nbsp;
                    <?php if($res ->etat === "O"){ ?>
                        <form method="post" action="vue.php?p=academique">
                            <input type="hidden" name="pass" value="<?= $res ->id?>">
                            <button type="submit" class="pen las la-graduation-cap"></button>
                        </form>   
                    <?php } ?>
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
<?php
    // }else{
    //     echo "Log out Need to implement";   
    // }
?>
<script src="js\zepto.js"></script>
<script src="js\search.js"></script>
<script src="js\scroll.js"></script>
<script src="js\popup.js"></script>
<style>
    
.colums{ 
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    color: #131D28;
    background: #efefef;
    font-weight: bold;
    justify-content: space-between;
    font-size: 1em;
    padding: 1em;
    width: auto;
}
</style>