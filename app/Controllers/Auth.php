<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    /**
     * Display the registration page.
     */
    public function register()
    {
        // Redirect users who are already logged in.
        if (session()->get('logged_in')) {
            if (session()->get('role') === 'admin') {
                return redirect()->to(site_url('admin/dashboard'));
            }

            return redirect()->to(site_url('pets'));
        }

        return view('auth/register');
    }

    /**
     * Process new adopter registration.
     */
    public function processRegister()
    {
        $rules = [
            'full_name' => [
                'rules'  => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required'   => 'Please enter your full name.',
                    'min_length' => 'Your name must be at least 3 characters.',
                    'max_length' => 'Your name cannot exceed 150 characters.',
                ],
            ],
            'email' => [
                'rules'  => 'required|valid_email|max_length[150]|is_unique[users.email]',
                'errors' => [
                    'required'    => 'Please enter your email.',
                    'valid_email' => 'Please enter a valid email address.',
                    'is_unique'   => 'This email is already registered.',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[8]|max_length[72]',
                'errors' => [
                    'required'   => 'Please enter a password.',
                    'min_length' => 'Your password must be at least 8 characters.',
                    'max_length' => 'Your password cannot exceed 72 characters.',
                ],
            ],
            'confirm_password' => [
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Please confirm your password.',
                    'matches'  => 'Your passwords do not match.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $userModel->insert([
            'full_name' => trim($this->request->getPost('full_name')),
            'email'     => strtolower(trim($this->request->getPost('email'))),
            'password'  => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            // Public registrations can only create adopter accounts.
            'role'      => 'adopter',
        ]);

        return redirect()->to(site_url('login'))
            ->with('success', 'Your account has been created. You can now log in.');
    }

    /**
     * Display the login page.
     */
    public function login()
    {
        if (session()->get('logged_in')) {
            if (session()->get('role') === 'admin') {
                return redirect()->to(site_url('admin/dashboard'));
            }

            return redirect()->to(site_url('pets'));
        }

        return view('auth/login');
    }

    /**
     * Authenticate a user.
     */
    public function processLogin()
    {
        $rules = [
            'email' => [
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => 'Please enter your email.',
                    'valid_email' => 'Please enter a valid email address.',
                ],
            ],
            'password' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter your password.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim($this->request->getPost('email')));
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Reject invalid credentials.
        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password.');
        }

        // Regenerate the session ID after successful authentication.
        $session = session();
        $session->regenerate(true);

        $session->set([
            'user_id'   => $user['id'],
            'full_name' => $user['full_name'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'logged_in' => true,
        ]);

        // Redirect according to the user's role.
        if ($user['role'] === 'admin') {
            return redirect()->to(site_url('admin/dashboard'));
        }

        return redirect()->to(site_url('pets'));
    }

    /**
     * Log out the current user.
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'))
            ->with('success', 'You have been logged out successfully.');
    }
}
