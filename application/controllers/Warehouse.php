<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Warehouse_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    // List
    public function index()
    {
        $keyword = $this->input->get('keyword');

        if ($keyword) {
            $data['warehouses'] = $this->Warehouse_model->search($keyword);
        } else {
            $data['warehouses'] = $this->Warehouse_model->getAll();
        }

        $this->load->view('warehouse', $data);
    }

    // Store
    public function store()
    {
        if ($this->input->post()) {

            $image = null;
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path']   = './uploads/warehouses/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['file_name']     = time();

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $image = $this->upload->data('file_name');
                }
            }

            $data = [
                'name'           => $this->input->post('name'),
                'contact_person' => $this->input->post('contact_person'),
                'phone'          => $this->input->post('phone'),
                'email'          => $this->input->post('email'),
                'description'    => $this->input->post('description'),
                'image'          => $image,
                'status'         => $this->input->post('status'),
                'created_by'     => 1,
                'created_at'     => date('Y-m-d H:i:s')
            ];

            $this->Warehouse_model->insert($data);
            $this->session->set_flashdata('success', 'Warehouse added successfully');
        }

        redirect('warehouse');
    }

    // Update
    public function update($id)
    {
        if ($this->input->post()) {

            $warehouse = $this->Warehouse_model->getById($id);
            $image = $warehouse->image;

            if (!empty($_FILES['image']['name'])) {
                $config['upload_path']   = './uploads/warehouses/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['file_name']     = time();

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $image = $this->upload->data('file_name');
                }
            }

            $data = [
                'name'           => $this->input->post('name'),
                'contact_person' => $this->input->post('contact_person'),
                'phone'          => $this->input->post('phone'),
                'email'          => $this->input->post('email'),
                'description'    => $this->input->post('description'),
                'image'          => $image,
                'status'         => $this->input->post('status'),
                'updated_by'     => 1,
                'updated_at'     => date('Y-m-d H:i:s')
            ];

            $this->Warehouse_model->update($id, $data);
            $this->session->set_flashdata('success', 'Warehouse updated successfully');
        }

        redirect('warehouse');
    }

    // Delete (Soft delete)
    public function delete($id)
    {
        $data = [
            'deleted_by' => 1,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        $this->Warehouse_model->update($id, $data);
        $this->session->set_flashdata('success', 'Warehouse deleted successfully');
        redirect('warehouse');
    }

    // Status Toggle
    public function toggle_status($id)
    {
        $w = $this->Warehouse_model->getById($id);

        $status = ($w->status == 1) ? 0 : 1;

        $this->Warehouse_model->update($id, [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        redirect('warehouse');
    }
}
