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
			$this->flags['pro'] = ($vars['mode'] == 'shi_painter_pro');
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
	}
	
	protected function get_theme ($theme_id) {
		if (!empty($theme_id)) {
			$this->items['theme'] = Database::get_full_row('painter_themes', $theme_id);
		}
	}
	
	protected function get_user () {
		$this->items['user_name'] = 'anonymous';
	}
}
