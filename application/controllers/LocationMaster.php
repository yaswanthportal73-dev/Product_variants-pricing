<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LocationMaster extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['url','form']);
        $this->load->library(['session','form_validation']);

        // ONE MODEL ONLY
        $this->load->model('LocationMaster_model', 'location');
    }

    /* =====================================================
       LOCATION1
    ====================================================== */
    public function location1()
    {
        $search = $this->input->get('search');

        $data['locations'] = $search
            ? $this->location->search_location1($search)
            : $this->location->get_location1();

        $this->load->view('location1', $data);
    }

    /* =====================================================
       COUNTRIES
    ====================================================== */
    public function countries()
    {
        $keyword = $this->input->get('keyword');
        $status  = $this->input->get('status');

        $data['countries'] = $this->location->search_countries($keyword, $status);
        $this->load->view('countries', $data);
    }

    public function store_country()
    {
        $this->form_validation->set_rules('country_name','Country','required');

        if ($this->form_validation->run() === FALSE) {
            redirect('locationmaster/countries');
        }

        $max = $this->db->select_max('display_order')->get('countries')->row()->display_order ?? 0;

        $this->db->insert('countries',[
            'country_name'  => $this->input->post('country_name'),
            'status'        => $this->input->post('status'),
            'display_order' => $max + 1,
            'created_by'    => 'Admin',
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success','Country Added');
        redirect('locationmaster/countries');
    }

    /* =====================================================
       STATES
    ====================================================== */
    public function states()
    {
        $keyword = $this->input->get('keyword');

        $data['states']    = $this->location->get_states($keyword);
        $data['countries'] = $this->location->get_countries();

        $this->load->view('states', $data);
    }

    /* =====================================================
       DISTRICTS
    ====================================================== */
    public function districts()
    {
        $keyword = $this->input->get('keyword');

        $data['districts'] = $this->location->get_districts($keyword);
        $data['countries'] = $this->location->get_countries();
        $data['states']    = $this->location->get_states();

        $this->load->view('districts', $data);
    }

    /* =====================================================
       MANDALS
    ====================================================== */
    public function mandals()
    {
        $keyword = $this->input->get('keyword');

        $data['mandals']   = $this->location->get_mandals(['keyword'=>$keyword]);
        $data['countries'] = $this->location->get_countries();
        $data['states']    = $this->location->get_states();
        $data['districts'] = $this->location->get_districts();

        $this->load->view('mandals', $data);
    }

    /* =====================================================
       VILLAGES
    ====================================================== */
    public function villages()
    {
        $keyword = $this->input->get('keyword');

        $data['villages']  = $this->location->get_villages($keyword);
        $data['countries'] = $this->location->get_countries();
        $data['states']    = $this->location->get_states();
        $data['districts'] = $this->location->get_districts();
        $data['mandals']   = $this->location->get_mandals();

        $this->load->view('villages', $data);
    }

    /* =====================================================
       COMMON DELETE (HARD)
    ====================================================== */
    public function delete($table, $id)
    {
        $this->db->where('id',$id)->delete($table);
        $this->session->set_flashdata('success','Deleted Successfully');
        redirect($_SERVER['HTTP_REFERER']);
    }

    /* =====================================================
       COMMON STATUS TOGGLE
    ====================================================== */
    public function toggle($table, $id)
    {
        $row = $this->db->get_where($table,['id'=>$id])->row();
        if(!$row) show_404();

        $this->db->where('id',$id)->update($table,[
            'status' => $row->status ? 0 : 1
        ]);

        redirect($_SERVER['HTTP_REFERER']);
    }
}
