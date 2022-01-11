<?php 
// function nav_item( $lien,  $titre,  $icon,$linkclasse)
//  , string $linkclasse)
// {
//     $classe = 'nav-item';
//     if($_SERVER ['SCRIPT_NAME'] === $lien){
//         $classe .= 'active';
    
//     }
//     return <<<HTML
//     <li>
//         <a href="$lien" class="$classe $linkclasse"> 
//             <span class=" las $icon"></span>
//             <span>$titre</span>
//         </a>
//     </li>
// HTML;

//     function nav_item( $lien,  $titre,  $icon)
//     {     
//         return <<<HTML
//         <li>
//             <a href="$lien" class="nav-item "> 
//                 <span class=" las $icon"></span>
//                 <span>$titre</span>
//             </a>
//         </li>
// HTML;
// href=\"panel?res="+link[i]+"\" class="+(res.equals(link[i]) ? "active" : ""
// }

    function nav_item( $lien,  $titre,  $icon)
    {     
        // $res = $_GET['p'];
        $classe = 'nav-item';
        return <<<HTML
        <li>
            <a href="$lien" class=""> 
                <span class=" las $icon"></span>
                <span>$titre</span>
            </a>
        </li>
HTML;
}
function nav_menu ()
{
    global $user;
    $res = [
        "Dashboard"=>["?p=dashboard", "la-home"],
        "Annee academique"=>["?p=academique", "las la-calendar-day"],
        "Etudiant"=>["?p=etudiants", "la-user-graduate"],
        "Professeur"=>["?p=professeur", "la-chalkboard-teacher"],
        "Filiere"=>["?p=filiere", "la-tasks"],
        "Cours"=>["?p=cours", "la-pencil-ruler"],
        "Horaire"=>["?p=horaire", "la-tasks"],
        "Utilisateur"=>["?p=utilisateur", "la-users"],
        "Note"=>["?p=notes", "la-book"]
    ];
    $r = "";
    $privileges = explode(",",$user->privilege);
    foreach ($res as $k => $v){
        if(in_array($k, $privileges) || $k == "Dashboard"){
            $r .= nav_item($v[0]." ", $k, $v[1]);
        }
    }
    return $r;
}
?>