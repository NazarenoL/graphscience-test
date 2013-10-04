<?php
/**
 * Pages
 *
 * This controllers allows you to view all your pages and 
 * post something to one of them
 *
 * @author  Nazareno Lorenzo
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

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
     * Index Page for Pages
     *
     * Lists all the pages that the user have connection
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
            'title' => 'Your pages - GraphScience Test')
            );

        //Get the data of all the pages that the user have connection
        $connections =  (object) $this->facebook->api('/me/accounts');
        
        $batchRequest = array();//Placeholder
        $pages = array();
        //Prepare a batch bunch of request to the api to retrieve additional info from each page
        //Also, create an array of pages with his ID as the key
        foreach($connections->data as $page){
            $batchRequest[] = array( 
                'relative_url' => '/' . $page['id'],
                'method' => 'get');
            $pages[$page['id']] = $page;
        }
        //Encode it to be sent as a GET param
        $batchRequest = urlencode(json_encode($batchRequest));

        //Make the batch request
        $batchResponse = $this->facebook->api("/?batch=" . $batchRequest, 'POST');
        
        //Iterate through responses:
        foreach($batchResponse as $response){
            //If it fails, I log it. Ideally, we would retry this
            if(empty($response['code']) || $response['code'] != 200){
                error_log("Failed to retrieve in batch request.");
            }else{
                //Convert to an array the important part of the response
                $response = json_decode($response['body']);
                $pages[$response->id]['talking_about_count'] = $response->talking_about_count ;
                $pages[$response->id]['new_like_count']      = $response->new_like_count ;
                $pages[$response->id]['about']               = $response->about ;
                $pages[$response->id]['link']                = $response->link ;
            }
        }

        //Prepare the data for the main view
        $data['pages'] = $pages;
        
        //Show the list
        $this->load->view('pages/index',$data);

        //Footer
        $this->load->view('template/footer');

    }

}

/* End of file Pages.php */
/* Location: ./application/controllers/Pages.php */