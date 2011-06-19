<?

class Upload_Input extends Input implements Plugins
{
	protected $user_id = 0;
	
	public function gallery ($query) {
		$this->user_id = (int) Globals::user_info('id');
		
		$data = $this->parse_shi_paint(file_get_contents('php://input'));
		
		$image_file = $this->get_temp_file($data['image']);		
		$image = new Transform_Image($image_file);
		
		$format = strtolower($image->get_format());
		$time = time();
		$save_full = IMAGES.SL.'gallery'.SL.$this->user_id.SL.'full'.SL.$time.'.'.$format;
		$save_thumb = IMAGES.SL.'gallery'.SL.$this->user_id.SL.'thumb'.SL.$time.'.jpg';
		$this->test_upload_dirs('gallery', $this->user_id, 'full');
		$this->test_upload_dirs('gallery', $this->user_id, 'thumb');
		
		$thumb_settings = Config::image('thumbnail');
		$sizes = array($thumb_settings['width'], $thumb_settings['height']);
		$compression = $thumb_settings['compression'];

		$image->save($save_full)->target($save_thumb)->scale($sizes, $compression, true);
		
		Art_Input::save($this->user_id, $save_full, $data['timer']);
		
		exit();
	}
	
	protected function parse_shi_paint ($input) {
		$return = array();
		
		list($headers, $image) = preg_split('/\r\n/', $input, 2);
		
		$headers = substr($headers, 9);		
		$headers = explode('&', $headers);
		array_pop($headers);
		
		foreach ($headers as $header) {
			list($name, $value) = preg_split('/=/', $header, 2);
			
			$return[$name] = $value;
		}
		
		$return['image'] = $image;
		
		return $return;
	}
	
	protected function get_temp_file ($data) {
		$filename = microtime(true).'_'.md5($this->user_id);
		
		if (!file_exists(CACHE.SL.'tmp_IMAGES')) {
			mkdir(CACHE.SL.'tmp_IMAGES');
		}
		
		$filename = CACHE.SL.'tmp_IMAGES'.SL.$filename;
		
		file_put_contents($filename, $data);
		
		return $filename;
	}
	
	protected function test_upload_dirs () {
		$dirs = func_get_args();
		
		$directory = IMAGES;
		
		foreach ($dirs as $dir) {
			$directory .= SL.$dir;
			
			if (!file_exists($directory)) {
				mkdir($directory);
			}
		}
	}
}
