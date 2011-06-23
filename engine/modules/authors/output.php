<?

class Authors_Output extends Output_Main implements Plugins
{
	public function get_content ($query, $perpage, $page, $start) {
		
		$condition = "type = 'author' order by id desc limit $start, $perpage";

		$items = Database::set_counter()->get_full_vector('meta', $condition);

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
