<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* =====================================================
   LOCATION MASTER MODEL (ALL LOCATION RELATED MODELS)
===================================================== */
class LocationMaster_model extends CI_Model {

    /* ================= TABLE NAMES ================= */
    protected $location1 = 'location1';
    protected $countries = 'countries';
    protected $states    = 'states';
    protected $districts = 'districts';
    protected $mandals   = 'mandals';
    protected $villages  = 'villages';

    /* =====================================================
       LOCATION1
    ====================================================== */
    public function get_location1()
    {
        return $this->db
            ->where('deleted_at IS NULL', null, false)
            ->order_by('id','DESC')
            ->get($this->location1)
            ->result();
    }

    public function search_location1($keyword)
    {
        return $this->db
            ->group_start()
                ->like('country_name', $keyword)
                ->or_like('state_name', $keyword)
                ->or_like('district_name', $keyword)
                ->or_like('mandal_name', $keyword)
                ->or_like('village_name', $keyword)
            ->group_end()
            ->where('deleted_at IS NULL', null, false)
            ->order_by('id','DESC')
            ->get($this->location1)
            ->result();
    }

    /* =====================================================
       COUNTRIES
    ====================================================== */
    public function get_countries()
    {
        return $this->db
            ->where('status', 1)
            ->where('deleted_at IS NULL', null, false)
            ->order_by('country_name', 'ASC')
            ->get($this->countries)
            ->result();
    }

    public function search_countries($keyword = null, $status = null)
    {
        if ($keyword) {
            $this->db->like('country_name', $keyword);
        }
        if ($status !== null && $status !== '') {
            $this->db->where('status', $status);
        }
        return $this->db->get($this->countries)->result();
    }

    /* =====================================================
       STATES
    ====================================================== */
    public function get_states($keyword = null, $status = null)
    {
        $this->db->select('s.*, c.country_name')
                 ->from('states s')
                 ->join('countries c','c.id=s.country_id','left')
                 ->where('s.deleted_at IS NULL', null, false);

        if ($keyword) {
            $this->db->group_start()
                     ->like('s.state_name', $keyword)
                     ->or_like('c.country_name', $keyword)
                     ->group_end();
        }

        if ($status !== null && $status !== '') {
            $this->db->where('s.status', $status);
        }

        return $this->db->order_by('s.state_name','ASC')->get()->result();
    }

    /* =====================================================
       DISTRICTS
    ====================================================== */
    public function get_districts($keyword = null)
    {
        $this->db->select('d.*, s.state_name, c.country_name')
                 ->from('districts d')
                 ->join('states s','s.id=d.state_id','left')
                 ->join('countries c','c.id=d.country_id','left')
                 ->where('d.deleted_at IS NULL', null, false);

        if ($keyword) {
            $this->db->group_start()
                     ->like('d.district_name',$keyword)
                     ->or_like('s.state_name',$keyword)
                     ->or_like('c.country_name',$keyword)
                     ->group_end();
        }

        return $this->db->order_by('d.id','DESC')->get()->result();
    }

    /* =====================================================
       MANDALS
    ====================================================== */
    public function get_mandals($filters = [])
    {
        $this->db->select('m.*, d.district_name, s.state_name, c.country_name')
                 ->from('mandals m')
                 ->join('districts d','d.id=m.district_id','left')
                 ->join('states s','s.id=m.state_id','left')
                 ->join('countries c','c.id=m.country_id','left');

        if (!empty($filters['keyword'])) {
            $this->db->like('m.mandal_name', $filters['keyword']);
        }
        if (!empty($filters['country_id'])) {
            $this->db->where('m.country_id', $filters['country_id']);
        }
        if (!empty($filters['state_id'])) {
            $this->db->where('m.state_id', $filters['state_id']);
        }
        if (!empty($filters['district_id'])) {
            $this->db->where('m.district_id', $filters['district_id']);
        }

        return $this->db->order_by('m.id','DESC')->get()->result();
    }

    /* =====================================================
       VILLAGES
    ====================================================== */
    public function get_villages($keyword = null)
    {
        $this->db->select('v.*, m.mandal_name, d.district_name, s.state_name, c.country_name')
                 ->from('villages v')
                 ->join('mandals m','m.id=v.mandal_id','left')
                 ->join('districts d','d.id=v.district_id','left')
                 ->join('states s','s.id=v.state_id','left')
                 ->join('countries c','c.id=v.country_id','left')
                 ->where('v.deleted_at IS NULL', null, false);

        if ($keyword) {
            $this->db->group_start()
                     ->like('v.village_name',$keyword)
                     ->or_like('m.mandal_name',$keyword)
                     ->or_like('d.district_name',$keyword)
                     ->or_like('s.state_name',$keyword)
                     ->or_like('c.country_name',$keyword)
                     ->group_end();
        }

        return $this->db->order_by('v.id','DESC')->get()->result();
    }
}
