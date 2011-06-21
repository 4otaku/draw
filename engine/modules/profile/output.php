<?

class Profile_Output extends Output implements Plugins
{
	public function main ($query) {

		$this->items = Globals::user_info();
	}
}
