<?php
/**
 * Pages
 *
 * This controllers allows you to view all your pages, 
 * post something to one or more of them, etc
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

    /**
     * Creates a new post in the timeline of a page
     * 
     * @param integer $pageId 
     * @return void
     */
    public function newPost($pageId){
        //Get the user id and data (if its connected)  
        $user = $this->Facebook_user_model->getUser();
        $data = $this->session->userdata('fb_data');

        //Get the page info
        $page =  (object) $this->facebook->api('/' . $pageId);

        //Header
        $this->load->view('template/header', array(
            'title'       => 'Creating New post to ' . $page->name . ' - GraphScience Test',
            'multiselect' => 'true',
            'schedule'     => 'true')
            );


        //If the user sent the form
        $postData['message'] = $this->input->post('message');
        if( !empty($postData['message']) ){
            //The user sent the post

            /* * * * 
            * IMPORTANT / TO DO:
            * ---
            * Its really important to validate the user input,
            * even if you trust them.
            * In this case, I won't do the validation, because this is just
            * a demostration of the Facebook Api Use.
            */
            
            //Get the info
            $postData['link'] = $this->input->post('link');
            $postData['targeting']['countries'] = $this->input->post('targeting');
            $postData['published'] = $this->input->post('published');
            $postData['schedule'] = $this->input->post('schedule');

            //Analyze the post data and prepare the request to the FBAPI in $post
            $post['message'] = $postData['message'];

            if( !empty($postData['link']) && $postData['link'] != "http://"){
                $post['link'] = $postData['link'];
            }

            if( !empty($postData['targeting']) ) {
                $post['targeting'] = json_encode($postData['targeting']);
            }

            if( !empty($postData['published']) ){
                $post['published'] = 0;
            }
            if( !empty($postData['schedule']) ){
                $post['scheduled_publish_time'] = strtotime($postData['schedule']);
                $post['published'] = 0;
            }

            /* * 
            * Re-validate that this user can edit this page, 
            * and retrieve the page access_token
            */
            $accounts = $this->facebook->api('/me/accounts');
            foreach($accounts['data'] as $page){
                //Check if this is the page that im editing
                if($page['id'] == $pageId){
                    $post['access_token'] = $page['access_token'];
                    break;
                }
            }

        var_dump($post);
            //Upload it to the page
            try {
                //Upload it to facebook
                $this->facebook->api('/' . $pageId . '/feed/', 'post', $post);
                //If nothing went wrong, show a success message
                $this->load->view('pages/success');
            } catch (FacebookApiException $e) {
                //Something went wrong, show the user an error and log it
                $this->load->view('pages/error', array('error'=>$e) );
                error_log($e);
            }

        }else{
            //Prepare all the data to be sent to the view
            $pageData['page'] = $page;
            $this->load->view('pages/form',$pageData);
        }

        /*
        * Allowed dates for the scheduled post
        * They must be greater than 10 minutes from now and less than 6 months
        */
        //Actual Date:
        $actualDate=time();
        //Calculate the timestamps
        $startDate = $actualDate + 10*60; //Current timestamp plus 10 minutes
        $endDate = $actualDate + 6*30*24*60*60;// Current timestamp plus 6 months
        //Convert them
        $startDate = date("Y-m-d H:i",$startDate); 
        $endDate = date("Y-m-d H:i",$endDate);  


        //Footer
        $this->load->view('template/footer', array(
            'multiselect'  => 'true',
            'schedule'     => 'true',
            'startDate'    => $startDate,
            'endDate'      => $endDate,)
        );
    }

}

/* End of file Pages.php */
/* Location: ./application/controllers/Pages.php */