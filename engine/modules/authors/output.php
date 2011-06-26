<?

class Authors_Output extends Output_Main implements Plugins
{
	public function get_content ($query, $perpage, $page, $start) {
		
		$names = Database::set_counter()->get_vector(
			'user', 
			array('id', 'username'), 
			'last_draw != "0000-00-00 00:00:00" order by last_draw desc'
		);

		$authors = Database::get_full_table(
			'meta', 
			Database::array_in('name', $names),
			$names
		);
		
		$items = array();
		$names = array_values($names);
		
		foreach ($authors as $author) {
			$order_id = array_search($author['name'], $names);
			$items[$order_id] = $author;
		}
		
		ksort($items);

		$return = array();
		$aliases = array();
		
		foreach ($items as $id => $item) {
			$return[$id] = new Item_Author($item);
			$aliases[] = $item['alias'];
		}
		unset ($items);
		
		$condition = Database::make_search_condition('meta', array(array('+', $aliases, 'author')));
		
		$arts = Database::get_table('art', 
			array('id', 'user_id', 'meta', 'name', 'comments'),
			$condition.' and area="main" order by date desc'
		);
	
		foreach ($return as $id => $gallery) {
			
			foreach ($arts as $art_id => $art) {
				
				if (strpos($art['meta'], 'author__'.$gallery['alias'])) {
					
					$gallery->add_to('images', $art);
					unset($arts[$art_id]);
				}
				
				if (count($gallery['images']) > 4) {
					break;
				}
			}
		}
	
		return $return;
	}
}
