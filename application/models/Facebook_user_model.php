<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Facebook user Model
 * 
 * It manages the user authentication from facebook connect
 * @package default
 */
class Facebook_user_model extends CI_Model {

    /**
     * User
     * 
     * Stores the connected user Id
     * @see https://developers.facebook.com/docs/reference/api/user/
     */
    private $user;

    public function __construct(){
            parent::__construct();

            //If the user its trying to log out, delete the data
            if( isset($_GET['logout']) ){
                $this->facebook->destroySession();
            }

            //Get the current user
            $this->user = $this->facebook->getUser();

            //If its connected-in
            if ($this->user) {
                try {
                    //Try to get info, to check if the access token is valid
                    $data['user_profile'] = (object) $this->facebook->api('/me?fields=id,name,link,email,first_name');
                } catch (FacebookApiException $e) {
                    //The user isn't connected
                    $this->user = null;
                }
            }

            //Generate the login or logout url, depending on the user state
            if ($this->user) {
                //Logout url
                $data['logout_url'] = $this->facebook->getLogoutUrl( array(
                    'next' => site_url('?logout=true'))
                    );
            } else {
                //Login url
                $data['login_url'] = $this->facebook->getLoginUrl( array(
                    'scope' => $this->config->item('fb_scope'),
                    'redirect_uri'  => site_url(''))
                    );
            }

            $this->session->set_userdata('fb_data', $data);
    }
    public function getUser() {
        return $this->user;
    }




}
        
?>
