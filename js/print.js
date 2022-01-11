/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/JavaScript.js to edit this template
 */

function printForm(el){
    var printer = window.open("", "", "width=700,height=900");
    printer.document.write('<link href="style.css" rel="stylesheet" type="text/css"/>'+document.getElementById(el).outerHTML);
    $(printer.document).find('.allrows').css('overflow-y', 'unset').find('*').css('color', '#333');
    printer.document.close();
    printer.focus();
    setTimeout(function(){
        printer.print();
    },200);
    printer.onafterprint = function(){
        printer.close();
    }
}

