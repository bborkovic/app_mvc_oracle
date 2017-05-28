$(document).ready(function() {
   // format for date fields
    $.fn.dataTable.moment( 'DD.MM.YYYY');
    var d = new Date();
    var dd = "Femto_" + json_parameters.time_level + "_" + d.toISOString();
    var table = $('#example').DataTable( {
        iDisplayLength: 25,
        lengthChange: true,
        "aaData": json_datatable,
        "aoColumns": json_columns,
        buttons: [ 
         {
             extend: 'copyHtml5',
             text: 'Clipboard',
             fieldSeparator: "\t",
         },
         {
             extend: 'excel',
             text: 'Excel',
             filename: dd.toString(),
         },
         , 'colvis' 
        ]
    } );
    table.buttons().container()
        .appendTo( '#example_wrapper .col-sm-6:eq(0)' );
} );