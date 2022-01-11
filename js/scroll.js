
$('.see .columns').on('resize', function(){
    $('.allrows').css({
        width : $(this)[0].scrollWidth+"px"
    })
})