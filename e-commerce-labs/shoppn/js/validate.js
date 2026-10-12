document.addEventListener("DOMContentLoaded", function () {

    // ==========================================
    // GET ELEMENTS
    // ==========================================

    const password =
        document.getElementById("password");

    const confirmPassword =
        document.getElementById("confirm_password");

    const passwordStrength =
        document.getElementById("passwordStrength");

    const passwordMatch =
        document.getElementById("passwordMatch");

    const showPassword =
        document.getElementById("showPassword");

    const showConfirmPassword =
        document.getElementById("showConfirmPassword");

    const registerForm =
        document.getElementById("registerForm");

    const country =
        document.getElementById("country");

    const city =
        document.getElementById("city");

    const countryCode =
        document.getElementById("countryCode");

    const phoneNumber =
        document.getElementById("phoneNumber");

    const contact =
        document.getElementById("contact");


    // ==========================================
    // PASSWORD REQUIREMENT ELEMENTS
    // ==========================================

    const requirementLength =
        document.getElementById("requirementLength");

    const requirementUppercase =
        document.getElementById("requirementUppercase");

    const requirementLowercase =
        document.getElementById("requirementLowercase");

    const requirementNumber =
        document.getElementById("requirementNumber");

    const requirementSpecial =
        document.getElementById("requirementSpecial");


    // ==========================================
    // SHOW / HIDE PASSWORD
    // ==========================================

    showPassword.addEventListener("click", function () {

        if (password.type === "password") {

            password.type = "text";

            showPassword.textContent = "🙈";

            showPassword.setAttribute(
                "aria-label",
                "Hide password"
            );

        } else {

            password.type = "password";

            showPassword.textContent = "👁";

            showPassword.setAttribute(
                "aria-label",
                "Show password"
            );

        }

    });


    // ==========================================
    // SHOW / HIDE CONFIRM PASSWORD
    // ==========================================

    showConfirmPassword.addEventListener(
        "click",
        function () {

            if (confirmPassword.type === "password") {

                confirmPassword.type = "text";

                showConfirmPassword.textContent =
                    "🙈";

                showConfirmPassword.setAttribute(
                    "aria-label",
                    "Hide password"
                );

            } else {

                confirmPassword.type = "password";

                showConfirmPassword.textContent =
                    "👁";

                showConfirmPassword.setAttribute(
                    "aria-label",
                    "Show password"
                );

            }

        }
    );


    // ==========================================
    // CHECK PASSWORD REQUIREMENTS
    // ==========================================

    function checkPasswordRequirements(value) {

        return {

            length:
                value.length >= 8,

            uppercase:
                /[A-Z]/.test(value),

            lowercase:
                /[a-z]/.test(value),

            number:
                /[0-9]/.test(value),

            special:
                /[^A-Za-z0-9]/.test(value)

        };

    }


    // ==========================================
    // UPDATE REQUIREMENT DISPLAY
    // ==========================================

    function updateRequirement(
        element,
        passed,
        text
    ) {

        if (passed) {

            element.textContent =
                "✓ " + text;

        } else {

            element.textContent =
                "○ " + text;

        }

    }


    // ==========================================
    // PASSWORD STRENGTH
    // ==========================================

    function checkPasswordStrength() {

        const value =
            password.value;

        const requirements =
            checkPasswordRequirements(value);


        // Update requirement list

        updateRequirement(
            requirementLength,
            requirements.length,
            "At least 8 characters"
        );

        updateRequirement(
            requirementUppercase,
            requirements.uppercase,
            "1 uppercase letter"
        );

        updateRequirement(
            requirementLowercase,
            requirements.lowercase,
            "1 lowercase letter"
        );

        updateRequirement(
            requirementNumber,
            requirements.number,
            "1 number"
        );

        updateRequirement(
            requirementSpecial,
            requirements.special,
            "1 special character"
        );


        // Count requirements

        let score = 0;

        if (requirements.length) {
            score++;
        }

        if (requirements.uppercase) {
            score++;
        }

        if (requirements.lowercase) {
            score++;
        }

        if (requirements.number) {
            score++;
        }

        if (requirements.special) {
            score++;
        }


        // Display strength

        if (value === "") {

            passwordStrength.textContent = "";

        }

        else if (score <= 2) {

            passwordStrength.textContent =
                "Weak password";

        }

        else if (score < 5) {

            passwordStrength.textContent =
                "Medium password";

        }

        else {

            passwordStrength.textContent =
                "Strong password";

        }


        // Also check confirmation

        checkPasswordMatch();

    }


    // ==========================================
    // PASSWORD INPUT
    // ==========================================

    password.addEventListener(
        "input",
        checkPasswordStrength
    );


    // ==========================================
    // CONFIRM PASSWORD
    // ==========================================

    confirmPassword.addEventListener(
        "input",
        checkPasswordMatch
    );


    function checkPasswordMatch() {

        if (confirmPassword.value === "") {

            passwordMatch.textContent = "";

            return;
        }


        if (
            password.value ===
            confirmPassword.value
        ) {

            passwordMatch.textContent =
                "✓ Passwords match";

        } else {

            passwordMatch.textContent =
                "✕ Passwords do not match";

        }

    }


    // ==========================================
    // COUNTRY → CITY
    // ==========================================

    const cities = {

        "Ghana": [
            "Accra",
            "Kumasi",
            "Tamale",
            "Takoradi",
            "Cape Coast",
            "Koforidua"
        ],

        "Niger": [
            "Niamey",
            "Maradi",
            "Zinder",
            "Agadez",
            "Tahoua",
            "Dosso"
        ],

        "Nigeria": [
            "Abuja",
            "Lagos",
            "Kano",
            "Ibadan",
            "Benin City",
            "Kaduna"
        ],

        "Benin": [
            "Cotonou",
            "Porto-Novo",
            "Parakou",
            "Abomey",
            "Bohicon"
        ],

        "Togo": [
            "Lome",
            "Sokode",
            "Kara",
            "Atakpame",
            "Kpalime"
        ],

        "Burkina Faso": [
            "Ouagadougou",
            "Bobo-Dioulasso",
            "Koudougou",
            "Banfora",
            "Ouahigouya"
        ],

        "Mali": [
            "Bamako",
            "Sikasso",
            "Mopti",
            "Segou",
            "Gao",
            "Timbuktu"
        ],

        "Senegal": [
            "Dakar",
            "Thies",
            "Touba",
            "Saint-Louis",
            "Kaolack"
        ],

        "Cote d'Ivoire": [
            "Abidjan",
            "Yamoussoukro",
            "Bouake",
            "Korhogo",
            "San-Pedro"
        ]

    };


    country.addEventListener(
        "change",
        function () {

            const selectedCountry =
                country.value;


            // Clear cities

            city.innerHTML = "";


            // No country selected

            if (selectedCountry === "") {

                const option =
                    document.createElement("option");

                option.value = "";

                option.textContent =
                    "Select Country First";

                city.appendChild(option);

                city.disabled = true;

                countryCode.value = "";

                return;
            }


            // Default city

            const defaultOption =
                document.createElement("option");

            defaultOption.value = "";

            defaultOption.textContent =
                "Select City";

            city.appendChild(defaultOption);


            // Get cities

            const countryCities =
                cities[selectedCountry];


            countryCities.forEach(
                function (cityName) {

                    const option =
                        document.createElement("option");

                    option.value = cityName;

                    option.textContent = cityName;

                    city.appendChild(option);

                }
            );


            city.disabled = false;

        }
    );


    // ==========================================
    // COUNTRY → PHONE CODE
    // ==========================================

    const phoneCodes = {

        "Ghana": "+233",

        "Niger": "+227",

        "Nigeria": "+234",

        "Benin": "+229",

        "Togo": "+228",

        "Burkina Faso": "+226",

        "Mali": "+223",

        "Senegal": "+221",

        "Cote d'Ivoire": "+225"

    };


    country.addEventListener(
        "change",
        function () {

            const selectedCountry =
                country.value;


            if (phoneCodes[selectedCountry]) {

                countryCode.value =
                    phoneCodes[selectedCountry];

            } else {

                countryCode.value = "";

            }

        }
    );


    // ==========================================
    // REGISTER FORM
    // ==========================================

    registerForm.addEventListener(
        "submit",
        function (event) {

            const requirements =
                checkPasswordRequirements(
                    password.value
                );


            // ----------------------------------
            // CHECK STRONG PASSWORD
            // ----------------------------------

            const strongPassword =
                requirements.length &&
                requirements.uppercase &&
                requirements.lowercase &&
                requirements.number &&
                requirements.special;


            if (!strongPassword) {

                event.preventDefault();

                passwordStrength.textContent =
                    "Password must meet all requirements.";

                alert(
                    "Please create a strong password using 8+ characters, uppercase, lowercase, number and special character."
                );

                password.focus();

                return;
            }


            // ----------------------------------
            // CHECK PASSWORD MATCH
            // ----------------------------------

            if (
                password.value !==
                confirmPassword.value
            ) {

                event.preventDefault();

                passwordMatch.textContent =
                    "✕ Passwords do not match";

                alert(
                    "Please make sure both passwords are the same."
                );

                confirmPassword.focus();

                return;
            }


            // ----------------------------------
            // CHECK COUNTRY
            // ----------------------------------

            if (country.value === "") {

                event.preventDefault();

                alert(
                    "Please select your country."
                );

                country.focus();

                return;
            }


            // ----------------------------------
            // CHECK CITY
            // ----------------------------------

            if (city.value === "") {

                event.preventDefault();

                alert(
                    "Please select your city."
                );

                city.focus();

                return;
            }


            // ----------------------------------
            // CHECK PHONE NUMBER
            // ----------------------------------

            if (
                phoneNumber.value.trim() === ""
            ) {

                event.preventDefault();

                alert(
                    "Please enter your phone number."
                );

                phoneNumber.focus();

                return;
            }


            // ----------------------------------
            // CHECK PHONE NUMBER FORMAT
            // ----------------------------------

            if (
                !/^[0-9]{7,15}$/.test(
                    phoneNumber.value.trim()
                )
            ) {

                event.preventDefault();

                alert(
                    "Please enter a valid phone number."
                );

                phoneNumber.focus();

                return;
            }


            // ----------------------------------
            // COMBINE PHONE CODE + NUMBER
            // ----------------------------------

            contact.value =
                countryCode.value +
                phoneNumber.value.trim();

        }
    );

});