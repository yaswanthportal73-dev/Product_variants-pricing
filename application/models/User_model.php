<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function register($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function login($identifier, $password) {
        $this->db->where('(email = ' . $this->db->escape($identifier) . ' OR phone = ' . $this->db->escape($identifier) . ')');
        $this->db->where('deleted_at IS NULL');
        $query = $this->db->get('users');

        $user = $query->row();
        if($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public function get_all_users() {
        $this->db->where('deleted_at IS NULL');
        return $this->db->get('users')->result();
    }

    public function delete_user($id) {
        $this->db->where('id', $id);
        $this->db->update('users', ['deleted_at' => date('Y-m-d H:i:s')]);
        return $this->db->affected_rows();
    }

     public function check_login($email, $password) {
        $this->db->where('email', $email);
        $this->db->where('password', md5($password)); // simple hashing
        $query = $this->db->get('users'); // your users table
        return $query->row();
    }
}
