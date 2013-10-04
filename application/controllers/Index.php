<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * GraphScience Test - NLorenzo
 *
 * A simple Facebook PHP API test by Nazareno Lorenzo
 * for GraphScience selection process
 *
 * @author  Nazareno Lorenzo
 */
class Index extends CI_Controller {

    /**
     * Constructor
     * 
     * Loads all the necesary logic for user authentication 
     * and basic data retrieving
     * @return void
     */
    function __construct() {
        parent::__construct();

        //Load the Facebook Library passing the app configuration
        $this->load->library('facebook/src/facebook', array(
            'appId'  => $this->config->item('fb_appId'),
            'secret' => $this->config->item('fb_secret'))
        );
        
        //Loads the user models that handles authentication
        $this->load->model('Facebook_user_model');
        
    }


    /**
     * Index Page for this App
     *
     */
    public function index()
    {

        //Get the user id and data (if its connected)
        $user = $this->Facebook_user_model->getUser();
        $data = $this->session->userdata('fb_data');

        //Header
        $this->load->view('template/header', array(
            'title' => 'GraphScience Test - Index')
            );

        // If the user has to connect
        if( !empty($data['login_url']) ){
            $this->load->view('index/connect', $data);
        }else{
            $data['profile_pic'] = "https://graph.facebook.com/" . $user . "/picture";
            $data['userId'] = $user;
            $this->load->view('index/start',$data);
        }

        //Footer
        $this->load->view('template/footer');


    }
}

/* End of file Index.php */
/* Location: ./application/controllers/Index.php */