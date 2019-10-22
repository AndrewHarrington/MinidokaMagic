$(document).ready(function() {
    $('#registrants').DataTable( {
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details for '+data[0]+' '+data[1];
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'table'
                } )
            }
        }
    } );
} );
// $(document).ready(function() {
//     $('#registrants').DataTable(
//     {
//         columnDefs: [
//             {
//                 className: "dt-left",
//                 targets: [0, 1, 2, 3, 4, 5]
//             }
//         ],
//         scrollY:        '50vh',
//         scrollX:        '50vh',
//         paging:         '50vh'
//     }
//     );
// } );