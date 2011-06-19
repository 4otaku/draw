<?

class Art_Output extends Output_Main implements Plugins
{
	
	public function single ($query) {
		$this->get_user_id();
		
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
		$this->get_user_id();
		
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
	
	protected function get_user_id () {
		$this->flags['user_id'] = 0;
	}
}
