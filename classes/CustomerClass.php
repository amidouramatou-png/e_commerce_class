<?php

require_once __DIR__ . '/../core/db_class.php';

class CustomerClass extends Database
{
    // ==========================================
    // CHECK EMAIL
    // ==========================================

    public function emailExists($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT customer_email
             FROM customer
             WHERE customer_email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        $exists = $result->num_rows > 0;

        $stmt->close();

        return $exists;
    }


    // ==========================================
    // ADD CUSTOMER
    // ==========================================

    public function addCustomer(
        $name,
        $email,
        $pass,
        $country,
        $city,
        $contact
    ) {
        $hash = password_hash(
            $pass,
            PASSWORD_BCRYPT
        );

        $stmt = $this->conn->prepare(
            "INSERT INTO customer
            (
                customer_name,
                customer_email,
                customer_pass,
                customer_country,
                customer_city,
                customer_contact
            )
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssss",
            $name,
            $email,
            $hash,
            $country,
            $city,
            $contact
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }


    // ==========================================
    // GET CUSTOMER BY EMAIL
    // ==========================================

    public function getCustomerByEmail($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT *
             FROM customer
             WHERE customer_email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        $customer = $result->fetch_assoc();

        $stmt->close();

        return $customer;
    }


    // ==========================================
    // LOGIN
    // ==========================================

    public function login($email, $pass)
    {
        $customer = $this->getCustomerByEmail($email);

        if (!$customer) {
            return false;
        }

        if (
            password_verify(
                $pass,
                $customer['customer_pass']
            )
        ) {
            return $customer;
        }

        return false;
    }


    // ==========================================
    // GET CUSTOMER BY ID
    // ==========================================

    public function getCustomerById($customer_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT *
             FROM customer
             WHERE customer_id = ?"
        );

        $stmt->bind_param("i", $customer_id);
        $stmt->execute();

        $result = $stmt->get_result();

        $customer = $result->fetch_assoc();

        $stmt->close();

        return $customer;
    }


    // ==========================================
    // CHANGE PASSWORD
    // ==========================================

    public function changePassword(
        $customer_id,
        $new_password
    ) {
        $hash = password_hash(
            $new_password,
            PASSWORD_BCRYPT
        );

        $stmt = $this->conn->prepare(
            "UPDATE customer
             SET customer_pass = ?
             WHERE customer_id = ?"
        );

        $stmt->bind_param(
            "si",
            $hash,
            $customer_id
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }


    // ==========================================
    // SAVE PASSWORD RESET TOKEN
    // ==========================================

    public function saveResetToken(
        $customer_id,
        $token,
        $expires
    ) {
        $stmt = $this->conn->prepare(
            "UPDATE customer
             SET reset_token = ?,
                 reset_expires = ?
             WHERE customer_id = ?"
        );

        $stmt->bind_param(
            "ssi",
            $token,
            $expires,
            $customer_id
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }


    // ==========================================
    // GET CUSTOMER BY RESET TOKEN
    // ==========================================

    public function getCustomerByResetToken($token)
    {
        $stmt = $this->conn->prepare(
            "SELECT *
             FROM customer
             WHERE reset_token = ?
             AND reset_expires > NOW()"
        );

        $stmt->bind_param("s", $token);
        $stmt->execute();

        $result = $stmt->get_result();

        $customer = $result->fetch_assoc();

        $stmt->close();

        return $customer;
    }


    // ==========================================
    // RESET PASSWORD
    // ==========================================

    public function resetPassword(
        $customer_id,
        $new_password
    ) {
        $hash = password_hash(
            $new_password,
            PASSWORD_BCRYPT
        );

        $stmt = $this->conn->prepare(
            "UPDATE customer
             SET customer_pass = ?,
                 reset_token = NULL,
                 reset_expires = NULL
             WHERE customer_id = ?"
        );

        $stmt->bind_param(
            "si",
            $hash,
            $customer_id
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }
    public function updateCustomer(
    $customer_id,
    $name,
    $email,
    $country,
    $city,
    $contact
) {

    $stmt = $this->conn->prepare(
        "UPDATE customer
         SET customer_name = ?,
             customer_email = ?,
             customer_country = ?,
             customer_city = ?,
             customer_contact = ?
         WHERE customer_id = ?"
    );

    $stmt->bind_param(
        "sssssi",
        $name,
        $email,
        $country,
        $city,
        $contact,
        $customer_id
    );

    $success = $stmt->execute();

    $stmt->close();

    return $success;
}
// ==========================================
// DELETE CUSTOMER
// ==========================================

public function deleteCustomer($customer_id)
{
    $stmt = $this->conn->prepare(
        "DELETE FROM customer
         WHERE customer_id = ?"
    );

    $stmt->bind_param(
        "i",
        $customer_id
    );

    $success = $stmt->execute();

    $stmt->close();

    return $success;
}
}