<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentMethods extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Payment_model');
    }

    // Load main page
    public function index(){
        $data['payments'] = $this->Payment_model->get_all();
        $this->load->view('payment_methods', $data);
    }

    // Save (Insert / Update)
 public function save()
{
    $id = $this->input->post('id');
    $username = $this->session->userdata('username') ?? 'Admin';

    $data = [
        'name'         => $this->input->post('name'),
        'code'         => $this->input->post('code'),
        'extra_charge' => $this->input->post('extra_charge'),
        'status'       => $this->input->post('status'),
    ];

    if ($id) {
        // UPDATE
        $data['updated_by'] = $username;
        $data['updated_at'] = date('Y-m-d H:i:s');

        $this->Payment_model->update($id, $data);
        $this->session->set_flashdata('success', 'Payment Method Updated Successfully');
    } else {
        // INSERT
        $data['created_by'] = $username;
        $data['created_at'] = date('Y-m-d H:i:s');

        $this->Payment_model->insert($data);
        $this->session->set_flashdata('success', 'Payment Method Added Successfully');
    }

    redirect('payment-methods');
}



    // Soft Delete
   public function delete($id)
{
    $username = $this->session->userdata('username') ?? 'Admin';

    $data = [
        'deleted_by' => $username,
        'deleted_at' => date('Y-m-d H:i:s'),
        'status'     => 0
    ];

    $this->Payment_model->update($id, $data);
    $this->session->set_flashdata('success', 'Payment Method Deleted');
    redirect('payment-methods');
}


    // Toggle ON/OFF
    public function toggle($id)
{
    $payment = $this->Payment_model->get($id);

    $data = [
        'status'     => $payment->status ? 0 : 1,
        'updated_by' => $this->session->userdata('username') ?? 'Admin',
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $this->Payment_model->update($id, $data);
    redirect('payment-methods');
}

    
}
