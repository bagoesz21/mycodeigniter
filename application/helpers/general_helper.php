<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('format_date_id'))
{
    function format_date_id($date)
    {
        $month_id = array('','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                          'September','Oktober','November','Desember');
        
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
    function terbilang($angka){

        $bilangan= array(1=>array(0=>"satu",1=>"se"),2=>"dua",3=>"tiga",4=>"empat",5=>"lima",6=>"enam",7=>"tujuh",8=>"delapan",9=>"sembilan");

        $satuan= array(0=>"",1=>"puluh",2=>"ratus",3=>"ribu",4=>"juta",5=>"miliar");
        

        $set=str_split($angka);

        $jml=strlen($angka);

        $terbilang="";

        $hitung=0;

        foreach ($set as  $value) {
            
            if($jml>=16 AND $jml<=($max=18)){
                return $jml;
            } else if($jml>=13 AND $jml<=($max=15)){
                //trilun
            } else if($jml>=10 AND $jml<=($max=12)){
                //miliar
            } else if($jml>=7 AND $jml<=($max=9)){
                //juta
            }  else if($jml>=4 AND $jml<=($max=6)){
                //ribu
            } else {
                //ratus
            } 

        }

    }
}


if ( ! function_exists('nameUser'))
{
    function nameUser($data){
        if(empty($data)) return;

        return (!empty($data->fullname) ? $data->fullname : $data->username);
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
    function active_menu($menu, $uri = 1){

        $str_uri = get_instance()->uri->segment($uri);
        if(strtolower($str_uri) == strtolower($menu)){
            return "active";
        }else{
            return "";
        }
    }
}

if ( ! function_exists('badge_color'))
{
    function badge_color($text, $color = "success"){
    ?>
        <span class="label label-<?=$color;?>"><?=$text;?></span>
    <?php
    }
}