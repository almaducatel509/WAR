
//  var myParam = location.search.split('p')[1];
//  console.log(myParam); nav-item nav_menu

function getNiveau(val) {
    $.ajax({
    type: "POST",
    url: "etudiants.php",
    data:'id_niveau='+val,
    success: function(data){
      $("#list-niveau").html(data);
    }
    });
  }