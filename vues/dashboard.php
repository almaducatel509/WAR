<?php
 $stat = allAcount();
?>
<script>
    var stats = {
        professeur :   <?= $stat["nbProf"] ?>,
        cours : <?= $stat["nbCours"] ?>,
        etudiant : <?= $stat["nbEtudiant"] ?>,
        utilisateur : <?= $stat["nbUser"]  ?>,
        filiere :  <?= $stat["nbFiliere"] ?>
    }
</script>
<div class="cards">
    <div class="card ">
        <div class="card-icon graduate">
            <span class="las  la-user-graduate"></span>
        </div>
        <div class="card-info">
           <h2><?= $stat["nbEtudiant"]  ?></h2> 
           <small>  Total Etudiants</small> 
        </div>
        <div class="card-images">
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
        </div>

    </div>
    <div class="card ">
        <div class="card-icon teacher">
            <span class="las la-chalkboard-teacher"></span>
        </div>
        <div class="card-info">
             <h2><?= $stat["nbProf"] ?></h2> 
            <small>Total Enseignants</small>
        </div>
        <div class="card-images">
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
        </div>

    </div> <div class="card school">
        <div class="card-icon ">
            <span class="las la-user-tie"></span>
        </div>
        <div class="card-info">
            <h2><?= $stat["nbUser"]  ?></h2>
            <small>Total Utilisateurs</small>
        </div>
        <div class="card-images">
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
            <div style="background-image: url(Dr_dada.png);"></div>
        </div>
    </div>
</div>
<div class="chart-grid" >
    <div class="chart-student chart">
        <canvas id="myChart"></canvas>
    </div>
</div>
<script src="js/chart.min.js" type="text/javascript"></script>
<script src="./js/graph.js" type="text/javascript"></script>
    