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
		
		$this->items['comments'] = Comments_Output::latest(5, 2);
	}
	
	protected function get_latest_art () {	
		$latest_art_count = Config::template('latest_art_count');
		
		$latest = Database::get_table(
			'art',
			array('id', 'user_id', 'name'),
			'area != "deleted" order by date desc limit '.$latest_art_count
		);
		
		$galleries = array();
		
		foreach ($latest as $art) {
			
			$galleries[$art['user_id']]['images'][] = array(
				'id' => $art['id'],
				'name' => $art['name'],
			);
			
			if (count($galleries[$art['user_id']]['images']) > 2) {
				continue;
			}
		}
		
		$galleries = array_slice($galleries, 0, 6, true);
		
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
