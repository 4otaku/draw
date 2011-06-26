<?

class Index_Output extends Output
{
	public function main ($query) {
		
		$this->items['themes'] = Database::get_vector(
			'painter_themes',
			array('id', 'name'),
			'disabled = 0 order by id'
		);
		
		$this->get_latest_art();
		
		$this->items['comments'] = Comments_Output::latest(
			Config::settings('latest_comments', 'block_limit'), 
			Config::settings('latest_comments', 'comment_limit')
		);
	}
	
	protected function get_latest_art () {	
		$latest_art_count = Config::settings('latest_art', 'count');
		
		$latest = Database::get_table(
			'art',
			array('id', 'user_id', 'name'),
			'area != "deleted" order by date desc limit '.$latest_art_count
		);
		
		$galleries = array();
		$image_limit = Config::settings('latest_art', 'image_limit');
		$galleries_limit =Config::settings('latest_art', 'galleries_limit');
		
		foreach ($latest as $art) {
			
			if (
				!empty($galleries[$art['user_id']]['images']) &&
				count($galleries[$art['user_id']]['images']) >= $image_limit
			) {
				continue;
			}
			
			$galleries[$art['user_id']]['images'][] = array(
				'id' => $art['id'],
				'name' => $art['name'],
			);
		}
		
		$galleries = array_slice($galleries, 0, $galleries_limit, true);
		
		$users = Database::get_vector(
			'user', 
			array('id', 'username'), 
			Database::array_in('id', $galleries),
			array_keys($galleries)
		);

		foreach ($users as $id => $user) {
			$alias = Meta_Author::get_alias_by_name($user);
			$galleries[$id]['link'] = empty($alias) ? $user : $alias;
			$galleries[$id]['username'] = $user;
		}

		$this->items['new'] = $galleries;
	}
}
