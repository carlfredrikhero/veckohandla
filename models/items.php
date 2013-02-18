<?php

/**
 * Description of item
 *
 * @author carlfredrikhero
 */
class Items extends ArrayObject {
	const table = 'items';
	protected $db;
	
	public function __construct($array = array()) {
		global $db;
		$this->db = $db;
		parent::__construct($array);
	}
	
	public function fetch($where){
		global $db;
		$where_string = array();
		
		foreach($where AS $key => $value){
			$where_string[] = $key . ' = :' . $key;
		}
		
		$result = $db->select(
			self::table,
			implode(' AND ', $where_string),
			$where
		);
		
		if ($result){
			foreach($result AS $row){
				$item = new item($row);
				$this->append($item);
			}
		} else {
			return false;
		}
		
		return true;
	}
	
	public function to_json(){
		$items = array();
		
		foreach($this->getIterator() AS $item){
			$items[] = $item->to_array();
		}
		
		return json_encode($items);
	}
}

/**
 * Description of item
 *
 * @author carlfredrikhero
 */
class Item {
	const table = 'items';
	protected $db;
	protected $id;
	protected $label;
	protected $tag;
	protected $done;
	protected $date; // YYYY-MM-DD
	
	
	public function __construct($attributes = array()) {
		global $db;
		$this->db = $db;
		
		if(isset($attributes['id'])){
			$this->set_id($attributes['id']);
		}
		if(isset($attributes['label'])){
			$this->set_label($attributes['label']);
		}
		if(isset($attributes['tag'])){
			$this->set_tag($attributes['tag']);
		}
		if(isset($attributes['done'])){
			$this->set_done($attributes['done']);
		}
		if(isset($attributes['date'])){
			$this->set_date($attributes['date']);
		}
	}
	
	public function get_id() {
		return $this->id;
	}

	public function set_id($id) {
		$this->id = (int) $id;
	}

	public function get_label() {
		return $this->label;
	}

	public function set_label($label) {
		$this->label = $label;
	}

	public function get_tag() {
		return $this->tag;
	}

	public function set_tag($tag) {
		$this->tag = $tag;
	}

	public function get_done() {
		return $this->done;
	}

	public function set_done($done) {
		$this->done = (bool) $done;
	}
	
	public function get_date() {
		return $this->date;
	}

	public function set_date($date) {
		$this->date = $date;
	}
		
	public function save(){
		// get user from db
		if ($this->get_id() &&
			$result = $this->db->select(self::table, "id = :id", array('id' => $this->get_id())))
		{
			$result = $this->db->update(
				self::table,
				$this->to_array(),
				"id = :id", 
				array('id' => $this->get_id())
			);
		} else {
			$result = $this->db->insert(
				self::table,
				$this->to_array()
			);
			
			if ($result){
				$this->set_id(
					$this->db->lastInsertId('id')
				);
			}
		}
		
		return $result;
	}
	
	public function remove(){
		if ($this->get_id() &&
			$result = $this->db->select(self::table, "id = :id", array('id' => $this->get_id())))
		{
			$result = $this->db->delete(
				self::table,
				'id = ' . $this->get_id()
			);
		}
	}


	public function to_array(){
		$array = array(
			'id' => $this->get_id(),
			'label' => $this->get_label(),
			'tag' => $this->get_tag(),
			'done' => $this->get_done(),
			'date' => $this->get_date(),
		);
		
		foreach($array AS $key=>$value){
			if (is_null($value)){
				unset($array[$key]);
			}
		}
		
		return $array;
	}
	
	public function to_json(){
		return json_encode($this->to_array());
	}
}

?>
