<?php
/**
 * GraphScience Test - NLorenzo
 *
 * A simple Facebook PHP API test by Nazareno Lorenzo
 * for GraphScience selection process
 *
 * @author  Nazareno Lorenzo
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

    /**
     * Codeigniter Required Controller Constructor
     * @return void
     */
    function __construct() {
        parent::__construct();
    }


    /**
     * Index Page for this App
     *
     */
    public function index()
    {

        //Load the facebook app configuration
        $this->config->load('facebook');

        //Load the Facebook Library passing the app configuration
        $this->load->library('facebook/src/facebook', array(
            'appId'  => $this->config->item('fb_appId'),
            'secret' => $this->config->item('fb_secret'))
        );

        //Get the current user
        $user = $this->facebook->getUser();

        //If its loged-in
        if ($user) {
            try {
                //Try to get info, to check if the access token is valid
                $data['user_profile'] = $this->facebook->api('/me');
            } catch (FacebookApiException $e) {
                //The user isn't loged
                $user = null;
            }
        }

        if ($user) {
            $data['logout_url'] = $this->facebook->getLogoutUrl();
        } else {
            $data['login_url'] = $this->facebook->getLoginUrl();
        }


        $this->load->view('template/header', array(
            'title' => 'GraphScience Test - Index')
            );


        // If the user has to connect
        if(!empty($data['login_url'])){
            $this->load->view('connect', $data);
        }else{
            var_dump($data);
        }

        $this->load->view('template/footer');


    }
}

/* End of file Index.php */
/* Location: ./application/controllers/Index.php */