<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('format_date_id'))
{
    function format_date_id($date, $display_date = "fulldate")
    {
        switch (strtolower($display_date)) {
            case 'fulldate':
                $month_id = array('','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                          'September','Oktober','Nopember','Desember');
                break;
            case 'shortdate':
                $month_id = array('','Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Juni', 'Juli', 'Agus',
                          'Sept','Okt','Nov','Des');
                break;
            default:
                return;
                break;
        }

        $month_number = date("n", strtotime($date));
        return date("d", strtotime($date))." ".$month_id[$month_number]." ".date("Y", strtotime($date));
    }
}

if ( ! function_exists('format_money_id'))
{
    function format_money_id($money)
    {
        return "Rp ".number_format($money,0,",",".");  
    }
}

if ( ! function_exists('format_number_id'))
{
    function format_number_id($money)
    {
        return number_format($money,0,",",".");  
    }
}

if ( ! function_exists('old_input'))
{
    function old_input($key)
    {
        $input = get_instance()->session->flashdata('old_input');
        if(empty($input)) return;
        return array_key_exists($key, $input) ? $input[$key] : "";
    }
}

if ( ! function_exists('show_message'))
{
    function show_message($msg, $status = "error"){
        $div_class = "";
        switch ($status) {
            case "error":
                $div_class = "danger";
                break;
            
            default:
                $div_class = $status;
                break;
        }
        if(!empty($msg)){
        ?>
        <div class="alert alert-<?=$div_class;?> alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong><?=strtoupper($status)?> !</strong> <?=$msg; ?>
        </div>
        <?php }
    }
}

if ( ! function_exists('show_messages'))
{
    function show_messages($msg = array(), $status = "error"){
        $div_class = "";
        switch ($status) {
            case "error":
                $div_class = "danger";
                break;
            
            default:
                $div_class = $status;
                break;
        }
        if(!empty($msg)){
            $str_msg = implode($msg, "<br/>");
        ?>
        <div class="alert alert-<?=$div_class;?> alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong><?=strtoupper($status)?> !</strong> <?=$str_msg; ?>
        </div>
        <?php }
    }
}

if ( ! function_exists('show_all_messages'))
{
    function show_all_messages(){
        $msg = get_instance()->session->flashdata('msg');
        $status = get_instance()->session->flashdata('msg_status');

        if(empty($msg)) return;
        $status = (empty($status) ? 'error' : $status);
        $div_class = "";


        switch ($status) {
            case "error":
            case "danger":
                $div_class = "danger";
                break;
            
            default:
                $div_class = $status;
                break;
        }
        if(!empty($msg)){
            $str_msg = implode($msg, "<br/>");
        ?>
        <div class="alert alert-<?=$div_class;?> alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong><?=strtoupper($status)?> !</strong> <?=$str_msg; ?>
        </div>
        <?php }
    }
}

if ( ! function_exists('flash_messages'))
{
    function flash_messages($msg, $status = "info", $withOldInput = NULL)
    {
        $msg = (!is_array($msg) ? array($msg) : $msg);
        get_instance()->session->set_flashdata('msg', $msg);
        get_instance()->session->set_flashdata('msg_status', $status);

        if(!is_null($withOldInput)){
            get_instance()->session->set_flashdata('old_input', $withOldInput);
        }
    }
}

if ( ! function_exists('json_output'))
{
    function json_output($data){
        return get_instance()->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
}

if ( ! function_exists('render_title'))
{
    function render_title($site_title, $title = ""){
        return $site_title." ~ ".$title;
    }
}

if ( ! function_exists('terbilang'))
{
    function terbilang($number){
        $number = str_replace('.', '', $number);
        if ( ! is_numeric($number)) return;
        $base    = array('nol', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan');
        $numeric = array('1000000000000000', '1000000000000', '1000000000000', 1000000000, 1000000, 1000, 100, 10, 1);
        $unit    = array('kuadriliun', 'triliun', 'biliun', 'milyar', 'juta', 'ribu', 'ratus', 'puluh', '');
        $str     = null;
        $i = 0;
        if ($number == 0)
        {
            $str = 'nol';
        }
        else
        {
            while ($number != 0)
            {
                $count = (int)($number / $numeric[$i]);
                if ($count >= 10)
                {
                    $str .= terbilang($count) . ' ' . $unit[$i] . ' ';
                }
                elseif ($count > 0 && $count < 10)
                {
                    $str .= $base[$count] . ' ' . $unit[$i] . ' ';
                }
                $number -= $numeric[$i] * $count;
                $i++;
            }
            $str = preg_replace('/satu puluh (\w+)/i', '\1 belas', $str);
            $str = preg_replace('/satu (ribu|ratus|puluh|belas)/', 'se\1', $str);
            $str = preg_replace('/\s{2,}/', ' ', trim($str));
        }
        return $str;
    }
}

if ( ! function_exists('bilangan_rupiah'))
{
    function bilangan_rupiah($number){
        if ( ! is_numeric($number)) return;

        $result = "";
        $len_number = strlen($number) - 1;
        /*dpr(format_money_id($number));
        dpr($len_number);*/

        if($len_number < 6){
            $result .= substr($number,0, strlen($number)-3)." ";
            $result .= "ribu";
        }elseif($len_number >= 6 && $len_number <= 8){
            $result .= substr($number,0, strlen($number)-6)." ";
            $result .= "juta";
        }elseif($len_number >= 8 && $len_number <= 11){
            $result .= substr($number,0, strlen($number)-9)." ";
            $result .= "milyar";
        }elseif($len_number >= 12 && $len_number <= 12){
            $result .= substr($number,0, strlen($number)-12)." ";
            $result .= "triliun";
        }
        return $result;
    }
}


if ( ! function_exists('nama_user'))
{
    function nama_user($data, $hak_akses = ""){
        if(empty($data)) return;

        $result = $data["username"];
        return $result;
    }
}

if ( ! function_exists('getModeInput'))
{
    function getModeInput($mode){
        if(empty($mode)) die('Input Mode !');

        $result = "";
        switch (strtolower($mode)) {
            case 'create':
            case 'insert':
                $result = "insert";
                break;
            case 'edit':
            case 'update':
                $result = "update";
                break;
            case 'login':
                $result = "login";
                break;
            case 'register':
                $result = "register";
                break;
            default:
                die('Input Mode !');
                break;
        }

        return $result;
    }
}

if ( ! function_exists('url_eferrer'))
{
    function url_eferrer(){
        get_instance()->load->library('user_agent');
        return get_instance()->agent->referrer();
    }
}


if ( ! function_exists('active_menu'))
{
    function active_menu($menu, $current_active, $class = "active"){

        if(strtolower($current_active) == strtolower($menu)){
            return $class;
        }else{
            return "";
        }
    }
}

if ( ! function_exists('nama_jenkel'))
{
    function nama_jenkel($jenkel){
        if(strtolower($jenkel) == "p"){
            return "Pria";
        }elseif(strtolower($jenkel) == "w"){
            return "Wanita";
        }
    }
}

if ( ! function_exists('logged_user'))
{
    function logged_user($array, $key){
        if(empty($array)) return;

        if(array_key_exists($key, $array)){
            return $array[$key];
        }
    }
}

if ( ! function_exists('badge_color'))
{
    function badge_color($text, $color = "success", $class = ""){
    ?>
        <span class="label label-<?=$color;?> <?=$class;?>"><?=$text;?></span>
    <?php
    }
}

if ( ! function_exists('exists_file'))
{
    function exists_file($file_path) {
        return $file_path !== null && is_file($file_path);
    }
}

if ( ! function_exists('uploads_folder'))
{
    function uploads_folder($text){
        return "/uploads/".$text;
    }
}

if ( ! function_exists('uploads_url'))
{
    function uploads_url($text, $default_img = true){
        return site_url(uploads_folder($text));
    }
}

if ( ! function_exists('assets_folder'))
{
    function assets_folder($text){
        return "/assets/".$text;
    }
}

if ( ! function_exists('assets_url'))
{
    function assets_url($text){
        return site_url(assets_folder($text));
    }
}

if ( ! function_exists('avatar_folder'))
{
    function avatar_folder($text){
        return uploads_folder("avatar/".$text);
    }
}

if ( ! function_exists('avatar_default'))
{
    function avatar_default(){
        return site_url(uploads_folder("user.png"));
    }
}

if ( ! function_exists('avatar_url'))
{
    function avatar_url($text, $default_img = true){
        if($text == "") return avatar_default();

        if(exists_file(FCPATH.avatar_folder($text))){
            return site_url(avatar_folder($text));
            exit();
        }

        if($default_img){
            return avatar_default();
        }
    }
}

if ( ! function_exists('no_img'))
{
    function no_img(){
        return site_url(uploads_folder("no-img.jpg"));
    }
}

if ( ! function_exists('selisih_tgl'))
{
    function selisih_tgl($tgl_mulai, $tgl_selesai){
        $strtotime_mulai = strtotime($tgl_mulai);
        $strtotime_selesai = strtotime($tgl_selesai);

        $geo_mulai = gregoriantojd(date("m", $strtotime_mulai),date("d", $strtotime_mulai), date("Y", $strtotime_mulai));
        $geo_selesai = gregoriantojd(date("m", $strtotime_selesai),date("d", $strtotime_selesai), date("Y", $strtotime_selesai));

        $geo_selisih = $geo_selesai - $geo_mulai;
        return $geo_selisih;
    }
}

if ( ! function_exists('hitung_umur'))
{
    function hitung_umur($tgl_lahir){
        $geo_today = date("Y-m-d");
        $geo_selisih = selisih_tgl($tgl_lahir, $geo_today);

        $umur['tahun'] = $geo_selisih / 365;
        $umur['sisa'] = $geo_selisih % 365;
        $umur['bulan'] = $umur['sisa'] / 30;
        $umur['hari'] = $umur['sisa'] % 30;
        return floor($umur['tahun']);
    }
}

if ( ! function_exists('upload_gambar'))
{
    function upload_gambar($input_name, $folder_upload = ""){
        if (empty($_FILES[$input_name]['name'])) {
            return;
        }

        $folder_upload = empty($folder_upload) ? $input_name : $folder_upload;
        $config['upload_path']          = $folder_upload;
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_ext_tolower']     = true;
        $config['overwrite']            = true;
        $config['max_size']             = 2048;

        get_instance()->load->library('upload', $config);

        try {
            if ( ! get_instance()->upload->do_upload($input_name))
            {
                //dpr(get_instance()->upload->display_errors());die;
                flash_messages(array(get_instance()->upload->display_errors()), "error", get_instance()->input->post());
                return redirect(url_referrer());
                exit();
            }

            return get_instance()->upload->data();
        } catch (Exception $e) {
            log_message('error', $e->__toString());
            flash_messages(array($e->__toString()), "error", get_instance()->input->post());
            return redirect(url_referrer());
            exit();
        }
    }
}

if ( ! function_exists('upload_avatar'))
{
    function upload_avatar(){
        if (empty($_FILES['avatar']['name'])) {
            return;
        }
        $config['upload_path']          = './uploads/avatar';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_ext_tolower']     = true;
        $config['overwrite']            = true;
        $config['max_size']             = 2048;

        get_instance()->load->library('upload', $config);

        try {
            if ( ! get_instance()->upload->do_upload('avatar'))
            {
                //dpr(get_instance()->upload->display_errors());die;
                flash_messages(array(get_instance()->upload->display_errors()), "error", get_instance()->input->post());
                return redirect(url_referrer());
                exit();
            }

            return get_instance()->upload->data();
        } catch (Exception $e) {
            log_message('error', $e->__toString());
            flash_messages(array($e->__toString()), "error", get_instance()->input->post());
            return redirect(url_referrer());
            exit();
        }
    }
}

if ( ! function_exists('admin_url'))
{
    function admin_url($url = "/"){
        return site_url(str_replace("//","/",'/admin/'.$url));
    }
}

if ( ! function_exists('front_url'))
{
    function front_url($url = "/"){
        return site_url(str_replace("//","/",'/'.$url));
    }
}

if ( ! function_exists('delete_file'))
{
    function delete_file($path){
        unlink(FCPATH."/".$path);
    }
}

if ( ! function_exists('delete_avatar'))
{
    function delete_avatar($path){
        delete_file(avatar_folder($path));
    }
}

if ( ! function_exists('dec_to_persen'))
{
    function dec_to_persen($value){
        return round($value*100);
    }
}

if ( ! function_exists('persen_to_dec'))
{
    function persen_to_dec($value){
        if($value < 1) return 0;
        return $value/100;
    }
}

if ( ! function_exists('check_required_dir'))
{
    function check_required_dir(){
        if(!file_exists(FCPATH.uploads_folder(""))){
            mkdir(FCPATH.uploads_folder(""));
        }

        if(!file_exists(FCPATH.uploads_folder("avatar"))){
            mkdir(FCPATH.uploads_folder("avatar"));
        }
    }
}

if ( ! function_exists('no_data_table'))
{
    function no_data_table($col=""){
        return "<tr><td colspan='".$col."' class='text-center'><b>Tidak ada data</b></td></tr>";
    }
}