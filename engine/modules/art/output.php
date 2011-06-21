<?

class Art_Output extends Output_Main implements Plugins
{
	
	public function single ($query) {
		$this->get_user_id($query);
		
		$id = $query['id'];
		$art = Database::get_full_row('art', $id);
		
		$this->test_area($art['area']);
		
		$this->items[$id] = new Item_Art($art);
		
		$meta = Meta::prepare_meta(array($id => $art['meta']));

		$this->items[$id] = Transform_Item::merge(
			$this->items[$id], 
			current($meta)
		);
	}

	public function get_content ($query, $perpage, $page, $start) {
		$this->get_user_id($query);
		
		$listing_condition = $this->build_listing_condition($query);
		$condition = $listing_condition . " order by date desc limit $start, $perpage";

		$items = Database::set_counter()->get_full_vector('art', $condition);
		$index = array();
		$return = array();
		
		foreach ($items as $id => $item) {
			$return[$id] = new Item_Thumbnail($item);
			$index[$id] = $item['meta'];
		}
		unset ($items);

		$meta = Meta::prepare_meta($index);
		
		foreach ($this->items as $id => & $item) {
			$item = Transform_Item::merge($item, $meta[$id]);
		}
		
		return $return;
	}
	
	protected function get_user_id ($query) {
		
		if (!empty($query['id'])) {
			$this->flags['user_id'] = Database::get_field('art', 'user_id', $query['id']);
		}
		
		if (!empty($query['alias'])) {
			$name = Database::get_field('meta', 'name', 'type="author" and alias = ?', $query['alias']);
			
			if (empty($name)) {
				$name = $query['alias'];
			}
			
			$this->flags['user_id'] = Database::get_field('user', 'id', 'username = ?', $name);
		}		
	}
}
