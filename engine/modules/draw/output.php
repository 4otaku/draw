<?

class Draw_Output extends Output implements Plugins
{
	
	public function main ($query) {
		$vars = Globals::$vars;
		
		$this->get_theme($vars['theme']);
		
		$this->flags['pro'] = ($vars['mode'] == 'shi_painter_pro');
		$this->items['width'] = (int) $vars['width'];
		$this->items['height'] = (int) $vars['height'];
		$this->items['layer_count'] = (int) $vars['layer_count'];
	}
	
	protected function get_theme ($theme_id) {
		$this->items['theme'] = Database::get_row('painter_themes', $theme_id);
	}
}
