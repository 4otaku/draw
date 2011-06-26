<?

class Description_Input extends Input implements Plugins
{
	public function edit ($query) {
		
		if (!($this->test_rights($query))) {
			return;
		}
		
		$insert = array(
			'type' => $query['type'],
			'description_id' => $query['id'],
			'pretty_text' => $query['text'],
			'text' => Transform_Text::format($query['text']),
		);
		
		Database::replace('description', $insert, array('type', 'description_id'));
	}
	
	protected function test_rights ($query) {
		if ($query['type'] == 'art') {
			
			$params = array(
				$query['id'],
				Globals::user_info('id')
			);
			
			return Database::get_field('art', 'id', 'id = ? and user_id = ?', $params);
		}
		
		if ($query['type'] == 'author') {
			
			$user = Meta_Author::get_data_by_alias((array) $query['id']);
			if (empty($user)) {
				return false;
			}
			
			$user = current($user);
			
			return (Globals::user_info('username') == $user['name']);
		}		
	}
}
