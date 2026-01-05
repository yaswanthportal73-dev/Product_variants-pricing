<?php
class Employees1_model extends CI_Model {

    private $table = 'employees';

    public function get_all() {
        return $this->db->order_by('id','DESC')->get($this->table)->result();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function get_by_id($id) {
        return $this->db->where('id',$id)->get($this->table)->row();
    }

    public function update($id, $data) {
        return $this->db->where('id',$id)->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->where('id',$id)->delete($this->table);
    }
}
