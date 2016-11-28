<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{
	public $table = 'user';
	public $primary_key = 'id_user';
	public $fillable = array();
	public $protected = array('id_user');
	public $timestamps = FALSE;
	public $pagination_delimiters = array('<span>','</span>');
	public $pagination_arrows = array('&lt;','&gt;');
    public $return_as = 'array'; //'object' | 'array' 

	/*public $_database_connection = NULL;
	public $soft_deletes = FALSE;
	public $delete_cache_on_save = TRUE;
	public $cache_driver = 'file';
	public $cache_prefix = 'mm';
	*/
	
	//validation
	public $rules = array(
        'insert' => array(

	        'username' => array(
	                'field'=>'username',
	                'label'=>'Username',
	                'rules'=>'trim|required|is_unique[user.username]|min_length[3]|max_length[20]|alpha_dash'),
	        'email' => array(
	            'field'=>'email',
	            'label'=>'Email',
	            'rules'=>'trim|valid_email|required|is_unique[user.email]'),
	        'password' => array(
	                'field'=>'password',
	                'label'=>'Password',
	                'rules'=>'trim|required'),
	        're-password' => array(
	                'field'=>'re-password',
	                'label'=>'Retype Password',
	                'rules'=>'trim|required|matches[password]'),
        ),
        'update' => array(
        	'username' => array(
	                'field'=>'username',
	                'label'=>'Username',
	                'rules'=>'trim|required|min_length[3]|max_length[20]|alpha_dash'),
	        'email' => array(
	            'field'=>'email',
	            'label'=>'Email',
	            'rules'=>'trim|valid_email|required'),
	        'password' => array(
	                'field'=>'password',
	                'label'=>'Password',
	                'rules'=>'trim'),
	        're-password' => array(
	                'field'=>'re-password',
	                'label'=>'Ulangi Password',
	                'rules'=>'trim|matches[password]'),
	        'id' => array(
	                'field'=>'id_user',
	                'label'=>'ID',
	                'rules'=>'trim|is_natural_no_zero|required'),
        ),
        //validasi utk login
        'login' => array(
            'user' => array(
                    'field'=>'user',
                    'label'=>'User',
                    'rules'=>'trim|required'),
            'password' => array(
                    'field'=>'password',
                    'label'=>'Password',
                    'rules'=>'trim|required'),
        ),
        //validasi utk register
        'register' => array(
            'username' => array(
                    'field'=>'username',
                    'label'=>'Username',
                    'rules'=>'trim|required|is_unique[user.username]|min_length[3]|max_length[20]|alpha_dash'),
            'email' => array(
                'field'=>'email',
                'label'=>'Email',
                'rules'=>'trim|valid_email|required|is_unique[user.email]'),
            'password' => array(
                    'field'=>'password',
                    'label'=>'Password',
                    'rules'=>'trim|required'),
        )
    );

	public function __construct()
	{
		parent::__construct();
	}

	private function data_user()
    {
        $data = array(
        );
        $this->db->insert_batch($this->table, $data);
    }

    public function data_dummy(){
    	$this->data_user();
    }

    public function validate($rules = array()){
        $this->load->library('form_validation');
    	$mode = getModeInput($this->input->post('mode'));

    	$all_rules = $this->rules[$mode];

    	if(!empty($rules)){
    		$all_rules = array_merge($all_rules, $rules);
    	}
    	$this->form_validation->set_rules($all_rules);

		return $this->form_validation->run();
    }

    public function encrypt_pass($str)
    {
    	$this->load->library('Bcrypt');
		return $this->bcrypt->hash($str);
    }

    public function check_pass($str, $hash_pass)
    {
    	$this->load->library('Bcrypt');
		return $this->bcrypt->verify($str, $hash_pass);
    }
}