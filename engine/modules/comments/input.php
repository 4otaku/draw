<?

class Comments_Input extends Input implements Plugins
{
	protected $referer = '';

	public function add ($query) {

		list($place, $item_id) = $this->parse_referer($query);

		if (!empty($query['parent'])) {
			$root = Database::get_field('comment', 'root', $query['parent']);

			if (empty($root)) {
				$root = $query['parent'];
			}
		} else {
			$root = 0;
		}

		$insert = array(
			'root' => $root,
			'parent' => $query['parent'],
			'place' => $place,
			'item_id' => $item_id,
			'area' => 'main',
			'username' => $query['name'],
			'email' => $query['email'],
			'ip' => Globals::$user_data['ip'],
			'cookie' => Globals::$user_data['cookie'],
			'text' => Transform_Text::format($query['text']),
			'pretty_text' => $query['text'],
		);

		Database::insert('comment', $insert);

		$this->redirect_address = $this->referer;
	}

	protected function parse_referer ($query) {

		if (!empty($query['place']) && !empty($query['item_id'])) {
			$url = '/'.$query['place'].'/'.$query['item_id'].'/';
		} else {
			$url = parse_url(getenv('HTTP_REFERER'), PHP_URL_PATH);
		}

		$this->referer = $url;

		Globals::get_url($url);

		$module = Query::get_module(Globals::$url);
		$module_config_file = ENGINE.SL.'modules'.SL.$module.SL.'settings.ini';
		Config::load($module_config_file, true, true);

		$comment_query = Query::make_query_output(Globals::$url);

		return array($module, $comment_query['id']);
	}
}
