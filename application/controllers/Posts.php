<?php
/**
 * Posts
 *
 * This controllers allows you to post to a page, 
 * friend or own timeline
 *
 * @author  Nazareno Lorenzo
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller {

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
     * Creates a new post in the timeline of a page or person
     * 
     * @param string $type marca el tipo de target (person, page)
     * @param integer $targetId 
     * @return void
     */
    public function newPost($type,$targetId){
        //Get the user id and data (if its connected)  
        $user = $this->Facebook_user_model->getUser();
        $data = $this->session->userdata('fb_data');

        $target = new stdClass();
        //Set the target id
        $target->id = $targetId;
        //set the target type
        if($type=="person"){
            $target->type="person";
        }else{
            $target->type="page";
        }

        //Set the target name
        $target->info =  (object) $this->facebook->api('/' . $target->id);
        $target->name = $target->info->name;

        //Header
        $configHeader['title'] = 'Creating New post to ' . $target->name . ' - GraphScience Test';
        if($target->type == "page"){
            $configHeader['multiselect'] = 'true';
            $configHeader['schedule']    = 'true';
        }
        $this->load->view('template/header', $configHeader);


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

            //Analyze the post data and prepare the request to the FBAPI in $post
            $post['message'] = $postData['message'];

            if( !empty($postData['link']) && $postData['link'] != "http://"){
                $post['link'] = $postData['link'];
            }

            //if the target its a page, it could include more fields
            if($type == "page"){
                $postData['targeting']['countries'] = $this->input->post('targeting');
                $postData['published'] = $this->input->post('published');
                $postData['schedule'] = $this->input->post('schedule');
            
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
                    if($page['id'] == $target->id){
                        $post['access_token'] = $page['access_token'];
                        break;
                    }
                }
            }

            //Upload it to the 
            try {
                //Upload it to facebook
                $this->facebook->api('/' . $target->id . '/feed/', 'post', $post);
                //If nothing went wrong, show a success message
                $this->load->view('posts/success');
            } catch (FacebookApiException $e) {
                //Something went wrong, show the user an error and log it
                $this->load->view('posts/error', array('error'=>$e) );
                error_log($e);
            }

        }else{
            //Prepare all the data to be sent to the view
            if($type == "page"){
                $viewData['page'] = $target->info;
            }
            $viewData['target'] = $target;
            
            $this->load->view('posts/form',$viewData);
        }

        
        /*
        * Pages includes some extra fields that need more configuration:
        * ---
        * Allowed dates for the scheduled post
        * They must be greater than 10 minutes from now and less than 6 months
        */
        if($type == "page"){
            //Actual Date:
            $actualDate=time();
            //Calculate the timestamps
            $startDate = $actualDate + 10*60; //Current timestamp plus 10 minutes
            $endDate = $actualDate + 6*30*24*60*60;// Current timestamp plus 6 months
            //Convert them
            $startDate = date("Y-m-d H:i",$startDate); 
            $endDate = date("Y-m-d H:i",$endDate);  
            $configFooter = array(
            'multiselect'  => 'true',
            'schedule'     => 'true',
            'startDate'    => $startDate,
            'endDate'      => $endDate);
        }else{
            $configFooter = array();
        }


        //Footer
        $this->load->view('template/footer', $configFooter);
    }

}

/* End of file Posts.php */
/* Location: ./application/controllers/Posts.php */