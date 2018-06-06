<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	/* Cara pemakaian template :
	* Setting lokasi template variable $tpl_sidebar, $tpl_header, $tpl_layout, $tpl_footer (bisa custom)
	* variable $data = penampungan info-info : (bisa custom)
	* - $this->data['header'] = var $data utk header
	* - $this->data['sidebar'] = var $data utk sidebar
	* - $this->data['rightbar'] = var $data utk rightbar
	* - $this->data['content'] = var $data utk content
	* - $this->data['footer'] = var $data utk footer
	* - $this->data['json'] = var $data utk json
	* - var $data (title, description, keywords, author) sbg global info site.
	*  header, sidebar, rightbar, footer kosongkan bila tdk ingin dirender
	 */
	protected $data = Array();

	protected $tpl_header = "header";
	protected $tpl_sidebar = "sidebar";
	protected $tpl_rightbar = "";
	protected $tpl_layout = "layout";
	protected $tpl_footer = "footer";
	protected $enabled_meta = false;
	protected $enabled_breadcrumbs = false;
	protected $autoTitle = true;
	protected $web_title = "MYCODEIGNITER";
	protected $logged_user = array();

	protected function info(){
		$this->data['title'] = $this->web_title;

		if($this->autoTitle){
			$this->data['title'] = $this->automatic_title();
		}

		$this->data['json'] = "";

		$this->data['header']= array();
		$this->data['content'] 	= array();
		$this->data['sidebar']	= array();
		$this->data['rightbar']	= array();
		$this->data['footer']	= array();

		$this->data['header']['site_title'] = "";
		$this->data['content']['breadcrumb'] = "";

		$this->set_global_info("body_class", "nav-md");
		$this->data['sidebar']['active_menu'] = uri_string();
	}

	protected function meta_info(){
		$this->data["meta"] = array();

		if(!$this->enabled_meta){
			return;
		}

		$this->data["meta"]['title'] = "";
		$this->data["meta"]['description'] = "";
		$this->data["meta"]['keywords'] = "";
		$this->data["meta"]['author'] = "BAGOESZ21";

		$this->data["meta"]['og']["locale"] = "en_US";
		$this->data["meta"]['og']["type"] = "website";
		$this->data["meta"]['og']["title"] = "";
		$this->data["meta"]['og']["description"] = "";
		$this->data["meta"]['og']["url"] = "";
		$this->data["meta"]['og']["site_name"] = "";
		$this->data["meta"]['og']["image"] = "";

		$this->data["meta"]['twitter']["card"] = "summary";
		$this->data["meta"]['twitter']["title"] = "";
		$this->data["meta"]['twitter']["description"] = "";
		$this->data["meta"]['twitter']["image"] = "";
	}

	public function __construct()
	{
		parent::__construct();

		//check is logged or not
		$this->isLogin();

		$this->info();
		$this->meta_info();
		$this->load_breadcrumb();
		$this->show_logged_user();
	}

	protected function render($view, $content_data = "", $render_as ="fullpage") {
        switch (strtolower($render_as)) {
        case "ajax"     :
        	if($this->enabled_breadcrumbs){
        		$this->data['content']['breadcrumb'] = $this->breadcrumbs->show();
        	}
            return $this->load->view($view,$this->data['content']);
        break;
        case "json"     :
            return json_output($this->data['json']);
        break;
        case "fullpage" :
        default         :
        	if($this->enabled_breadcrumbs){
        		$this->data['content']['breadcrumb'] = $this->breadcrumbs->show();
        	}

			if(!empty($this->tpl_header)){
				$this->data["_header"] = $this->load->view($this->tpl_header, $this->data['header'],true);
			}

			if(!empty($content_data)){
				foreach ($content_data as $key => $value) {
					$this->data["content"][$key] = $value;
				}
			}
			$this->data["_main"] = $this->load->view($view, $this->data['content'],true);

			if(!empty($this->tpl_sidebar)){
				$this->data["_sidebar"] = $this->load->view($this->tpl_sidebar, $this->data['sidebar'],true);
			}

			if(!empty($this->tpl_rightbar)){
				$this->data["_rightbar"] = $this->load->view($this->tpl_rightbar,$this->data['rightbar'],true);
			}

			if(!empty($this->tpl_footer)){
				$this->data["_footer"] = $this->load->view($this->tpl_footer,$this->data['footer'],true);
			}

			//render view
			$this->load->view($this->tpl_layout,$this->data);

			/*if(!$this->input->is_ajax_request()){
				$this->output->enable_profiler(config_item('enable_debug'));	
			}*/
		break;
    	}
	}

	private function load_breadcrumb(){
		if(!$this->enabled_breadcrumbs){
			return;
		}
		$this->load->library('breadcrumbs');
		$this->breadcrumbs->push('Home', '#');
	}

	protected function render_ajax($view) {
		return $this->render($view, 'ajax');
	}

	protected function render_json($view) {
		return $this->render($view, 'json');
	}

	protected function set_tpl_header($val){
		$this->tpl_header = $val;		
	}

	protected function set_tpl_layout($val){
		$this->tpl_layout = $val;		
	}

	protected function set_tpl_sidebar($val){
		$this->tpl_sidebar = $val;		
	}

	protected function set_tpl_footer($val){
		$this->tpl_footer = $val;		
	}

	protected function set_title($val){
		$this->data['title'] = $this->render_title($val);
	}

	protected function set_global_info($key, $val){
		$this->data[$key] = $val;
	}

	protected function enabled_csrf(){
		$this->data['content']["csrf"] = array(
	        'name' => $this->security->get_csrf_token_name(),
	        'hash' => $this->security->get_csrf_hash()
	    );
	}

	private function automatic_title(){
		$segments = $this->uri->segment_array();
		return $this->render_title(implode(" ", $segments));
	}

	private function render_title($val){
		return $this->web_title." ~ ".ucwords($val);
	}

	public function isLogin(){
		if( $this->session->has_userdata('id_user') == FALSE ) {
			return redirect(site_url('login'));
		}
		return true;
	}

	private function show_logged_user(){
		$this->load->model("user_model");
		$user = $this->user_model->as_array()->fields("id_user,username,email")->get($this->session->userdata("id_user"));
		if(empty($user)){
			return redirect(site_url('logout'));
		}

		$this->logged_user = $user;

		$this->data["header"]["user"] = $this->logged_user;
		$this->data["content"]["logged_user"] = $this->logged_user;
	}
}