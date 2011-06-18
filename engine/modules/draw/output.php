<?

class Draw_Output extends Output implements Plugins
{
	
	public function main ($query) {
		$vars = Globals::$vars;
		
		$this->get_theme($vars['theme']);
	}
	
	protected function get_theme ($theme_id) {
		$this->items['theme'] = Database::get_row('painter_themes', $theme_id);
	}
}
