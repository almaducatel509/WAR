<?php

require_once ('dbutils/PDO.php');
    if(isset($_SESSION['dba'])) {
        $db =$_SESSION['dba'];
    }else{
        $db = new DBA();
    }
    $fac = null;
    $requestSent = false;
    $message = "";
    if(isset($_POST["modifier"])){
        $res = $db->query("select * from filiere where id = :id", [
            "id"=>$_POST["modifier"]
        ]);
        if(count($res)) $fac = $res[0];
    }
    if(isset($_POST["trash"])){
        $res = $db->query("delete from filiere where id = :id", [
            "id"=>$_POST["trash"]
        ]);
    }
    if(isset($_POST["nomFiliere"]) && isset($_POST["niveau"])){
        $filiere = $_POST["nomFiliere"];
        $niveau = $_POST["niveau"];
        $id = isset($_POST["fid"]) ? $_POST["fid"] : null;
        if(!empty($filiere) && !empty($niveau)){
            if($id == null){
                $res = $db->query("insert into filiere(nom, niveau) values(:nom, :niveau)",[
                    "nom"=>$filiere,
                    "niveau"=>$niveau
                ]);
            }
            else{
                $res = $db->query("update filiere set nom = :nom, niveau = :niveau where id=:id",[
                    "nom"=>$filiere,
                    "niveau"=>$niveau,
                    "id"=>$id
                ]);
            }
            if($db->error == ""){
                $message = $id == null ? "Enregistrement réussi !" : "Modification réussie !";
            }
            else{
                $message = "Error : " . $db->error;
            }
        }
    }
?>

<div class="popup_entry  <?= $fac != null ? "active" : "" ?>">
    <div class="entry ">
        <div class="popup_head">
            <h3>Filière</h3>
        </div>
        <div class="popup_body">
            <form action="vue.php?p=filiere" method="post" class="form_entry">
                <input type="hidden" name="fid" value="<?= $fac != null ? $fac->id : "" ?>">
                <div class="input">
                    <label for="dateDebut" class="labels">Entrez le nom de la filière</label>
                    <input type="text" name="nomFiliere" value="<?= $fac == null ? "" : $fac->nom ?>" >
                </div>
                <br>
                <div class="input" style="text-align: left">
                    <label class="labels">Choisissez la plage des niveaux</label>
                    <?php
                        $list = [
                            "EUF, L1, L2, L3",
                            "EUF, PCM1, PCM2, SPEC1, SPEC2",
                            "EUF, DCEM1, DCEM2, Externat, Internat, Social"
                        ];
                        $i = 1;
                        foreach($list as $niveau){
                            $trim = preg_replace("# +#", "", $niveau);
                            ?>
                            <div class="combinaison">
                                <input type="radio" name="niveau" <?= ($i == 1 && $fac == null) || ($fac != null && $fac->niveau == $trim)  ? "checked" : "" ?> value="<?= $trim ?>" id="niveau<?= $i?>"> <label for="niveau<?= $i?>"><?= $niveau ?></label>
                            </div>
                            <?
                            $i++;
                        }
                    ?>
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
        <span class=" las  la-chalkboard-teacher"></span>
        <h2 class=" title_view">Filière</h2>
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
                <div class="column">Filiere</div>
                <div class="column">Niveau</div> 
                <div class="column">Opérations</div>
            </div>
            <div class="allrows">
                <?php
                   $sql = "select * from filiere";
                   $response = $db->query($sql);
                    if ($db->error == '') {
                        // var_dump($response);
                        foreach($response as $res){
                    ?>
            <div class="rows">
                <div class="row point nom">
                    <?= $res ->nom?>
                </div>
                <div class="row">                                
                    <?= $res ->niveau?>
                </div>
                
                <div class="row icons_operation">
                    <form method="post" action="vue.php?p=filiere">
                        <input type="hidden" name="modifier" value="<?= $res ->id?>"> 
                        <button type="submit" class=" pen las  la-pencil-alt"></button>
                    </form>   &nbsp; &nbsp; &nbsp;
                    <form method="post" action="vue.php?p=filiere">
                        <input type="hidden" name="trash" value="<?= $res -> id?>">
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
<?php
    // }else{
    //     echo "Log out Need to implement";   
    // }
?>
<style>
    .combinaison{
        display: flex;
    }
    .combinaison input{
        width: 10%;
    }
    .rows {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        justify-content: space-between;
        align-items: center;
        color: #efefef;
        font-size: 1em;
        padding: 1em;
        border: solid 0.1em #efefef;
        position: relative;
    }
</style>
<script src="js\zepto.js"></script>
<script src="js\search.js"></script>
<script src="js\scroll.js"></script>
<script src="js\popup.js"></script>