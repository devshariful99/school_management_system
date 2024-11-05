function file_upload(
    selectors,
    name,
    creatorType,
    existingFiles = [],
    multipleFile = false
) {
    $.each(selectors.reverse(), function (index, selector) {
        var actualName = $(selector).attr("data-actualName");
        const inputElement = document.querySelector(selector);
        const pond = FilePond.create(inputElement, {
            acceptedFileTypes: ["image/*"],
        });
        pond.setOptions({
            allowMultiple: multipleFile,
            files: existingFiles
                .filter((fileUrl) => fileUrl) // Filter out empty URLs
                .map((fileUrl) => ({
                    source: fileUrl,
                    options: {
                        type: "local", // Marks the file as already uploaded
                    },
                })),
            server: {
                url: "/admin/file-management",
                load: (source, load, error) => {
                    fetch(source)
                        .then((res) => res.blob())
                        .then(load)
                        .catch((err) => {
                            console.error("File load error:", err);
                            error(err);
                        });
                },
                process: {
                    url: "/upload-temp-file",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    onload: (response_data) => {
                        var f_selector = $(
                            'input[name="' + name + (index + 1) + '"]'
                        );
                        $(f_selector).attr("name", actualName);

                        tempFileIds =
                            JSON.parse(sessionStorage.getItem("tempFileIds")) ||
                            [];
                        tempFileIds.push(response_data.split("<")[0]);
                        sessionStorage.setItem(
                            "tempFileIds",
                            JSON.stringify(tempFileIds)
                        );

                        return response_data;
                    },
                    onerror: (response_data) => {
                        console.log(response_data);
                    },
                    ondata: (formData) => {
                        formData.append("name", name + (index + 1));
                        formData.append("creatorType", creatorType);
                        return formData;
                    },
                },
                revert: {
                    url: "/delete-temp-file",
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    onload: (response_data) => {
                        sessionStorage.removeItem("pageReload");
                        sessionStorage.removeItem("tempFileIds");
                    },
                    onerror: (response_data) => {
                        console.log(response_data);
                    },
                },
                fetch: null,
            },
        });
    });
}
// Detect if the page is about to be unloaded (e.g., reloaded or navigated away)
$(window).on("beforeunload", function () {
    sessionStorage.setItem("pageReload", "true");
});

$(() => {
    if (sessionStorage.getItem("pageReload") === "true") {
        let tempFileIds = sessionStorage.getItem("tempFileIds");
        tempFileIds = JSON.parse(tempFileIds) || [];
        if (tempFileIds.length > 0) {
            $.ajax({
                url: "/admin/file-management/delete-unsaved-temp-files",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    "Content-Type": "application/json",
                },
                data: JSON.stringify({
                    tempFileIds: tempFileIds,
                }),
                success: function (response) {
                    sessionStorage.removeItem("pageReload");
                    sessionStorage.removeItem("tempFileIds");
                    console.log(response.message);
                },
                error: function (xhr, status, error) {
                    sessionStorage.removeItem("pageReload");
                    sessionStorage.removeItem("tempFileIds");
                    console.error("Error cleaning up temp files:", error);
                },
            });
        }
    }
});
