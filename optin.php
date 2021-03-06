<?php

global $wpdb;

if ($_GET['subscribed'] == "true")
{
	require "templates/confirm_subscription.html";
	exit;
}

function error($error)
{

	?>

<div style="font-family: Arial">
  <h2 align="center">An Error Has Occured</h2>
  <div align="center">
    <div style="width: 400px; padding: 10px; text-align: left; background-color: #336699; color: #fff; font-weight:bold; font-family: Arial; border: 1px solid #ccc;"> <?php echo $error ?> </div>
    <a href="javascript:window.history.go(-1);">Click Here To Go Back</a> </div>
</div>
<?php
	wp_credits();
	exit;

}



/*

 * Used to validate an email address

 */

$success = (boolean) (isset($_POST['newsletter']) && isset($_POST['name']) && isset($_POST['email']));

if ($success)
{
	$name = wpr_sanitize($_POST['name']);
	$email = strtolower(wpr_sanitize($_POST['email']));
	$followup = wpr_sanitize($_POST['followup']);
	$newsletter = (int) wpr_sanitize($_POST['newsletter']);
	$bsubscription = wpr_sanitize($_POST['blogsubscription']);
	$responder = (int) wpr_sanitize($_POST['responder']);
	$bcategory = (int) wpr_sanitize($_POST['cat']);
	$return_url = wpr_sanitize($_POST['return_url']);
	$commentfield = $_POST['comment'];
	
	
	
	if (!empty($commentfield))
	{
		//stupid spambot spamming my subscription forms. damn the bot!
		exit;
	}
	
	do_action("_wpr_subscriptionform_prevalidate");
	
	
	$skiplist = array("name","email","followup","blogsubscription","cat","return_url","responder");
	
	$query = "SELECT count(*) count FROM ".$wpdb->prefix."wpr_newsletters where id=$newsletter";	
	$results = $wpdb->get_results($query);	
	$count = $results[0]->count;	
	if ($count == 0)
	{  
	     error("The newsletter to which you are trying to subscribe doesn't exist in our records.");
	}

	$fid = (int) $_POST['fid'];
	if (!empty($followup) && !in_array($followup,array("autoresponder","postseries")))
	{
		  error('The form you filled out is coded improperly. The followup subscription hidden fields did not have a valid value.');
		  exit;
	}

        if (empty($name))

            {

            error('You have not filled the name field in the subscription form. Please <a href="javascript:window.history.go(-1);">go back</a> and enter your name in the name field.');

        }

	//start validations

	if (!validateEmail($email))   //the expression is just for now.

	{

		error('<center><div style="font-size: 20px;">Invalid Email Address</div></center> The e-mail address you mentioned is not a valid e-mail address. Please <a href="javascript:window.history.go(-1);">go back</a> and re-enter the e-mail in the correct format.');

	}
        
        $errors = array();
        
        $errors = apply_filters("_wpr_subscriptionform_validate",$errors);
        
        if (count($errors) !=0)
        {
            $errorString = implode("<li>",$errors);
            error("<ol>$errorString</ol>");
        }


	if (!empty($followup) && !empty($responder))

	{

		switch ($followup)

		{

			case 'postseries':

			$query = "SELECT COUNT(*) count FROM ".$wpdb->prefix."wpr_blog_series where id=".$responder;

			$items = $wpdb->get_results($query);

			$count = $items[0]->count;

			if ($count == 0)

			{

				error("There was a problem while processing your subscription request. The postseries you have subscribed to doesn't exist in our records. The post series you have subscribed to doesn't exist and/or may have been deleted by the site administrator. The site owner has been notified of the problem.");

				wpr_error("$name ($email) tried to subscribe to a non-existent postseries (of id $responder) that doesn't exist from ".$_SERVER['HTTP_REFERER']);

				

			}

			break;

			case 'autoresponder':
			$query = "SELECT COUNT(*) count FROM ".$wpdb->prefix."wpr_autoresponders where id=".$responder;
			$items = $wpdb->get_results($query);
			$count = $items[0]->count;
			if ($count == 0)

			{

				error("There was a problem while processing your subscription. The follow-up series you have subscribed to doesn't exist in our records. The site administrator has been notified of the problem.");

				wpr_error("$name ($email) tried to subscribe to autoresponder ( of id$responder) that doesn't exist from ".$_SERVER['HTTP_REFERER']);

			}

			break;
			default:
			print "Error! The form is badly formed. ";
			exit;
		}

	}

	

	if ($bsubscription == "cat")

	{

		$category = get_category($bcategory);

		if (empty($category))

		{
			error("The was a problem when processing your subscription. The content to which you are trying to subscribe doesn't exist. It may have been deleted by the site administrator. The site administrator has been notified of the problem.");

			wpr_error("$name ($email) tried to subscribe to a blog category ($bcategory) that doesn't exist from ".$_SERVER['REQUEST_URI']);

		}

	}

	$newsletter = _wpr_newsletter_get($newsletter);			

	

	$nid = $newsletter->id;

	//the hash...

    //gnerate a small string
	/*
	
	between 48 and 57
	between 97 and 123 
	and between 65 and 90
	*/
	for ($i=0;$i<6;$i++)
	{
		$a[] = rand(65,90);
		$a[] = rand(97,123);
		$a[] = rand(48,57);
		
		$whichone = rand(0,2);
		$currentCharacter = chr($a[$whichone]);
		
		$hash .= $currentCharacter;
		unset($a);
		
	}
     $hash .= time();
	//insert into subscribers list


	$query = "SELECT * FROM ".$wpdb->prefix."wpr_subscribers where email='$email' and nid='$nid';";
	$subscribeList = $wpdb->get_results($query);
	
	
	$zone = date_default_timezone_get();
	date_default_timezone_set("UTC");
	if (count($subscribeList) ==0)  //the visitor is a new subscriber

	{
		//new subscriber, add him to records
		
		$date = time();
		$query = "INSERT INTO ".$wpdb->prefix."wpr_subscribers (nid,name,email,date,active,fid,hash) values ('$nid','$name','$email','$date',1,'$fid','$hash');";
		$wpdb->query($query);
		//now get the subscriber object 
		$query = "SELECT * FROM ".$wpdb->prefix."wpr_subscribers where email='$email' and nid='$nid';";
		$subscriber = $wpdb->get_results($query);
		$subscriber = $subscriber[0];
	}
	else   //the subscriber already exists
	{
		 //find if the subscriber had already subscribed before and is still subscribed.
		 $query = "select * from ".$wpdb->prefix."wpr_subscribers where active=1 and confirmed=1 and email='$email' and nid='$nid'; ";
		 $results = $wpdb->get_results($query);

		 if (count($results) >0)
		 {
 			 error("You are already subscribed to this newsletter.");
		 }
		 else
		 {
     		 $subscriber = $subscribeList[0];		 
			 $date = time();
			 $query = "update ".$wpdb->prefix."wpr_subscribers set active=1, confirmed=0, date='$date' where email='$email' and nid='$nid';";
   			 $wpdb->get_results($query);
		 }
	}
	$id = intval($subscriber->id);
	
	
	//insert the subscriber's custom field values
	foreach ($_POST as $field_name=>$value)
	{
		if (ereg('cus_.*',$field_name))
		{

			$name = base64_decode(str_replace("cus_","",$field_name));

			$query = "select * from ".$wpdb->prefix."wpr_custom_fields where name='$name' and nid='$nid'";

			$custom_fields = $wpdb->get_results($query);

			$custom_fields = $custom_fields[0];

			$cid = $custom_fields->id;

			$value = $_POST[$field_name];

			$query = "DELETE FROM ".$wpdb->prefix."wpr_custom_fields_values WHERE nid=$nid and sid=$id and cid=$cid";			

			$wpdb->query($query);

			$query = "INSERT INTO ".$wpdb->prefix."wpr_custom_fields_values (nid,sid,cid,value)  values ('$nid','$id','$cid','$value');";

			$wpdb->query($query);

		}

		//sparing the inserted values, insert null for the rest of the custom fields defined for this newslette

					 

	}

	

	//what custom fields already exist? so that we dont try to insert duplicate values for those

	$query = "SELECT b.name name from ".$wpdb->prefix."wpr_custom_fields_values a, ".$wpdb->prefix."wpr_custom_fields b where a.sid=$id and b.id=a.cid;";

	$fields = $wpdb->get_results($query);

	if (count ($fields) > 0)

	{

		foreach ($fields as $field)

		{

			$existing[] = $field->name;

		}

	}

	if (count($existing) != 0)

		$notin = implode("','",$existing);

	else

		$notin ="";



	$notin = "IN('".$notin."')";

	$query = "SELECT * FROM ".$wpdb->prefix."wpr_custom_fields WHERE `name` NOT $notin AND nid='$nid'";



	$otherfields = $wpdb->get_results($query);

	foreach ($otherfields as $field)

	{

		$cid  = $field->id;

		$query = "INSERT INTO ".$wpdb->prefix."wpr_custom_fields_values (nid,sid,cid,value) VALUES ('$nid','$id','$cid','');";

		$wpdb->query($query);

	}

	

	if ($followup)

	{

		$query = "SELECT a.* FROM ".$wpdb->prefix."wpr_followup_subscriptions a, ".$wpdb->prefix."wpr_subscribers b where a.sid=b.id and a.type='$type' and a.eid='$responder' and b.id='$id'";

		$subscriptions = $wpdb->get_results($query);

		//subscribe to autoresponder only if they aren't or ever haven't subscribed to this newsletter

		if (count($subscriptions) == 0)

		{

			$date = time();

			$query = "DELETE FROM ".$wpdb->prefix."wpr_followup_subscriptions where sid=$id and type='$followup' and eid='$responder';";

			$wpdb->query($query);

			

			$query = "INSERT INTO ".$wpdb->prefix."wpr_followup_subscriptions (sid,type,eid,sequence,doc) values ('$id','$followup','$responder',-1,$date);";

			$wpdb->query($query);

		}

	}

	//if blog subscription is mentioned in the form
	if (!empty($bsubscription))
	{
		$suffix = ($bsubscription == "cat")?" and a.catid='$bcategory'":"";

		$query = "SELECT * FROM ".$wpdb->prefix."wpr_blog_subscription a,".$wpdb->prefix."wpr_subscribers b where a.sid=b.id and b.id=$id and a.type='$bsubscription' $suffix ;";

		$blogSubscriptions = $wpdb->get_results($query);

		//subscribe to blog or blog category only if they are not already subscribed.
		if (count($blogSubscriptions) == 0)
		{	

			$query = "INSERT INTO ".$wpdb->prefix."wpr_blog_subscription (sid,type,catid) values ('$id','$bsubscription','$bcategory');";

			$wpdb->query($query);

		}

	}

	

	if (!empty($fid) && $fid != 0)

	{

		$query = "SELECT * from ".$wpdb->prefix."wpr_subscription_form where id='$fid'";

		$theForm = $wpdb->get_results($query);

		

		if (count($theForm) != 0)

		{

			$theForm = $theForm[0];

			$confirm_subject = $theForm->confirm_subject;

			$confirm_body = $theForm->confirm_body;

		}		

	}
	
	
	do_action("_wpr_subscriber_added",$id);

	$theqstring = $subscriber->id."%%".$subscriber->hash."%%".$fid;

	$p = trim(base64_encode($theqstring),"=");

	$link = get_bloginfo("siteurl")."/?wpr-confirm=".$p;
	
	$dirname = str_replace("optin.php","",__FILE__);
	$confirm = file_get_contents($dirname."/templates/confirm.txt");
	$confirm = str_replace("[!confirm_link!]",$link,$confirm);

	$newsletter = _wpr_newsletter_get($nid);

	$newslettername = $newsletter->name;

	$url = ($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"Unknown";

	$ip = $_SERVER['REMOTE_ADDR'];

	$date = "At ".date("g:i dS M, Y");



	$address = get_option('wpr_address');

	$confirm_subject = str_replace("[!ipaddress!]",$ip,$confirm_subject);
	$confirm_body = str_replace("[!ipaddress!]",$ip,$confirm_body);
	$confirm_subject = str_replace("[!date!]",$date,$confirm_subject);
	$confirm_body = str_replace("[!date!]",$date,$confirm_body);

	$confirm_subject = str_replace("[!url!]",$url,$confirm_subject);

	$confirm_body = str_replace("[!url!]",$url,$confirm_body);

	

	$confirm_subject = str_replace("[!newslettername!]",$newslettername,$confirm_subject);
	$confirm_body = str_replace("[!newslettername!]",$newslettername,$confirm_body);


	$confirm_subject = str_replace("[!address!]",$address,$confirm_subject);
	$confirm_body = str_replace("[!address!]",$address,$confirm_body);
	$confirm_body = str_replace("[!confirm!]",$confirm,$confirm_body);
	$additional_parameters = array(
								    	"ipaddress" => $_SERVER['REMOTE_ADDR'],
										"date"     => date("g:i d F Y",time()),
										"url"      => $_SERVER['HTTP_REFERER']
								   );

	$params = array();
	
	date_default_timezone_set($zone);
	
	$params[0] = $confirm_subject;
	$params[1] = $confirm_body;
	wpr_create_temporary_tables($nid);	
	wpr_make_subscriber_temptable($nid);
	wpr_place_tags($id,$params,$additional_parameters);
	$from_email = $newsletter->fromemail;
	
	if (!$from_email)	
		$from_email = get_bloginfo("admin_email");	

	$from_name = $newsletter->fromname;
	
	if (!$from_name)
		$from_name = get_bloginfo("name");

	$subject = $params[0];
	$body = $params[1];
	
	$verificationEmail = array(
							   		'to'=>$email,
									'subject'=>$subject,
									'textbody'=>$body,
									'fromname'=>$from_name,
									'from'=>$from_email
								);
	//try {
		//ob_start();
    	@dispatchEmail($verificationEmail);
//		ob_get_clean();
	//}
//	catch (Exception $exc)
	{
		//STFU!
		
	}
	 
	if (empty($return_url))
	{
		if (isset($theForm))
		   $return_url = $theForm->return_url;
	}

	if (!empty($return_url))
	{ 
        ?>
<script>
		window.location='<?php echo $return_url; ?>';
		</script>
<?php
		exit;
	}
	else
	{
        ?>
<script>
		window.location='<?php echo get_bloginfo("home")."/?wpr-optin=2" ?>';
		</script>
<?php
		exit;
	}
	exit;

}

else

{

	if (!isset($_POST['newsletter']))
	{
		?>
<div align="center" style="font-family:Georgia, 'Times New Roman', Times, serif; font-size:24px; width:600; margin-left:auto; margin-right:auto">
  <h2>Invalid Request</h2>
  This page should not be visited. Please use a subscription form to subscribe to a newsletter.</div>
<?php
	}

}
exit;
