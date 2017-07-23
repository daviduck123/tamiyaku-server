<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

 	public function __construct(){
        parent::__construct();
        $this->load->model('User_Model');
    }

	public function index(){
		$this->load->view('welcome_message');
	}

    public function send_email(){
        $this->load->library("email");
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'mini4wdku@gmail.com', // change it to yours
            'smtp_pass' => 'namamu123', // change it to yours
            'mailtype'  => 'html', 
            'newline' => "\r\n",
            'starttls'  => true,
            'charset'   => 'iso-8859-1'
            );

            $this->email->initialize($config);
            $this->email->from('mini4wdku@gmail.com', "Admin Mini4WD");
            $this->email->to($email);


            $this->email->subject('Email Verifikasi');
            $this->email->message('<html><head></head><body>Welcome to Website Tamiyaku.<br/>'.$nama.' here is the <a href="http://https://www.facebook.com/">activation link.</a><br/><br/>Thank you for joining with us.<br/><br/>Admin Tamiyaku.</body></html>');

            if ($this->email->send()) {
                echo "Sukses";
            } else {
                print_r($this->email->print_debugger());
            }
    }
}
