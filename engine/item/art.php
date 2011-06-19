<?

class Item_Art extends Item_Abstract_Meta implements Plugins
{
	public function postprocess () {
		parent::postprocess();
		
		list(
			$this->data['weight'], 
			$this->data['weight_type']
		) = Transform_String::round_bytes($this->data['weight']);
	}
}
