<?

class Index_Output extends Output
{
	public function main ($query) {
		$this->items['themes'] = Database::get_vector(
			'painter_themes',
			array('id', 'name'),
			'disabled = 0 order by id'
		);
	}
}
