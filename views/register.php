<?php

require_once '../core/core.php';
require_once 'layout/header.php';

?>

<main>

    <h2>Create Account</h2>

    <?php if (isset($_SESSION['error'])): ?>

        <p style="color:red;">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </p>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <?php if (isset($_SESSION['success'])): ?>

        <p style="color:green;">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </p>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>


    <form
        action="../actions/register_action.php"
        method="POST"
        id="registerForm"
    >

        <!-- NAME -->

        <label>Name:</label><br>

        <input
            type="text"
            name="name"
            required
        >

        <br><br>


        <!-- EMAIL -->

        <label>Email:</label><br>

        <input
            type="email"
            name="email"
            required
        >

        <br><br>


        <!-- PASSWORD -->

        <label>Password:</label><br>

        <input
            type="password"
            name="password"
            id="password"
            required
        >

        <button
            type="button"
            id="showPassword"
        >
            Show
        </button>

        <br>

        <small id="passwordStrength"></small>

        <br><br>


        <!-- CONFIRM PASSWORD -->

        <label>Confirm Password:</label><br>

        <input
            type="password"
            name="confirm_password"
            id="confirm_password"
            required
        >

        <button
            type="button"
            id="showConfirmPassword"
        >
            Show
        </button>

        <br>

        <small id="passwordMatch"></small>

        <br><br>


        <!-- COUNTRY -->

        <label>Country:</label><br>

        <select
            name="country"
            id="country"
            required
        >

            <option value="">
                Select Country
            </option>

            <option value="Ghana">
                Ghana
            </option>

            <option value="Niger">
                Niger
            </option>

            <option value="Nigeria">
                Nigeria
            </option>

            <option value="Benin">
                Benin
            </option>

            <option value="Togo">
                Togo
            </option>

            <option value="Burkina Faso">
                Burkina Faso
            </option>

            <option value="Mali">
                Mali
            </option>

            <option value="Senegal">
                Senegal
            </option>

            <option value="Cote d'Ivoire">
                Cote d'Ivoire
            </option>

        </select>

        <br><br>


        <!-- CITY -->

        <label>City:</label><br>

        <select
            name="city"
            id="city"
            required
            disabled
        >

            <option value="">
                Select Country First
            </option>

        </select>

        <br><br>


        <!-- CONTACT -->

        <label>Contact:</label><br>

        <select
            id="countryCode"
            required
        >

            <option value="">
                Code
            </option>

            <option value="+233">
                +233 Ghana
            </option>

            <option value="+227">
                +227 Niger
            </option>

            <option value="+234">
                +234 Nigeria
            </option>

            <option value="+229">
                +229 Benin
            </option>

            <option value="+228">
                +228 Togo
            </option>

            <option value="+226">
                +226 Burkina Faso
            </option>

            <option value="+223">
                +223 Mali
            </option>

            <option value="+221">
                +221 Senegal
            </option>

            <option value="+225">
                +225 Cote d'Ivoire
            </option>

        </select>


        <input
            type="tel"
            id="phoneNumber"
            placeholder="Phone number"
            required
        >


        <!-- Hidden field sent to PHP -->

        <input
            type="hidden"
            name="contact"
            id="contact"
        >

        <br><br>


        <!-- REGISTER -->

        <button type="submit">
            Register
        </button>

    </form>

</main>


<script src="../js/validate.js"></script>


<?php

require_once 'layout/footer.php';

?>