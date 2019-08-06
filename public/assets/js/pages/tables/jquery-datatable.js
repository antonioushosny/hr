$(function () {
    $('.js-basic-example').DataTable(
        
    );

    //Exportable table
    $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', {
                extend: 'csv',
                text: 'Export csv',
                charset: 'utf-8',
                extension: '.csv',
                fieldSeparator: ';',
                fieldBoundary: '',
                filename: 'export',
                bom: true
            }, 'excel', 'pdf', 'print',
          
        ]
    }); 

    
});