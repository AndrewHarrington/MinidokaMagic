$(document).ready(function() {
    $('#volunteers').DataTable( {
        columnDefs: [
            {
                className: "dt-left",
                targets: [0, 1, 2, 3, 4]
            },
            {
                className: "dt-center",
                targets: [5,6]
            },
            {
                responsive: true
            }
            ],
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
            },
    } );
} );