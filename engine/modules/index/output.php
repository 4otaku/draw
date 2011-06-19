<?

class Index_Output extends Output
{
	public function main ($query) {
		$this->items['themes'] = Database::get_vector(
			'painter_themes',
			array('id', 'name'),
			'disabled = 0 order by id'
		);
		
		$latest = Database::get_table(
			'art',
			array('user_id', 'name'),
			'area != "deleted" order by date desc limit 10'
		);
		
		$galleries = array();
		
		foreach ($latest as $art) {
			
			$galleries[$art['user_id']]['images'][] = $art['name'];
			
			if (count($galleries[$art['user_id']]['images']) > 4) {
				break;
			}
		}
		
		$users = Database::get_vector(
			'user', 
			array('id', 'username'), 
			Database::array_in('id', $galleries),
			array_keys($galleries)
		);

		foreach ($users as $id => $user) {
			$galleries[$id]['user'] = $user;
		}
		
		$this->items['new'] = $galleries;
	}
}
