
$('.entry').on('change', '[name]', function(){
   var name = $(this).attr('name'),
        value = $(this).val(),
        parent = $(this).parent().parent(),
        filiere = parent.find('[name="filiere"]').val(),
        niveau = parent.find('[name="niveau"]').val(),
        session = parent.find('[name="session"]').val(),
        etudiant = parent.find('[name="etudiant"]').val(),
        cours = parent.find('[name="cours"]').val(),
        note = parent.find('[name="noteSur100"]').val(),
        option = '';
   if(["filiere","niveau", "session"].indexOf(name) >= 0){
       // if(name != 'session'){
            for(var i in dataset.etudiant){
                if(dataset.etudiant[i].filiere == filiere && dataset.etudiant[i].niveau == niveau){
                    option += '<option value="'+dataset.etudiant[i].id+'">'+dataset.etudiant[i].nom+'</option>';
                }
            }
            console.log('[Etudiants]', option)
            parent.find('[name="etudiant"]').html(option);
            option = '';
        // }
        for(var i in dataset.cours){
            if(dataset.cours[i].filiere == filiere && dataset.cours[i].niveau == niveau && dataset.cours[i].session == session){
                option += '<option value="'+dataset.cours[i].id+'">'+dataset.cours[i].nom+'</option>';
            }
        }
        parent.find('[name="cours"]').html(option);
   }
})

$('.entry [name]').trigger('change');