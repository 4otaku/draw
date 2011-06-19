<?

class Art_Input extends Input implements Plugins
{
	public static function save ($user_id, $filename, $timer = false) {
		
		$sizes = getimagesize($filename);
		
		$insert = array(
			'name' => pathinfo($filename, PATHINFO_FILENAME),
			'width' => $sizes[0],
			'height' => $sizes[1],
			'weight' => filesize($filename),
			'extension' => pathinfo($filename, PATHINFO_EXTENSION),
			'timer' => (int) $timer,
			'meta' => self::get_base_meta($user_id),
			'area' => 'main',
		);
		
		Database::insert('art', $insert);
	}
	
	protected static function get_base_meta ($user_id) {
		$user_id = (int) $user_id;
		
		$name = Database::get_field('user', 'username', $user_id);
		
		return 'index: area__main author__'.$name;
	}
}
