<?

class Art_Input extends Input implements Plugins
{
	public static function save ($filename, $username, $user_id = 0, $timer = false) {
		
		$sizes = getimagesize($filename);
		
		$insert = array(
			'name' => pathinfo($filename, PATHINFO_FILENAME),
			'width' => $sizes[0],
			'height' => $sizes[1],
			'weight' => filesize($filename),
			'extension' => pathinfo($filename, PATHINFO_EXTENSION),
			'timer' => (int) $timer,
			'meta' => self::get_base_meta($username),
			'area' => 'main',
			'user_id' => $user_id
		);
		
		Database::insert('art', $insert);
		
		$update = array('last_draw' => Database::unix_to_date());
		
		Database::update('user', $user_id, $update);
	}
	
	protected static function get_base_meta ($username) {
		
		return 'index: area__main author__'.$username;
	}
}
