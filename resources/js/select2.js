import Select2 from "select2";
import "select2/dist/css/select2.min.css";
window.Select2 = Select2;

document.addEventListener("DOMContentLoaded", () => {
    const selects = document.querySelectorAll("select");
    selects.forEach((select) => {
        new Select2(select);
    });
});
