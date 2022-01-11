
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}

var niveau = [];

function readFile(file, cb){
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(){
        cb(reader.result);
    };
}

Zepto('body').on('change', '[name="filiere"]', function(){
    var filiere = $(this).val();
    niveau = filiere_niveau[filiere];
    // console.log('[Niveau]', niveau, filiere)
    var option = "<option value=''>Selectionnez un niveau</option>";
    for(var i in niveau){
        option += "<option value='"+niveau[i]+"'>"+ niveau[i]+"</option>";
    }
    var input_niveau = $(this).parent().parent();
    while(!input_niveau.find('[name="niveau"]').length){
        input_niveau = input_niveau.parent();
    }
    input_niveau.find('[name="niveau"]').html(option);
}).on('click', '.avatar', function(){
    $(this).parent().find('.file').trigger('click');
}).on('change', '.file', function(){
    var image = this.files[0]; 
    var vAvatar = $(this).parent().find('.avatar');
    $(this).val('');
    if(!/\.(jp(e)?g|png)$/i.test(image.name)){
        window.confirm("Vous devez sélectionner un fichier image de type jpg, jpeg, png");
        return;
    }
    readFile(image, function(result){
        vAvatar.css('background-image', 'url('+result+')');
        $('.avatar_input').val(result);  
    })
} ).on('click', '#user', function(){
console.log("onclick user ");
    $('.user_icon').toggleClass('active');
}).on('click', '.rows', function(){
    $(this).toggleClass('active');
}).on('click','.etatActive', function(){
    $('.etat__btn').toggleClass('active');
}).on('click','.add_span',function(){
    $('.popup_entry').addClass('active').find('[disabled]').removeAttr('disabled');
    $('.popup_entry [type="hidden"]').val('');
}).on('click','.quit',function(){
    $('.popup_entry').removeClass('active');
    $('.form_entry')[0].reset();
}).on('click', '#submit', function(){
    $('.form_entry').find('[disabled]').removeAttr('disabled')
    $('.form_entry').submit();
}).on('click','.add_span_filiere',function(){
    $('.filiere').addClass('active').find('[disabled]').removeAttr('disabled');
    $('.filiere [type="hidden"]').val('');
}).on('click','.quit',function(){
    $('.filiere').removeClass('active');
    $('.form_filiere')[0].reset();
}).on('click', '#submit', function(){
    $('.form_filiere').find('[disabled]').removeAttr('disabled')
    $('.form_filiere').submit();
}).on('click', '.import', function(e){
    e.preventDefault();
    $(this).parent().find('[type]').click();
}).on('change', '.import-file', function(){
    var file = this.files[0],
        parent = $(this).parent();
    $(this).val('');
    if(!/\.xls$/i.test(file.name)){
        window.confirm("Vous devez sélectionner un fichier excel de type xls");
        return;
    }
    readFile(file, function(result){
        parent.find('[name="excel"]').val(result);
        parent.submit();
    });
})
$('[name="filiere"]').trigger('change');

