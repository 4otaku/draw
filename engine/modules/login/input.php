<?

class Login_Input extends Input implements Plugins
{
	const	INCORRECT_PASSWORD = 'profile_incorrect_password',
			NO_SUCH_USER = 'profile_no_such_user',
			PASSWORDS_DONT_MATCH = 'profile_passwords_dont_match',
			PASSWORD_TOO_SHORT = 'profile_password_too_short',
			LOGIN_TOO_SHORT = 'profile_login_too_short',
			USER_ALREADY_EXISTS = 'profile_user_already_exists',
			REGISTER_SUCCESS = 'profile_register_success',
			LOGIN_SUCCESS = 'profile_login_success';
	
	public function login ($query) {
		$params = array($query['login']);
		$params[] = $this->encode_password($query['pass']);

		$cookie = Database::get_field('user', 'cookie', '`username` = ? and `password` = ?', $params);
		
		if (!empty($cookie)) {
			Cookie::set_cookie($cookie);
			exit(self::LOGIN_SUCCESS);
		} 
		
		if (Database::get_field('user', 'cookie', '`username` = ?', $query['login'])) {
			exit(self::INCORRECT_PASSWORD);
		} else {
			exit(self::NO_SUCH_USER);
		}
	}
	
	public function register ($query) {
		if (count($query['pass']) != 2 || count(array_unique($query['pass'])) != 1) {
			exit(self::PASSWORDS_DONT_MATCH);
		}
		
		$password = current($query['pass']);
		
		if (mb_strlen($password) < Config::settings('min_length', 'password')) {
			exit(self::PASSWORD_TOO_SHORT);
		}
		
		if (mb_strlen($query['login']) < Config::settings('min_length', 'login')) {
			exit(self::LOGIN_TOO_SHORT);
		}
		
		if (Database::get_field('user', 'cookie', '`username` = ?', $query['login'])) {
			exit(self::USER_ALREADY_EXISTS);
		}
		
		if (empty($query['mail'])) {
			$query['mail'] = md5(rand());
		}
		
		$insert = array(
			'cookie' => Globals::$user_data['cookie'],
			'username' => $query['login'],
			'password' => $this->encode_password($password),
			'email'=> $query['mail'],
		);
		
		Database::insert('user', $insert);
		
		Meta::add('author', $query['login']);

		exit(self::REGISTER_SUCCESS);
	}
	
	public function remember_password ($query) {
		$this->redirect_address = '';
	}
	
	protected function encode_password ($password) {
		return Crypt::md5_salt($password, Config::main('salt'));
	}
}
