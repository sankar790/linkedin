<?php
try {
    // include the LinkedIn class
    require_once('linkedin_3.2.0.class.php');
    
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
    
    $OBJ_linkedin = new LinkedIn($API_CONFIG);
    $OBJ_linkedin->setTokenAccess($_REQUEST);
    $OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_XML);
    
    $response = $OBJ_linkedin->profile('~:(id,first-name,last-name,picture-url,email-address)');
    if($response['success'] === TRUE) {
        $response['linkedin'] = new SimpleXMLElement($response['linkedin']);
        //echo "<pre>" . print_r($response['linkedin'], TRUE) . "</pre>";
        header('Content-type: application/json');
        $callback = '';
        if (isset($_REQUEST['callback']))
        {
            $callback = filter_var($_REQUEST['callback'], FILTER_SANITIZE_STRING);
        }
        $main = json_encode(xml2array($response['linkedin']));
        echo $callback . '('.$main.');';
    }
}catch(LinkedInException $e) {
  // exception raised by library call
  echo $e->getMessage();
}

function xml2array($xml)
{
    $arr = array();

    foreach ($xml as $element)
    {
        $tag = $element->getName();
        $e = get_object_vars($element);
        if (!empty($e))
        {
            $arr[$tag] = $element instanceof SimpleXMLElement ? xml2array($element) : $e;
        }
        else
        {
            $arr[$tag] = trim($element);
        }
    }

    return $arr;
}
?>