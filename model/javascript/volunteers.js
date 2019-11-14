$(document).ready(function() {
    let cols = document.getElementsByTagName("th").length;
    if(cols === 5){
        $('#volunteers').DataTable( {
            columnDefs: [
                {
                    className: "dt-left",
                    targets: [0, 1, 2, 3, 5]
                },
                {
                    className: "dt-center",
                    targets: [4, 6]
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
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        } );
    }
    else{
        $('#volunteers').DataTable( {
            columnDefs: [
                {
                    className: "dt-left",
                    targets: [0, 1, 2, 3, 5]
                },
                {
                    className: "dt-center",
                    targets: [4, 6]
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
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        } );
    }
} );