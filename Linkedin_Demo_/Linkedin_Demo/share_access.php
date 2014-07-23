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
?>
    <form id="linkedin_share_form" action="" method="post">
        <input type="hidden" name="lType" id="lType" value="share" />
        <div style="font-weight: bold;">Comment:</div>
        <textarea name="scomment" id="scomment" rows="4" style="display: block; width: 400px;"></textarea>
        
        <div style="font-weight: bold;">Title:</div>            
        <input type="text" name="stitle" id="stitle" length="255" maxlength="255" style="display: block; width: 400px;" value="" />
        
        <div style="font-weight: bold;">Content Url:</div>            
        <input type="text" name="surl" id="surl" length="255" maxlength="255" style="display: block; width: 400px;" value="" />
        
        <div style="font-weight: bold;">Content Picture Url:</div>            
        <input type="text" name="simgurl" id="simgurl" length="255" maxlength="255" style="display: block; width: 400px;" value="" />
        
        <div style="font-weight: bold;">Description:</div>
        <textarea name="sdescription" id="sdescription" rows="4" style="display: block; width: 400px;"></textarea>
        
        <input type="submit" value="Post Content" /><input type="checkbox" value="1" name="sprivate" id="sprivate" checked="checked" /><label for="sprivate">share with your connections only</label>
    </form>
<?php
if(isset($_POST['lType']) && $_POST['lType'] == "share"){
    $content = array();
      if(!empty($_POST['scomment'])) {
        $content['comment'] = $_POST['scomment'];
      }
      if(!empty($_POST['stitle'])) {
        $content['title'] = $_POST['stitle'];
      }
      if(!empty($_POST['surl'])) {
        $content['submitted-url'] = $_POST['surl'];
      }
      if(!empty($_POST['simgurl'])) {
        $content['submitted-image-url'] = $_POST['simgurl'];
      }
      if(!empty($_POST['sdescription'])) {
        $content['description'] = $_POST['sdescription'];
      }
      if(!empty($_POST['sprivate'])) {
        $private = TRUE;
      } else {
        $private = FALSE;
      }
      
      // share content
      $response = $OBJ_linkedin->share('new', $content, $private);
}
}catch(LinkedInException $e) {
  // exception raised by library call
  echo $e->getMessage();
}
?>