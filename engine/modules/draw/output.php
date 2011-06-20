<?

class Draw_Output extends Output implements Plugins
{
	
	public function main ($query) {
		$vars = Globals::$vars;
		
		$this->get_theme($vars['theme']);
		$this->get_user();
		
		$this->flags['pro'] = ($vars['mode'] == 'shi_painter_pro');
		$this->items['width'] = (int) $vars['width'];
		$this->items['height'] = (int) $vars['height'];
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
