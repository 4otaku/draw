<?

final class Error
{	
	public static function fatal($message) {
		ob_end_clean();
		
		header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("HTTP/1.x 404 Not Found");		
		
		readfile(TEMPLATES.SL.'html'.SL.'error.html');
		
		exit();
	}
	
	public static function warning($message) {
		echo "<br /><br />$message<br /><br />";
	}
}
