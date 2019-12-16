<?php
date_default_timezone_set('Asia/Jakarta');
function helper_log($tipe = "", $str = "")
{
    $CI = &get_instance();

    if (strtolower($tipe) == "login") {
        $log_tipe   = 0;
    } elseif (strtolower($tipe) == "logout") {
        $log_tipe   = 1;
    } elseif (strtolower($tipe) == "add") {
        $log_tipe   = 2;
    } elseif (strtolower($tipe) == "edit") {
        $log_tipe  = 3;
    } elseif (strtolower($tipe) == "delete") {
        $log_tipe  = 3;
    } elseif (strtolower($tipe) == "approval") {
        $log_tipe  = 4;
    } elseif (strtolower($tipe) == "unapproval") {
        $log_tipe  = 5;
    } elseif (strtolower($tipe) == "send") {
        $log_tipe  = 6;
    } elseif (strtolower($tipe) == "access") {
        $log_tipe  = 7;
    } else {
        $log_tipe  = 8;
    }

    // paramter
    $param['log_user']      = $CI->session->userdata('nama');
    $param['log_tipe']      = $log_tipe;
    $param['log_desc']      = $str;
    $param['log_time']      = (date("Y-m-d H:i:s"));

    //load model log
    $CI->load->model('Log_model');

    //save to database
    $CI->Log_model->save_log($param);
}
