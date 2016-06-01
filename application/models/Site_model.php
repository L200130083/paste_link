<?php
defined('BASEPATH') OR die();
class Site_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	//insert then get data of inserted records, espicially the ID
	function insert_get($table, $data, $select = FALSE)
	{
		if ($this->read($table, $data)->num_rows() == 0)
		{
			if( ! $this->db->insert($table, $data))
			{
				return FALSE;
			}
		}
		if ($select)
		{
			$this->db->select($select);
			$this->db->where($data);
			$this->db->from($table);
			return $this->db->get();
		}
		return TRUE;
	}
	public function read($table, $where = FALSE, $select = '*', $order_by  = FALSE, $limit  = FALSE, $start = FALSE)
	{
		$this->db->select($select);
		$this->db->from($table);
		if ($limit && $start)
		{
			$this->db->limit($limit, $start);
		}
		else if ($limit)
		{
			$this->db->limit($limit);
		}
		if ($where) $this->db->where($where);
		if ($order_by) $this->db->order_by($order_by[0], $order_by[1]);
		return $this->db->get();
		
	}
	public function delete($table, $where)
	{
		return $this->db->delete($table, $where);
	}
	public function insert($table, $data)
	{
		return $this->db->insert($table, $data);
	}
	public function update($table, $where, $data)
	{
		
		return $this->db->update($table, $data, $where);
	}
	public function has_permission($user_id, $manga_id)
	{
		$data['users_id'] = $user_id;
		$data['manga_id'] = $manga_id;
		$this->db->where($data);
		$this->db->from('users_manage_manga');
		return ($this->db->get()->num_rows() > 0);
	}
}

?>