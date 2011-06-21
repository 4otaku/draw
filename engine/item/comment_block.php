<?

class Item_Comment_Block extends Item_Abstract_Container implements Plugins
{
	protected $item_data = array();
	protected $thumbnail_type = 'large_thumbnail';
	
	public function postprocess () {

		$this->data['id'] = (int) $this->data['id'];
		$this->data['place'] = preg_replace('/[^a-z_]+/i', '', $this->data['place']);
		$this->item_data = Database::get_full_row($this->data['place'], $this->data['id']);

		$this->data['link'] = !empty($this->item_data['url']) ?
			$this->item_data['url'] :
			$this->data['id'];

		parent::postprocess();
	}
	
	public function get_title () {
		if (!empty($this->item_data['title'])) {
			return $this->item_data['title'];
		}
		
		if ($this->data['place'] == 'art') {
			return 'Изображение №'.$this->data['id'];
		}
		
		return '';
	}
	
	public function get_comments () {
		if (!empty($this->item_data['comments'])) {
			return $this->item_data['comments'];
		}
		
		return 0;
	}
	
	public function set_small_thumbnail () {
		$this->thumbnail_type = 'thumbnail';
	}
	
	public function get_image () {
		switch ($this->data['place']) {
			case 'art':
				return '/images/gallery/'.
						$this->item_data['user_id'].
						'/'.$this->thumbnail_type.'/'.
						$this->item_data['name'].'.jpg';
			case 'news':
				return '/images/news/thumbnail/'.$this->item_data['image'];
			default:
				return false;
		}
	}
}
