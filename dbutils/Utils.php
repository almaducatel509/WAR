<?php
  $date = date('d-m-y h:i:s');
?>
<?php

require_once ('dbutils/PDO.php');

if(session_id() == '') {
    session_start();
} 

if(isset($_SESSION['dba'])) {
    $db =$_SESSION['dba'];
}else{
    $db = new DBA();
}

function field(){
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $pdo->exec('TRUNCATE TABLE post_category');
    $pdo->exec('TRUNCATE TABLE post');
    $pdo->exec('TRUNCATE TABLE category');
    $pdo->exec('TRUNCATE TABLE user');
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
}

function getCode($nom, $prenom){
    $tab = allAcount();
    $total = (int) $tab["nbProf"];
    $total += (int) $tab["nbEtudiant"];
    $total += (int) $tab["nbUser"];
    $code = strtoupper($nom[0])."".strtoupper($prenom[0])."-".$total;
    for($i = 0; $i < 6 - strlen("".$total); $i++){
        $code .= "0";
    }
    return $code;
}

function slashquote($val){
    return preg_replace("/'/", "\\'", $val);
}

function script_filiere(){
  global $db;
  $r = [];
  $res = $db->query("select * from filiere");
  ?>
    <script>
      var filiere_niveau = {};
  <?php
  foreach($res as $fac){
    $r[$fac->nom] = $fac->niveau;
    ?>
    filiere_niveau["<?= $fac->nom ?>"] = "<?= $fac->niveau ?>".split(",");
    <?php
  }
  ?>
      console.log('[Filieres]', filiere_niveau);
    </script>
  <?php
  return $r;
}

function allAcount(){
    global $db;
    $tab =[];

    $sql="SELECT count(*) AS nbEtudiant FROM etudiant";
    $response = $db ->query($sql);
    if ($db->error == '') {
        foreach($response as $res){
            $tab['nbEtudiant']= $res->nbEtudiant;
        }
    }

    $sql="SELECT count(*) AS nbProf FROM professeur";
    $response = $db ->query($sql);
    if ($db->error == '') {
        foreach($response as $res){
            $tab['nbProf']= $res->nbProf;
        }
    }

    $sql="SELECT count(*) AS nbUser FROM utilisateur";
    $response = $db ->query($sql);
    if ($db->error == '') {
        foreach($response as $res){
            $tab['nbUser']= $res->nbUser;
        }
    }

    $sql="SELECT count(*) AS nbCours FROM cours";
    $response = $db ->query($sql);
    if ($db->error == '') {
        foreach($response as $res){
            $tab['nbCours']= $res->nbCours;
        }
    }

    $sql = "SELECT count(*) AS nbFiliere FROM filiere";
    $response = $db ->query($sql);
    if ($db->error == '') {
        foreach($response as $res){
            $tab['nbFiliere']= $res->nbFiliere;
        }
    }
   
    return $tab; 
 }

 function allCours(){
    global $db;
     $r = [];
     $sql = "SELECT * FROM cours";
     $response = $db ->query($sql);
     if ($db->error == '') {
         foreach($response as $res){
             $r[]= $res;
         }
     }
     return $r;
 }

 function getJours(){
     return [
         "Lundi",
         "Mardi",
         "Mercredi",
         "Jeudi",
         "Vendredi"
     ];
 }

 function allProfesseur(){
    global $db;
     $r = [];
     $sql = "SELECT * FROM professeur";
     $response = $db ->query($sql);
     if ($db->error == '') {
         foreach($response as $res){
             $r[]= $res;
         }
     }
     return $r;
 }

 function allHoraire($id = null){
    global $db;
     $r = [];
     $sql = "SELECT h.id, h.jour, h.heure_debut, h.heure_fin, h.type, c.nomCours, c.codeCours, c.session, c.filiere, c.niveau, 
            (select concat(prenom, ' ', nom) from professeur where idProfesseur = c.professeur_titulaire) as titulaire,
            (select concat(prenom, ' ', nom) from professeur where idProfesseur = c.professeur_supleant) as suppleant 
            from horaire h, cours c where h.idCours = c.codeCours ".($id == null ? "" : "and h.id=:id");
     $response = $db ->query($sql, $id == null ? null : ["id"=>$id]);
     foreach($response as $res){
         $r[] = $res;
     }
     return $r;
 }

 function allFiliere(){
    global $db;     
    $sql = "select * from filiere"; 
    $response = $db->query($sql);
    return $response;
}
function allPrivileges(){
    return [
      "Annee academique",
      "Etudiant",
      "Professeur",
      "Filiere",
      "Cours",
      "Horaire",
      "Utilisateur",
      "Note"
    ];
}
function setSuite($tableau){
    $suite = "";
    foreach ($tableau as $k => $v){
        if($v != null){
            $suite .= (strlen($suite) ? " and " : "where ")."$k = '$v'";
        }
    }
    return $suite;
}
function selectCours($filiere = null, $niveau = null, $session = null){
    global $db;
    $suite = setSuite([
        "filiere"=>$filiere,
        "niveau"=>$niveau,
        "session"=>$session
    ]);
    $listC = $db->query("SELECT nomCours,codeCours, coefficient, professeur_titulaire, professeur_supleant, niveau, session, filiere 
                        FROM cours ".$suite);
    return $listC;
}
function selectProfesseur(){
    global $db;
    $listP = $db->query("SELECT idProfesseur, nom, prenom, filiere_affecte FROM professeur");
    return $listP;
}
function estUneAnneeAcademique($annee1, $annee2){
    $r = true;
    $e = [$annee1, $annee2];
    foreach ($e as $k => $annee){
        if(preg_match("/^[0-9]{4}(-[0-9]{2}){2}$/", $annee)){
            $t = explode("-", $annee);
            $e[$k] = [(int)$t[0], (int) $t[1] * (int) $t[2]];
        }
        else{
            $r = false;
            break;
        }
    }
    if($r){
        if($e[1][0] - $e[0][0] != 1){
            $r = false;
        }
    }
    return $r;
}
function getAYBanner(){
    global $db;
    global $user;
    $r = "";
    $res = $db->query("select academicY from annee_academique where etat='O'");
    if(count($res)){
        $r = $res[0]->academicY;
    }
    else{
        $r = in_array(allPrivileges()[0], explode(",", $user->privilege)) ? "<a href='vue.php?p=academique'>Ajouter +</a>" : "--";
    }
    return $r;
}

function selectEtudiant($filiere = null, $niveau = null){
    global $db;
    $suite = setSuite([
        "filiere"=>$filiere,
        "niveau"=>$niveau
    ]);
    $listE = $db->query("SELECT idEtudiant, nomEtudiant,filiere,niveau,codeEtudiant, prenomEtudiant FROM etudiant ".$suite);
    return $listE;
}

function getNiveau($filiere){
    $niveau = [];
    foreach (allFiliere() as $fac){
        if($fac->nom == $filiere){
            $niveau = explode(",", $fac->niveau);
        }
    }
    return $niveau;
}

function etudiantEnToutEpreuve($etudiant, $annee){
    global $db;
    $r = -1;
    $notes = [0,0,0,0];
    for($i = 1; $i <= 2; $i++){
        $notes[$i-1] = 0;
        $effectif = 0; $total = 0;
        $Cours = selectCours($etudiant->filiere,$etudiant->niveau,$i);
        foreach($Cours as $cours){
            $response = $db->query("SELECT noteSur100 FROM notes where codeCours = ".$cours->codeCours." 
                        and idEtudiant= ".$etudiant->idEtudiant." and 
                        annee_academique= ".$annee);
            if(count($response) > 0){
                $data = $response[0];
                $notes[$i-1] += (double)$data->noteSur100 * (int)$cours->coefficient;
                $notes[$i+1] += 100 * (int) $cours->coefficient;
                $effectif++;
                $r = 0;
            }
            $total++;
        }
        if($total == 0 || $total != $effectif){
            $r = -1;
            break;
        }
    }
    return $r >= 0 ? round(($notes[0] / $notes[2] + $notes[1] / $notes[3]) / 2 * 10000) / 100 : $r;
}

function indexOf($el, $arr){
    $r = -1;
    foreach ($arr as $k => $v){
        if($v == $el){
            $r = $k;
            break;
        }
    }
    return $r;
}

function passerEtudiants($annee){
    global $db;
    $effectif = 0;
    $etudiants = selectEtudiant();
    $okList = [];
    $total = count($etudiants);
    foreach($etudiants as $etudiant){
        $result = etudiantEnToutEpreuve($etudiant,$annee);
        if($result >= 0){
            if($result >= 65){
                $niveau = getNiveau($etudiant->filiere);
                $indexNiveau = indexOf($etudiant->niveau, $niveau);
                $indexNiveau = $indexNiveau < 0 ? 1 : $indexNiveau + 1;
                $okList[] = [$etudiant->idEtudiant, $indexNiveau >= count($niveau) ? $etudiant->niveau : $niveau[$indexNiveau], $indexNiveau >= count($niveau)];
            }
            $effectif++;
        }
    }
    if($effectif == $total){
        foreach($okList as $ok){
            $db->query("update etudiant set ".(!$ok ? "niveau='".(!$ok[2] ? $ok[1] : "T")."'" : "etat=:val'".(!$ok[2] ? $ok[1] : "T")."'")." where idEtudiant=".$ok[0]);
        }
    }
    return [$effectif, $total];
}

function anneeSuivant($actuel){
    global $db;
    $r = 0;
    $res = $db->query("select y.id from annee_academique a, annee_academique y where a.id = $actuel and a.annee_debut = (y.annee_debut - 1) limit 1");
    if(count($res)){
        $r = (int) $res[0]->id;
    }
    else{
        $res = $db->query("select * from annee_academique where id = $actuel");
        $data = $res[0];
        $debut = $data->date_debut;
        $a_debut = $data->annee_debut;
        $fin = $data->date_fin;
        $a_fin = $data->annee_fin;
        $res = $db->query("insert into annee_academique (date_debut,date_fin,academicY,annee_debut, annee_fin) 
            values('$debut' + INTERVAL 1 YEAR, '$fin' + INTERVAL 1 YEAR, CONCAT($a_debut+1, '-',$a_fin + 1), $a_debut+1,$a_fin + 1)");
        $res = $db->query("select id from annee_academique order by id desc limit 1");
        $r = (int) $res[0]->id;
    }
    return $r;
}