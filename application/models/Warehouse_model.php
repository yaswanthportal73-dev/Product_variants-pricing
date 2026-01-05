<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_model extends CI_Model {

    protected $table = 'warehouses';

    // Get all (not deleted)
    public function getAll()
    {
        return $this->db
            ->where('deleted_at IS NULL', null, false)
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();
    }

    // Search
    public function search($keyword)
    {
        return $this->db
            ->group_start()
                ->like('name', $keyword)
                ->or_like('contact_person', $keyword)
                ->or_like('phone', $keyword)
            ->group_end()
            ->where('deleted_at IS NULL', null, false)
            ->get($this->table)
            ->result();
    }

    // Get by ID
    public function getById($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row();
    }

    // ✅ INSERT
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // UPDATE
    public function update($id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table, $data);
    }
}
