$(document).ready(function () {
    $(".edit_et").on("click", function () {
        let id = $(this).data("id");
        let _url = details.edit_url;
        let __url = _url.replace("id", id);
        $.ajax({
            url: __url,
            method: "GET",
            dataType: "json",
            success: function (data) {
                var result = "";
                var variables = JSON.parse(data.email_template.variables);

                variables.forEach(function (variable) {
                    result += `
                                <tr>
                                    <td>{${variable.key}}</td>
                                    <td>{${variable.meaning}}</td>
                                </tr>
                            `;
                });
                $(".variables").html(result);

                $("#updateEmailTemplate").attr(
                    "data-id",
                    data.email_template.id
                );
                $("#subject").val(data.email_template.subject);
                $("#template").val(data.email_template.template);
                showModal("exampleModal");
            },
            error: function (xhr, status, error) {
                console.error("Error fetching member data:", error);
            },
        });
    });
});

$(document).ready(function () {
    $("#updateEmailTemplate").click(function () {
        var form = $("#emailTemplateForm");
        let id = $(this).data("id");
        let _url = details.edit_url;
        let __url = _url.replace("id", id);
        $.ajax({
            type: "PUT",
            url: __url,
            data: form.serialize(),
            success: function (response) {
                hideModal("exampleModal");
                window.location.reload();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    // Handle validation errors
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        // Display validation errors
                        var errorHtml = "";
                        $.each(messages, function (index, message) {
                            errorHtml +=
                                '<span class="invalid-feedback d-block" role="alert">' +
                                message +
                                "</span>";
                        });
                        $('[name="' + field + '"]').after(errorHtml);
                    });
                } else {
                    // Handle other errors
                    console.log("An error occurred.");
                }
            },
        });
    });
});
