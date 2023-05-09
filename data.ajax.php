<?php
session_start();
require_once 'dbconfig.php';

function generateGUID()
{
	 if (function_exists('com_create_guid'))
	 {
        return com_create_guid();
    }
    else 
	 {
        mt_srand((double)microtime()*10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;
	}
}


$action = $_REQUEST['action'];
switch ($action) 
{
	
	case "accept_terms":
	
	
	$stmt = $db->prepare("SELECT * FROM users WHERE email_address = :email_address AND state='1'");
	$stmt->bindValue(':email_address', $_REQUEST['email_address'], PDO::PARAM_STR);
	$stmt->execute();
	if ($stmt->rowCount() == 0)
	{
		$payload = array('validated_account' => false);
		echo json_encode($payload);
	}
	else 
	{
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['user'] = $user;
		$user_id = $user['id'];
		$hash = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
		$stmt = $db->prepare("UPDATE users SET terms_accepted = :terms_accepted, state='2' WHERE email_address = :email_address");
		$stmt->bindValue(':terms_accepted', time(), PDO::PARAM_STR);
		$stmt->bindValue(':email_address', $_REQUEST['email_address'], PDO::PARAM_STR);
		$stmt->execute();
		
		// Udate user in session
		$stmt = $db->prepare("SELECT * FROM users WHERE id = :user_id");
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['user'] = $user;

		// Check subdomain and see if available
		$_REQUEST['subdomain'] = "ibex_" . time();
		
		$stmt = $db->prepare("SELECT id FROM accounts WHERE subdomain = :subdomain");
		$stmt->bindValue(':subdomain', $_REQUEST['subdomain'], PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 0)
		{
			$subdomain = $_REQUEST['subdomain'];
		}
		else 
		{
			$subdomain = $_REQUEST['subdomain'] . "-" . time();
		}
		
		
		// Create account
		$_REQUEST['company_name'] = "My Company";
		$stmt = $db->prepare("INSERT INTO accounts(name,subdomain,administrator_id) VALUES (?,?,?)");
		$stmt->bindParam(1, $_REQUEST['company_name']);
		$stmt->bindParam(2, $subdomain);
		$stmt->bindParam(3, $user_id);
		$stmt->execute();
		$account_id = $db->lastInsertId();
		
		// Link user to account
		$type = "1";
		$stmt = $db->prepare("INSERT INTO user_account_links(user_id,account_id,type) VALUES (?,?,?)");
		$stmt->bindParam(1, $user_id);
		$stmt->bindParam(2, $account_id);
		$stmt->bindParam(3, $type);
		$stmt->execute();
		
		
		// Create subdomain with Cloudflare
		$data = '{"type":"A","name":"' . $subdomain . '","content":"167.99.194.5","ttl":120, "proxied": true}';
					
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,            "https://api.cloudflare.com/client/v4/zones/8b1b16994c781e5f93b25763349b85ae/dns_records");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST,           1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,     $data); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Email: bishopssltd@gmail.com','X-Auth-Key: f09f61ed6bdcfb15865e1d18170676ad293c7', 'Content-Type: application/json'));
		
		$result = curl_exec ($ch);
		$json_result = json_decode($result, true);
		curl_close($ch);

		$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
		$stmt->bindValue(':id', $user['id'], PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['user'] = $user;
		
		// Now setup Gantt demo
		
	 	$sharing_identififer = generateGUID();
		
		$name = "Ibex";
		$parent_id = 0;
		$time = time();
		
		$stmt = $db->prepare("INSERT INTO gantt_programmes(account_id,name,parent_id,sharing_identifier,created) VALUES (?,?,?,?,?)");
		$stmt->bindParam(1, $account_id);
		$stmt->bindParam(2, $name);
		$stmt->bindParam(3, $parent_id);
		$stmt->bindParam(4, $sharing_identififer);
		$stmt->bindParam(5, $time);
		$stmt->execute();
		$programme_id = $db->lastInsertId();
		
		// Columns
		$columns_json = '[{"status": true, "wbs":false,"text":true,"start_date":true,"end_date":false,"duration_worked":false,"progress":false,"baseline_start":false,"constraint_date":false,"baseline_end":false,"constraint_type":false,"deadline":false,"task_calendar":false,"resource_id":false}]';
		
		$columns_resources_json = '[{"name":true,"resource_calendar":false,"company":true,"notes":false,"cost_rate":true}]';
		
		// Columns
		$user_id = $_SESSION['user']['id'];
		$stmt = $db->prepare("INSERT INTO gantt_columns(programme_id,user_id,task_columns,resource_columns) VALUES (?,?,?,?)");
		$stmt->bindParam(1, $programme_id);
		$stmt->bindParam(2, $user_id);
		$stmt->bindParam(3, $columns_json);
		$stmt->bindParam(4, $columns_resources_json);
		$stmt->execute();
		
		
		// Settings
		$group_name = "Administrators";
		$admin_group = "1";
		$stmt = $db->prepare("INSERT INTO gantt_user_groups(programme_id,name,is_admin_group) VALUES (?,?,?)");
		$stmt->bindParam(1, $programme_id);
		$stmt->bindParam(2, $group_name);
		$stmt->bindParam(3, $admin_group);
		$stmt->execute();
		$user_group_id = $db->lastInsertId();
		
		
			// Settings
			$group_id_insert = '{"' . $user_group_id . '": true}';
			
			
			$permission_sets = '{"group_' . $user_group_id . '_set_1":true,"group_' . $user_group_id . '_set_2":true,"group_' . $user_group_id . '_set_3":true,"group_' . $user_group_id . '_set_4":true,"group_' . $user_group_id . '_set_5":true,"group_' . $user_group_id . '_set_6":true}';
			
			
		$stmt = $db->prepare("INSERT INTO gantt_settings(programme_id,duration_unit,time_unit,default_permission_groups,default_permission_sets) VALUES (?,?,?,?,?)");
		$stmt->bindParam(1, $programme_id);
		$stmt->bindParam(2, $_REQUEST['duration_unit']);
		$stmt->bindParam(3, $_REQUEST['time_unit']);
		$stmt->bindParam(4, $group_id_insert);	
		$stmt->bindParam(5, $permission_sets);
		$stmt->execute();
		
		
		// Link user to group
		$stmt = $db->prepare("INSERT INTO gantt_user_groups_links(user_id,user_group_id) VALUES (?,?)");
		$stmt->bindParam(1, $user_id);
		$stmt->bindParam(2, $user_group_id);
		$stmt->execute();
		
		// Set up first two messages about verifying email address and profile
		$sender_id = "0";
		$unread = "1";
		$created = time();
		$text = "Welcome to Ibex! Thanks for joining us.<br>Please verify your email address by following the prompts on the email that we have sent to you.<br><br>Please ensure that you complete your profile by clicking on the button below.<br><br><a data-toggle='modal' data-target='#modal_edit_profile'><button>Complete your profile</button></a><br><br>";
		$stmt = $db->prepare("INSERT INTO gantt_messages(programme_id,sender_id,recipient_id,created,unread,`text`) VALUES (?,?,?,?,?,?)");
		$stmt->bindParam(1, $programme_id);
		$stmt->bindParam(2, $sender_id);
		$stmt->bindParam(3, $user_id);
		$stmt->bindParam(4, $created);
			$stmt->bindParam(5, $unread);
				$stmt->bindParam(6, $text);
				$stmt->execute();
				
				/*
		$sender_id = "0";
		$unread = "1";
		$created = time();
		$text = "";
		$stmt = $db->prepare("INSERT INTO gantt_messages(programme_id,sender_id,recipient_id,created,unread,`text`) VALUES (?,?,?,?,?,?)");
		$stmt->bindParam(1, $programme_id);
		$stmt->bindParam(2, $sender_id);
		$stmt->bindParam(3, $user_id);
		$stmt->bindParam(4, $created);
			$stmt->bindParam(5, $unread);
				$stmt->bindParam(6, $text);
		$stmt->execute();
		*/
		
		
		
		
		
		
		
		$working_days = '{"working_day_monday":1,"working_day_tuesday":1,"working_day_wednesday":1,"working_day_thursday":1,"working_day_friday":1,"working_day_saturday":0,"working_day_sunday":0}';
		
		// Working days
		$working_days = json_decode($working_days, true);
		$calendar_name = "Default task calendar";
		$is_default_task_calendar = "1";
		
		$start_time = "07:00";
		$end_time = "17:00";
		$start_hour = "7";
		$start_minute = "0";
		$end_hour = "17";
		$end_minute = "0";
		$stmt = $db->prepare("INSERT INTO gantt_calendars(programme_id,name,is_default_task_calendar,start_time,end_time,start_hour,start_minute,end_hour,end_minute,working_day_monday,working_day_tuesday,working_day_wednesday,working_day_thursday,working_day_friday,working_day_saturday,working_day_sunday) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->bindParam(1, $programme_id);
		$stmt->bindParam(2, $calendar_name);
		$stmt->bindParam(3, $is_default_task_calendar);
		$stmt->bindParam(4, $start_time);
		$stmt->bindParam(5, $end_time);
		$stmt->bindParam(6, $start_hour);
		$stmt->bindParam(7, $start_minute);
		$stmt->bindParam(8, $end_hour);
		$stmt->bindParam(9, $end_minute);
		$stmt->bindParam(10, $working_days['working_day_monday']);
		$stmt->bindParam(11, $working_days['working_day_tuesday']);
		$stmt->bindParam(12, $working_days['working_day_wednesday']);
		$stmt->bindParam(13, $working_days['working_day_thursday']);
		$stmt->bindParam(14, $working_days['working_day_friday']);
		$stmt->bindParam(15, $working_days['working_day_saturday']);
		$stmt->bindParam(16, $working_days['working_day_sunday']);
		
		$stmt->execute();
		$calendar_id = $db->lastInsertId();
		
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // Create a default resource calendar
    $working_days = '{"working_day_monday":1,"working_day_tuesday":1,"working_day_wednesday":1,"working_day_thursday":1,"working_day_friday":1,"working_day_saturday":0,"working_day_sunday":0}';
		$working_days = json_decode($working_days, true);
		$calendar_name = "Default resource calendar";
    $type = "2";
		$is_default_resource_calendar = "1";
		$start_time = "07:00";
		$end_time = "17:00";
		$start_hour = "7";
		$start_minute = "0";
		$end_hour = "17";
		$end_minute = "0";
		$stmt = $db->prepare("INSERT INTO gantt_calendars(programme_id,name,is_default_resource_calendar,start_time,end_time,start_hour,start_minute,end_hour,end_minute,working_day_monday,working_day_tuesday,working_day_wednesday,working_day_thursday,working_day_friday,working_day_saturday,working_day_sunday,type) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->bindParam(1, $programme_id);
		$stmt->bindParam(2, $calendar_name);
		$stmt->bindParam(3, $is_default_resource_calendar);
		$stmt->bindParam(4, $start_time);
		$stmt->bindParam(5, $end_time);
		$stmt->bindParam(6, $start_hour);
		$stmt->bindParam(7, $start_minute);
		$stmt->bindParam(8, $end_hour);
		$stmt->bindParam(9, $end_minute);
		$stmt->bindParam(10, $working_days['working_day_monday']);
		$stmt->bindParam(11, $working_days['working_day_tuesday']);
		$stmt->bindParam(12, $working_days['working_day_wednesday']);
		$stmt->bindParam(13, $working_days['working_day_thursday']);
		$stmt->bindParam(14, $working_days['working_day_friday']);
		$stmt->bindParam(15, $working_days['working_day_saturday']);
		$stmt->bindParam(16, $working_days['working_day_sunday']);
    $stmt->bindParam(17, $type);
		$stmt->execute();
		$res_calendar_id = $db->lastInsertId();
		
		// add first task
		// Default project row
		$guid = generateGUID();
		$order_ui = "0";
		$parent_id = "0";
		$text = "Example task";
		$start_date = date("Y-m-d " . $start_time . ":00");
		$end_date = date("Y-m-d " . $end_time . ":00");
		$duration = "12";
		$opened = "1";
		$type = "task";
		$duration = "480";
		
		
		// Link me to project with admin permissions
		$permission_type = "1";
		$stmt = $db->prepare("INSERT INTO gantt_user_programme_links(user_id,programme_id,permission_type) VALUES (?,?,?)");
		$stmt->bindParam(1, $_SESSION['user']['id']);
		$stmt->bindParam(2, $programme_id);
		$stmt->bindParam(3, $permission_type);
		$stmt->execute();
		
		
		// Create default resource group
		$is_group = "1";
		$name = "Surfacing Plant";
		$min_output_value = "100";
    $max_output_value = "100";
    $outputs_unit = "no";
    $period = "2";
		$contains_consumable_resources = "1";
		$stmt = $db->prepare("INSERT INTO gantt_resource_groups(programme_id,name,is_group,outputs_unit,min_output_value,max_output_value,period,contains_consumable_resources) VALUES (?,?,?,?,?,?,?,?)");
		$stmt->bindParam(1, $programme_id);
		$stmt->bindParam(2, $name);
		$stmt->bindParam(3, $is_group);
		$stmt->bindParam(4, $outputs_unit);
    $stmt->bindParam(5, $min_output_value);
    $stmt->bindParam(6, $max_output_value);
    $stmt->bindParam(7, $period);
		$stmt->bindParam(8, $contains_consumable_resources);
		$stmt->execute();
		$res_group_id = $db->lastInsertId();
		
		
		
		
		
		// Create default res

		$name = "JCB 3CX Compact";
    $company = "JCB Hire";
    $notes = "2m wide and 2.74m high";
		$cost_rate = "25";
		$unit_of_measure = "1"; // no
		$guid = generateGUID();
		$created = time();
		$stmt = $db->prepare("INSERT INTO gantt_resources(programme_id,group_id,calendar_id,name,company,notes,created,cost_rate,unit_of_measure,guid) VALUES (?,?,?,?,?,?,?,?,?,?)");
		$stmt->bindParam(1, $programme_id);
		$stmt->bindParam(2, $res_group_id);
		$stmt->bindParam(3, $res_calendar_id);
		$stmt->bindParam(4, $name);
    $stmt->bindParam(5, $company);
    $stmt->bindParam(6, $notes);
		$stmt->bindParam(7, $created);
		$stmt->bindParam(8, $cost_rate);
		$stmt->bindParam(9, $unit_of_measure);
		$stmt->bindParam(10, $guid);
		$stmt->execute();
		
		
		
		
		// Do CRM bits - POST to CRM
		
		


		$setup_company = file_get_contents('https://crm.ibex.software/?ng=api&key=f7xz76q7uh0l0j07i2esugzdw6fcrjwxhkmfg3d1&method=add_account&name=' . $_REQUEST['company_name'] . "&subdomain="  . $subdomain);
		$json_company = json_decode($setup_company, true);
		$company_id_crm = $json_company['company_id'];
		
		
		       $d->account = $_REQUEST['account'];
            $d->first_name = $_REQUEST['first_name'];
            $d->last_name = $_REQUEST['last_name'];
            $d->email = $_REQUEST['email_address'];
            $d->cid = $_REQUEST['company_id'];
				
				
				$setup_contact = file_get_contents('https://crm.ibex.software/?ng=api&key=f7xz76q7uh0l0j07i2esugzdw6fcrjwxhkmfg3d1&method=add_contact&platform=Gantt&account=' . $_REQUEST['company_name'] . '&first_name='  . $user['first_name'] . "&last_name=" . $user['last_name'] . "&email_address=" . $user['email_address'] . "&company_id=" . $company_id_crm . "&dashboard_id=" . $user['id']);
		$json_contact = json_decode($setup_contact, true);
		$contact_id_crm = $json_contact['contact_id'];
				
		
		
$route = "production";


	$payload = array('programme_created' => true, "programme_id" => $programme_id, "route" => $route);
		
		
	echo json_encode($payload);
		
	}
	
	break;
	die();
	
	
	case "verify_auth_code":
	
	$stmt = $db->prepare("SELECT id FROM users WHERE email_address = :email_address AND invite_code = :code");
	$stmt->bindValue(':email_address', $_REQUEST['email_address'], PDO::PARAM_STR);
	$stmt->bindValue(':code', $_REQUEST['code'], PDO::PARAM_STR);
	$stmt->execute();
	if ($stmt->rowCount() == 0)
	{
		$payload = array('code_valid' => false);
		echo json_encode($payload);
	}
	else 
	{
		$payload = array('code_valid' => true);
		echo json_encode($payload);
	}
	
	break;
	die();
	
	
	
	case "check_user_exists":
	
	$stmt = $db->prepare("SELECT id FROM users WHERE email_address = :email_address");
	$stmt->bindValue(':email_address', $_REQUEST['email_address'], PDO::PARAM_STR);
	$stmt->execute();
	if ($stmt->rowCount() == 0)
	{
		// Send email to user
		$code = rand(1000, 9999);
		$link = "https://beta.ibex.software/mmb-basic/auth-email.php?email_address=" . $_REQUEST['email_address'] . "&code="
 . $code;
 $time = time();
		
		$stmt = $db->prepare("INSERT INTO users(first_name,last_name,email_address,invite_code,created) VALUES (?,?,?,?,?)");
		$stmt->bindParam(1, $_REQUEST['first_name']);
		$stmt->bindParam(2, $_REQUEST['last_name']);
		$stmt->bindParam(3, $_REQUEST['email_address']);
		$stmt->bindParam(4, $code);
		$stmt->bindParam(5, $time);
		$stmt->execute();
		
		// Email admin
		$config = array();
		$config['api_key'] = "key-86929a614db601844f2de754bf641f80";
		$config['api_url'] = "https://api.mailgun.net/v3/ibex.software/messages";
					 
		$message = array();
		$message['from'] = "Ibex <support@ibex.software>";
		$message['to'] = $_REQUEST['email_address'];
		$message['subject'] = "Welcome to Ibex";
						
		$content = file_get_contents('email-templates/auth_link_signup.html');
		$content = str_replace("{{AUTH_URL}}", $link, $content);
			
		$message['html'] = $content;
					 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $config['api_url']);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$message);
					 
		$result = curl_exec($ch);
		curl_close($ch);
		
		$payload = array('user_exists' => false);
		echo json_encode($payload);
	}
	else 
	{
		$payload = array('user_exists' => true);
		echo json_encode($payload);
	}
	
	break;
	
}

?>