function initializeDataTable({
    main_class = ".datatable",
    displayLength = 10,
    export_columns = [],
    main_route = "",
    order_route = "",
    model = "",
    table_columns = [], // Array for defining table columns
} = {}) {
    $(function () {
        var table = $(main_class).DataTable({
            dom: "Bfrtip",
            colReorder: true,
            responsive: true,
            processing: true,
            serverSide: true,
            iDisplayLength: displayLength,
            rowReorder: {
                selector: "td:last-child .reorder",
                update: true,
            },
            buttons: [
                "copy",
                {
                    extend: "pdfHtml5",
                    download: "open",
                    orientation: "portrait",
                    pagesize: "A4",
                    exportOptions: {
                        columns: export_columns, // Modify as needed
                    },
                },
                {
                    extend: "print",
                    exportOptions: {
                        columns: export_columns, // Modify as needed
                    },
                },
                {
                    extend: "csv",
                    exportOptions: {
                        columns: export_columns, // Modify as needed
                    },
                },
                "pageLength",
            ],
            ajax: {
                url: main_route,
                type: "GET",
            },
            columns: [
                {
                    data: null, // `data` set to `null` because we are not using a field from the dataset
                    name: "serial", // A unique name for the serial column
                    orderable: false, // You probably don’t want to allow sorting by this column
                    searchable: false, // No search on serial number
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // meta.row gives the row index (0-based), so add 1
                    },
                },
                // Map the rest of the columns from `table_columns`
                ...table_columns.map(function (item) {
                    return {
                        data: item[0], // column data from the dataset
                        name: item[0], // same as above
                        orderable: item[1], // is column orderable
                        searchable: item[2], // is column searchable
                    };
                }),
            ],
        });

        table.on("row-reorder", function (e, diff, edit) {
            let orderData = [];
            for (var i = 0; i < diff.length; i++) {
                let rowData = table.row(diff[i].node).data();

                // Collect the IDs and new order for the server
                orderData.push({
                    id: rowData.id, // Assuming the ID is part of the data
                    newOrder: diff[i].newPosition,
                });
            }

            // If newOrder is not empty, send it to the server
            if (orderData.length > 0) {
                $.ajax({
                    url: order_route, // Your route for sorting update
                    type: "POST",
                    data: {
                        model: model,
                        datas: orderData,
                        _token: document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            handleErrors(response);
                        }
                        table.ajax.reload(); // Reload the table to reflect changes
                    },
                    error: function (error) {
                        toastr.error("Something went wrong. Please try again.");
                    },
                });
            }
        });
    });
}
