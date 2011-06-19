<?

class Footer_Output extends Output_Simple implements Plugins
{
	public function main () {

		return array('year' => date('Y'));
	}
}
