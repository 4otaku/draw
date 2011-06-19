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
		$save_resized = IMAGES.SL.'gallery'.SL.$this->user_id.SL.'resized'.SL.$time.'.jpg';
		$save_large_thumbnail = IMAGES.SL.'gallery'.SL.$this->user_id.SL.'large_thumbnail'.SL.$time.'.jpg';
		$save_thumbnail = IMAGES.SL.'gallery'.SL.$this->user_id.SL.'thumbnail'.SL.$time.'.jpg';		
		
		$this->test_upload_dirs('gallery', $this->user_id, 'full');
		$this->test_upload_dirs('gallery', $this->user_id, 'resized');
		$this->test_upload_dirs('gallery', $this->user_id, 'large_thumbnail');
		$this->test_upload_dirs('gallery', $this->user_id, 'thumbnail');		
		
		$resized_settings = Config::image('resized');
		$large_thumbnail_settings = Config::image('large_thumbnail');
		$thumbnail_settings = Config::image('thumbnail');
		
		$image->save($save_full);
		
		if (
			$image->get_width() > $resized_settings['width'] ||
			$image->get_height() > $resized_settings['height']
		) {
			$image->target($save_resized)->scale(
				array(
					$resized_settings['width'], 
					$resized_settings['height']
				),
				$resized_settings['compression']
			);
		}
		
		$image->target($save_large_thumbnail)->scale(
			array(
				$large_thumbnail_settings['width'], 
				$large_thumbnail_settings['height']
			),
			$large_thumbnail_settings['compression'],
			true
		);
		
		$image->target($save_thumbnail)->scale(
			array(
				$thumbnail_settings['width'], 
				$thumbnail_settings['height']
			),
			$thumbnail_settings['compression'],
			true
		);
		
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
