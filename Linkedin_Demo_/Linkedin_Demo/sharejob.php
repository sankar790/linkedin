<?php

header('Access-Control-Allow-Origin: *');

try {
    // include the LinkedIn class
    require_once('linkedin_3.2.0.class.php');
    
    if(!session_start()) {
      throw new LinkedInException('This script requires session support, which appears to be disabled according to session_start().');
    }
    
    // display constants
    $API_CONFIG = array(
      'appKey'       => '7j4ona8m27gd',
      'appSecret'    => 'wXUnjIBNDZJXw7oD',
      'callbackUrl'  => NULL 
    );
    define('DEMO_GROUP', '4010474');
    define('DEMO_GROUP_NAME', 'Simple LI Demo');
    define('PORT_HTTP', '80');
    define('PORT_HTTP_SSL', '443');
  
    // set index
    $_REQUEST['lType'] = (isset($_REQUEST['lType'])) ? $_REQUEST['lType'] : '';
    switch($_REQUEST['lType']) {
        case 'initiate':
            $protocol = 'http';
            // set the callback url
            $API_CONFIG['callbackUrl'] = $protocol . '://' . $_SERVER['SERVER_NAME'] . ((($_SERVER['SERVER_PORT'] != PORT_HTTP) || ($_SERVER['SERVER_PORT'] != PORT_HTTP_SSL)) ? ':' . $_SERVER['SERVER_PORT'] : '') . $_SERVER['PHP_SELF'] . '?' . LINKEDIN::_GET_TYPE . '=initiate&' . LINKEDIN::_GET_RESPONSE . '=1';
            $OBJ_linkedin = new LinkedIn($API_CONFIG);
          
            // check for response from LinkedIn
            $_GET['lResponse'] = (isset($_GET['lResponse'])) ? $_GET['lResponse'] : '';
            if(!$_GET['lResponse']) {
                // LinkedIn hasn't sent us a response, the user is initiating the connection
                // send a request for a LinkedIn access token
                $response = $OBJ_linkedin->retrieveTokenRequest();
                if($response['success'] === TRUE) {
                    // store the request token
                    $_SESSION['oauth']['linkedin']['request'] = $response['linkedin'];
                    #echo LINKEDIN::_URL_AUTH . $response['linkedin']['oauth_token'];exit;
                    header('Location: ' . LINKEDIN::_URL_AUTH . $response['linkedin']['oauth_token']);
                } else {
                    // bad token request
                    echo "Request token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
                }
            }else{
                $response = $OBJ_linkedin->retrieveTokenAccess($_SESSION['oauth']['linkedin']['request']['oauth_token'], $_SESSION['oauth']['linkedin']['request']['oauth_token_secret'], $_GET['oauth_verifier']);
                if($response['success'] === TRUE) {
                $access = $response['linkedin'];
                header('Location: http://192.168.1.12/Linkedin_Demo/share_access.php?oauth_token='.$access['oauth_token'].'&oauth_token_secret='.$access['oauth_token_secret'].'&oauth_expires_in'.$access['oauth_expires_in'].'&oauth_authorization_expires_in='.$access['oauth_authorization_expires_in']);
                }
            }
        break;
    }
} catch(LinkedInException $e) {
  // exception raised by library call
  echo $e->getMessage();
}
?>