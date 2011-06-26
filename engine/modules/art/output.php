<?

class Art_Output extends Output_Main implements Plugins
{
	
	public function single ($query) {
		
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
	
	public function description ($query) {
		
		$params = array();
		
		if (isset($query['meta']) && $query['meta'] == 'author' && isset($query['alias'])) {
			$params['type'] = 'author';
			$params['id'] = $query['alias'];
		} elseif (isset($query['id'])) {
			$params['type'] = 'art';
			$params['id'] = $query['id'];			
		} else {
			return;
		}
		
		$params['text'] = Database::get_field(
			'description', 
			'text',
			'type = ? and description_id = ?', 
			$params
		);
		
		if ($params['type'] == 'author') {
			$user = Meta_Author::get_data_by_alias((array) $params['id']);
			$user = current($user);
			$params['username'] = $user['name'];
		} else {
			$params['data'] = Database::get_full_row('art', $query['id']);
			$params['username'] = Database::get_field('user', 'username', $params['data']['user_id']);
			$params['alias'] = Meta_Author::get_alias_by_name($params['username']);
			$params['data']['weight'] = Transform_File::weight($params['data']['weight']);
			$params['data']['date'] = Transform_String::rudate(Database::date_to_unix($params['data']['date']));
		}
		
		return $params;
	}
}
