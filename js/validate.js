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
    // SHOW / HIDE PASSWORD
    // ==========================================

    showPassword.addEventListener("click", function () {

        if (password.type === "password") {

            password.type = "text";

            showPassword.textContent = "Hide";

        } else {

            password.type = "password";

            showPassword.textContent = "Show";

        }

    });


    // ==========================================
    // SHOW / HIDE CONFIRM PASSWORD
    // ==========================================

    showConfirmPassword.addEventListener("click", function () {

        if (confirmPassword.type === "password") {

            confirmPassword.type = "text";

            showConfirmPassword.textContent = "Hide";

        } else {

            confirmPassword.type = "password";

            showConfirmPassword.textContent = "Show";

        }

    });


    // ==========================================
    // PASSWORD STRENGTH
    // ==========================================

    password.addEventListener("input", function () {

        const value = password.value;


        if (value.length === 0) {

            passwordStrength.textContent = "";

        }

        else if (value.length < 6) {

            passwordStrength.textContent =
                "Weak password";

        }

        else if (value.length < 10) {

            passwordStrength.textContent =
                "Medium password";

        }

        else {

            passwordStrength.textContent =
                "Strong password";

        }


        checkPasswordMatch();

    });


    // ==========================================
    // CONFIRM PASSWORD
    // ==========================================

    confirmPassword.addEventListener("input", function () {

        checkPasswordMatch();

    });


    function checkPasswordMatch() {

        if (confirmPassword.value === "") {

            passwordMatch.textContent = "";

        }

        else if (
            password.value === confirmPassword.value
        ) {

            passwordMatch.textContent =
                "Passwords match";

        }

        else {

            passwordMatch.textContent =
                "Passwords do not match";

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


    country.addEventListener("change", function () {

        const selectedCountry = country.value;


        city.innerHTML = "";


        if (selectedCountry === "") {

            const option =
                document.createElement("option");

            option.value = "";

            option.textContent =
                "Select Country First";

            city.appendChild(option);

            city.disabled = true;

            return;
        }


        const defaultOption =
            document.createElement("option");

        defaultOption.value = "";

        defaultOption.textContent =
            "Select City";

        city.appendChild(defaultOption);


        const countryCities =
            cities[selectedCountry];


        countryCities.forEach(function (cityName) {

            const option =
                document.createElement("option");

            option.value = cityName;

            option.textContent = cityName;

            city.appendChild(option);

        });


        city.disabled = false;

    });


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


    country.addEventListener("change", function () {

        const selectedCountry =
            country.value;

        if (phoneCodes[selectedCountry]) {

            countryCode.value =
                phoneCodes[selectedCountry];

        } else {

            countryCode.value = "";

        }

    });


    // ==========================================
    // REGISTER FORM
    // ==========================================

    registerForm.addEventListener("submit", function (event) {


        // Check passwords

        if (
            password.value !== confirmPassword.value
        ) {

            event.preventDefault();

            passwordMatch.textContent =
                "Passwords do not match";

            alert(
                "Please make sure both passwords are the same."
            );

            return;
        }


        // Check phone number

        if (phoneNumber.value.trim() === "") {

            event.preventDefault();

            alert(
                "Please enter your phone number."
            );

            return;
        }


        // Combine country code + phone number

        contact.value =
            countryCode.value +
            phoneNumber.value.trim();

    });

});