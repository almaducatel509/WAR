/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/JavaScript.js to edit this template
 */
 /* global XLSX */

const excel_file = document.getElementById('excel_file');
 excel_file.addEventListener('change', (event) =>{
     var reader = new FileReader();
     reader.readAsArrayBuffer(event.target.files[0]);
     
     reader.onload = function(event){
         var data = new Uint8Array(reader.result);
         var work_book = XLSX.read(data,{type:'array'});
         var sheet_name = work_book.SheetNames;
         var sheet_data = XLSX.utils.sheet_to_json(work_book.Sheets[sheet_name[0]], {header:1}
        );
     }  
    if(sheet_data.lenght > 0){
        var table_output = '<table class ="table table-striped table-bordered">';
        for(var row = 0; row < sheet_data.lenght; roww++)
        {
            table_output += '<tr>';
        }
     }
 })

