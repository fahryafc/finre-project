
import NiceSelect from "nice-select2/src/js/nice-select2.js";

//===============================
document.addEventListener("DOMContentLoaded", function (e) {
    // default
    var els = document.querySelectorAll(".selectize");
    els.forEach(function (select) {
        new NiceSelect(select);
    });
});
document.addEventListener("DOMContentLoaded", function (e) {
    // default
    var els = document.querySelectorAll(".selectize-search");
    els.forEach(function (select) {
        new NiceSelect(select,{
            searchable: true
        });
    });
});

//
document.addEventListener("DOMContentLoaded", function (e) {
    // seachable
    new NiceSelect(document.getElementById("search-select"), {
        searchable: true
    })
})

