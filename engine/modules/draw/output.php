<?

class Draw_Output extends Output implements Plugins
{
	
	public function main ($query) {
		$vars = Globals::$vars;
		
		if (!empty($vars['theme'])) {
			$this->get_theme($vars['theme']);
		}
		
		$this->get_user();
		
		if (!empty($vars['mode'])) {
			$this->flags['mode'] = $vars['mode'];
		}
		
		if (!empty($vars['width'])) {
			$this->items['width'] = (int) $vars['width'];
		} else {
			$this->items['width'] = Config::settings('default', 'width');
		}
		
		if (!empty($vars['height'])) {
			$this->items['height'] = (int) $vars['height'];
		} else {
			$this->items['height'] = Config::settings('default', 'height');
		}
		
		$this->items['cookie'] = Globals::$user_data['cookie'];
	}
	
	protected function get_theme ($theme_id) {
		if (!empty($theme_id)) {
			$this->items['theme'] = Database::get_full_row('painter_themes', $theme_id);
		}
	}
	
	protected function get_user () {
		$username = Globals::user_info('username');
		$this->items['user_name'] = Meta_Author::get_alias_by_name($username);

		if (empty($this->items['user_name'])) {
			$this->items['user_name'] = 'anonymous';
		}
	}
}
