<?php

require_once '../../core/core.php';
require_once '../../classes/CustomerClass.php';

require_login();

$customerModel = new CustomerClass();

$customer =
    $customerModel->getCustomerById(
        $_SESSION['customer_id']
    );

if (!$customer) {

    $_SESSION['error'] =
        'Customer account not found.';

    redirect('my_account.php');
}

require_once '../layout/header.php';

?>

<main class="account-page">

    <h2>Edit Account</h2>


    <?php if (isset($_SESSION['error'])): ?>

        <div class="message error-message">

            <?= htmlspecialchars($_SESSION['error']) ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <form
        action="../../actions/edit_account_action.php"
        method="POST"
        id="editAccountForm"
    >


        <!-- NAME -->

        <div class="form-group">

            <label for="name">
                Name:
            </label>

            <br>

            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($customer['customer_name']) ?>"
                required
            >

        </div>


        <br>


        <!-- EMAIL -->

        <div class="form-group">

            <label for="email">
                Email:
            </label>

            <br>

            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($customer['customer_email']) ?>"
                required
            >

        </div>


        <br>


        <!-- COUNTRY -->

        <div class="form-group">

            <label for="country">
                Country:
            </label>

            <br>

            <select
                name="country"
                id="country"
                required
            >

                <option value="">
                    Select Country
                </option>

                <option
                    value="Ghana"
                    <?= $customer['customer_country'] === 'Ghana' ? 'selected' : '' ?>
                >
                    Ghana
                </option>

                <option
                    value="Niger"
                    <?= $customer['customer_country'] === 'Niger' ? 'selected' : '' ?>
                >
                    Niger
                </option>

                <option
                    value="Nigeria"
                    <?= $customer['customer_country'] === 'Nigeria' ? 'selected' : '' ?>
                >
                    Nigeria
                </option>

                <option
                    value="Benin"
                    <?= $customer['customer_country'] === 'Benin' ? 'selected' : '' ?>
                >
                    Benin
                </option>

                <option
                    value="Togo"
                    <?= $customer['customer_country'] === 'Togo' ? 'selected' : '' ?>
                >
                    Togo
                </option>

                <option
                    value="Burkina Faso"
                    <?= $customer['customer_country'] === 'Burkina Faso' ? 'selected' : '' ?>
                >
                    Burkina Faso
                </option>

                <option
                    value="Mali"
                    <?= $customer['customer_country'] === 'Mali' ? 'selected' : '' ?>
                >
                    Mali
                </option>

                <option
                    value="Senegal"
                    <?= $customer['customer_country'] === 'Senegal' ? 'selected' : '' ?>
                >
                    Senegal
                </option>

                <option
                    value="Cote d'Ivoire"
                    <?= $customer['customer_country'] === "Cote d'Ivoire" ? 'selected' : '' ?>
                >
                    Cote d'Ivoire
                </option>

            </select>

        </div>


        <br>


        <!-- CITY -->

        <div class="form-group">

            <label for="city">
                City:
            </label>

            <br>

            <select
                name="city"
                id="city"
                required
            >

                <option value="">
                    Select City
                </option>

            </select>

        </div>


        <br>


        <!-- CONTACT -->

        <div class="form-group">

            <label for="contact">
                Contact:
            </label>

            <br>

            <input
                type="tel"
                id="contact"
                name="contact"
                value="<?= htmlspecialchars($customer['customer_contact']) ?>"
                required
            >

        </div>


        <br>


        <button type="submit">
            Save Changes
        </button>


        <a href="my_account.php">
            Cancel
        </a>

    </form>

</main>


<script>

const country =
    document.getElementById("country");

const city =
    document.getElementById("city");


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


const currentCountry =
    country.value;

const currentCity =
    <?= json_encode($customer['customer_city']) ?>;


function loadCities(selectedCountry, selectedCity = "") {

    city.innerHTML = "";

    const defaultOption =
        document.createElement("option");

    defaultOption.value = "";

    defaultOption.textContent =
        "Select City";

    city.appendChild(defaultOption);


    if (!cities[selectedCountry]) {

        return;

    }


    cities[selectedCountry].forEach(
        function (cityName) {

            const option =
                document.createElement("option");

            option.value = cityName;

            option.textContent =
                cityName;


            if (cityName === selectedCity) {

                option.selected = true;

            }


            city.appendChild(option);

        }
    );

}


loadCities(
    currentCountry,
    currentCity
);


country.addEventListener(
    "change",
    function () {

        loadCities(
            country.value
        );

    }
);

</script>


<?php

require_once '../layout/footer.php';

?>