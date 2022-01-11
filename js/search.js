/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/JavaScript.js to edit this template
 */
function sortTable(){
  var val = $('header .search input').val().toLowerCase(),
      tab = val.split(/ +/),
      hide = true,
      filiere = $('.rang_input [name="filiere"]').val(),
      niveau = $('.rang_input [name="niveau"]').val(),
      session = $('.rang_input [name="session"]').val();
      
  if(filiere != null) filiere = filiere.toLowerCase();
  else filiere = "";
  if(niveau != null) niveau = niveau.toLowerCase();
  else niveau = "";
  if(session != null) session = session.toLowerCase();
  else session = "";

  // console.log({filiere,niveau,session})
  
//  console.log({filiere, niveau,session,val})
  $('.rows').each(function(){
      hide = true;
      if(val.length == 0){
          hide = false;
      }
      for(var i in tab){
        if(tab[i].length){
            hide = true;
            if($(this).find('.point.nom').length && $(this).find('.point.nom').text().toLowerCase().includes(tab[i])){
                hide = false;
            }
            if($(this).find('.point.prenom').length && $(this).find('.point.prenom').text().toLowerCase().includes(tab[i])){
                hide = false;
            }
        }
      }
      if(filiere.length > 0 && $(this).find('.point.filiere').length && !$(this).find('.point.filiere').text().toLowerCase().includes(filiere)){
          hide = true;
      }
      if(niveau.length > 0 && $(this).find('.point.niveau').length && !$(this).find('.point.niveau').text().toLowerCase().includes(niveau)){
          hide = true;
      }
      if(session.length > 0 && $(this).find('.point.session').length && !$(this).find('.point.session').text().toLowerCase().includes(session)){
          hide = true;
      }
      $(this).css('display', hide ? 'none !important' : 'flex !important');
  })
}
$('header .search input').on('input', function(){
  sortTable();
})
$('body').on('change','.rang_input [name]', function(){
    sortTable();
})