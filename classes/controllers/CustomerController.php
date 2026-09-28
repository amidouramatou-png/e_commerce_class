<?php

require_once '../classes/CustomerClass.php';

class CustomerController
{
    private $customerModel;


    // ==========================================
    // CONSTRUCTOR
    // ==========================================

    public function __construct()
    {
        $this->customerModel = new CustomerClass();
    }


    // ==========================================
    // REGISTER
    // ==========================================

    public function register($data)
    {
        if (
            $this->customerModel->emailExists(
                $data['email']
            )
        ) {
            return [
                'success' => false,
                'error' => 'Email already registered'
            ];
        }


        $success = $this->customerModel->addCustomer(
            $data['name'],
            $data['email'],
            $data['pass'],
            $data['country'],
            $data['city'],
            $data['contact']
        );


        if ($success) {
            return [
                'success' => true
            ];
        }


        return [
            'success' => false,
            'error' => 'Registration failed'
        ];
    }


    // ==========================================
    // LOGIN
    // ==========================================

    public function login($email, $pass)
    {
        $customer =
            $this->customerModel->login(
                $email,
                $pass
            );


        if ($customer) {
            return $customer;
        }


        return [
            'success' => false,
            'error' => 'Invalid email or password.'
        ];
    }


    // ==========================================
    // CHANGE PASSWORD
    // ==========================================

    public function changePassword(
        $customer_id,
        $current_password,
        $new_password
    ) {

        $customer =
            $this->customerModel->getCustomerById(
                $customer_id
            );


        if (!$customer) {

            return [
                'success' => false,
                'error' => 'Customer account not found.'
            ];

        }


        if (
            !password_verify(
                $current_password,
                $customer['customer_pass']
            )
        ) {

            return [
                'success' => false,
                'error' => 'Current password is incorrect.'
            ];

        }


        $success =
            $this->customerModel->changePassword(
                $customer_id,
                $new_password
            );


        if ($success) {

            return [
                'success' => true
            ];

        }


        return [
            'success' => false,
            'error' => 'Password could not be changed.'
        ];
    }


    // ==========================================
    // FORGOT PASSWORD
    // ==========================================

    public function createPasswordReset($email)
    {
        $customer =
            $this->customerModel->getCustomerByEmail(
                $email
            );


        if (!$customer) {

            return [
                'success' => false,
                'error' => 'No account was found with that email.'
            ];

        }


        // Generate a secure random token

        $token =
            bin2hex(
                random_bytes(32)
            );


        // Token expires in 30 minutes

        $expires =
            date(
                'Y-m-d H:i:s',
                time() + (30 * 60)
            );


        $success =
            $this->customerModel->saveResetToken(
                $customer['customer_id'],
                $token,
                $expires
            );


        if (!$success) {

            return [
                'success' => false,
                'error' => 'Could not create password reset request.'
            ];

        }


        return [
            'success' => true,
            'token' => $token
        ];
    }


    // ==========================================
    // GET CUSTOMER USING RESET TOKEN
    // ==========================================

    public function getCustomerByResetToken($token)
    {
        return $this->customerModel
            ->getCustomerByResetToken($token);
    }


    // ==========================================
    // RESET PASSWORD USING TOKEN
    // ==========================================

    public function resetPassword(
        $customer_id,
        $new_password
    ) {

        $success =
            $this->customerModel->resetPassword(
                $customer_id,
                $new_password
            );


        if ($success) {

            return [
                'success' => true
            ];

        }


        return [
            'success' => false,
            'error' => 'Password could not be reset.'
        ];
    }
}