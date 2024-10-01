function fetchAndShowModal(detailsUrl, headers, modalWrapId, modalId) {
    let url = detailsUrl;
    $.ajax({
        url: url,
        method: "GET",
        dataType: "json",
        success: function (data) {
            // Define your headers dynamically
            showModalWithData(headers, data, modalWrapId, modalId);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching admin data:", error);
        },
    });
}

function showModalWithData(headers, data, modalWrapId, modalId) {
    // Create the table header dynamically
    const commonHeaders = [
        { label: "Created Date", key: "creating_time" },
        { label: "Created By", key: "creater_name" },
        { label: "Updated Date", key: "updating_time" },
        { label: "Updated By", key: "updater_name" },
    ];
    headers.push(...commonHeaders);
    const headerHtml = headers
        .map((header) => {
            if (header.badge) {
                return `
                    <tr>
                        <th class="text-nowrap">${header.label}</th>
                        <th>:</th>
                        <td><span class="badge ${data[header.badgeClass]}">${
                    data[header.key]
                }</span></td>
                    </tr>
                `;
            } else {
                return `
                    <tr>
                        <th class="text-nowrap">${header.label}</th>
                        <th>:</th>
                        <td>${data[header.key]}</td>
                    </tr>
                `;
            }
        })
        .join("");

    // Construct the full table HTML
    const result = `
            <table class="table table-striped">
                <tbody>
                    ${headerHtml}
                </tbody>
            </table>
        `;

    $(modalWrapId).html(result);
    showModal(modalId);
}
