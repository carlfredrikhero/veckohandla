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
	
	public function fetch(){
		global $db;
		$result = $db->select(self::table);
		
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
	
	
	public function __construct($attributes) {
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

		
	public function save(){
		// get user from db
		if ($this->get_id() &&
			$result = $this->db->select(self::table, "id = " . $this->get_id()))
		{
			$result = $this->db->update(
				self::table,
				$this->to_array(),
				'id = ' . $this->get_id()
			);
		} else {
			$result = $this->db->insert(
				self::table,
				$this->to_array()
			);
		}
		
		return $result;
	}


	public function to_array(){
		return array(
			'id' => $this->get_id(),
			'label' => $this->get_label(),
			'tag' => $this->get_tag(),
			'done' => $this->get_done(),
		);
	}
	
	public function to_json(){
		return json_encode($this->to_array());
	}
}

?>
