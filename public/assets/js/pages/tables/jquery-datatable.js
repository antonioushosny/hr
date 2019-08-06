$(function () {
    $('.js-basic-example').DataTable(
        
    );

    //Exportable table
    $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', {
                extend: 'csv',
                text: 'csv',
                charset: 'utf-8',
                extension: '.csv',
                fieldBoundary: '',
                bom: true
            }, 'excel', 'pdf', 'print',
          
        ]
    }); 

    
});