function file_upload(
    selectors,
    name,
    creatorType,
    existingFiles = [],
    multipleFile = false
) {
    const uploadedFiles = {};
    $.each(selectors.reverse(), function (index, selector) {
        var actualName = $(selector).attr("data-actualName");
        const inputElement = document.querySelector(selector);
        const pond = FilePond.create(inputElement, {
            acceptedFileTypes: ["image/*"],
        });
        const fileUrl = existingFiles[selector];
        console.log("existingFiles for selector:", selector, fileUrl);
        pond.setOptions({
            allowMultiple: multipleFile,
            files: fileUrl
                ? [
                      {
                          source: fileUrl,
                          options: {
                              type: "local",
                              metadata: {
                                  fileId: "1",
                              },
                          },
                      },
                  ]
                : [],
            // onaddfile: (error, fileItem) => {
            //     if (error) {
            //         console.error("Error adding file:", error);
            //         return;
            //     }

            //     // Store file metadata in the uploadedFiles object
            //     const fileId = fileItem.getMetadata("fileId");
            //     uploadedFiles[fileItem.id] = fileId;

            //     console.log("File added to FilePond:", fileItem);
            // },
            // onremovefile: (error, fileItem) => {
            //     if (error) {
            //         console.error("Error removing file:", error);
            //         return;
            //     }

            //     // Retrieve the fileId from the uploadedFiles object
            //     const fileId = uploadedFiles[fileItem.id];
            //     console.log("File removed from FilePond:", fileId);
            // },
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
                        var f_selector = $('input[name="' + name + '"]');
                        $(f_selector).attr("name", actualName);

                        tempFileIds =
                            JSON.parse(sessionStorage.getItem("tempFileIds")) ||
                            [];
                        const newFileId = response_data.split("<")[0];
                        f_selector.val(newFileId);
                        if (!tempFileIds.includes(newFileId)) {
                            tempFileIds.push(newFileId);
                            sessionStorage.setItem(
                                "tempFileIds",
                                JSON.stringify(tempFileIds)
                            );
                        }
                        return response_data;
                    },
                    onerror: (response_data) => {
                        console.log(response_data);
                    },
                    ondata: (formData) => {
                        formData.append("name", name);
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
                        response_data = JSON.parse(response_data);
                        tempFileIds =
                            JSON.parse(sessionStorage.getItem("tempFileIds")) ||
                            [];

                        tempFileIds = tempFileIds.filter(
                            (id) => id != response_data.id
                        );
                        sessionStorage.setItem(
                            "tempFileIds",
                            JSON.stringify(tempFileIds)
                        );
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
