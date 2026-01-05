<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
    }

    // Registration page
    public function register() {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('phone', 'Phone', 'required|is_unique[users.phone]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|callback_password_check');

        if ($this->form_validation->run() == FALSE) {
    $this->load->view('register-3'); // your existing page
} else {
    $data = [
        'user_id' => 'USR'.time(),
        'name' => $this->input->post('name'),
        'email' => $this->input->post('email'),
        'phone' => $this->input->post('phone'), // if you add phone field
        'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
    ];
    $this->User_model->register($data);
    $this->session->set_flashdata('success', 'Registered successfully!');
    redirect('users/login');
}
    }


    // Password strength check
    public function password_check($password) {
        if(!preg_match("#[0-9]+#", $password) ||
           !preg_match("#[A-Z]+#", $password) ||
           !preg_match("#[a-z]+#", $password) ||
           !preg_match("#[\W]+#", $password)) {
            $this->form_validation->set_message('password_check', 'Password must contain uppercase, lowercase, number and special character.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    // Login page
     public function login() {
        if ($this->input->post()) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->Users_model->check_login($email, $password);
            if ($user) {
                $this->session->set_userdata('user_id', $user->id);
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid email or password.');
                redirect('signin-3');
            }
        } else {
            $this->load->view('signin-3'); // make sure the view exists
        }
    }

    public function logout() {
        $this->session->unset_userdata('user_id');
        redirect('signin-3');
    }

    // User dashboard
    public function dashboard() {
        $data['users'] = $this->User_model->get_all_users();
        $this->load->view('views/user_list', $data);
    }

    // Delete user
    public function delete($id) {
        $this->User_model->delete_user($id);
        $this->session->set_flashdata('success', 'User deleted successfully!');
        redirect('users/dashboard');
    }
}
