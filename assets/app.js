document.addEventListener("DOMContentLoaded", function () {

    const selects = document.querySelectorAll(
        ".search-box select"
    );

    selects.forEach(function (select) {

        select.addEventListener("change", function () {

            this.form.submit();

        });

    });


    const inputs =
        document.querySelectorAll(
            "input, textarea, select"
        );


    inputs.forEach(function (input) {

        input.addEventListener(
            "focus",
            function () {

                this.style.borderColor =
                    "#5956d9";

            }
        );


        input.addEventListener(
            "blur",
            function () {

                this.style.borderColor =
                    "#dfe3ec";

            }
        );

    });

});