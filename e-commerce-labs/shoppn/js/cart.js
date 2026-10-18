document.addEventListener("DOMContentLoaded", function () {

    // ==========================================
    // GET ELEMENTS
    // ==========================================

    const removeForm = document.getElementById("removeForm");
    const updateForm = document.getElementById("updateForm");
    const errorBox = document.getElementById("cartJsError");

    // The cart is empty, so there is nothing to validate.
    if (!removeForm || !updateForm) {
        return;
    }

    // Whole number from 1 to 99.
    const qtyRegex = /^[1-9][0-9]?$/;


    // ==========================================
    // SHOW / CLEAR ERROR MESSAGE
    // ==========================================

    function showError(message) {
        errorBox.textContent = message;
        errorBox.hidden = false;
        errorBox.scrollIntoView({ behavior: "smooth", block: "center" });
    }

    function clearError() {
        errorBox.textContent = "";
        errorBox.hidden = true;
    }


    // ==========================================
    // REMOVE FORM: AT LEAST ONE BOX TICKED
    // form.elements includes the checkboxes linked
    // with form="removeForm".
    // ==========================================

    removeForm.addEventListener("submit", function (event) {

        clearError();

        const checked = Array.from(removeForm.elements).filter(
            function (element) {
                return element.name === "remove[]" && element.checked;
            }
        );

        if (checked.length === 0) {
            event.preventDefault();
            showError("Please tick at least one item to remove.");
            return;
        }

        const confirmed = confirm(
            "Remove " + checked.length + " item(s) from your cart?"
        );

        if (!confirmed) {
            event.preventDefault();
        }
    });


    // ==========================================
    // UPDATE FORM: EVERY QUANTITY 1 TO 99
    // ==========================================

    updateForm.addEventListener("submit", function (event) {

        clearError();

        const qtyInputs = Array.from(updateForm.elements).filter(
            function (element) {
                return element.name === "qty[]";
            }
        );

        let firstInvalid = null;

        qtyInputs.forEach(function (input) {

            const value = input.value.trim();

            if (!qtyRegex.test(value)) {
                input.classList.add("qty-invalid");

                if (!firstInvalid) {
                    firstInvalid = input;
                }
            } else {
                input.classList.remove("qty-invalid");
            }
        });

        if (firstInvalid) {
            event.preventDefault();
            showError("Each quantity must be a whole number from 1 to 99.");
            firstInvalid.focus();
        }
    });


    // Clear the red border as soon as the user fixes a quantity.
    updateForm.addEventListener("input", function (event) {

        if (
            event.target.name === "qty[]" &&
            qtyRegex.test(event.target.value.trim())
        ) {
            event.target.classList.remove("qty-invalid");
        }
    });
});