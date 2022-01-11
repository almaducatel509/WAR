<?php

require_once ('dbutils/PDO.php');

    if(isset($_SESSION['dba'])) {
        $db =$_SESSION['dba'];
    }else{
        $db = new DBA();
    }
?>
 <?php
    $modifier = null;
    $requestSent = false; $message = "";
    if(isset($_POST["modifier"])){
        $modifier = $_POST["modifier"];
    }
    $not = null;
    script_filiere();
    $idSeance = null;
    if(isset($_POST["trash"])){
     $idSeance = $_POST["trash"];
    }
    if($idSeance !=null){
        $requestSent = true;
        $response = $db ->query("DELETE FROM horaire WHERE id =:id", ["id"=>$idSeance]);
        $message = "Suppression effectuée !";
    ?>
        <div class="boxAlert">Suppression reussi!</div>
        <script>
            setTimeout(function(){
                document.querySelector(".boxAlert").style.display="none";
            }, 5000);
        </script>
        <?php
    }

    if(isset($_POST["cours"]) && isset($_POST["jour"]) && isset($_POST["type"])
        && isset($_POST["jour"]) && isset($_POST["heure_debut"]) && isset($_POST["heure_fin"])
    ){
        $id = $_POST["horaireId"];
        $nomCours= $_POST["cours"];
        $jour= $_POST["jour"];
        $type= $_POST["type"];
        $h_debut= $_POST["heure_debut"];
        $h_fin = $_POST["heure_fin"];
        $requestSent = true;
        if(!empty($jour) && !empty($type) && !empty($h_debut) && !empty($h_fin) && !empty($nomCours)) {
            if (empty($modifier)) {
                $sql = "INSERT INTO horaire(idCours, jour, heure_debut, heure_fin, type) 
                   VALUES ($nomCours, '$jour', '$h_debut', '$h_fin', '$type')";
                $message = "Enregistrement réussi !";
            }else{
                $sql = "UPDATE `horaire` SET `idCours`='$nomCours',`jour`='$jour',
                   `heure_debut`='$h_debut',`heure_fin`='$h_fin',`type`='$type'WHERE id = '$id'";
                $message = "Modification réussie !";
            }
            $db->query($sql);
        }
        else $message = "Erreur de soumission de formulaire";
    }

     $listHoraire = allHoraire($modifier);
     $filiere = allFiliere();
     if($modifier != null){
         $not = $listHoraire[0];
     }
?>
<div class="popup_entry <?= $not == null ? "" : "active" ?>" >

<div class="entry">
    <div class="popup_head"> 
        <h3> Programmer une seance</h3>
    </div>
    <div class="popup_body">
        <form action="./vue.php?p=horaire" method="POST" class="form_entry">
             <input type="hidden" name="horaireId" value=" <?= $not == null ? "" : $not->id ?>" >
            <div class="input">
             <select name="filiere"  <?= $not == null ? "" : "disabled" ?>>
                    <option value="">Choisissez la filiere</option>
                    <?php foreach($filiere as $fil) { ?>
                    <option value="<?= $fil->nom ?>" <?= $not == null || $not->filiere != $fil->nom ? "" : "selected" ?>> <?= $fil->nom ?> </option>
                   <?php
                    //    $i++;
                    }
                   ?>
                </select>
            </div>
            <div class="input">
                <select name="niveau" <?= $not == null ? "" : "disabled" ?> >
                    <option value="">Choisissez le niveau</option>
                    <!-- <% if(not != null){ 
                        for(String niveau : Utils.getNiveau(not.get("filiere").toString())){
                    %>
                    <option value="<%= niveau %>" <%= not == null || !not.get("niveau").toString().equals(niveau) ? "" : "selected" %> ><%= niveau %></option>
                    <%  }
                       }
                    %> -->
                </select>
            </div>
            <div class="input">
                <select name="session" <?= $not == null ? "" : "disabled" ?> >
                    <option value="">Selectionnez la session</option>
                    <option value="1" <?= $not == null || $not->session != 1 ? "" : "selected" ?> >Session 1</option>
                    <option value="2" <?= $not == null || $not->session != 2 ? "" : "selected" ?> >Session 2</option>
                </select>
            </div>
            <div class="input">
                <select name="cours" <?= $not == null ? "" : "disabled" ?> >
                    <option value="">Selectionnez le cours</option>
                    <!-- <% if(not != null){ 
                        for(String[] crs : Utils.selectCours(not.get("filiere").toString(), not.get("niveau").toString())){
                    %>
                    <option value="<%= crs[0] %>" <%= not == null || !not.get("codeCours").toString().equals(crs[0]) ? "" : "selected" %> ><%= crs[1] %></option>
                    <%  }
                       }
                    %> -->
                </select>
            </div>
            <div class="input">
                <select name="jour" <?= $not == null ? "" : "disabled" ?>>
                    <option value="">Selectionnez le jour</option>
                    <?php foreach(getJours() as $jour){
                    ?>
                    <option value="<?= $jour ?>" <?= $not == null || $not->jour != $jour ? "" : "selected" ?> ><?= $jour ?></option>
                    <?php 
                       }
                    ?>
                </select>
            </div>
            <div class="input">
              <select name="type"  <?= $not == null ? "" : "disabled" ?>>
                    <option value="">Selectionnez le type du cours</option>
                    <option value="cours magistral" <?= $not == null || $not->type != "cours magistral" ? "" : "selected" ?> >Cours magistral</option>
                    <option value="tp" <?= $not == null || $not->type != "tp" ? "" : "selected" ?> >TP</option>
                </select>
            </div>
            <div class="input">
                 <input placeholder="" type="time" name="heure_debut" value="<?= $not == null  ? "" : $not->heure_debut ?>">
            </div>
            <div class="input">
                <input type="time" name="heure_fin" value="<?= $not == null  ? "" : $not->heure_fin ?>">
            </div>
        </form>
    </div>
    <div class="button_input">
        <button type="submit" value="submit" name="submit" id="submit" style="cursor:pointer;">Ajouter</button>
        <button type="reset" value="clear" name="clear" id="quit" class="quit" style="cursor:pointer;">Quiter</button>
    </div>              
</div>

</div>

<div class="main-content">
    
    <div class="head">
       <div class="view">
           <span class=" las la-tasks"></span>
           <h2 class=" title_view">Horaire</h2>
       </div>
       <div class="AcadY">
           <span class="iconY las  la-calendar-check"></span>
           <span class="ActualY"><?= $AYBanner ?></span>
       </div>
    </div>

<script>
    var dataset = {
        cours : {},
        professeur: {},
        horaires: {}
    };
    
    <?php
    $k = 0;
    foreach(allCours() as $crs){ ?>
        dataset.cours[<?= $k ?>] = {
            "id" : "<?= $crs->codeCours ?>",
            "nom" : "<?= $crs->nomCours ?>",
            "coefficient" : <?= $crs->coefficient ?>,
            "niveau" : "<?= $crs->niveau ?>",
            "filiere" : "<?= $crs->filiere ?>",
            "session" : <?= $crs->session ?>,
            "titulaire" : "<?= $crs->professeur_titulaire ?>",
            "suppleant" : <?= $crs->professeur_supleant == null ? "null" : "\"".$crs->professeur_supleant."\"" ?>
        }
    <?php 
        $k++;
    } 
        $k = 0;
    ?>
    <?php 
    foreach(allProfesseur() as  $prof){ ?>
        dataset.professeur[<?= $k ?>] = {
            "id" : "<?= $prof->idProfesseur ?>",
            "nom" : "<?= $prof->nom ?>",
            "prenom" : "<?= $prof->prenom ?>",
            "filiere" : "<?= $prof->filiere_affecte ?>"
        }
    <?php 
        $k++;
    } 
    $k = 0;
    ?>
    <?php 
    foreach($listHoraire as $hr){ ?>
        dataset.horaires[<?= $k ?>] = {
            "id" : "<?= $hr->id ?>",
            "nom" : "<?= $hr->nomCours ?>",
            "filiere" : "<?= $hr->filiere ?>",
            "niveau" : "<?= $hr->niveau ?>",
            "session" : "<?= $hr->session ?>",
            "titulaire" : "<?= $hr->titulaire ?>",
            "suppleant" : "<?= $hr->suppleant ?>",
            "jour" : "<?= $hr->jour ?>",
            "heure_debut" : "<?= $hr->heure_debut ?>",
            "heure_fin" : "<?= $hr->heure_fin ?>",
            "type" : "<?= $hr->type ?>"
        }
    <?php
        $k++;
    } 
    $k = 0;
    ?>
</script>

<?php if($requestSent && !empty($message)){ ?>
    <div class="boxAlert"><?= $message ?></div>
    <script>
        setTimeout(function(){
            document.querySelector(".boxAlert").style.display="none";
        }, 5000);
    </script>
<?php } ?>

<div class="lastBolock">
    <div class="see" id="notes">
        <div class="button_head">
            <button class="print" onClick="printForm('horaire')">
                <span class="print_icon las  la-print"></span>
                <span class="print_span"></span> 
            </button>
            <button class="print">
                <span class="print_icon las  la-plus-square"></span>
                <span class="add_span"></span> 
            </button>
        </div>
        <div class="rang">
            <form action="vue.php?p=horaire" class="rang_entry" method="POST">
                <div class="rang_input">
                    <select name="filiere">
                        <option value=''>Choisissez la filiere</option>
                        <?php foreach($filiere as $res){ ?>
                        <option value="<?= $res ->nom ?>"><?= $res ->nom ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="rang_input">
                    <select name="niveau">
                        <option value=''>Choisissez le niveau</option>
                        <option value="niveau"></option>
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
        <div class="horaire-view" id="horaire">
            <?php foreach(getJours() as $day){ ?>
            <div class="day-column" for="<?= $day ?>">
                <div class="en-tete"><?= $day ?></div>
                <div class="cours"></div>
            </div>
            <?php } ?>
        </div>          
</div>
</div>
<script src="js\zepto.js"></script>
<script src="js\scroll.js"></script>
<script src="js\popup.js"></script>
<script src="js\print.js"></script>
 <script>
     var base = 12 * 60;
     function tempsEnMinute(heure){
         var t = heure.split(':');
         return (parseInt(t[0]) - 7) * 60 + parseInt(t[1]);
     }
     function positionRelative(debut, fin){
         var debut = tempsEnMinute(debut);
         return{
             top: debut / base * 100,
             height: (tempsEnMinute(fin) - debut) / base * 100
         }
     }
     $('.rang').on('change', '[name]', function(){
         var filiere = $('.rang [name="filiere"]').val(),
             niveau = $('.rang [name="niveau"]').val(),
             session = $('.rang [name="session"]').val(),
             pos;
         console.log({filiere,niveau,session,horaire: dataset.horaires})
         $('.horaire-view .day-column .cours').html('');
         for(var i in dataset.horaires){
             if(dataset.horaires[i].filiere == filiere && dataset.horaires[i].niveau == niveau && dataset.horaires[i].session == session){
                pos = positionRelative(dataset.horaires[i].heure_debut, dataset.horaires[i].heure_fin);
                console.log('[Pos]', pos,dataset.horaires[i]);
                $('.horaire-view .day-column[for="'+dataset.horaires[i].jour+'"] .cours').append(
                    '<div class="case" style="top: '+pos.top+'%; height: '+pos.height+'%">'+
                        '<div class="seance">'+
                            '<span class="nom">'+dataset.horaires[i].nom+'</span>'+
                            '<span class="heure">'+dataset.horaires[i].heure_debut+' - '+dataset.horaires[i].heure_fin+'</span>'+
                            '<span class="prof">'+(dataset.horaires[i].type == 'TP' ? dataset.horaires[i].suppleant == null ? 'A preciser' : dataset.horaires[i].suppleant : dataset.horaires[i].titulaire)+'</span>'+
                            '<span class="operations">'+
                            '<form method="post" action="./vue.php?p=horaire">'+
                                '<input type="hidden" name="modifier" value="'+dataset.horaires[i].id+'">'+
                                '<button type="submit" class=" pen las  la-pencil-alt"></button>'+
                            '</form>'+
                            '<form method="post" action="./vue.php?p=horaire">'+
                               ' <input type="hidden" name="trash" value="'+dataset.horaires[i].id+'">'+
                                '<button href="#"class=" trash las la-trash-alt" type="submit"></button>'+
                            '</form>'+
                            '</span>'+
                        '</div>'+
                    '</div>'
                )
             }
         }
     })
</script>
<!-- <script src="js\files.js"></script> -->
<script src="js\note.js"></script> 
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

.allrows{
    display: inline-block;
    height: 60vh;
    width: 100%;
    overflow-y: auto;
}
.rows{
    width: 100%;
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    justify-content: space-between;
    align-items: center;
    color: #efefef;
    font-size: 1em;
    padding: 1em;
    border: solid .1em #efefef;
    position: relative
}
.rows .row-info{
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    height: 350px;
    background-color: #efefef;
    z-index: 2;
    display: none;
}

.row-info .span_info{
    color: #131D28;
    font-size: 18px;
    font-weight: 600;
}
.rows:hover{
    background: #efefef;
    color: #131D28;
    -moz-box-shadow:0px 0px 5px 1px rgba(0,0,0,0.03) ;
    -webkit-box-shadow:0px 0px 5px 1px rgba(0,0,0,0.03);
    box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.03);
    border: solid 3px #131D28;
}
.rows.active .row-info{
    display: grid;
    color: #131D28;
    grid-template-columns: 20% 80%;
    justify-content: space-between;
    border: solid 1.5px #131D28;
    padding: 20px;
    overflow-y: auto;
}
.row-info .list_info{
    display: grid;
    grid-template-columns: repeat(3, 1fr) ;
    justify-content: space-between;
    padding: 20px;
    border: none;
}
.rows:focus{
    background: #efefef;
    color: #131D28;
    -moz-box-shadow:0px 0px 5px 1px rgba(0,0,0,0.03) ;
    -webkit-box-shadow:0px 0px 5px 1px rgba(0,0,0,0.03);
    box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.03);
    border: solid 3px #131D28;
}
.rows:focus .row-info{
    display: inline-block;
    -moz-box-shadow:0px 0px 5px 1px rgba(0,0,0,0.03) ;
    -webkit-box-shadow:0px 0px 5px 1px rgba(0,0,0,0.03);
    box-shadow: 0px 0px 5px 1px rgba(255, 192, 192, 0.3);
    border: solid 1.5px #131D28;
}

.rows:focus{
    -moz-box-shadow:0px 0px 5px 1px rgba(0,0,0,0.03) ;
    -webkit-box-shadow:0px 0px 5px 1px rgba(0,0,0,0.03);
    box-shadow: 0px 0px 5px 1px rgba(255, 192, 192, 0.03);
    border: solid 1.5px #131D28;
}
.operations{
    position: absolute;
    top: 0;
    right: 8px;
}
.operations button{
    background: unset;
    border: none;
}
</style>