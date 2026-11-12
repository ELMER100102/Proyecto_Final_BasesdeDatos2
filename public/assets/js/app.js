$(document).ready(function () {
    $(".datatable").DataTable({
        columnDefs: [{ targets: "no-sort", orderable: false }],
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Sin registros",
            infoFiltered: "(filtrado de _MAX_ en total)",
            zeroRecords: "No se encontraron resultados",
            emptyTable: "No hay registros para mostrar",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior",
            },
        },
    });
});