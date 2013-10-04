<?php
/**
 * Friends
 *
 * This controllers allows you to view all your friends and 
 * post something to one of them
 *
 * @author  Nazareno Lorenzo
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends extends CI_Controller {

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

        /*
        * This section its only for loged users, so, if no user its connected
        * redirect them to index
        */
        $user = $this->Facebook_user_model->getUser();
        if(empty($user)){
            header("Location: " . site_url());
            die();
        }
    }


    /**
     * Index Page for Friends
     *
     * Lists all your friends
     * 
     * @return void
     * @see https://developers.facebook.com/docs/reference/api/user/
     */
    public function index()
    {
        //Get the user id and data (if its connected)
        $user = $this->Facebook_user_model->getUser();
        $data = $this->session->userdata('fb_data');

        //Header
        $this->load->view('template/header', array(
            'title' => 'Your friends - GraphScience Test',
            'datatable' => true)
            );

        //Get the data of all your friends
        $friends =  (object) $this->facebook->api('/me/friends');

        //Prepare the data for the main view
        $data['friends'] = $friends;
        
        //Show the list
        $this->load->view('friends/index',$data);

        //Footer
        $this->load->view('template/footer', array('datatable'=>true) );

    }

}

/* End of file Friends.php */
/* Location: ./application/controllers/Friends.php */