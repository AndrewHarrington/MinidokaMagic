$(document).ready(function() {

    $('#registrants').DataTable ({

        columnDefs: [
            {
                className: "dt-left",
                targets: [0, 1, 2, 3, 4, 8]
            },
            {
                responsive: true
            },
            {
                className: "dt-center",
                targets: [5,6,7,9]
            }],
        responsive: {

            details: {

                display: $.fn.dataTable.Responsive.display.modal({
                    header: function ( row ) {
                        let data = row.data();
                        return 'Details for '+data[0]+' '+ data[1];
                    }
                }),

                renderer: $.fn.dataTable.Responsive.renderer.tableAll ({

                     tableClass: 'table',

                })
            }
        },
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
    } );
} );