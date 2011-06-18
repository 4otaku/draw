<?

class Admin_Output extends Output implements Plugins
{
	
	public function main ($query) {
		$function = empty(Globals::$url[1]) ? 'menu' : Globals::$url[1];
		
		$parameters = array_slice(Globals::$url, 2);		
		
		call_user_func_array(array($this, $function), $parameters);
		$this->flags['display'] = $function;
	}
	
	protected function add_painter_theme() {
		$fields = Database::get_full_row('painter_themes', 1);
		unset($fields['id'], $fields['disabled']);

		$this->items = array_keys($fields);
	}
	
	protected function edit_painter_theme($id = false) {
		
		if (empty($id)) {
			$this->items = Database::get_vector(
				'painter_themes',
				array('id', 'name'),
				'1 order by id'
			);
			
			$this->flags['mode'] = 'list';
		} else {
			$fields = Database::get_full_row('painter_themes', $id);
			unset($fields['id'], $fields['disabled']);

			$this->items = $fields;		
		
			
			$this->flags['mode'] = 'edit';
			$this->flags['id'] = $id;
		}
	}
}
