<?

class Admin_Input extends Input implements Plugins
{
	public function add_painter_theme ($query) {
		
		$insert = array();
		
		foreach ($query as $field => $value) {
			if (substr($field, 0, 6) == 'field_') {
				$insert[substr($field, 6)] = $value;
			}
		}
		
		Database::insert('painter_themes', $insert);
	}
	
	public function edit_painter_theme ($query) {
		
		$update = array();
		
		$id = (int) $query['item_id'];
		
		foreach ($query as $field => $value) {
			if (substr($field, 0, 6) == 'field_') {
				$update[substr($field, 6)] = $value;
			}
		}
		
		Database::update('painter_themes', $id, $update);
	}	
}
