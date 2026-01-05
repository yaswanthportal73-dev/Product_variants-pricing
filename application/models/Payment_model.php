<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model {

    protected $table = 'payment_methods';

    /* ================= GET ALL ================= */
     public function get_all()
    {
        return $this->db
            ->where('deleted_at IS NULL')
            ->get($this->table)
            ->result();
    }


    /* ================= GET BY ID ================= */
    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->where('deleted_at IS NULL', null, false)
            ->get($this->table)
            ->row();
    }

    /* ================= INSERT ================= */
    public function insert($data)
    {
        return $this->db->insert('payment_methods', $data);
    }
    /* ================= UPDATE ================= */
     public function update($id, $data)
    {
        return $this->db->where('id', $id)
                        ->update('payment_methods', $data);
    }

    /* ================= TOGGLE STATUS ================= */
    public function toggle_status($id)
    {
        $row = $this->get_by_id($id);
        if (!$row) return false;

        $status = ($row->status == 1) ? 0 : 1;
        return $this->update($id, ['status' => $status]);
    }

    /* ================= SOFT DELETE ================= */
    public function soft_delete($id, $user_id)
    {
        return $this->update($id, [
            'deleted_by' => $user_id,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }
}
