<?php
	//include 'config.php';
	require_once('../../includes/ep-configuration.php');
	require_once('../../includes/classes/dbclass.php');
	include_once('../../student/includes/define.php');
	require_once('app_config.php');
	$dbClassObj   =   new  dbClass();

	require_once('../hashPassword.php');
	class API {
		
		private $dbClassObj;

		private $hasherObj;
		
		const SITE_URL = SITE_URL;
		
		function __construct($dbClassObj){
			$this->dbClassObj = $dbClassObj;
			$this->hasherObj = new hashPassword();
		}
		//METHOD: used to get the plan type by the parent id.
		public function getPlanTypeByParent($userId){
			//QUERY: to get the plan type by user id.
			$getResults = $this->dbClassObj->query("SELECT plan_type FROM `ep_subscription_parent` WHERE parent_id = ".$userId);
			//CHECK: is the getResults are filled with values or not.
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				$planType = $getResults['plan_type'];
				//SWITCH: case used to return the values acc to the get value.
				if(!empty($planType)){
					switch($planType){
						case '1':		return 'Free Trial';		break;
						case '2':		return 'Monthly';			break;
						case '3':		return 'Yearly';			break;
						case '4':		return 'Direct Debit';		break;
						default:	return 'Invalid Plan Type';
					}
				}else{ return 'No plan associated'; }
			}
		}
		
		//METHOD: used to get the years by student id.
		public function getStudentYearByUserId($userId){
			//QUERY: to get the plan type by user id.
			$getResults = $this->dbClassObj->query("SELECT student_year FROM `ep_wsusers` WHERE id = ".$userId);
			//CHECK: is the getResults are filled with values or not.
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				$studentYear = $getResults['student_year'];
				if(!empty($studentYear)){ return $studentYear; }
				else{ return 'Year is not associated with your account'; }
			}else{ return 'Year is not associated with your account'; }
		}
		
		//METHOD: used to get ksu id by worksheet id.
		public function _getKsuIdByWorksheet($worksheetId){
			$getResults = $this->dbClassObj->query("SELECT ksu FROM `ep_worksheets` WHERE id = ".$worksheetId);
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				return $getResults['ksu'];
			}else{
				return 0;
			}
		}
		
		//METHOD: used to get year by worksheet id.
		public function _getYearByWorksheet($worksheetId){
			$getResults = $this->dbClassObj->query("SELECT year FROM `ep_worksheets` WHERE id = ".$worksheetId);
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				$yearId =  $getResults['year'];
				//GET: the level value on the basis of level id.
				$getResults = $this->dbClassObj->query("SELECT year FROM `ep_wsyears` WHERE id = ".$yearId);
				if($this->dbClassObj->num_rows($getResults)){
					$getResults = $this->dbClassObj->fetch_assoc($getResults);
					return $getResults['year'];
				}else{ return 0; }
			}else{ return 0; }
		}
		
		//METHOD: used to get the objective id by worksheet id.
		public function _getObjectiveIdByWorksheet($worksheetId){
			$getResults = $this->dbClassObj->query("SELECT objective FROM `ep_worksheets` WHERE id = ".$worksheetId);
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				return $getResults['objective'];
			}else{
				return 0;
			}
		}
		
		//METHOD: used to get the worksheet name by worksheet id.
		public function _getWorksheetNameByWorksheet($worksheetId){
			$getResults = $this->dbClassObj->query("SELECT worksheetName FROM `ep_worksheets` WHERE id = ".$worksheetId);
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				return $getResults['worksheetName'];
			}else{
				return 0;
			}
		}
		
		//METHOD: used to get the level by worksheet id.
		public function _getLevelByWorksheet($worksheetId){
			$getResults = $this->dbClassObj->query("SELECT level FROM `ep_worksheets` WHERE id = ".$worksheetId);
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				$levelId =  $getResults['level'];
				//GET: the level value on the basis of level id.
				$getResults = $this->dbClassObj->query("SELECT level FROM `ep_wslevel` WHERE id = ".$levelId);
				if($this->dbClassObj->num_rows($getResults)){
					$getResults = $this->dbClassObj->fetch_assoc($getResults);
					return $getResults['level'];
				}else{ return 0; }
			}else{ return 0; }
		}
		
		//METHOD: used to get the subject by worksheet id.
		public function _getSubjectByWorksheet($worksheetId){
			$getResults = $this->dbClassObj->query("SELECT subject FROM `ep_worksheets` WHERE id = ".$worksheetId);
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				$subjectId =  $getResults['subject'];
				//GET: the level value on the basis of level id.
				$getResults = $this->dbClassObj->query("SELECT subject FROM `ep_wssubjects` WHERE id = ".$subjectId);
				if($this->dbClassObj->num_rows($getResults)){
					$getResults = $this->dbClassObj->fetch_assoc($getResults);
					return $getResults['subject'];
				}else{ return 0; }
			}else{ return 0; }
		}
		
		//METHOD: used to get the questions count by worksheet id.
		public function _getQuestionsCountByWorksheet($worksheetId){
			$getResults = $this->dbClassObj->query("SELECT count(id) as questions_count FROM `ep_questions` WHERE worksheetId = ".$worksheetId);
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				return $getResults['questions_count'];
			}else{ return 0; }
		}
		
		//METHOD: used to get the topic by worksheet id.
		public function _getTopicByWorksheet($worksheetId){
			$getResults = $this->dbClassObj->query("SELECT topic_id FROM `ep_worksheets` WHERE id = ".$worksheetId);
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				$topicId =  $getResults['topic_id'];
				//GET: the topic value on the basis of topic id.
				$getResults = $this->dbClassObj->query("SELECT topicTags FROM `ep_wstopictags` WHERE id = ".$topicId);
				if($this->dbClassObj->num_rows($getResults)){
					$getResults = $this->dbClassObj->fetch_assoc($getResults);
					return $getResults['topicTags'];
				}else{ return 0; }
			}else{ return 0; }
		}
		
		//METHOD: used to get the curriculum topic by worksheet id.
		public function _getCurriculumTopicByWorksheet($worksheetId){
			$ksuId = $this->_getKsuIdByWorksheet($worksheetId);
			$getResults = $this->dbClassObj->query("SELECT * FROM `ep_wsksu` WHERE id = ".$ksuId);
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				return $getResults['ksu'];
			}else{
				return 'No curriculum topic associated';
			}
		}

		//METHOD: used to get the sub curriculum topic by worksheet id.
		public function _getCurriculumSubTopicByWorksheet($worksheetId){
			$ksuId = $this->_getKsuIdByWorksheet($worksheetId);
			$objectiveId = $this->_getObjectiveIdByWorksheet($worksheetId);
			$getResults = $this->dbClassObj->query("SELECT * FROM `ep_wsobjective` WHERE ksu_id = ".$ksuId." AND id = ".$objectiveId);
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				return $getResults['objectives'];
			}else{
				return 'No sub curriculum topic associated';
			}
		}
		
		//METHOD: used to get the count of students by parent id.
		public function _getStudentsCountByParent($parentId){
			$getResults = $this->dbClassObj->query("select count(U.id) as students from ep_wsusers U ,ep_subscription_student S where U.user_type='student' and S.student_id=U.id and S.is_current='1' and U.parent_id= '".$parentId."'");
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				return $getResults['students'];
			}else{
				return 'No sub curriculum topic associated';
			}
		}
		
		//METHOD: used to update the parent 'first_added' to 1.
		public function _updateParentFirstAdded($parent_id){
			$this->dbClassObj->query("UPDATE ep_wsusers SET first_added = '1' WHERE id =".$parent_id);
		}
	
		//METHOD: used to get the plan type by the parent id.
		public function _getPlanTypeByStudent($student_id){
			//GET: parent id by student id.
			$parentDetails = $this->_getParentDetailsByStudent($student_id);
			$parent_id = $parentDetails['id'];
			//QUERY: to get the plan type by user id.
			$getResults = $this->dbClassObj->query("SELECT sp.name as plan_type FROM `ep_subscription_student` as ss left join ep_subscription_plan as sp on sp.id = ss.plan_id where student_id = ".$student_id." and parent_id = ".$parent_id);
			//CHECK: is the getResults are filled with values or not.
			if($this->dbClassObj->num_rows($getResults)>0){
				$getResults = $this->dbClassObj->fetch_assoc($getResults);
				$planType = $getResults['plan_type'];
				return $planType;
			}
		}
		
		//METHOD: used to return the subject name by subject id.
		public function _getSubjectNameById($subject_id){
			switch($subject_id){
				case 1: return 'Maths'; break;
				case 2: return 'English'; break;
				case 3: return 'Science'; break;
				case 9: return 'All'; break;
				default: return '';
			}
		}
		
		public function _getParentEmailByStudentId($student_id){
			$query = $this->dbClassObj->query("SELECT email FROM ep_wsusers WHERE id = ( SELECT parent_id FROM ep_wsusers WHERE id = {$student_id} )");
			$fetch_assoc = $this->dbClassObj->fetch_assoc($query);
			$parent_email = $fetch_assoc['email'];
			return $parent_email;
		}
		
		/*
		 * METHOD: used to get token from headers
		 */
		public function getToken() {
			$headers = apache_request_headers();
			if(isset($headers['Authorization'])) {
				$token = preg_replace('/Bearer /', '', $headers['Authorization']);
				return $token;
			}
			else {
				return FALSE;
			}
		}
		
		/*
		 * METHOD: used to validate token
		 */
		public function validateToken($token) {
			$curr_time = date('Y-m-d H:i:s');
			$res = $this->dbClassObj->query("select at.expires FROM ep_access_tokens as at where access_token='" . $token . "'");
			if ($this->dbClassObj->num_rows($res) > 0) {
				$expires = $res->fetchColumn();
				if ($expires > $curr_time) {
					return TRUE;
				}
				else{
					return FALSE;
				}
			}
			else {
				return FALSE;
			}
		}
		
		/*
		 * METHOD: used to check whether request is private or not,
		 * if it is a private request, then access token in request is user token, else it is FALSE
		 */
		public function isPrivate($token) {
			$res = $this->dbClassObj->query("SELECT at.uid FROM ep_access_tokens as at where access_token='" . $token . "'");
			
			if ($this->dbClassObj->num_rows($res) > 0) {
				$uid = $res->fetchColumn();
				if($uid) {
					return $uid;
				}
				else {
					return FALSE;
				}
			}
			else {
				return FALSE;
			}
		}
		
		/*
		 * METHOD: used to get uid  from refresh token,
		 * 
		 */
		public function getUidFromRefreshToken($refresh_token) {
			$res = $this->dbClassObj->query("SELECT rt.uid FROM ep_refresh_tokens as rt where refresh_token='" . $refresh_token . "'");
			
			if ($this->dbClassObj->num_rows($res) > 0) {
				$uid = $res->fetchColumn();
				if($uid) {
					return $uid;
				}
				else {
					return FALSE;
				}
			}
			else {
				return FALSE;
			}
		}
		
		/*
		 * METHOD: used to get uid  from user token,
		 * 
		 */
		public function getUidFromUserToken($user_token) {
			$res = $this->dbClassObj->query("SELECT rt.uid FROM ep_access_tokens as rt where access_token='" . $user_token . "'");
			if ($this->dbClassObj->num_rows($res) > 0) {
				$uid = $res->fetchColumn();
				if($uid) {
					return $uid;
				}
				else {
					return FALSE;
				}
			}
			else {
				return FALSE;
			}
		}

     
	    /*
		 * METHOD: used to get uid  from user token,
		 * 
		 */
       public function getUserTypeFromUid($uid) {
		   if(isset($uid)&&($uid=="")){
			   return "guest";
		   }
           else {
			   $res = $this->dbClassObj->query("SELECT rt.user_type FROM ep_wsusers as rt where id='" . $uid . "'");
			   if ($this->dbClassObj->num_rows($res) > 0) {
					$userType = $res->fetchColumn();
					if($userType) {
						return $userType;
					}
					else {
						return FALSE;
					}
				}
				else {
					return FALSE;
				}
		   }
	   }
		/*
		 * METHOD: used to update user id for access token and refresh token table,
		 * if it is a private request, then access token in request is user token, else it is app_token
		 */
		public function updateUserToken($access_token, $uid) {
			$created = date('Y-m-d H:i:s');
			$refresh_token = hash('sha256', microtime(TRUE).rand().CLIENT_ID);
			$expires = date('Y-m-d H:i:s', time() + USER_TOKEN_EXPIRY);
			$user_token = hash('sha256', microtime(TRUE).rand().CLIENT_SECRET);
			/*
			$res = $this->dbClassObj->query("SELECT at.expires FROM ep_access_tokens as at where uid='" . $uid . "'");
			
			if ($this->dbClassObj->num_rows($res) > 0) {
				$expiry = $res->fetchColumn();
				if ($expires > $curr_time) {
				$sql = "update ep_access_tokens as at inner join ep_refresh_tokens as rt on at.id=rt.access_token_id set rt.uid='" . $uid . "',  at.uid='" . $uid . "', at.expires='" . $expires . "', at.access_token='" . $user_token ."' WHERE at.access_token='" . $access_token . "'";
				$this->dbClassObj->query($sql);
			}
			else {*/
				$sql = $this->dbClassObj->query("insert into ep_access_tokens set uid='" . $uid . "',  expires='" . $expires . "', access_token='" . $user_token . "', scope='" . SCOPE . "', token_type='" . TOKEN_TYPE . "', created='" . $created . "'");
				$last_id = $this->dbClassObj->inserted_id();
				$sql1 = $this->dbClassObj->query("insert into ep_refresh_tokens set access_token_id='" . $last_id . "',  uid='" . $uid . "',  refresh_token='" . $refresh_token . "', created='" . $created . "'");
			//}
			return $user_token;
		}
		
		/*** Service to provide App Token
		** URL : http://epl-edplace.netsol.local/webservice/?s=access_token
		** INPUT : { "client_id":"client123", "client_secret": "secret123", "grant_type": "password", "username": "appuser@edplace.com", "password": "123456" }
		**/
		public function access_token() {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				$client_id = $data->client_id;
				$client_secret = $data->client_secret;
				$username = $data->username;
				$password = $data->password;
				
				if($client_id == CLIENT_ID && $client_secret == CLIENT_SECRET && $username == CLIENT_USERNAME && $password == CLIENT_PASSWORD) {
					$created = date('Y-m-d H:i:s');
					$access_token = hash('sha256', microtime(TRUE).rand().CLIENT_SECRET);
					$refresh_token = hash('sha256', microtime(TRUE).rand().CLIENT_ID);
					$expires = date('Y-m-d H:i:s', time() + APP_TOKEN_EXPIRY);
					$sql = $this->dbClassObj->query("insert into ep_access_tokens set expires='" . $expires . "', access_token='" . $access_token . "', scope='" . SCOPE . "', token_type='" . TOKEN_TYPE . "', created='" . $created . "'");
					$last_id = $this->dbClassObj->inserted_id();
					$sql1 = $this->dbClassObj->query("insert into ep_refresh_tokens set access_token_id='" . $last_id . "', refresh_token='" . $refresh_token . "', created='" . $created . "'");
					
					if($sql && $sql1) {
						$data = [
							'access_token' => $access_token,
							'token_type' => TOKEN_TYPE,
							'refresh_token' => $refresh_token,
							'expires' => APP_TOKEN_EXPIRY,
							'scope' => SCOPE
						];
						$response = [ 'error' => 0, 'message' => "Success", 'response' => $data ]; 
					}
					else {
						$response = [ 'error' => 1, 'message' => "Access token service is not working" ];
					}
				}
				else {
					$response = [ 'error' => 1, 'message' => "Access token service is not working" ];
				}
				echo json_encode($response);
				die();
			}
		}
		
		/*** Service to provide App Token using refresh token
		** URL : http://epl-edplace.netsol.local/webservice/?s=refresh_token
		** INPUT : { "refresh_token":"token_value" }
		**/
		public function refresh_token() {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				$refresh_token = $data->refresh_token;
				if(empty($refresh_token)){
					$response = array('error' => REFRESH_TOKEN_NOT_EXISTS,'message' => REFRESH_TOKEN_NOT_EXISTS_MSG);
				
				}else{
					$update_access_token = hash('sha256', microtime(TRUE).rand().CLIENT_SECRET);
					//$uid = $this->getUidFromRefreshToken($refresh_token);
					//echo $uid;die;
					
					$expires = date('Y-m-d H:i:s', time() + APP_TOKEN_EXPIRY);
					$token_expires = APP_TOKEN_EXPIRY;
					$sql = $this->dbClassObj->query("update ep_access_tokens as at join ep_refresh_tokens as rt on at.id = rt.access_token_id set at.access_token='" . $update_access_token . "', at.expires='" . $expires . "' Where rt.refresh_token='" . $refresh_token . "'");
					
					$data = [
						'access_token' => $update_access_token,
						'token_type' => TOKEN_TYPE,
						'refresh_token' => $refresh_token,
						'expires' => $token_expires,
						'scope' => SCOPE
					];
					$response = array('error' => STATUS_OK,'message' => "Success",'response' => $data); 					
					
				}
				
				echo json_encode($response); 
				die();
			}
		}
		
		
		/*** Service to login user
		** URL : http://epl-edplace.netsol.local/webservice/?s=login
		** INPUT : {"email":"test1@mailinator.com","password":"edplace","type":"parent"}
		**/
		public function login() {
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
					
					$data = file_get_contents('php://input');
					$data = json_decode($data);
					$username = $data->email;
					if(strlen($data->password)==90 && $data->type=='student'){
						$password = $data->password;				
					}else{
						$password = $this->hasherObj->createHash($data->password);
					}
					$type = $data->type;
					// Check user type is parent
					if ($type == 'parent' || $type == 'student') {
						
						if ($type == 'parent'){ 
							$sql  =  "SELECT *, id as user_id FROM ep_wsusers WHERE email = '".$username."' AND password = '".$password."' and user_type= '".$type."' and school_type_id = 0 and organization_id = ".APP_ORGANIZATION_ID;
						
						}else{ 
							$sql  =  "SELECT *,U.id as user_id FROM ep_wsusers U, ep_subscription_student S WHERE  U.email = '".$username."' AND U.password = '".$password."' AND U.user_type = '".$type."' AND S.student_id = U.id AND S.is_current = '1' AND STATUS = '1' and organization_id = ".APP_ORGANIZATION_ID;
						}
						$res   =  $this->dbClassObj->query($sql);
						
						// If user authenticates
						if ($this->dbClassObj->num_rows($res)>0) {
							$parentData = array();
							while ($row = $this->dbClassObj->fetch_assoc($res)) {	
								
								if ($row['password'] == $password) { // Check if user password and db password matches
									$parentData['id']         = $row['user_id'];
									$parentData['type']       = $row['user_type'];
									$parentData['first_name'] = $row['fname'];
									$parentData['last_name']  = $row['lname'];
									$parentData['username']   = $row['user_name'];
									$parentData['self_assign_y_n']   = $row['self_assign_y_n'];
									
									// Creates user token
									$parentData['token'] = $this->updateUserToken($token, $row['user_id']);
									$parentData['expires'] = USER_TOKEN_EXPIRY;
									
									if($type == 'parent') {
										//GET: the current login user's subscribed subjects

										$subjects_query = $this->dbClassObj->query("SELECT S.subject_id FROM ep_wsusers AS U LEFT JOIN ep_subscription_parent AS P ON P.parent_id = U.id LEFT JOIN ep_subscription_student AS S ON S.student_id = U.id WHERE U.parent_id =".$row['user_id']." AND ( S.is_current = '1' OR S.is_addedby_parent_account = '1' ) AND U.delete_status = '0' AND S.is_drafted = '0'");
										if($this->dbClassObj->num_rows($subjects_query) > 0)	{ 
											$i=0; 
											while($subject_arr = $this->dbClassObj->fetch_assoc($subjects_query)){
												$subjects_arr[$i] = $subject_arr['subject_id']; $i++; 
											} 
										} else { 
											$subjects_arr = array('0'); 
										}
										$parentData['plan_type'] = $this->getPlanTypeByParent($row['user_id']);
										$parentData['subjects']   = $subjects_arr;
									}else {
										
										//GET: parent email address
										$parentData['parent_email'] = $this->_getParentEmailByStudentId($row['user_id']);
										
										//GET: the current login user's subscribed subjects
										$subjects_query = $this->dbClassObj->query("SELECT S.subject_id FROM ep_wsusers AS U LEFT JOIN ep_subscription_parent AS P ON P.parent_id = U.id LEFT JOIN ep_subscription_student AS S ON S.student_id = U.id WHERE U.id =".$row['user_id']." AND ( S.is_current = '1' OR S.is_addedby_parent_account = '1' ) AND U.delete_status = '0' AND S.is_drafted = '0'");

										if($this->dbClassObj->num_rows($subjects_query) > 0){ $subject_arr = $this->dbClassObj->fetch_assoc($subjects_query); }
										else{ $subjects_arr = array('subject_id' => 0); }

										$parentData['student_year'] = $this->getStudentYearByUserId($row['user_id']);
										$parentData['plan_type'] = $this->_getPlanTypeByStudent($row['user_id']);
										$parentData['subjects'][0]   = $subject_arr['subject_id'];
										$i=0; $subjectsArray=array();
										foreach($parentData['subjects'] as $subject_id){
											$subjectsArray[$i] = $this->_getSubjectNameById($subject_id);
											$i++;
										}
										$parentData['subject_names'] = $subjectsArray;
									}

									
									if($type == 'parent'){
										// Check parent subscription
										$sql = "select * from ep_subscription_parent where parent_id= '".$row['id']."'";								
										$parentData['parenttype']='parent';
									}elseif($type == 'student'){
										$sql = "select * from ep_subscription_parent where parent_id= '".$row['parent_id']."'";
										$usertypequery = "SELECT user_type FROM ep_wsusers WHERE id =".$row['parent_id'];
										$resusertypequery   =  $this->dbClassObj->query($usertypequery);
										$rowusertypequery=$this->dbClassObj->fetch_object($resusertypequery);
										$parentData['parenttype']=$rowusertypequery->user_type;
									}
									$res   =  $this->dbClassObj->query($sql);
									while ($row = $this->dbClassObj->fetch_assoc($res)) {
										$parentData['is_free_signup'] = $row['is_free_signup'];
										// Check parent account is active
										if($row['subs_status'] == '1' && $row['plan_type'] !='1'){
											$parentData['subscriptionStatus'] = "Your EdPlace subscription is active";
										} else if($row['subs_status'] == '1' && $row['plan_type'] == '1') { 
											$diff_days 	= round ( (strtotime($row['subs_end_date']) - strtotime(date("Y-m-d")))/ (60*60*24));
											if($row['is_free_signup']!='0') {
												$parentData['subscriptionStatus'] = "Your account expires in ".$diff_days." days";
											} else {
												$parentData['subscriptionStatus'] = "Your Free Trial ends in ".$diff_days." days";
											}
										} else { // Parent account is inactive
											$parentData['subscriptionStatus'] = "Your EdPlace subscription is Inactive ".$diff_days;
										}
										
										//Validations for the childrens status
										$is_all_child_updated = $this->dbClassObj->query("SELECT count( usr.id ) AS cnum FROM ep_wsusers usr LEFT JOIN ep_subscription_student ss ON ss.student_id = usr.id WHERE usr.parent_id ='".$parentData['id']."' AND usr.is_child_password_updated = '0' AND ss.is_current = '1'"); 
										
										$is_all_child_updated = $this->dbClassObj->fetch_assoc($is_all_child_updated);
										$is_all_child_updated = $is_all_child_updated['cnum'];
										
										$is_free_trial_account = $this->dbClassObj->query("select is_free_signup from ep_subscription_parent where parent_id='".$parentData['id']."'"); 
										$is_free_trial_account = $this->dbClassObj->fetch_assoc($is_free_trial_account);
										$is_free_trial_account = $is_free_trial_account['is_free_signup'];
										
										if( $is_all_child_updated > 0 && $is_free_trial_account == '0'){
											$parentData['childpassbox'] = 1;
										}else{ $parentData['childpassbox'] = 0; }
										
										//SET: the count of student by parent.
										$parentData['students_count'] = $this->_getStudentsCountByParent($parentData['id']);
										
									}
									$response = array('error' => STATUS_OK,'message' => "Success",'response' => $parentData); 
								} else {
									$response = array('error' => ERROR_EXISTS,'message' => "Invalid Login Credentials");
								}
							}
						} else { // Error message of "Invalid login credentials"
							$response = array('error' => ERROR_EXISTS,'message' => "Invalid login credentials");
						}
					}
				}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
				echo json_encode($response); 
			}	
		}
		
		/*** Service to fetch students of a parent
		** URL : http://epl-edplace.netsol.local/webservice/?s=parent_students
		** INPUT : { "user_token":"token_value" }
		**/ 
		public function parent_dashboard() {
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				 $user_token = $this->getToken();
			  if($user_token && $this->validateToken($user_token) == TRUE ) {
				$parentid = $this->getUidFromUserToken($user_token);
				$data = file_get_contents('php://input');
				$data = json_decode($data);
			
				$sql = "select U.id,U.fname,U.lname, TRIM(CONCAT(U.fname,' ',U.lname)) as fullname,U.user_name,U.auto_assign_y_n, U.self_assign_y_n, S.student_id from ep_wsusers U ,ep_subscription_student S where U.user_type='student' and S.student_id=U.id and S.is_current='1' and U.parent_id= '".$parentid."'";
				
				$res   =  $this->dbClassObj->query($sql);
				if ($this->dbClassObj->num_rows($res)>0) {
					$i = 0;
					while ($row = $this->dbClassObj->fetch_assoc($res)) {

						//CHECK: if username is null or not
						if(!empty($row['user_name'])){ 
							//CHECK: the full name is empty then user_name is used
							if(empty($row['fullname'])){ 
								$row['fullname'] = $row['fname'].' '.$row['lname']; 
							}
							$studentData[$i] = $row;
							
							// Worksheets Assigned to Each Student
							$workesshetsAssigned = $this->__count_worksheet_assign($row['student_id'],0,$parentid);
							$studentData[$i]['worksheetsAssigned'] = $workesshetsAssigned;
							
							// Worksheet Completed By each Student
							$workesshetsCompleted = $this->__count_worksheet_assign($row['student_id'],1,$parentid);
							$studentData[$i]['worksheetsCompleted'] = $workesshetsCompleted;
							
							// Calculate average score of student
							$averageScore = $this->__count_score_avg($row['student_id'],1,$parentid);
							$studentData[$i]['averageScore'] = $averageScore.'%';
							
							// Calculate rewards set of a student
							$rewards = $this->__count_rewardSet($row['student_id'],$parentid);
							$studentData[$i]['rewards'] = $rewards;

							// Calculate badges set of a student
							$badges = $this->__countBadges($row['student_id'],1);
							$studentData[$i]['badges'] = $badges;
						
						}
						$i++;
					}
					$response = array('error' => STATUS_OK,'message' => "Success",'response' => $studentData); 
				}else{ $response = array('error' => ERROR_EXISTS,'message' => "Information not found");  } }
				else {$response = array('error' => USER_TOKEN_EXPIRED, 'messsage' => USER_TOKEN_EXPIRED_MSG); }
			}else{ $response = array('error' => ERROR_EXISTS,'message' =>POST_ERROR_MSG);  }
			echo json_encode($response);
		}
		
		/*** Service to browse assignments of a student
		** URL : http://epl-edplace.netsol.local/webservice/?s=browse_assignments
		** INPUT : {"subject":"maths"}
		**/ 
		public function browse_assignments() {
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$token = $this->getToken();
				$this->validateToken($token);
				if($token && $this->validateToken($token) == TRUE ) {
					$student_id = $this->getUidFromUserToken($token);
					$userType = $this->getUserTypeFromUid($student_id);
					$data = file_get_contents('php://input');
					$data = json_decode($data);
					if (!empty($data)) {
						// Subject of worksheet
						$subject = $data->subject;
						//$userType = $data->userType;
						//$student_id = $data->student_id;
						$studentQuery = '';
						//if user type is parent as per old code it consider as a guest 
						if($userType == 'guest' || $userType == 'parent'){ $guestQuery = " AND W.is_worksheet_on_ios = '2'"; }else{$guestQuery='';}
						
						if(!empty($student_id) && $userType != 'parent'){ 
							$query_select = $this->dbClassObj->query("SELECT student_year FROM ep_wsusers WHERE id = ".$student_id);
							$user_data = $this->dbClassObj->fetch_assoc($query_select);
							$studentQuery = " AND WY.year =".$user_data['student_year']; 
						}else{$studentQuery ='';}
						
						// Query to get key stage number
						$keyStageQuery	= "select id,key_stage,keystage_no,substring(key_stage,3) as kstxt from ep_wskeystage where status='1' group by keystage_no order by keystage_no";
						$res	= $this->dbClassObj->query($keyStageQuery);
						$worksheets = array();
						while($wsres	= $this->dbClassObj->fetch_assoc($res)) {
							$worksheets = array_merge($worksheets, $this->__displayStat($wsres['keystage_no'],$subject,$guestQuery,$studentQuery));
						}
						$response = array('error' => STATUS_OK,'message' => 'Success','response' => $worksheets);
					} else {
						$response = array('error' => ERROR_EXISTS,'message' => 'Error','response' => 'Null input receiving');
					}
				}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
				echo json_encode($response);
			}
		}
		
		// This method is used to browse Student Badges
		private function __studentBadges($studentId) {
			// $badges_sql = "select T.* from (select BC.*, AB.id as sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found from ep_badges_condition as BC left join ep_badges_assigned as AB on BC.id=AB.assigned_badges_id and AB.student_id=".$studentId." where BC.badges_type_id in (1,2,3) and BC.badges_id IN(1,2,3) and BC.status='1' and BC.subject_id in (1,2,3) AND AB.is_found IS NOT NULL AND AB.is_found='1' union select BC.*, AB.id as sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found from ep_badges_condition as BC left join ep_badges_assigned as AB on BC.id=AB.assigned_badges_id and AB.student_id=".$studentId." where BC.badges_type_id not in (1,2,3) and BC.badges_id IN(1,2,3) and BC.status='1' AND AB.is_found IS NOT NULL AND AB.is_found='1' ORDER BY id DESC)as T GROUP BY badge_title order by T.badges_type_id asc";
			
			// $badges_sql = "SELECT T.* FROM (SELECT BC.*, AB.id AS sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found  FROM ep_badges_condition AS BC LEFT JOIN ep_badges_assigned AS AB ON BC.id=AB.assigned_badges_id WHERE BC.badges_type_id IN (1,2,3) AND AB.student_id=".$studentId." AND BC.badges_id IN(1,2,3) AND BC.status='1' AND BC.subject_id IN (1,2,3) AND AB.is_found IS NOT NULL AND AB.is_found='1' UNION SELECT BC.*, AB.id AS sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found FROM ep_badges_condition AS BC LEFT JOIN ep_badges_assigned AS AB ON BC.id=AB.assigned_badges_id WHERE BC.badges_type_id NOT IN (1,2,3) AND AB.student_id=".$studentId." AND BC.badges_id IN(1,2,3) AND BC.status='1' AND AB.is_found IS NOT NULL AND AB.is_found='1' ORDER BY id DESC)AS T GROUP BY badge_title ORDER BY T.badges_type_id ASC";
			
			$badges_sql = "SELECT T.* FROM (SELECT BC.*, AB.id AS sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found  FROM ep_badges_condition AS BC LEFT JOIN ep_badges_assigned AS AB ON BC.id=AB.assigned_badges_id WHERE BC.badges_type_id IN (1,2,3) AND AB.student_id=".$studentId." AND BC.badges_id IN(1,2,3) AND BC.status='1' AND BC.subject_id IN (1,2,3) AND AB.is_found IS NOT NULL AND AB.is_found='1' UNION SELECT BC.*, AB.id AS sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found FROM ep_badges_condition AS BC LEFT JOIN ep_badges_assigned AS AB ON BC.id=AB.assigned_badges_id WHERE BC.badges_type_id NOT IN (1,2,3) AND AB.student_id=".$studentId." AND BC.badges_id IN(1,2,3) AND BC.status='1' AND AB.is_found IS NOT NULL AND AB.is_found='1' ORDER BY badges_id DESC)AS T GROUP BY badge_title, badges_type, topic_id,  badges_subjects, subject_id ORDER BY T.badges_type_id ASC";
						
			$conditonBadge	= array(1,2,3);
			$badgesDetails		= array();
			$badgesDetailsResult= $this->dbClassObj->query($badges_sql);
			if($this->dbClassObj->num_rows($badgesDetailsResult)>0){
				while($line=$this->dbClassObj->fetch_assoc($badgesDetailsResult)){
					$bd_detail_type_id =$line['badges_type_id'];
					if(in_array($bd_detail_type_id,$conditonBadge)){
						$dbtype=$line['badges_type'];
						$badgesDetails[$dbtype][]=$line;
					}else{
						$badgesDetails['bonus'][] =$line;
					}
				}
			}

			if(count($badgesDetails)){
				$inc=0;
				foreach($badgesDetails as $key=>$badge){
					$badgeArray = array();
					foreach($badge as $k=>$v){
						$is_active 		= ($v['is_found']==1)?'':'_inactive';
						$b_in_image		= SITE_URL."/".substr($v['badges_icon'], 0, -4).$is_active.'.png';
						$b_image		= ($v['is_found']==1)?SITE_URL."/".$v['badges_icon']:$b_in_image;
						$b_badges_type_id=$v['badges_type_id'];			
						
						if($v['is_found']==1){ 
							
							if( $row['id'] != $oldId ) { $oldId = $row['id']; $q++; }
							
							// $no_badge_type	=array(1,2,3,4,5,6,10,11);
							$no_badge_type	=array(2,3,4,5,6,10,11);
							$badges_txt		= '';
							$subjects=array('1'=>'maths','2'=>'english','3'=>'science');
							$subject_id 	= $v['subject_id'];
							$badges_subjects= ($v['badges_type_id']==1)?$subjects[$subject_id]:$v['badges_subjects'];
							// $badges_txt 	= ($v['is_found']==1)?' - '.$v['badges']:$badges_txt;
							$badges_txt 	= ' - '.$v['badges'];
							$badges_title	= (in_array($v['badges_type_id'],$conditonBadge))?$badges_subjects.' '.$v['badge_title']:$badges_subjects;
							$badges_txt		= (in_array($v['id'],$no_badge_type))?'':$badges_txt;
							$b_description	= ucwords($badges_title).$badges_txt;
							
							$badgeArray['badge_id'] = $v['id'];
							//$badgeArray['badge_name'] = $v['badge_title'];
							$badgeArray['badge_name'] = $b_description;
							$badgeArray['badge_image'] = $v['badges_icon'];
							$badgeArray['badge_description'] = $v['completed_description'];
							
							if($v['subject_id']==""){
								$badgeArray['subject'] = 'Bonus';
							}else{
								$queryName = "SELECT subject FROM ep_wssubjects WHERE id = ".$v['subject_id'];
								$nameObj = $this->dbClassObj->query($queryName);
								$rowName = $this->dbClassObj->fetch_array($nameObj);
								$badgeArray['subject'] = $rowName[0];
							}
							
							
							//print_r($badgeArray);
							
							$queryName = $this->dbClassObj->query("SELECT CONCAT(fname,' ',lname) as student_name FROM ep_wsusers WHERE id = ".$studentId);
							$queryName = $this->dbClassObj->fetch_assoc($queryName);
							
							$responseArray['student_id'] = $studentId;
							$responseArray['student_name'] = $queryName['student_name'];
							$responseArray['badges'][$inc] =  array_merge((array)$responseArray['badges'][$inc],$badgeArray);
							
							//print_r($responseArray);
							$inc++ ;
						} 
					}
				}
			}
			return $responseArray;
		}
		
		
		// This method is used to browse worksheets
		private function __countBadges($studentId,$countOnly =NULL) {
			$oldId=$q=-1;
			$row['id'] = $studentId;
			$responseArray[0]['total_badges'] = 0;
			
			if($countOnly == 1){
				$badges_sql = "SELECT T.* FROM ( SELECT BC . * , AB.id AS sb_id, AB.assigned_badges_id, AB.is_found FROM ep_badges_condition AS BC LEFT JOIN ep_badges_assigned AS AB ON BC.id = AB.assigned_badges_id WHERE BC.badges_type_id IN ( 1, 2, 3 )  AND AB.student_id =".$row['id']." AND BC.badges_id =  '1' AND BC.status =  '1' AND BC.subject_id IN ( 1, 2, 3 )  UNION SELECT BC . * , AB.id AS sb_id, AB.assigned_badges_id, AB.is_found FROM ep_badges_condition AS BC LEFT JOIN ep_badges_assigned AS AB ON BC.id = AB.assigned_badges_id WHERE BC.badges_type_id NOT  IN ( 1, 2, 3 )  AND AB.student_id =".$row['id']." AND BC.badges_id =  '1' AND BC.status =  '1' ) AS T ORDER BY T.badges_type_id ASC";
			}else{
				$badges_sql = "select T.* from (select BC.*, AB.id as sb_id, AB.assigned_badges_id, AB.is_found from ep_badges_condition as BC left join ep_badges_assigned as AB on BC.id=AB.assigned_badges_id where BC.badges_type_id in (1,2,3) and AB.student_id=".$row['id']." and BC.badges_id='1' and BC.status='1' and BC.subject_id in (1,2,3) union select BC.*, AB.id as sb_id, AB.assigned_badges_id, AB.is_found from ep_badges_condition as BC left join ep_badges_assigned as AB on BC.id=AB.assigned_badges_id and AB.student_id=".$row['id']." where BC.badges_type_id not in (1,2,3) and BC.badges_id='1' and BC.status='1' )as T order by T.badges_type_id asc";
			}
			
			$conditonBadge	= array(1,2,3);
			$badgesDetails		= array();
			$badgesDetailsResult= $this->dbClassObj->query($badges_sql);
			if($this->dbClassObj->num_rows($badgesDetailsResult)>0){
				while($line=$this->dbClassObj->fetch_assoc($badgesDetailsResult)){
					$bd_detail_type_id =$line['badges_type_id'];
					if(in_array($bd_detail_type_id,$conditonBadge)){
						$dbtype=$line['badges_type'];
						$badgesDetails[$dbtype][]=$line;
					}else{
						$badgesDetails['bonus'][] =$line;
					}
				}
			}
			
			if(count($badgesDetails)>0){
				$inc=0;
				foreach($badgesDetails as $key=>$badge){
					$badgeArray = array();
					foreach($badge as $k=>$v){
						$is_active 		= ($v['is_found']==1)?'':'_inactive';
						$b_in_image		= SITE_URL."/".substr($v['badges_icon'], 0, -4).$is_active.'.png';
						$b_image		= ($v['is_found']==1)?SITE_URL."/".$v['badges_icon']:$b_in_image;
						$b_badges_type_id=$v['badges_type_id'];			
						
						if($v['is_found']==1){ 
							if( $row['id'] != $oldId ) { $oldId = $row['id']; $q++; }
							
							$badgeArray['badge_id'] = $v['id'];
							$badgeArray['badge_name'] = $v['badge_title'];
							$badgeArray['badge_image'] = $v['badges_icon'];
							
							if($v['subject_id']==""){
								$badgeArray['subject'] = 'Bonus';
							}else{
								$queryName = "SELECT subject FROM ep_wssubjects WHERE id = ".$v['subject_id'];
								$nameObj = $this->dbClassObj->query($queryName);
								$rowName = $this->dbClassObj->fetch_array($nameObj);
								$badgeArray['subject'] = $rowName[0];
							}
							
							$responseArray[$q]['student_id'] = $row['id'];
							$responseArray[$q]['student_name'] = $row['student_name'];
							$responseArray[$q]['badges'][$inc] =  array_merge((array)$responseArray[$q]['badges'][$inc],$badgeArray);
							$responseArray[$q]['total_badges'] = count($responseArray[$q]['badges']);
							$inc++ ;
						} 
					}
				}
			}
				
			return $responseArray[0]['total_badges'];
		}
		// This method is used to browse worksheets
		private function __displayStat($keystage,$subject,$guestQuery,$studentQuery) {
			$data = array();
			
			$lstws	= "select W.keyStage,KS.key_stage,KS.keystage_no,KS.subject_id,WY.year,WY.age,substring(KS.key_stage,3) as kstxt from ep_worksheets as W left join ep_wskeystage as KS on W.keyStage = KS.id left join ep_wsyears as WY on W.year = WY.id where KS.keystage_no='".$keystage."'".$guestQuery.$studentQuery." group by KS.key_stage,WY.year order by KS.key_stage,WY.year";
			
			$rsws111	= $this->dbClassObj->query($lstws);
			$nrows		= $this->dbClassObj->num_rows($rsws111);
			$index = 0;
			
			// Get Subject Id
			$subject_id = '';
			switch ($subject) {
				case 'english' : 
					$subject_id = "2";
					break;
				case 'maths' : 
					$subject_id = "1";
					break;
				case 'science' :
					$subject_id = "3";
					break;
			}
			while($wsres = $this->dbClassObj->fetch_assoc($rsws111)) {
				if( !empty($wsres['year']) && !empty($wsres['age'])){
					$data[$index]['keystage_id'] = $wsres['keystage_no'];
					$data[$index]['year'] = $wsres['year'];
					$data[$index]['age'] = $wsres['age'];
					$cntwsM	= $this->__cntworksheet($keystage,$wsres['year'],$subject_id); // Get  Worksheets
					
					$data[$index]['worksheets'] = $cntwsM[0];
					$data[$index]['topics']     = $cntwsM[1];
					$data[$index]['subject_id']     = $subject_id;
					$index++;
				}
			}
			return $data;
		}
		
		// This method is used to fetch count of worksheets of each subject
		private function __cntworksheet($keystage,$year,$subID) {
			
			$cntfnc	= "select count(W.keyStage) as cnt, 100 as percentage from ep_worksheets as W 
			left join ep_wskeystage as KS on W.keyStage = KS.id left join ep_wsyears as WY on 
			W.year = WY.id where KS.keystage_no='".$keystage."' and KS.subject_id = '".$subID."' and WY.year='".$year."' and W.status='1' and W.organization_id IN (".APP_ORGANIZATION_ID.")  
			UNION 
			select count(distinct(W.topicTags)) as cnt,50 as percentage
			from ep_worksheets as W 
			left join ep_wskeystage as KS on W.keyStage = KS.id 
			left join ep_wsyears as WY 
			on W.year = WY.id where KS.keystage_no='".$keystage."' and KS.subject_id ='".$subID."' and W.organization_id IN (".APP_ORGANIZATION_ID.") and WY.year='".$year."' and W.status='1'";
			$rsfnc	= $this->dbClassObj->query($cntfnc);
			while($resfn	= $this->dbClassObj->fetch_assoc($rsfnc)) {
				$resfn1[]	= $resfn['cnt'];
			}
			return $resfn1;
		}
		
		// This method is used to fetch assigments assigned and completed by student
		private function __count_worksheet_assign($studentId="",$worksheetFlag,$parentId) {
			
			$check = '';
			if(!empty($studentId)) {
				$stQuery	= " and a.studentId='".$studentId."'";
			}
			
			$flagCond = '';
			if($worksheetFlag == 1){
				$flagCond = ' AND a.score_collected BETWEEN 0 AND 10';
				
				// $check = " AND IF( a.worksheetCat =2, a.dateChecked !=  '0000-00-00 00:00:00', a.dateChecked =  '0000-00-00 00:00:00' ) AND a.dateAppeared != '0000-00-00 00:00:00'";
				$check = " AND a.dateAppeared != '0000-00-00 00:00:00'";
			}
			
			// $sql_a  = "select count( a.id ) as totwsheetassign from ep_wsassigned as a left join ep_worksheets as w on a.worksheetId=w.id where is_complete ='".$worksheetFlag."' and w.id is not null ".$stQuery." and a.parentId =".$parentId.$flagCond;
			$sql_a  = "select count( a.id ) as totwsheetassign from ep_wsassigned as a left join ep_worksheets as w on a.worksheetId=w.id where is_complete ='".$worksheetFlag."' and w.id is not null ".$stQuery." and a.parentId =".$parentId.$check;
			
			$reccnt = $this->dbClassObj->query($sql_a);
			$result	= $this->dbClassObj->fetch_assoc($reccnt);
			return $result['totwsheetassign']; 
		}
		
		// This method is used to fetch average acore of a student
		private function __count_score_avg($studentId="",$worksheetFlag,$parentid) {
			// if(!empty($studentId)) { $stQuery	= " and studentId='".$studentId."'"; }
			if(!empty($studentId)) { $stQuery	= " AND C.id = '".$studentId."'"; }
			
			// $sql_a  = "select (sum(score_collected)/sum(score_total)*100)  as avg_total from ep_wsassigned where parentId='".$parentid."' and is_complete='".$worksheetFlag."' ".$stQuery." group by parentId";
			
			$sql_a  = "SELECT ((sum(score_collected)*100)/sum(score_total))  as avg_total, sum( score_collected ) AS score_collected, sum( score_total ) AS score_total, count( A.id ) AS worksheet_total FROM ep_wsassigned A, ep_worksheets B, ep_wssubjects D, ep_wslevel E, ep_wsusers C WHERE A.is_complete = '1' ".$stQuery." AND C.parent_id='".$parentid."' AND A.worksheetId = B.id AND B.subject = D.id AND B.level = E.id AND A.studentId = C.id AND ( B.worksheetCat='".$worksheetFlag."' or ( B.worksheetCat='2' and A.dateChecked !='0000-00-00 00:00:00'))";
			
			$reccnt = $this->dbClassObj->query($sql_a);
			$result	= $this->dbClassObj->fetch_assoc($reccnt);
			return number_format($result['avg_total'],0);
		}
		
		// This method is used to fetch rewards of a student
		private function __count_rewardSet($studentId="",$parentid) {
			if(!empty($studentId)) { $stQuery	= " and student_id='".$studentId."'"; }
			$sql	= "select count(id) as cnt from ep_rewards  where createdBy =".$parentid." ".$stQuery." and points_allocated != points_collected";
			$rs		= $this->dbClassObj->query($sql);
			$num_rows = $this->dbClassObj->num_rows($rs);
			if($num_rows > 0){ $result	= $this->dbClassObj->fetch_assoc($rs); }
			else{ $result = array(); }
			return $result['cnt'];
		}
		
		// This method is used to fetch rewards by parent
		private function __rewardSetByParent($parentid) {
			$std = $i = 0;
			$selectStudents	= "select U.id, U.fname, U.lname, TRIM(CONCAT(U.fname,' ',U.lname)) as student_name, U.auto_assign_year from ep_wsusers U ,ep_subscription_student S where U.user_type='student' and S.student_id=U.id and S.is_current='1' and U.parent_id= ".$parentid;
			$selectStudents = $this->dbClassObj->query($selectStudents);
			while($student = $this->dbClassObj->fetch_assoc($selectStudents)){
				$i = 0;
				if(empty($student['student_name'])){ $student['student_name'] = $student['fname'].' '.$student['lname']; }
				$arr[$std]['id'] = $student['id'];
				$arr[$std]['student_name'] = $student['student_name'];
				$sql	= "select rw.*,rwtyp.reward_name,rwtyp.reward_icon,rwtyp.reward_white_icon,rwtyp.reward_large_icon from ep_rewards as rw LEFT JOIN ep_rewards_type as rwtyp ON rwtyp.id = rw.reward_type_id where rw.createdBy =".$parentid." and rw.student_id=".$student['id']." ORDER BY rw.date_allocated ASC";
				// $sql	= "select rw.*,rwtyp.reward_name,rwtyp.reward_icon,rwtyp.reward_white_icon,rwtyp.reward_large_icon from ep_rewards as rw LEFT JOIN ep_rewards_type as rwtyp ON rwtyp.id = rw.reward_type_id where rw.createdBy =".$parentid." and reward_status = 'Locked' and rw.student_id=".$student['id']." ORDER BY rw.id DESC";
				// $sql	= "select * from ep_rewards  where createdBy =".$parentid." and student_id='".$student['id']."'";
				$rs		= $this->dbClassObj->query($sql);
				while($result	= $this->dbClassObj->fetch_assoc($rs)){
					$result['reward'] = stripslashes(urldecode($result['reward']));
					$arr[$std]['rewards'][$i] = $result;
					$i++;
				}
				if(empty($arr)){ $arr[$std]['rewards'] = array(); }
				$std++;
			}
			return $arr;
		}
		
		// This method is used to fetch rewards by student
		private function __rewardSetByStudent($parentid,$studentid) {
			$i = 0; $arr = array();
			$sql	= $this->dbClassObj->query("select rw.*,rwtyp.reward_name,rwtyp.reward_icon,rwtyp.reward_white_icon,rwtyp.reward_large_icon from ep_rewards as rw LEFT JOIN ep_rewards_type as rwtyp ON rwtyp.id = rw.reward_type_id where rw.createdBy =".$parentid." and reward_status = 'Locked' and rw.student_id=".$studentid." ORDER BY rw.date_allocated ASC LIMIT 0,10");
			while($result	= $this->dbClassObj->fetch_assoc($sql)){
				$result['reward'] = stripslashes(urldecode($result['reward']));
				$arr[$i] = $result;
				$i++;
			}
			if(empty($arr)){ $arr = array(); }
			return $arr;
		}

		//Count the number of worksheets
		private function __worksheetCount($topicID,$ks_yr="",$type,$subID="",$keyStageID)
		{
			if($type == 'topic')
			{
				$sql	= "select count(topic_id) as totworksheet from ep_worksheets where subject=".$subID." AND status='1' AND topic_id=".(int)$topicID." AND year = ".$ks_yr." AND keyStage = ".$keyStageID;
				
			} elseif($type == 'nc') 
			{
				$sql	= "select count(objective) as totworksheet from ep_worksheets where status='1' AND subject='".$subID."' AND objective='".(int)$topicID."' AND year = ".$ks_yr." AND keyStage = ".$keyStageID;
			}
			$rs		= $this->dbClassObj->query($sql); // or die(mysql_error());
			$result	= $this->dbClassObj->fetch_assoc($rs);
			return $result['totworksheet'];
		}
		
		public function update_student_auto_assign(){
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
					//$std_id = $this->getUidFromUserToken($token);
					
					$data = file_get_contents('php://input');
					$data = json_decode($data);
					$std_id = $data->student_id;
					
					$sql_query="select id,user_name,auto_assign_y_n,status from ep_wsusers where status='1' and id='".$std_id."' ";
					$sql_result=$this->dbClassObj->query($sql_query);
					$sql_row=$this->dbClassObj->fetch_assoc($sql_result);

					if(!empty($sql_row['id'])){
						if($sql_row['auto_assign_y_n'] == '1')
						{
							$sqlAa	= "update ep_wsusers set auto_assign_y_n='0' where id='".$std_id."'";
							$rsAa	= $this->dbClassObj->query($sqlAa);
						} else {
							$sqlAa	= "update ep_wsusers set auto_assign_y_n='1' where id='".$std_id."'";
							$rsAa	= $this->dbClassObj->query($sqlAa);
						}
						$response = array('error' => STATUS_OK,'message' => "Success",'response' => 'Success');
					}
				}else {
						if($this->isPrivate($token)) {
							// Error message of "Token has been expired"
							$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
						}
						else{ 
							// Error message of "Token has been expired"
							$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
						}
					}
				
			}else{
						$response = array('error' => ERROR_EXISTS,'message' => "Invalid Post Data");
					}
				echo json_encode($response);
			
		}
		
		public function update_student_self_assign(){
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
					//$std_id = $this->getUidFromUserToken($token);
					
					$data = file_get_contents('php://input');
					$data = json_decode($data);
					$std_id = $data->student_id;

					$sql_query="select id,user_name,self_assign_y_n,status from ep_wsusers where status='1' and id='".$std_id."' ";
					$sql_result=$this->dbClassObj->query($sql_query);
					$sql_row=$this->dbClassObj->fetch_assoc($sql_result);
					
					if(!empty($sql_row['id'])){
						if($sql_row['self_assign_y_n'] == '1')
						{
							$sqlAa	= "update ep_wsusers set self_assign_y_n='0' where id='".$std_id."'";
							$rsAa	= $this->dbClassObj->query($sqlAa);
						} else {
							$sqlAa	= "update ep_wsusers set self_assign_y_n='1' where id='".$std_id."'";
							$rsAa	= $this->dbClassObj->query($sqlAa);
						}
						$response = array('error' => STATUS_OK,'message' => "Success",'response' => 'Success');
					}
				}else {
						if($this->isPrivate($token)) {
							// Error message of "Token has been expired"
							$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
						}
						else{ 
							// Error message of "Token has been expired"
							$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
						}
					}
			}
			else{
						$response = array('error' => ERROR_EXISTS,'message' => "Invalid Post Data");
					}
				echo json_encode($response);
			
		}
		
		
		public function topiclist(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
					$data = file_get_contents('php://input');
					$data = json_decode($data);
					if (!empty($data)) {
						// Subject of worksheet
						$subID = $data->subject_id;
						$year = $data->year;
						$keyStageId = $data->keystage_id;
						$userType = $data->userType;
						
						//Filter KeyStage
						$actualKSId = "SELECT id FROM ep_wskeystage WHERE keystage_no = ".$keyStageId." AND subject_id = ".$subID." LIMIT 0,1";
						$ksid = $this->dbClassObj->query($actualKSId);
						while($ksObj = $this->dbClassObj->fetch_assoc($ksid)) { $ksID = $ksObj['id'];}
						
						//Query to find the actual ID of the year
						$actualYearId = "SELECT id FROM ep_wsyears WHERE keystage_id = ".$ksID." AND year = ".$year." LIMIT 0,1";
						$akid = $this->dbClassObj->query($actualYearId);
						while($yearObj = $this->dbClassObj->fetch_assoc($akid)) { $yearID = $yearObj['id'];}
						
						//Query to find the list of topics acc to 'yearID' , 'subjectID' and 'keyStageID'
						// $ws_topic = "SELECT distinct(topic_id) as id,topicTags FROM ep_worksheets WHERE year = ".$yearID." AND subject = ".(int)($subID)." AND keyStage = ".$ksID.$guestQuery." order by topicTags";
						$ws_topic = "SELECT distinct(topic_id) as id,topicTags FROM ep_worksheets WHERE year = ".$yearID." AND subject = ".(int)($subID)." AND keyStage = ".$ksID." order by topicTags";
						
						$res = $this->dbClassObj->query($ws_topic);
						$worksheets = array();
						$i = 0;
						
						while($wsres = $this->dbClassObj->fetch_assoc($res)) {
							
							//Call function to count the total worksheets in the particular topic.
							$count  = $this->__worksheetCount($wsres['id'],$yearID,"topic",$subID,$ksID);

							if( $count != 0 ){
								//Creating the array $worksheets.
								$worksheets[$i]['topicid'] = $wsres['id'];
								$worksheets[$i]['topicname'] = $wsres['topicTags'];
								$worksheets[$i]['worksheets'] = $count;
								$i++;
							}
						}
						$response = array('error' => STATUS_OK,'message' => 'Success','response' => $worksheets);
					} else {
						$response = array('error' => ERROR_EXISTS,'message' => 'Error','response' => 'Null input receiving');
					}
				}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
				echo json_encode($response);
			}
		}
		
	public function worksheetslist(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
				
				$studentId = $this->getUidFromUserToken($token);
				$userType = $this->getUserTypeFromUid($studentId);
						
				//Files included.
				require_once('../../includes/worksheet_function.php');
				
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				if (!empty($data)) {
					
					// Subject of worksheet
					$topicID = $data->topic_id;
					$keyStageID = $data->keystage_id;
					$subID = $data->subject_id;
					$year = $data->year;
					$nc = $data->nc;
					//$userType = $data->userType;

					if(isset($data->userId)){ $studentId = $data->userId; }
					
					if($userType == 'guest'){$userType=" ORDER BY is_worksheet_on_ios DESC";}else{$userType="" ;}
					// if($userType == 'guest'){$userType=" AND is_worksheet_on_ios = '2'";}else{$userType="" ;}
					
					//Filter KeyStage
					$actualKSId = "SELECT id FROM ep_wskeystage WHERE keystage_no = ".$keyStageID." AND subject_id = ".$subID." LIMIT 0,1";
					$ksid = $this->dbClassObj->query($actualKSId);
					while($ksObj = $this->dbClassObj->fetch_assoc($ksid)) { $ksID = $ksObj['id'];}
					
					//Query to find the actual ID of the year
					$yearQuery = "SELECT id FROM ep_wsyears WHERE keystage_id = ".$ksID." AND year = ".$year." LIMIT 0,1";
					$yearObj = $this->dbClassObj->query($yearQuery);
					$yearID = $this->dbClassObj->fetch_assoc($yearObj);
					
					if(!empty($nc)){
						//Fetching the worksheets
						$wslist	= "SELECT id, worksheetName, topicTags, level, objective, subject, year, keyStage, ksu, level, worksheet_intro_video , type, is_worksheet_on_ios, dateCreated FROM ep_worksheets WHERE objective = ".(int)$topicID." AND status = '1' AND subject = ".$subID." AND type != 'null' AND year = ".$yearID['id']." AND keyStage = ".$ksID.$userType;
					}else{
						//Fetching the worksheets
						$wslist	= "SELECT id, worksheetName, topicTags, level, objective, subject, year, keyStage, ksu, level, worksheet_intro_video , type, is_worksheet_on_ios, dateCreated FROM ep_worksheets WHERE topic_id = ".(int)$topicID." AND status = '1' AND subject = ".$subID." AND year = ".$yearID['id']." AND keyStage = ".$ksID.$userType;
					}
					
					$charset="SET CHARACTER SET utf8";
					$this->dbClassObj->query($charset);
					
					$wsObj = $this->dbClassObj->query($wslist);
					
					$i=0;
					$responseArray = array();
					while( $wsRow = $this->dbClassObj->fetch_assoc($wsObj) ){
						//counteverything function is called which is defined in the includes/worksheet_function.php
						//$wsRow['worksheetName'] = utf8_decode($wsRow['worksheetName']);
						
						if(isset($data->userId)){ 
							//Is assigned or not key.
							$isAssigned = $this->_isWorksheetAssigned($studentId, $wsRow['id']);
							$wsRow['is_assigned'] = $isAssigned;
						}
						
						$totalQues	= counteverything('id','ep_questions',$wsRow['id']);
						
						$getUserType = $this->_userType($data->userId);
						if($getUserType == 'parent'){
							$wsRow['assignmentCount'] = $this->_getAssignmentCount($wsRow['id'],$data->userId);
						}
						
						$responseArray[$i] = array_merge((array)$responseArray[$i],$wsRow);
						$responseArray[$i]['questions'] = $totalQues;
						$i++;
					}
					
					if(!empty($responseArray)){
						$response = array('error' => STATUS_OK,'message' => 'Success','response' => $responseArray);
						echo json_encode($response);
					}else{
						$response = array('error' => ERROR_EXISTS,'message' => 'Worksheets not exists','response' => array());
						echo json_encode($response);
					}
				}
			}
			else {
				if($this->isPrivate($token)) {
					// Error message of "Token has been expired"
					$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					echo json_encode($response);
				}
				else{ 
					// Error message of "Token has been expired"
					$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					echo json_encode($response);
				}
			}
			}else {
				$response = array( 'error' => ERROR_EXISTS, 'message' => POST_ERROR_MSG , 'response' => NULL); 
			}
		}
		
		public function _isWorksheetAssigned($studentId, $worksheetId){
			$select_query = $this->dbClassObj->query("SELECT is_complete FROM `ep_wsassigned` WHERE studentId = ".$studentId." AND worksheetId =".$worksheetId." ORDER BY id DESC LIMIT 0,1");
			//~ $select_query = mysql_fetch_assoc($select_query);
			
			if($this->dbClassObj->num_rows($select_query) > 0){
				$select_query = $this->dbClassObj->fetch_assoc($select_query);
				$isCompleted = $select_query['is_complete'];
				switch($isCompleted){
					case 0:
						return 1;
						break;
					case 1:
						return 0;
						break;
					default:
						return 0;
				}
			}else{
				return 0;
			}
		}
		
		public function _getAssignmentCount($worksheetId, $parentId){
			$count = 0;
			$selectQuery = $this->dbClassObj->query("SELECT U.id,U.fname,U.lname, TRIM(CONCAT(U.fname,' ',U.lname)) as fullname,U.user_name,U.auto_assign_y_n, U.self_assign_y_n, S.student_id FROM ep_wsusers U ,ep_subscription_student S WHERE U.user_type='student' AND S.student_id=U.id AND S.is_current='1' AND U.parent_id= '".$parentId."'");
			if($this->dbClassObj->num_rows($selectQuery)>0){
				while($student = $this->dbClassObj->fetch_assoc($selectQuery)){
					$assignStatus = $this->_isWorksheetAssigned($student['id'], $worksheetId);
					$count = $count + $assignStatus;
				}
				return $count;
			}else{
				return 0;
			}
		}
		
		public function ncWorksheetCount($topicID,$ks_yr="",$type,$subID="")
		{	$year = '';
			if(!empty($ks_yr)){
				$year = ' AND year = '.$ks_yr;
			}
			if($type == 'topic')
			{
				$sql	= "select count(topic_id) as totworksheet from ep_worksheets where subject='".$subID."' AND status='1' AND topic_id='".(int)$topicID."' ".$year."";
			} elseif($type == 'nc') 
			{
				$sql	= "select count(objective) as totworksheet from ep_worksheets where status='1' AND subject='".$subID."' AND objective='".(int)$topicID."' ".$year."";
			}
			$rs		= $this->dbClassObj->query($sql); // or die(mysql_error());
			$result	= $this->dbClassObj->fetch_assoc($rs);
			return $result['totworksheet'];
		}

		//Worksheet list by national curriculum.
		public function ncworksheetslist(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				if (!empty($data)) {
					// Subject of worksheet
					$keyStageID = $data->keystage_id;
					$subID = $data->subject_id;
					$year = $data->year;
					
					//Filter KeyStage
					$actualKSId = "SELECT id FROM ep_wskeystage WHERE keystage_no = ".$keyStageID." AND subject_id = ".$subID." LIMIT 0,1";
					$ksid = $this->dbClassObj->query($actualKSId);
					while($ksObj = $this->dbClassObj->fetch_assoc($ksid)) { $ksID = $ksObj['id'];}
					
					//Query to find the actual ID of the year
					$yearQuery = "SELECT id FROM ep_wsyears WHERE keystage_id = ".$ksID." AND year = ".$year." LIMIT 0,1";
					$yearObj = $this->dbClassObj->query($yearQuery);
					$yearID = $this->dbClassObj->fetch_assoc($yearObj);
					
					$wslist = "select A.id,A.ksu,A.year_id as year ,B.keystage_id as keyStage,C.subject_id from ep_wsksu as A,ep_wsyears AS B,ep_wskeystage AS C where A.year_id=B.id and B.keystage_id=C.id and C.subject_id=".(int)($subID)." AND A.year_id=".$yearID['id']." AND B.keystage_id=".$ksID;
					
					$we_rs = $this->dbClassObj->query($wslist);
					$responseArray = array();
					$inc=0;
					while($ws_result	= $this->dbClassObj->fetch_assoc($we_rs)) {
						$i=0;
						$query2 = "SELECT * FROM `ep_wsobjective` WHERE `ksu_id` =".$ws_result['id']." AND status='1'";
						
						$querObj = $this->dbClassObj->query($query2);
						while($row = $this->dbClassObj->fetch_assoc($querObj)){
							$count  = $this->ncWorksheetCount($row['id'],$yearID['id'],"nc",$subID);
							$row['worksheets'] = $count;
							
							if(!empty($count)){
								$responseArray[$inc]['parent_topic'] = $ws_result['ksu'];
								$responseArray[$inc]['child_topics'][$i] = array_merge((array)$responseArray[$inc]['child_topics'][$i],$row);
								$i++;
							}
						}if(!empty($count)){$inc++;}
					}
					if(!empty($responseArray)){
						$response = array('error' => STATUS_OK,'message' => 'Success','response' => $responseArray);
						// echo json_encode($response);
					}else{
						$response = array('error' => ERROR_EXISTS,'message' => 'Error','response' => 'Topics are not available');
						//echo json_encode($response);
					}
				}
			}
			else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
				echo json_encode($response);
			}
			
		}

		//Worksheets assigned to the students on the basis of Parent Token
		public function assignedWorksheets() {
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
					$parent_id = $this->getUidFromUserToken($token);
					$data = file_get_contents('php://input');
					//$data = json_decode($data);
					
					if (isset($parent_id) && ($parent_id != "")) {
						$parentID = $parent_id;
						
						$charset="SET CHARACTER SET utf8";
						$this->dbClassObj->query($charset);
						
						//PROCESS: for getting the student details
						$responseArrErr = array(); $e=0;
						$query_students = $this->dbClassObj->query("select U.id,U.fname,U.lname, TRIM(CONCAT(U.fname,' ',U.lname)) as fullname,U.user_name,U.auto_assign_y_n, U.self_assign_y_n, S.student_id from ep_wsusers U ,ep_subscription_student S where U.user_type='student' and S.student_id=U.id and S.is_current='1' and U.parent_id= '".$parentID."'");
						while($ids = $this->dbClassObj->fetch_assoc($query_students)){ 
							//SET: an array for the student names
							if(empty($ids['studentName'])){ $ids['studentName'] = $ids['fname'].' '.$ids['lname']; }
							$responseArrErr[$e]['student_name'] = $ids['studentName'];
							$responseArrErr[$e]['student_id'] = $ids['id'];
							$e++;
						}
						
						//PROCESS: for the getting assigned worksheets list
						$query = "
							SELECT wa.id as assigned_id, wu.fname, wu.lname, ws.id, ws.worksheetName, wa.studentId, CONCAT( wu.fname,' ',wu.lname ) as studentName, sb.subject, ws.topicTags, ws.level, yr.year, wa.dateAssigned, (SELECT count( id ) FROM ep_questions WHERE worksheetId = ws.id) AS points
							FROM ep_wsusers AS wu
							LEFT JOIN ep_wsassigned AS wa ON wa.studentId = wu.id
							LEFT JOIN ep_worksheets AS ws ON ws.id = wa.worksheetId
							LEFT JOIN ep_wssubjects AS sb ON ws.subject = sb.id
							LEFT JOIN ep_wsyears AS yr ON yr.id = ws.year
							WHERE wu.parent_id =".$parentID."
							AND wa.is_complete ='0'
							ORDER BY wu.id ASC,wa.dateAssigned DESC
						";
						$queryObj = $this->dbClassObj->query($query);
						
						$i=0; $responseArray = array(); $oldId=0; $j=-1;
						while( $row = $this->dbClassObj->fetch_assoc($queryObj)){
						
							if(empty($row['studentName'])){ $row['studentName'] = $row['fname'].' '.$row['lname']; }
						
							$student_id = $row['studentId'];
							if( $student_id != $oldId ) { $oldId = $student_id; $j++; $i=0; }
							$responseArray[$j]['student_name'] = $row['studentName'];
							$responseArray[$j]['student_id'] = $row['studentId'];
							
							unset($row['studentId']);
							unset($row['studentName']);

							//~ $row['worksheetName'] = utf8_decode($row['worksheetName']);
							//$row['worksheetName'] = htmlentities($row['worksheetName']);
							
							$row['curriculum_topic'] = $this->_getCurriculumTopicByWorksheet($row['id']);
							$row['curriculum_subtopic'] = $this->_getCurriculumSubTopicByWorksheet($row['id']);

							$responseArray[$j]['worksheets'][$i] = array_merge((array)$responseArray[$j]['worksheets'][$i],$row);
							$i++;
							
						}
						
						if(!empty($responseArray)){
							$response = array( 'error' => STATUS_OK , 'message' => 'Assigned worksheets list' , 'response' => $responseArray);
						}else{
							$response = array( 'error' => STATUS_OK , 'message' => 'Worksheets are not assigned' , 'response' => $responseArrErr);
						}
					}
					else{
						$response = array( 'error' => ERROR_EXISTS , 'message' => 'User Token is required' , 'response' => NULL);
					}
				}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response =  array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}
			else {
				$response = array( 'error' => ERROR_EXISTS , 'message' => 'OOPS! Something went wrong.' , 'response' => NULL);
			}
			echo json_encode($response);
		} 

		//Worksheets completed by the students on the basis of Parent Token
		public function completedWorksheets(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parentID = $this->getUidFromUserToken($token);
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				//$parentID = $data->parent_id;
				//$offset = $data->offset;
				
				//if(empty($offset)){$offset = 0;	}
				
				$student_arr = ""; $responseArrErr = array(); $e=0;
				//Getting the subscribed students
				
				// $query_students = mysql_query("SELECT U.fname, U.lname, P.subs_status AS p_subs_status, S.* FROM ep_wsusers AS U LEFT JOIN ep_subscription_parent AS P ON P.parent_id = U.id LEFT JOIN ep_subscription_student AS S ON S.student_id = U.id WHERE U.parent_id =".$parentID." AND ( S.is_current = '1' OR S.is_addedby_parent_account = '1' ) AND U.delete_status = '0' AND S.is_drafted = '0'");
				
				$charset="SET CHARACTER SET utf8";
				$this->dbClassObj->query($charset);
				
				$query_students = $this->dbClassObj->query("select U.id,U.fname,U.lname, TRIM(CONCAT(U.fname,' ',U.lname)) as fullname,U.user_name,U.auto_assign_y_n, U.self_assign_y_n, S.student_id from ep_wsusers U ,ep_subscription_student S where U.user_type='student' and S.student_id=U.id and S.is_current='1' and U.parent_id= '".$parentID."'");
				
				while($ids = $this->dbClassObj->fetch_assoc($query_students)){ 
					$student_arr .= "'".$ids['student_id']."',";
					
					//SET: an array for the student names
					if(empty($ids['studentName'])){ $ids['studentName'] = $ids['fname'].' '.$ids['lname']; }
					$responseArrErr[$e]['student_name'] = $ids['studentName'];
					$responseArrErr[$e]['student_id'] = $ids['id'];
					$e++;
					
				}
				$student_arr = substr($student_arr,0,-1);
				
				if (isset($parentID)&&($parentID!="")) {
					//$parentID = $data->parent_id;
					
					
					
					
					
					
					$student_arr = explode(',',$student_arr);
					$responseArray = array(); $oldId=0; $j=0;
					foreach($student_arr as $student){
					
					
					
						$i=0;
						
						$query = " SELECT ws.id,wa.id as assignment_id, ws.worksheetName, ws.worksheetCat, wa.score_collected, wa.score_total, wa.studentId, wu.fname, wu.lname, wa.is_complete, CONCAT( wu.fname,' ',wu.lname ) as studentName, sb.subject, ws.topicTags, ws.level, yr.year, wa.dateAssigned, wa.dateAppeared FROM ep_wsusers AS wu LEFT JOIN ep_wsassigned AS wa ON wa.studentId = wu.id LEFT JOIN ep_worksheets AS ws ON ws.id = wa.worksheetId LEFT JOIN ep_wssubjects AS sb ON ws.subject = sb.id LEFT JOIN ep_wsyears AS yr ON yr.id = ws.year WHERE wu.parent_id =".$parentID." AND wa.studentId = ".$student." AND wa.is_complete ='1' AND wa.dateAppeared !='0000-00-00 00:00:00' ORDER BY wa.dateAppeared DESC";
						$queryObj = $this->dbClassObj->query($query);
						
						
						while( $row = $this->dbClassObj->fetch_assoc($queryObj)){
							
							if(empty($row['studentName'])){ $row['studentName'] = $row['fname'].' '.$row['lname']; }
							
							//~ $row['worksheetName'] = utf8_decode($row['worksheetName']);
							
							$student_id = $row['studentId'];
							//if( $student_id != $oldId ) { $oldId = $student_id; $j++; $i=0; }
							$responseArray[$j]['student_name'] = $row['studentName'];
							$responseArray[$j]['student_id'] = $row['studentId'];
							
							unset($row['studentId']);
							unset($row['studentName']);
							
							//SET: isChecked value
							$row['isChecked'] = 0;if($row['worksheetCat']=='1' || ($row['worksheetCat']=='2' && $row['dateChecked']!='0000-00-00 00:00:00')){$row['isChecked'] = 1;}else{$row['isChecked'] = 0;}

							$responseArray[$j]['worksheets'][$i] = array_merge((array)$responseArray[$j]['worksheets'][$i],$row);
							$i++;
						}
						$j++;
					
					}
					
					
					
					if(!empty($responseArray)){ $response = array( 'error' => STATUS_OK , 'message' => 'Completed worksheets list' , 'response' => $responseArray); }
					else{ $response = array( 'error' => STATUS_OK , 'message' => 'Your student hasn\'t completed any worksheets yet.' , 'response' => $responseArrErr); }
				}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Parent Token is required' , 'response' => NULL); }
			}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}else{ $response = array( 'error' => ERROR_EXISTS, 'message' => POST_ERROR_MSG , 'response' => NULL); }
			echo json_encode($response);
		}
		
		//Badges assigned to the students
		public function studentBadges(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parentId = $this->getUidFromUserToken($token);
			
				
				if (isset($parentId)) {
					//$parentId = $data->parent_id;

					/* $year = $data->year;
					//Listing the years
					$yearsList = '';
					$queryYear = "SELECT id FROM ep_wsyears WHERE year = ".$year;
					$queryObj = mysql_query($queryYear);
					while($row = mysql_fetch_array($queryObj)){ $yearsList .= $row['id'].','; }
					$yearsList =  substr($yearsList,0,-1); 
					
					//Parameter in query after 12th line
					and BC.year_id in(".$yearsList.") 
					*/
					
					$oldId=$q=-1;
					$checkUser = $this->dbClassObj->query("SELECT user_type FROM ep_wsusers WHERE id = ".$parentId);
					$checkUserResult = $this->dbClassObj->fetch_assoc($checkUser);
					if($checkUserResult['user_type'] == 'student'){	
						$condition = " and S.student_id=U.id and U.user_type = 'student' and U.parent_id= ".$parentId ;
					}else{
						$condition = "and S.parent_id=U.id and U.user_type = 'parent' and U.id= ".$parentId;
						//In case of parent student id is send 
						$json_data = file_get_contents('php://input');
						$json_data = json_decode($json_data);
						$student_id = $json_data->student_id;
					}
					$queryYear = "select U.id as id,U.fname,U.lname, TRIM(CONCAT(U.fname,' ',U.lname)) as student_name,U.user_name,U.auto_assign_year,U.auto_assign_y_n, U.self_assign_y_n, S.student_id from ep_wsusers U ,ep_subscription_student S where S.is_current='1' ".$condition;
					$queryObj = $this->dbClassObj->query($queryYear);
					
					while($row = $this->dbClassObj->fetch_array($queryObj)){ 
					
						// $badges_sql = "select T.* from (select BC.*, AB.id as sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found from ep_badges_condition as BC left join ep_badges_assigned as AB on BC.id=AB.assigned_badges_id and AB.student_id=".$row['id']." where BC.badges_type_id in (1,2,3) and BC.badges_id IN(1,2,3) and BC.status='1' and BC.subject_id in (1,2,3) AND AB.is_found IS NOT NULL AND AB.is_found='1' union select BC.*, AB.id as sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found from ep_badges_condition as BC left join ep_badges_assigned as AB on BC.id=AB.assigned_badges_id and AB.student_id=".$row['id']." where BC.badges_type_id not in (1,2,3) and BC.badges_id IN(1,2,3) and BC.status='1' AND AB.is_found IS NOT NULL AND AB.is_found='1' ORDER BY id DESC)as T GROUP BY badge_title order by T.badges_type_id asc";
						if($checkUserResult['user_type'] == 'student'){
							$student_id = $row['id'] ;
						}
						$badges_sql = "SELECT T.* FROM (SELECT BC.*, AB.id AS sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found  FROM ep_badges_condition AS BC LEFT JOIN ep_badges_assigned AS AB ON BC.id=AB.assigned_badges_id WHERE BC.badges_type_id IN (1,2,3) AND AB.student_id=".$student_id." AND BC.badges_id IN(1,2,3) AND BC.status='1' AND BC.subject_id IN (1,2,3) AND AB.is_found IS NOT NULL AND AB.is_found='1' UNION SELECT BC.*, AB.id AS sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found FROM ep_badges_condition AS BC LEFT JOIN ep_badges_assigned AS AB ON BC.id=AB.assigned_badges_id WHERE BC.badges_type_id NOT IN (1,2,3) AND AB.student_id=".$student_id." AND BC.badges_id IN(1,2,3) AND BC.status='1' AND AB.is_found IS NOT NULL AND AB.is_found='1' ORDER BY badges_id DESC)AS T GROUP BY badge_title, badges_type, topic_id,  badges_subjects, subject_id ORDER BY T.badges_type_id ASC";
						
						
						// year_id,
						
						// GROUP BY badge_title
						// $badges_sql = "SELECT T . * FROM ( SELECT BC . * , AB.id AS sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found FROM ep_badges_condition AS BC LEFT JOIN ep_badges_assigned AS AB ON BC.id = AB.assigned_badges_id AND AB.student_id = '".$row['id']."' WHERE BC.badges_type_id IN ( 1, 2, 3 ) AND BC.badges_id = '1' AND BC.status = '1' AND BC.year_id IN ( ".$row['auto_assign_year']." ) AND BC.subject_id IN ( 1, 2, 3 ) UNION SELECT BC . * , AB.id AS sb_id, AB.completed_description, AB.assigned_badges_id, AB.is_found FROM ep_badges_condition AS BC LEFT JOIN ep_badges_assigned AS AB ON BC.id = AB.assigned_badges_id AND AB.student_id = '".$row['id']."' WHERE BC.badges_type_id NOT IN ( 1, 2, 3 ) AND BC.badges_id = '1' AND BC.status = '1' ORDER BY id DESC) AS T ORDER BY T.badges_type_id ASC ";
						
						// echo $badges_sql; exit;
						
						$conditonBadge	= array(1,2,3);
						$badgesDetails		= array();
						$badgesDetailsResult= $this->dbClassObj->query($badges_sql);
						if($this->dbClassObj->num_rows($badgesDetailsResult)>0){
							while($line=$this->dbClassObj->fetch_assoc($badgesDetailsResult)){
								$bd_detail_type_id =$line['badges_type_id'];
								if(in_array($bd_detail_type_id,$conditonBadge)){
									$dbtype=$line['badges_type'];
									$badgesDetails[$dbtype][]=$line;
								}else{
									$badgesDetails['bonus'][] =$line;
								}
							}
						}else{
							$q++;
							$responseArray[$q]['student_id'] = $row['id'];
							$responseArray[$q]['student_name'] = $row['student_name'];
							$responseArray[$q]['badges'] =  array();
							$badgesDetails = 0;
						}

						if(count($badgesDetails) > 0){
							$inc=0;
							foreach($badgesDetails as $key=>$badge){
								$badgeArray = array();
								foreach($badge as $k=>$v){
									$is_active 		= ($v['is_found']==1)?'':'_inactive';
									$b_in_image		= SITE_URL."/".substr($v['badges_icon'], 0, -4).$is_active.'.png';
									$b_image		= ($v['is_found']==1)?SITE_URL."/".$v['badges_icon']:$b_in_image;
									$b_badges_type_id=$v['badges_type_id'];			
									
									
									
									// if($v['is_found']=='1'){ 
									
									
										if( $row['id'] != $oldId ) { $oldId = $row['id']; $q++; }
										//print_r($v);
										
										
										
										// $no_badge_type	=array(1,2,3,4,5,6,10,11);
										$no_badge_type	=array(2,3,4,5,6,10,11);
										// $no_badge_type	=array(4,5,6,10,11);
										$badges_txt		= '';
										$subjects=array('1'=>'maths','2'=>'english','3'=>'science');
										$subject_id 	= $v['subject_id'];
										$badges_subjects= ($v['badges_type_id']==1)?$subjects[$subject_id]:$v['badges_subjects'];
										// $badges_txt 	= ($v['is_found']==1)?' - '.$v['badges']:$badges_txt;
										$badges_txt 	= ' - '.$v['badges'];
										$badges_title	= (in_array($v['badges_type_id'],$conditonBadge))?$badges_subjects.' '.$v['badge_title']:$badges_subjects;
										$badges_txt		= (in_array($v['id'],$no_badge_type))?'':$badges_txt;
										$b_description	= ucwords($badges_title).$badges_txt;
										
										$badgeArray['badge_id'] = $v['id'];
										//$badgeArray['badge_name'] = $v['badge_title'];
										$badgeArray['badge_name'] = $b_description;
										$badgeArray['badge_image'] = $v['badges_icon'];
										if(!empty($v['completed_description'])){ $badgeArray['badge_description'] = $v['completed_description']; }
										else{ $badgeArray['badge_description'] = ''; }
										
										if($v['subject_id']==""){
											$badgeArray['subject'] = 'Bonus';
										}else{
											$queryName = "SELECT subject FROM ep_wssubjects WHERE id = ".$v['subject_id'];
											$nameObj = $this->dbClassObj->query($queryName);
											$rowName = $this->dbClassObj->fetch_array($nameObj);
											$badgeArray['subject'] = $rowName[0];
										}
										
										
										//print_r($badgeArray);
										
										$responseArray[$q]['student_id'] = $row['id'];
										$responseArray[$q]['student_name'] = $row['student_name'];
										$responseArray[$q]['badges'][$inc] =  array_merge((array)$responseArray[$q]['badges'][$inc],$badgeArray);
										
										// print_r($responseArray[$q]);
										$inc++ ;
									// } 
								}
							}
						}
					}
					
					if(!empty($responseArray)){ 
					$response = array( 'error' => STATUS_OK , 'message' => 'Badges List' , 'response' => $responseArray ); }
					else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'No badges found'); }
				}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Invalid post data'); }
			}
			else {
						if($this->isPrivate($token)) {
							// Error message of "Token has been expired"
							$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
						}
						else{ 
							// Error message of "Token has been expired"
							$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
						}
					}
			}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'OOPS!Something went wrong.'); }
			echo json_encode($response);
		}
		
		//Search webservice
		public function seacrhWorksheet(){
			$token = $this->getToken();
			if($token && $this->validateToken($token) == TRUE ) {
				$studentId = $this->getUidFromUserToken($token);
				$userType = $this->getUserTypeFromUid($studentId);
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				
				$search = $data->search_text;
				$subject_id = $data->subject_id;
				$keystage = $data->keystage;
				$years = $data->years;
				$level = $data->level;
				$offset = $data->offset;
				//$userType = $data->userType;
				//$studentId = $data->userId;
				$studentQuery = '';
				
				$charset="SET CHARACTER SET utf8";
				$this->dbClassObj->query($charset);
				
				if($userType == 'guest'){
					$userType=" ORDER BY is_worksheet_on_ios DESC";
				}else if($userType == 'student')
				{
					if(!empty($studentId)){
						$query_select = $this->dbClassObj->query("SELECT student_year FROM ep_wsusers WHERE id = ".$studentId);
						$user_data = $this->dbClassObj->fetch_assoc($query_select);
						$studentYear = $user_data['student_year'];

						if(!empty($studentYear)){
							$yearQuery = "SELECT id FROM ep_wsyears WHERE year = ".$studentYear;
							$yearObj = $this->dbClassObj->query($yearQuery);
							
							$keys='';
							while($yearId = $this->dbClassObj->fetch_assoc($yearObj)){
								$keys.= $yearId['id'].',';
							}
							$keys = substr($keys,0,-1);
							$studentQuery = ' AND year IN ('.$keys.')';
						}
					}else{$studentQuery ='';}
				}
				else{
					$userType="" ;
				}
				
				$subMain = $subMain = $keyMain = $yearMain = $levelMain = '';
							
				//Fetching the subject ID
				if(!empty($search)){ $searchMain = " AND (worksheetName LIKE '%".$search."%' OR topicTags LIKE '%".$search."%') "; }
				
				if(!empty($subject_id)){ $subMain = " AND subject = ".$subject_id; }
				
				//Fetching the keystage ID
				if(!empty($keystage)){
					//$keyQuery = "SELECT id FROM ep_wskeystage WHERE keystage_no = ".$keystage." AND subject_id = ".$subject_id;
					$keyQuery = "SELECT id FROM ep_wskeystage WHERE keystage_no = ".$keystage;
					$keyObj = $this->dbClassObj->query($keyQuery);
					$keys='';
					while($keyId = $this->dbClassObj->fetch_assoc($keyObj)){
						$keys.= $keyId['id'].',';
					}
					$keys = substr($keys,0,-1);
					$keyMain = ' AND keyStage IN ('.$keys.')';
				}
							
				//Fetching the years ID
				if(empty($studentQuery))
				{
					if(!empty($years)){
						//$yearQuery = "SELECT id FROM ep_wsyears WHERE year = ".$years." AND keystage_id = ".$keyId['id'];
						$yearQuery = "SELECT id FROM ep_wsyears WHERE year = ".$years;
						$yearObj = $this->dbClassObj->query($yearQuery);
						$keys='';
						while($yearId = $this->dbClassObj->fetch_assoc($yearObj)){
							$keys.= $yearId['id'].',';
						}
						$keys = substr($keys,0,-1);
						$yearMain = ' AND year IN ('.$keys.')';
					}
				}
				
				if(empty($offset)){$offset = 0;	}
							
				//Fetching the level ID
				if(!empty($level)){	$levelMain = " AND level = '".$level."'"; }
				
				//Main search query
				$searchQuery = "SELECT * FROM ep_worksheets WHERE 1=1 AND status='1' ".$searchMain.$subMain.$keyMain.$yearMain.$levelMain.$studentQuery."  ORDER BY worksheetName ASC LIMIT ".$offset.",15";
				
				if($userType == 'guest'){
					$searchQuery = "SELECT * FROM ep_worksheets WHERE 1=1 AND status='1' ".$searchMain.$subMain.$keyMain.$yearMain.$levelMain.$userType." LIMIT ".$offset.",15";
				}
				
				$qryObj = $this->dbClassObj->query($searchQuery);
				$i=0; $responseArray = array();
				while($row = $this->dbClassObj->fetch_assoc($qryObj)) {
					//FOR GUEST USER
					if(!empty($studentId)){
						//Is assigned or not key.
						$isAssigned = $this->_isWorksheetAssigned($studentId, $row['id']);
						$row['is_assigned'] = $isAssigned;
						
						$getUserType = $this->_userType($studentId);
						if($getUserType == 'parent'){
							$row['assignmentCount'] = $this->_getAssignmentCount($row['id'],$studentId);
						}
						
					}
				
					//GET: number of questions associated with every worksheet
					$get_ques = $this->dbClassObj->query("SELECT count(*) as questions FROM `ep_questions` WHERE worksheetId =".$row['id']);
					$get_ques = $this->dbClassObj->fetch_assoc($get_ques);
					$row['questions'] = $get_ques['questions'];
					
				
					
					//SET: response array
					$responseArray[$i] = array_merge((array)$responseArray[$i],$row);
					$i++;
				}
				if(!empty($responseArray)){
					$response = array( 'error' => STATUS_OK , 'message' => 'Search Results' , 'response' => $responseArray );
				}else{
					if($offset == 0){
						$response = array( 'error' => ERROR_EXISTS , 'message' => 'No worksheets found.');
					}else{
						$response = array( 'error' => ERROR_EXISTS , 'message' => 'No more worksheets found.');
					}
				}
			}
			else {
				if($this->isPrivate($token)) {
					// Error message of "Token has been expired"
					$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
				}
				else{ 
					// Error message of "Token has been expired"
					$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
				}
			}
			echo json_encode($response);
		} 
		
		public function getQuestionsByParent(){
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
					$json_data = file_get_contents('php://input');
					$json_data = json_decode($json_data);
					$worksheet_id = $json_data->worksheet_id;
					
					if(!empty($worksheet_id)){
						$charset="SET CHARACTER SET utf8";
						$this->dbClassObj->query($charset);

						//Getting overview fields query of worksheet
						$overviewQuery = "SELECT ws.id AS worksheet_id, ws.worksheetName, ws.worksheetCat AS marking, ws.subject, ws.level, ws.description, ws.worksheet_intro_video, ks.key_stage, wks.ksu, ob.objectives FROM ep_worksheets AS ws LEFT JOIN ep_wskeystage AS ks ON ks.id = ws.keyStage LEFT JOIN ep_wsksu AS wks ON wks.id = ws.ksu LEFT JOIN ep_wsobjective AS ob ON ob.id = ws.objective WHERE ws.id = ".$worksheet_id;
						
						$overviewObj = $this->dbClassObj->query($overviewQuery);
						$overviewArray = $this->dbClassObj->fetch_assoc($overviewObj);
						
						//Getting the number of questions in the worksheet
						$quesQuery = "SELECT * FROM ep_questions WHERE worksheetId = ".$overviewArray['worksheet_id'].' ORDER BY id ASC';
						$quesObj = $this->dbClassObj->query($quesQuery);
						$quesArray = $this->dbClassObj->num_rows($quesObj);
						$overviewArray['questions'] = $quesArray;
						
						//Modify the value of the marking key with 1 - Auto and 2 - Manual
						if($overviewArray['marking'] == 1){ $overviewArray['marking'] = 'Auto'; }
						else{ $overviewArray['marking'] == 'Manual'; }
						
						//Create and update the array 'overview' key with $overviewArray values
						$responseArray = array();
						$responseArray['overview'] = $overviewArray;
						
						//Getting HTML introduction for the particular worksheet
						$htmlQuery = "SELECT text_introduction FROM ep_worksheets WHERE id = ".$worksheet_id;
						$htmlObj = $this->dbClassObj->query($htmlQuery);
						$htmlArray = $this->dbClassObj->fetch_assoc($htmlObj);
						
						//Create and update the array 'introduction' key with $htmlArray values
						$responseArray['introduction'] = $htmlArray;
						
						//Getting Questions by the particular worksheet ID
						$i=0;
						$quesQuery = "SELECT id, question, answerType, totalAnswerBoxes, correctAnswer, explanation, status, matrix_type, matrix_row, matrix_column FROM ep_questions WHERE worksheetId = ".$worksheet_id.' ORDER BY id ASC';
						
						$quesObj = $this->dbClassObj->query($quesQuery);

						while( $quesArray = $this->dbClassObj->fetch_assoc($quesObj)){
							
							//~ $quesArray['question'] = utf8_decode($quesArray['question']);
							$quesArray['subject_id'] = $overviewArray['subject'];
							
							//Code making easier to understand the type of the answer
							if($quesArray['answerType'] == 1){
								$quesArray['answerType'] = 'textbox';
							}elseif($quesArray['answerType'] == 2){
								$quesArray['answerType'] = 'checkbox';
							}elseif($quesArray['answerType'] == 3){
								$quesArray['answerType'] = 'radio';
							}elseif($quesArray['answerType'] == 4){
								$quesArray['answerType'] = 'textarea';
							}elseif($quesArray['answerType'] == 5){
								$quesArray['answerType'] = 'matrix';
							}elseif($quesArray['answerType'] == 6){
								$quesArray['answerType'] = 'dragable';
							}
						
							// CODE STARTS - Generating the HTML for the answerType 5 ( Matrix Category )
							$k = 0;
							
							$rows = $quesArray['matrix_row'];
							$columns = $quesArray['matrix_column'];
							$type = $quesArray['matrix_type'];
							
							$array_rows = explode(',',$rows); 
							// $array_rows=array_map('trim',$array_rows);
							$quesArray['array_rows'] = $array_rows;
							
							$array_columns = explode(',',$columns);
							// $array_columns=array_map('trim',$array_columns);
							$quesArray['array_columns'] = $array_columns;
							
							$total_rows = count(explode(',',$rows)); 
							$total_columns = count(explode(',',$columns));
							
							$answer_html = '';
							if(!empty($type)){
								if(empty($total_rows)){ $total_rows=0; }
								if(empty($total_columns)){ $total_columns=0; }
								//Generating a matrix in the form of HTML table
								$answer_html .= '<table id="ans_table">';
									for( $x = 0 ; $x < $total_rows ; $x++ ){
										
										if( $x == 0 ){
											$answer_html .= '<tr><td></td>';
											for( $q = 0 ; $q < $total_columns ; $q++ ){
												$answer_html .= '<td>'.$array_columns[$q].'</td>';
											}
											$answer_html .= '</tr>';
										}
										
										$answer_html .= '<tr><td>'.$array_rows[$x].'</td>';
										for( $k = 0 ; $k < $total_columns ; $k++ ){
											$answer_html .= '<td>';
												if($type=="checkbox"){
													$answer_html .= '<input type="checkbox" name="checkbox_'.$x.'_'.$k.'" class="checkboxes"  />';
												}elseif($type=="radio"){
													$answer_html .= '<input type="radio" name="radiogroup_'.$x.'" class="radios" />';
												}
											$answer_html .= '</td>';
										}
										$answer_html .= '</tr>';
									}
								$answer_html .= '</table>';
							}
							$quesArray['answerHTML'] = $answer_html;
							// CODE ENDS - Generating the HTML for the answerType 5 ( Matrix Category )

							//Updating the value in the $responseArray
							$responseArray['questions'][$i] = $quesArray;
							
							// CODE STARTS - Generating the HTML for the answerType 6 ( Dragable Category )
							if($quesArray['answerType'] == 'dragable'){
								$arr_of_correct_ans =array();
								$arr_of_correct_ans = explode('!~!', $quesArray['correctAnswer']);
								shuffle($arr_of_correct_ans);
								foreach($arr_of_correct_ans as $key=>$value){
									$responseArray['questions'][$i]['optionsAnswers'][$key]['answerOption'] = $value;
								}
							}
							// CODE ENDS - Generating the HTML for the answerType 6 ( Dragable Category )
							
							
							//Getting options(answers) for the particular question
							$j = 0;
							$optionsQuery = "SELECT answerOption FROM ep_options WHERE questionId = ".$quesArray['id']." AND worksheetId = ".$worksheet_id." ORDER BY id ASC ";
							$optionsObj = $this->dbClassObj->query($optionsQuery);
							while($optionsArray = $this->dbClassObj->fetch_assoc($optionsObj)){
								(array)$responseArray['questions'][$i]['options'][$j] = $optionsArray;
								$j++;
							}
							$i++;
						}

						if(!empty($responseArray)){ $response = array('error'=>STATUS_OK,'message'=>'Worksheet Details', 'response'=>$responseArray); }
						else{ $response = array('error'=>ERROR_EXISTS,'message'=>'Details not found'); }
					}else{ $response = array('error'=>ERROR_EXISTS,'message'=>'Worksheet ID is required');}
				}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}else{ $response = array('error'=>ERROR_EXISTS,'message'=>POST_ERROR_MSG);}
			echo json_encode($response);
		}
		
		public function completedQuestionsByStudent(){
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				//$student_id = $this->getUidFromUserToken($token);
				$json_data = file_get_contents('php://input');
				$json_data = json_decode($json_data);
				$worksheet_id = $json_data->worksheet_id;
				$student_id = $json_data->student_id;
				$assigmentId = $json_data->assignment_id;
				
				if(!empty($worksheet_id)){
					
					$overviewArray = array();
					$responseArray = array();
					
					$charset="SET CHARACTER SET utf8";
					$this->dbClassObj->query($charset);
					
					//Getting the assignment ID
					$asgQuery = "SELECT id,points_collected as totalPoints FROM ep_wsassigned WHERE worksheetId = ".$worksheet_id." AND studentId = ".$student_id." AND id=".$assigmentId;
					
					$asgObj = $this->dbClassObj->query($asgQuery);
					$asgArray = $this->dbClassObj->fetch_assoc($asgObj);
					$assigmentId = $asgArray['id'];
					
					//Getting the number of questions in the worksheet
					$quesQuery = "SELECT * FROM ep_questions WHERE worksheetId = ".$worksheet_id.' ORDER BY id ASC';
					$quesObj = $this->dbClassObj->query($quesQuery);
					$quesArray = $this->dbClassObj->num_rows($quesObj);
					$overviewArray['questions'] = $quesArray;
					
					//Getting Questions by the particular worksheet ID
					$i=0;
					$quesQuery = "SELECT id, question, answerType, totalAnswerBoxes, correctAnswer, explanation, caseSensitive, status, matrix_type, matrix_row, matrix_column, matrix_correct_answers FROM ep_questions WHERE worksheetId = ".$worksheet_id." ORDER BY id ASC";
					$quesObj = $this->dbClassObj->query($quesQuery);

					while( $quesArray = $this->dbClassObj->fetch_assoc($quesObj)){
						
						if($quesArray['answerType'] == 4){ $quesArray['correctAnswer'] = $quesArray['explanation']; }
						
						//~ $quesArray['question'] = utf8_decode($quesArray['question']);
						
						//Code making easier to understand the type of the answer
							if($quesArray['answerType'] == 1){ $quesArray['answerTypeNum'] = 1; $quesArray['answerType'] = 'textbox';	}
						elseif($quesArray['answerType'] == 2){ $quesArray['answerTypeNum'] = 2; $quesArray['answerType'] = 'checkbox'; 	}
						elseif($quesArray['answerType'] == 3){ $quesArray['answerTypeNum'] = 3; $quesArray['answerType'] = 'radio'; 	}
						elseif($quesArray['answerType'] == 4){ $quesArray['answerTypeNum'] = 4; $quesArray['answerType'] = 'textarea'; 	}
						elseif($quesArray['answerType'] == 5){ $quesArray['answerTypeNum'] = 5; $quesArray['answerType'] = 'matrix'; 	}
						elseif($quesArray['answerType'] == 6){ $quesArray['answerTypeNum'] = 6; $quesArray['answerType'] = 'dragable'; 	}
					
						//Updating the value in the $responseArray
						$responseArray['questions'][$i] = $quesArray;
						//$responseArray['questions'][$i]['question'] = strip_slashes($quesArray['question']);
						$responseArray['questions'][$i]['totalPoints'] = $asgArray['totalPoints'];

						//Getting the answer given by children
						$answQuery = "SELECT answer FROM ep_answers WHERE assignmentId = ".$assigmentId." AND questionId = ".$quesArray['id'];
						$answObj = $this->dbClassObj->query($answQuery);
						$answArray = $this->dbClassObj->num_rows($answObj);
						if($answArray > 0){
							
							if($quesArray['answerTypeNum'] == 1){
								$q = 0;
								$tempArr = array();
								while($answArray = $this->dbClassObj->fetch_assoc($answObj)){
									$tempArr[$q] = $answArray['answer'];
									$q++;
								}
								$answArray['answer'] = implode('!~!',$tempArr);
							}elseif($quesArray['answerTypeNum'] == 2){
								$tempArr = array();
								$a=0;
								while($answArray = $this->dbClassObj->fetch_assoc($answObj)){
									$tempArr[$a] = $answArray['answer'];
									$a++;
								}
							}elseif($quesArray['answerTypeNum'] == 5){
								$tempArr = array();
								$answArray = $this->dbClassObj->fetch_assoc($answObj);
								// $tempArr = unserialize(html_entity_decode($answArray['answer']));
								$tempArr = html_entity_decode($answArray['answer']);
							}elseif($quesArray['answerTypeNum'] == 6){
								$tempArr = array();
								$a=0;
								while($answArray = $this->dbClassObj->fetch_assoc($answObj)){
									$tempArr[$a] = $answArray['answer'];
									$a++;
								}
							}else{
								$tempArr = array();
								$answArray = $this->dbClassObj->fetch_assoc($answObj);
								$tempArr = explode('!~!',$answArray['answer']);
							}
							
							$answArray['correctAnswerArr'] = "";
							$answArray['studentAnswerArr'] = "";
							$point = 0;
							
							//STARTS: correct answer finding algo
								//SET: variables value
								$answerType = $quesArray['answerTypeNum'];
								
								if($answerType == 5){$studentAnswerArr = unserialize($tempArr);}
								elseif($answerType == 2){ $studentAnswerArr = explode('!~!',$tempArr[0]); }
								elseif($answerType == 6){ $studentAnswerArr = explode('!~!',$tempArr[0]); }
								else{ $studentAnswerArr = $tempArr; }
								
								$correctAnswerArr = explode('!~!',$quesArray['correctAnswer']);
								
								if($quesArray['answerTypeNum'] == 4){ $correctAnswerArr = explode('!~!',$quesArray['explanation']); }
								
								$totalAnswerBoxes = $quesArray['totalAnswerBoxes'];
								
								//UPDATE & SORT: mapping of array values to the lower string and sorting the arrays by values
								if($answerType != 6 && $quesArray['caseSensitive'] == '0'){
									/* asort(array_map('strtolower',$studentAnswerArr));
									asort(array_map('strtolower',$correctAnswerArr)); */
									
									if($answerType == 1){ $temStudAns = $studentAnswerArr; }
									
									
									$studentAnswerArr = array_map('strtolower',$studentAnswerArr);
									asort($studentAnswerArr);
									$correctAnswerArr = array_map('strtolower',$correctAnswerArr);
									asort($correctAnswerArr);
								}
								
								//CHECK: answer type is not equal to 5 and 6 (matrix type & dragable)
								if($answerType < 5){
									if($answerType == 1){
										if($totalAnswerBoxes == 1){
											foreach($studentAnswerArr as $studentAnswer){
												//CHECK: is student answer is exists in the correct array
												//htmlentities($studentAnswer, ENT_QUOTES, 'UTF-8'),
												
												//REPLACE: the £ with &pound; to overcome the pound special character problem.
												$studentAnswer = str_replace('£','&pound;',$studentAnswer);
												if(
													in_array(
														$studentAnswer,
														$correctAnswerArr
													)
												){ $point = 1; }
												else{ $point = 0; break; }
											}
										}else{
											//CHECK: is the values in the both array are equal
											if(count($studentAnswerArr) == count($correctAnswerArr)){
												//PROCESS: finding the student every answer in the corrected ones
												for($inc=0 ; $inc<count($correctAnswerArr) ; $inc++){
													if($studentAnswerArr[$inc] == $correctAnswerArr[$inc]){ $point = 1; }
													else{ $point = 0; break; }
												}
											}else{
												//SET: if both arrays count are not mathched
												$point = 0; 
											}
											$answArray['studentAnswerArr'] = $studentAnswerArr;
											$answArray['correctAnswerArr'] = $correctAnswerArr;
										}
									}elseif($answerType == 2){
										//CHECK: is the values in the both array are equal
										if(count($studentAnswerArr) == count($correctAnswerArr)){
											//PROCESS: finding the student every answer in the corrected ones
											for($inc=0 ; $inc<count($correctAnswerArr) ; $inc++){
												// if($studentAnswerArr[$inc] == $correctAnswerArr[$inc]){ $point = 1; }
												if(in_array($studentAnswerArr[$inc],$correctAnswerArr)){ $point = 1; }
												else{ $point = 0; break; }
											}
										}else{
											//SET: if both arrays count are not mathched
											$point = 0; 
										}
										$answArray['studentAnswerArr'] = $studentAnswerArr;
										$answArray['correctAnswerArr'] = $correctAnswerArr;
									}else{
										foreach($studentAnswerArr as $studentAnswer){
											//CHECK: is atudent answer is exists in the correct array
											if(in_array($studentAnswer,$correctAnswerArr)){ $point = 1; }
											else{ $point = 0; break; }
										}
									}
								}elseif($answerType == 6){
									// $correctAnswerArr = $correctAnswerArr;
									// $studentAnswerArr = serialize($studentAnswerArr);
									
									//GET: student questions answers combinations
									$stdHtml = '<table><tr><td class="orange">COLUMN A</td><td class="orange">COLUMN B</td></tr>'; $numb=0;
									$options = $this->dbClassObj->query("SELECT * FROM `ep_options` WHERE questionId = ".$quesArray['id']." ORDER BY id ASC");
									while($option = $this->dbClassObj->fetch_assoc($options)){
										if($numb%2 == 0){ $color = 'grey'; }else{ $color = ''; }
										$stdHtml .= '<tr><td class="'.$color.'">'.$option['answerOption'].'</td><td class="'.$color.'">'.$studentAnswerArr[$numb].'</td></tr>';
										$numb++;
									}
									$stdHtml .= '</table><style>.orange{background-color:#FF6600;color:#FFFFFF}.grey{background-color:#999999;}</style>';
									$responseArray['questions'][$i]['studentHtml'] = $stdHtml;
									
									//GET: correct questions answers combinations
									$stdHtml = '<table><tr><td class="orange">COLUMN A</td><td class="orange">COLUMN B</td></tr>'; $numb=0;
									$options = $this->dbClassObj->query("SELECT * FROM `ep_options` WHERE questionId = ".$quesArray['id']." ORDER BY id ASC");
									while($option = $this->dbClassObj->fetch_assoc($options)){
										if($numb%2 == 0){ $color = 'grey'; }else{ $color = ''; }
										$stdHtml .= '<tr><td class="'.$color.'">'.$option['answerOption'].'</td><td class="'.$color.'">'.$correctAnswerArr[$numb].'</td></tr>';
										$numb++;
									}
									$stdHtml .= '</table><style>.orange{background-color:#FF6600;color:#FFFFFF}.grey{background-color:#999999;}</style>';
									$responseArray['questions'][$i]['correctHtml'] = $stdHtml;
									
									if($studentAnswerArr == $correctAnswerArr){ $point = 1; }
									else{ $point = 0; }
								}else{
									$correctAnswerArr = $quesArray['matrix_correct_answers'];
									$studentAnswerArr = $tempArr;
									
									//echo '<pre>'; print_r($studentAnswerArr); echo '</pre>'; exit;
									
									//PROCESS: code executes if the format of answer is in nestet arrays format
									/* if($studentAnswerArr != $correctAnswerArr){ 
										if($studentAnswerArr[0] == $correctAnswerArr){ 
											$studentAnswerArr = $studentAnswerArr[0];
										}elseif(isset($studentAnswerArr[0])){
											$studentAnswerArr = unserialize(html_entity_decode($studentAnswerArr[0]));
											foreach($studentAnswerArr as $key=>$value){
												$studentAnswerArr[$key] = $value[0];
											}
											$studentAnswerArr = serialize($studentAnswerArr);
										}
									} */
									
									
									/***********CODE STARTS: HTML FOR THE CORRECT AND STUDENT ANSWERS**********/
									//SET: the correct answers html
									$mquery = $this->dbClassObj->query("SELECT matrix_type, matrix_row, matrix_column FROM `ep_questions` WHERE worksheetId = ".$worksheet_id." AND answerType =5 AND id=".$quesArray['id']);
									$mobject = $this->dbClassObj->fetch_assoc($mquery);
									
									//SET: variables
									$type = $mobject['matrix_type'];
									$array_rows = explode(',',$mobject['matrix_row']);
									$array_columns = explode(',',$mobject['matrix_column']);
									// $array_columns = array_map('trim',$array_columns);
									$total_rows = count($array_rows);
									$total_columns = count($array_columns);
									
									if($quesArray['answerTypeNum'] == 5 && $type=="checkbox"){
										$studentAnswerArr = unserialize($studentAnswerArr);
										$studentAnswerArr = array_map('array_filter', $studentAnswerArr);
										$studentAnswerArr = array_filter($studentAnswerArr);
										$studentAnswerArr = serialize($studentAnswerArr);
									}
									
									
									if($quesArray['answerTypeNum'] == 5 && $type=="textfield"){
										$studentAnswerArr = unserialize($studentAnswerArr);
										foreach($studentAnswerArr as $key=>$value){
											$studentAnswerArr[$key] = array_map('strtolower',$value);
										}
										$studentAnswerArr = serialize($studentAnswerArr);
									}
									
									$studentAns = unserialize($studentAnswerArr);
									
									
									//CODE ADDED: 05-05-2015
									if($quesArray['answerTypeNum'] == 5 && $type=="textfield"){
										$studentAns = unserialize($tempArr);
									}
									//CODE ADDED: 05-05-2015
									
									
									
									//SET: student answer HTML
									$answer_html = '';
									if(!empty($type)){
										if(empty($total_rows)){ $total_rows=0; }
										if(empty($total_columns)){ $total_columns=0; }
										//Generating a matrix in the form of HTML table
										$answer_html .= '<table id="ans_table">';
											for( $x = 0 ; $x < $total_rows ; $x++ ){
												
												if($quesArray['answerTypeNum'] == 5 && $type=="radio"){ $columnIndexArrSt[$x] = 9; }
												
												if( $x == 0 ){
													$answer_html .= '<tr><td></td>';
													for( $q = 0 ; $q < $total_columns ; $q++ ){ $answer_html .= '<td>'.$array_columns[$q].'</td>'; }
													$answer_html .= '</tr>';
												}
												//PROCESS: generating the radio buttons nature
												if($quesArray['answerTypeNum'] < 5){
													$studentOption = $studentAns[$x];
													$studentOption = explode('~sep~',$studentOption);
													$rowIndex = array_search($studentOption[0],$array_rows);
													$columnIndex = array_search($studentOption[1],$array_columns);
												}
												
												//CHECK: if the question type is checkbox
												if($quesArray['answerTypeNum'] == 5 && $type=="checkbox"){
													$numbr=0;
													foreach($studentAns[$x] as $studentOption){
														$studentOption = explode('~sep~',$studentOption);
														$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
														$columnIndexArrSt[$x][$numbr] = array_search($studentOption[1],$array_columns);
														$numbr++;
													}
												}
												
												//CHECK: if the question type is radio and answer type = 5
												if($quesArray['answerTypeNum'] == 5 && $type=="radio"){
													$numbr=0;
													
													if(!is_array($studentAns[$x])){ $studentAns[$x] = array('0'=>$studentAns[$x]); }

													foreach($studentAns[$x] as $studentOption){
														if(count($studentOption) > 0){
															$studentOption = explode('~sep~',$studentOption);
															$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
															$columnIndexArrSt[$x] = array_search($studentOption[1],$array_columns);
														}elseif(!empty($studentOption)){
															$studentOption = explode('~sep~',$studentOption);
															$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
															$columnIndexArrSt[$x] = array_search($studentOption[1],$array_columns);
														}else{
															$rowIndexArr[$x][] = 9;
															$columnIndexArrSt[$x] = 9;
														}
													}
												}
												
												
												//CHECK: if the question type is textfield
												if($quesArray['answerTypeNum'] == 5 && $type=="textfield"){
													$numbr=0;
													foreach($studentAns[$x] as $studentOption){
														$text_value_st[$numbr] = $studentOption;
														$numbr++;
													}
												}	
												
												$answer_html .= '<tr><td>'.$array_rows[$x].'</td>';
												for( $k = 0 ; $k < count($array_columns) ; $k++ ){
													$checked = '';
													//SET: checked variable
													// if(empty($studentOption[1])){$columnIndex=2;}
													
													if($quesArray['answerTypeNum'] < 5){ if($columnIndex == $k){ $checked = 'checked'; }else{ $checked = ''; } }
													
													//CHECK: if the question type is checkbox
													if($quesArray['answerTypeNum'] == 5 && $type=="checkbox"){ if(in_array($k,$columnIndexArrSt[$x])){ $checked = 'checked'; }else{ $checked = ''; } }
													
													//CHECK: if the question type is radio
													if($quesArray['answerTypeNum'] == 5 && $type=="radio"){ if($k == $columnIndexArrSt[$x]){ $checked = 'checked'; }else{ $checked = ''; } }
													
													//PROCESS: generating the HTML
													$answer_html .= '<td>';
														if($type=="checkbox"){ $answer_html .= '<input type="checkbox" name="checkbox_'.$x.'_'.$k.'" class="checkboxes" '.$checked.' disabled />'; }
														elseif($type=="radio"){ $answer_html .= '<input type="radio" name="radiogroup_'.$x.'" class="radios" '.$checked.' disabled />'; }
														elseif($type=="textfield"){ $answer_html .= '<input type="text" name="text_'.$x.'" class="text" value="'.$text_value_st[$k].'" disabled />'; }
													$answer_html .= '</td>';
													$checked = '';
												}
												$answer_html .= '</tr>';
												
												if($singleQues['answerTypeNum'] == 5 && $type=="checkbox"){ $columnIndexArrSt[$x] = array(); }
											}
										$answer_html .= '</table>';
									}
									$responseArray['questions'][$i]['studentHtml'] = $answer_html;
									
									//SET: correct answer HTML
									$correctAns = unserialize($correctAnswerArr);
									$answer_html = '';
									$rowIndexArr = $columnIndexArr = array();
									if(!empty($type)){
										if(empty($total_rows)){ $total_rows=0; }
										if(empty($total_columns)){ $total_columns=0; }
										//Generating a matrix in the form of HTML table
										$answer_html .= '<table id="ans_table">';
											for( $x = 0 ; $x < $total_rows ; $x++ ){
												if( $x == 0 ){
													$answer_html .= '<tr><td></td>';
													for( $q = 0 ; $q < $total_columns ; $q++ ){ $answer_html .= '<td>'.$array_columns[$q].'</td>'; }
													$answer_html .= '</tr>';
												}

												//PROCESS: generating the radio buttons nature
												$studentOption = $correctAns[$x];
												$studentOption = explode('~sep~',$studentOption);
												$rowIndex = array_search($studentOption[0],$array_rows);
												$columnIndex = array_search($studentOption[1],$array_columns);
												
												//CHECK: if the question type is checkbox
												if($quesArray['answerTypeNum'] == 5 && $type=="checkbox"){
													foreach($correctAns[$x] as $studentOption){
														$studentOption = explode('~sep~',$studentOption);
														$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
														$columnIndexArr[$x][] = array_search($studentOption[1],$array_columns);
													}
												}
												
												//CHECK: if the question type is textfield
												if($quesArray['answerTypeNum'] == 5 && $type=="textfield"){
													$numbr=0;
													foreach($correctAns[$x] as $studentOption){
														$text_value[$numbr] = $studentOption;
														$numbr++;
													}
												}
												
												$answer_html .= '<tr><td>'.$array_rows[$x].'</td>';
												for( $k = 0 ; $k < $total_columns ; $k++ ){
													//SET: checked variable
													if($columnIndex == $k){ $checked = 'checked'; }else{ $checked = ''; }
													
													//CHECK: if the question type is checkbox
													if($quesArray['answerTypeNum'] == 5 && $type=="checkbox"){ if(in_array($k,$columnIndexArr[$x])){ $checked = 'checked'; }else{ $checked = ''; } }
													
													//PROCESS: generating the HTML
													$answer_html .= '<td>';
														if($type=="checkbox"){ $answer_html .= '<input type="checkbox" name="checkbox_'.$x.'_'.$k.'" class="checkboxes" '.$checked.' disabled />'; }
														elseif($type=="radio"){ $answer_html .= '<input type="radio" name="radiogroup_'.$x.'" class="radios" '.$checked.' disabled />'; }
														elseif($type=="textfield"){ $answer_html .= '<input type="text" name="text_'.$x.'" class="text" value="'.$text_value[$k].'" disabled />'; }
													$answer_html .= '</td>';
												}
												$answer_html .= '</tr>';
											}
										$answer_html .= '</table>';
									}
									$responseArray['questions'][$i]['correctHtml'] = $answer_html;
									/***********CODE ENDS: HTML FOR THE CORRECT AND STUDENT ANSWERS**********/
									
									//CODE ADDED: 05-05-2015
									if($quesArray['answerTypeNum'] == 5 && $type=="textfield"){
										$studentAnswerArr = unserialize($studentAnswerArr);
										foreach($studentAnswerArr as $key=>$value){
											$studentAnswerArr[$key] = array_map('strtolower',$value);
										}
										$studentAnswerArr = serialize($studentAnswerArr);

										$correctAnswerArr = unserialize($correctAnswerArr);
										foreach($correctAnswerArr as $key=>$value){
											$correctAnswerArr[$key] = array_map('strtolower',$value);
										}
										$correctAnswerArr = serialize($correctAnswerArr);
									}
									//CODE ADDED: 05-05-2015
									
									if($studentAnswerArr == $correctAnswerArr){ $point = 1; }
									else{ $point = 0; }
								}
								$responseArray['questions'][$i]['correctAnswerArr'] = $correctAnswerArr;
								$responseArray['questions'][$i]['studentAnswerArr'] = $studentAnswerArr;
								$responseArray['questions'][$i]['point'] = $point;
							//ENDS: correct answer finding algo
						}else{ 
							$answObj = $this->dbClassObj->query("SELECT answer FROM ep_answers WHERE assignmentId = ".$assigmentId." AND questionId = ".$quesArray['id']);
							$answArray = $this->dbClassObj->fetch_assoc($answObj); 
						}
						// $responseArray['questions'][$i]['std_answer'] = html_entity_decode($answArray['answer']);
						
						$responseArray['questions'][$i]['std_answer'] = implode('!~!',$studentAnswerArr);
						
						if($quesArray['answerTypeNum'] == 1 && $quesArray['caseSensitive'] == '0'){
							$responseArray['questions'][$i]['studentAnswerArr'] = $temStudAns;
							$responseArray['questions'][$i]['std_answer'] = implode('!~!',$temStudAns);
						}
						
						$i++;
					}
					if(!empty($responseArray)){
						$response = array('error'=>STATUS_OK,'message'=>'Worksheet Details', 'response'=>$responseArray);
					}else{
						$response = array('error'=>ERROR_EXISTS,'message'=>'Details not found');
					}
				}else{ $response = array('error'=>ERROR_EXISTS,'message'=>'Worksheet ID is required');}
			
			}
			else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}else{ $response = array('error'=>ERROR_EXISTS,'message'=>POST_ERROR_MSG);}
			echo json_encode($response);
		}
		
		
		public function scoresbyparent(){
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parent_id = $this->getUidFromUserToken($token);
				$json_data = file_get_contents('php://input');
				//$json_data = json_decode($json_data);
				//$parent_id = $json_data->parent_id;
				
				//$years = $json_data->years;

				if(!empty($parent_id)){
					$j=0;
					$respnseArray = array();
					$queryParent = $this->dbClassObj->query("select U.id, U.fname, U.lname, TRIM(CONCAT(U.fname,' ',U.lname)) as student_name, U.auto_assign_year from ep_wsusers U ,ep_subscription_student S where U.user_type='student' and S.student_id=U.id and S.is_current='1' and U.parent_id= '".$parent_id."'");
					while( $studentId = $this->dbClassObj->fetch_array($queryParent)){
						$uid = $studentId['id'];
						$auto_assign_year = $studentId['auto_assign_year'];
						
						if(empty($studentId['student_name'])){ $studentId['student_name'] = $studentId['fname'].' '.$studentId['lname']; }
						
						$student_name = $studentId['student_name'];
						
						//For the individual subjects
						$str_subjects = array(
							'overall'	=>	'1,2,3',
							'math'		=>	'1',
							'english'	=>	'2',
							'science'	=>	'3'
						);
						
						foreach( $str_subjects as $key => $data )
						{
							//CODE STARTS - Getting the average Ed Place marks by studentID
							$queryEdScore = $this->dbClassObj->query("SELECT U.id, U.fname, U.lname, U.user_name, U.student_year, U.auto_assign_year, S.student_id FROM ep_wsusers U, ep_subscription_student S WHERE U.user_type = 'student' AND S.student_id = U.id AND S.is_current = '1' AND U.id = '".$uid."'");
							
							$queryEdScore = $this->dbClassObj->fetch_assoc($queryEdScore);
							$year = $queryEdScore['auto_assign_year'];
							$sqlAvg = $this->dbClassObj->query("select * from ep_score_log  where score_type='edplace' and year_id='".$year."' and log_time='6'"); /*and modified_date=now()*/
							$sqlAvg = $this->dbClassObj->fetch_assoc($sqlAvg);
							//CODE ENDS - Getting the average Ed Place marks by studentID

							//CODE STARTS - Getting the Scores by studentID
							$sql = "SELECT sum( score_collected ) AS score_collected, sum( score_total ) AS score_total, count( A.id ) AS worksheet_total FROM ep_wsassigned A, ep_worksheets B, ep_wssubjects D, ep_wslevel E, ep_wsusers C WHERE A.is_complete = '1' AND C.id = '".$uid."' AND A.worksheetId = B.id AND B.subject = D.id AND B.level = E.id AND A.studentId = C.id AND ( B.worksheetCat='1' or ( B.worksheetCat='2' and A.dateChecked !='0000-00-00 00:00:00'))";

							if($data!=''){	$sql .= " and D.id in (".$data.") "; }

							$sqlObj = $this->dbClassObj->query($sql);
							$sqlArr = $this->dbClassObj->fetch_assoc($sqlObj);
							/* if($this->getLevel() !=''){$sql .= " and B.level ='".$this->getLevel()."' ";} */
							//CODE ENDS - Getting the Scores by studentID
							
							//Creating a response array with fetched values.
							$respnseArray[$j]['student_id'] = $uid;
							$respnseArray[$j]['student_name'] = $student_name;
							
							//Calling the graph_score values
							// $graph_points = 1;
							
							/* CODE commented on 20-03-2015
							$graph_points = $this->graph_score($uid,$auto_assign_year,6);
							*/
							
							//$respnseArray[$j]['score_graph'] = $graph_points;

							//Calling the edplace_graph_score values
							
							/* CODE commented on 20-03-2015
							$ed_graph_points = 2;
							$ed_graph_points = $this->edplace_graph_score($uid,$auto_assign_year,6);
							*/
							
							//$respnseArray[$j]['company_graph'] = $ed_graph_points;
							
							//Setting graph variables according to the $key value
							
							/* CODE commented on 20-03-2015
							switch($key){
								case 'math':
									$respnseArray[$j]['data'][$key]['score_graph'] = $graph_points['1'];
									$respnseArray[$j]['data'][$key]['company_graph'] = $ed_graph_points['1'];
									break;
								case 'english':
									$respnseArray[$j]['data'][$key]['score_graph'] = $graph_points['2'];
									$respnseArray[$j]['data'][$key]['company_graph'] = $ed_graph_points['2'];
									break;
								case 'science':
									$respnseArray[$j]['data'][$key]['score_graph'] = $graph_points['3'];
									$respnseArray[$j]['data'][$key]['company_graph'] = $ed_graph_points['3'];
									break;
								case 'overall':
									$respnseArray[$j]['data'][$key]['score_graph'] = $graph_points['all'];
									$respnseArray[$j]['data'][$key]['company_graph'] = $ed_graph_points['all'];
									break;
								default:
									$respnseArray[$j]['data'][$key]['score_graph'] = $graph_points['all'];
									$respnseArray[$j]['data'][$key]['company_graph'] = $ed_graph_points['all'];
							}
							*/
						
							switch($data){
								case '1,2,3':
									$respnseArray[$j]['data'][$key]['best_topic_listing']  = $this->myTaskListPerformance($uid, 0, 5,'desc','1,2,3');
									$respnseArray[$j]['data'][$key]['worst_topic_listing']  = $this->myTaskListPerformance($uid,0, 5,'asc','1,2,3');
									break;
								case '1':
									$respnseArray[$j]['data'][$key]['best_topic_listing']  = $this->myTaskListPerformance($uid, 0, 5,'desc','1');
									$respnseArray[$j]['data'][$key]['worst_topic_listing']  = $this->myTaskListPerformance($uid,0, 5,'asc','1');
									break;
								case '2':
									$respnseArray[$j]['data'][$key]['best_topic_listing']  = $this->myTaskListPerformance($uid, 0, 5,'desc','2');
									$respnseArray[$j]['data'][$key]['worst_topic_listing']  = $this->myTaskListPerformance($uid,0, 5,'asc','2');
									break;
								case '3':
									$respnseArray[$j]['data'][$key]['best_topic_listing']  = $this->myTaskListPerformance($uid, 0, 5,'desc','3');
									$respnseArray[$j]['data'][$key]['worst_topic_listing']  = $this->myTaskListPerformance($uid,0, 5,'asc','3');
									break;
								default:
									$respnseArray[$j]['data'][$key]['best_topic_listing']  = $this->myTaskListPerformance($uid, 0, 5,'desc','1,2,3');
									$respnseArray[$j]['data'][$key]['worst_topic_listing']  = $this->myTaskListPerformance($uid,0, 5,'asc','1,2,3');
							}
							
							//GET: count of completed worksheets by the student.
							$count_ws = $this->dbClassObj->query("SELECT count( A.id ) AS worksheet_total FROM ep_wsassigned A, ep_worksheets B, ep_wssubjects D, ep_wslevel E, ep_wsusers C WHERE B.id = A.worksheetId AND D.id = B.subject AND E.id = B.level AND C.id = A.studentId AND A.studentId = '".$uid."' AND A.dateAppeared != '0000-00-00 00:00:00' AND A.is_complete = '1'");
							$count_ws = $this->dbClassObj->fetch_assoc($count_ws);
							$count_ws = $count_ws['worksheet_total'];
							
							$respnseArray[$j]['data'][$key]['score_collected']  = $sqlArr['score_collected'];
							$respnseArray[$j]['data'][$key]['score_total']  = $sqlArr['score_total'];
							//$respnseArray[$j]['data'][$key]['worksheet_total']  = $sqlArr['worksheet_total'];
							$respnseArray[$j]['data'][$key]['worksheet_total']  = $count_ws;
							$respnseArray[$j]['data'][$key]['average_score'] = $sqlAvg[$key];

							if($sqlArr['score_total']>0){
								$respnseArray[$j]['data'][$key]['score_percentage']  = number_format(($sqlArr['score_collected']*100)/$sqlArr['score_total']); 
							}
							else{ $respnseArray[$j]['data'][$key]['score_percentage']  = "0"; }                 
						}
						$j++;
					}
					$response = array('error'=>STATUS_OK,'message'=>'Scores details','response'=>$respnseArray);
				}else{ $response = array('error'=>ERROR_EXISTS,'message'=>'Student does not  exists'); }
			}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}else{ $response = array('error'=>ERROR_EXISTS,'message'=>'OOPS! Something went wrong.');}
			echo json_encode($response);
		}
		
		//Return the graph Y - points based upon the student scores
		public function graph_score($sid,$student_year,$log_time){
			$worksheet_count_arr = $child_loop_score = array();
			
			$result   = $this->dbClassObj->query("select count(A.id) as numworksheet, B.subject from ep_wsassigned as A , ep_worksheets as B where A.worksheetId = B.id and B.year in(".$student_year.") and A.is_complete='1' and A.studentId='".$sid."' and date(A.dateAppeared) between date(now() - INTERVAL ".$log_time." MONTH) and date(now()) group by B.subject ");	
			
			while($line = $this->dbClassObj->fetch_assoc($result))
			{	$subject = $line['subject'];
				$worksheet_count_arr[$subject] =$line['numworksheet'];
			}
			$total_worksheet =array_sum($worksheet_count_arr);
			$worksheet_count_arr['all']		= $total_worksheet;
			
			////////////////////////////////////////////////////////////////////
			$worksheet_count = $worksheet_count_arr;
			foreach($worksheet_count as $subject=>$wcont)
			{	$meanvalue = floor($wcont/6);
				$arrval = ($meanvalue > 20)?$meanvalue:20; 	// Change the value from 1 to 20 which are based upon the number of worksheets
				$loop   = array($arrval,$arrval*2,$arrval*3,$arrval*4,$arrval*5);
				$temp =1;
				$j=1;
				$sqlbunch="";
				foreach($loop as $key=>$lvalue)
				{	if($temp){ $temp =0; $start=0; }
					else{ $start = $loop[$key-1]; }
					$checkarr =array();
					for($i=0; $i<20; $i++){	// Change the value from 1 to 20 which are based upon the number of worksheets
						$checkarr[] = $lvalue+$i;
					}
					if(in_array($wcont, $checkarr) || $lvalue<=$wcont)
					{
						$sql   = " select A.score_collected, A.score_total from ep_wsassigned as A , ep_worksheets as B where A.worksheetId = B.id and B.year in(".$student_year.") and A.is_complete='1' and A.studentId='".$sid."' and date(A.dateAppeared) between date(now() - INTERVAL ".$log_time." MONTH) and date(now()) ";	
						if($subject!='all')
						{
							$query =$sql." and B.subject ='".$subject."' order by A.id asc limit ".$start.",".$lvalue;
						}else{ $query =$sql." order by A.id asc limit ".$start.",".$lvalue; }
					}else{ continue; }
					$sql ="select ((sum(score_collected) *100 )/sum(score_total)) as score_percentage, ".$j." as break from (".$query.") as t ";
					$sqlbunch .=($sqlbunch!="")?" union ".$sql:$sql;
					$j++;
				}
				if($sqlbunch!="")
				{	$result	= $this->dbClassObj->query($sqlbunch);
					$subjectarr = array();
					while($line = $this->dbClassObj->fetch_assoc($result))
					{	$limtvalue = $arrval*$line['break'];
						$subjectarr[$limtvalue] = number_format($line['score_percentage'], 0);
					}
					$child_loop_score[$subject] = @implode('!~!',$subjectarr);     /* 1->M, 2->E, 3->S , 1,2,3->All */
				}
			}
			$subjectarr = $child_loop_score;
			foreach($subjectarr as $key=>$value)
			{	$score_value=($scorearr[$key]!='')?$scorearr[$key]:'';
				$score[$value]=$score_value;
			}
			return $subjectarr;
		}
		
		//Return the graph Y - points based upon the Ed place average scores
		public function edplace_graph_score($sid,$student_year,$log_time){
			$arr_detail = array();
			$sql = "select * from ep_score_log where score_type='edplace' and year_id='".$student_year."' and log_time='".$log_time."'"; /*and modified_date=now()*/
			$result	 = $this->dbClassObj->query($sql);
			if($line = $this->dbClassObj->fetch_assoc($result)){		
				$arr_detail = $line;
			}
			$ed_score = $arr_detail;
			
				/*******************************************/
				$score = array();
				$subjectarr=array('1'=>'score_math','2'=>'score_english','3'=>'score_science','all'=>'score_overall');
					/*******************************/
					$sqlQuery = "SELECT ((sum( A.score_collected ) *100) / sum( A.score_total )) AS score_percentage, B.subject FROM ep_wsassigned AS A, ep_worksheets AS B, ep_subscription_student AS S WHERE A.worksheetId = B.id AND B.year IN ( ".$student_year." ) AND A.is_complete = '1' AND date( A.dateAppeared ) BETWEEN date( now( ) - INTERVAL ".$log_time." MONTH ) AND date( now( ) ) AND A.studentId = S.student_id AND S.is_current = '1' GROUP BY B.subject";
					$result	= $this->dbClassObj->query($sqlQuery);
					$subjectarr=array('1'=>'math','2'=>'english','3'=>'science');
					$edplace_score_arr = array();
					foreach($subjectarr as $value)
					{ $edplace_score_arr[$value]=0; }
					while($line = $this->dbClassObj->fetch_assoc($result))
					{ 	$subject = $line['subject'];
						$subjectname=$subjectarr[$subject];
						$edplace_score_arr[$subjectname] = number_format($line['score_percentage'], 0);
					}
					$score_overall = $this->dbClassObj->query("select ceil((sum(A.score_collected) *100 )/sum(A.score_total)) as score_percentage FROM ep_wsassigned AS A, ep_worksheets AS B, ep_subscription_student AS S WHERE A.worksheetId = B.id AND B.year IN ( ".$student_year." ) AND A.is_complete = '1' AND date( A.dateAppeared ) BETWEEN date( now( ) - INTERVAL ".$log_time." MONTH ) AND date( now( ) ) AND A.studentId = S.student_id AND S.is_current = '1'");	
					$score_overall = $this->dbClassObj->fetch_assoc($score_overall);
					$edplace_score_arr['overall'] = number_format($score_overall, 0);
					$score = $edplace_score_arr;
					/*******************************/

					/******************************/
					$edplace_loop_score = array();
						/***********************/
						$sqlQuery2 = "SELECT count( A.id ) AS numworksheet, B.subject FROM ep_wsassigned AS A, ep_worksheets AS B, ep_subscription_student AS S WHERE A.worksheetId = B.id AND B.year IN ( ".$student_year." ) AND A.is_complete = '1' AND date( A.dateAppeared ) BETWEEN date( now( ) - INTERVAL ".$log_time." MONTH ) AND date( now( ) ) AND A.studentId = S.student_id AND S.is_current = '1' GROUP BY B.subject";
						
						$result	= $this->dbClassObj->query($sqlQuery2);
						$worksheet_count_arr	= array();
						while($line = $this->dbClassObj->fetch_assoc($result))
						{   $subject = $line['subject'];
							$worksheet_count_arr[$subject] =$line['numworksheet'];
						}
						$total_worksheet =array_sum($worksheet_count_arr);
						$worksheet_count_arr['all']		= $total_worksheet;
						$worksheet_count = $worksheet_count_arr;
						/***********************/
						
					foreach($worksheet_count as $subject=>$wcont)
					{	$meanvalue = floor($wcont/6);
						$arrval = ($meanvalue > 20)?$meanvalue:20;	// Change the value from 1 to 20 which are based upon the number of worksheets
						$loop   = array($arrval,$arrval*2,$arrval*3,$arrval*4,$arrval*5);
						$temp =1;
						$j=1;
						$sqlbunch="";
						foreach($loop as $key=>$lvalue)
						{	if($temp){
								$temp =0;
								$start=0;
							}else{
								$start = $loop[$key-1];
							}
							$checkarr =array();
							for($i=0; $i<20; $i++){	// Change the value from 1 to 20 which are based upon the number of worksheets
								$checkarr[] = $lvalue+$i;
							}
							if(in_array($wcont, $checkarr) || $lvalue<=$wcont)
							{
								$sqlQuery3 = "SELECT A.score_collected, A.score_total FROM ep_wsassigned AS A, ep_worksheets AS B, ep_subscription_student AS S WHERE A.worksheetId = B.id AND B.year IN ( ".$student_year." ) AND A.is_complete = '1' AND date( A.dateAppeared ) BETWEEN date( now( ) - INTERVAL ".$log_time." MONTH ) AND date( now( ) ) AND A.studentId = S.student_id AND S.is_current = '1'";
								
								if($subject!='all')
								{	$query =$sqlQuery3." and B.subject ='".$subject."' order by A.id asc limit ".$start.",".$lvalue; }
								else{ $query =$sqlQuery3." order by A.id asc limit ".$start.",".$lvalue; }
							}else{ continue; }
							$sqlQuery3 ="select ceil((sum(score_collected) *100 )/sum(score_total)) as score_percentage, ".$j." as break from (".$query.") as t ";
							$sqlbunch .=($sqlbunch!="")?" union ".$sqlQuery3:$sqlQuery3;
							$j++;
						}
						
						if($sqlbunch!="")
						{
							$result	= $this->dbClassObj->query($sqlbunch);
							$subjectarr = array();
							while($line = $this->dbClassObj->fetch_assoc($result))
							{
								$limtvalue = $arrval*$line['break'];
								$subjectarr[$limtvalue] = number_format($line['score_percentage'], 0);
							}
							$edplace_loop_score[$subject] =	@implode('!~!',$subjectarr);     /* 1->M, 2->E, 3->S , 1,2,3->All */
						}
					}
					$scorearr = $edplace_loop_score;
					/******************************/
					return $scorearr;
					/* EdPlace Score End */
		}
		
		//Getting the students by parent Id and check worksheet is assigned to student or not
		public function getStudentWorksheetAssignmentStatus(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parent_id = $this->getUidFromUserToken($token);
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				//$parent_id = $data->parent_id;
				$worksheet_id = $data->worksheet_id;
				$responseArray =  array();
				$i=0;
				if (isset($parent_id)) {
				
					//GET: worksheet subject id
					$ws_query = $this->dbClassObj->query("SELECT subject FROM `ep_worksheets` WHERE id = ".$worksheet_id);
					if($this->dbClassObj->num_rows($ws_query)>0){
						$ws_query = $this->dbClassObj->fetch_assoc($ws_query);
						$subject_id = $ws_query['subject'];
					}else{ echo json_encode(array( 'error' => ERROR_EXISTS , 'message' => 'Invalid Worksheet ID')); exit; }
					
					//GET: the student's subject id
					$student_query = $this->dbClassObj->query("SELECT U.id as student_id FROM ep_wsusers AS U LEFT JOIN ep_subscription_parent AS P ON P.parent_id = U.id LEFT JOIN ep_subscription_student AS S ON S.student_id = U.id WHERE U.parent_id =".$parent_id." AND ( S.is_current = '1' OR S.is_addedby_parent_account = '1' ) AND U.delete_status = '0' AND S.is_drafted = '0' AND (S.subject_id = ".$subject_id." || S.subject_id=9)");
					
					// echo "SELECT U.id as student_id FROM ep_wsusers AS U LEFT JOIN ep_subscription_parent AS P ON P.parent_id = U.id LEFT JOIN ep_subscription_student AS S ON S.student_id = U.id WHERE U.parent_id =".$parent_id." AND ( S.is_current = '1' OR S.is_addedby_parent_account = '1' ) AND U.delete_status = '0' AND S.is_drafted = '0' AND (S.subject_id = ".$subject_id." || S.subject_id=9)"; exit;
					
					if($this->dbClassObj->num_rows($student_query) > 0){ 
						$i=0; $j=0; $students_string='';
						while($student_id = $this->dbClassObj->fetch_assoc($student_query)){ 
							$student_array[$j] = $student_id['student_id']; $j++; 
							$students_string .= $students_string.$student_id['student_id'].',';
						} 
						$students_string = substr($students_string,0,-1);
					}
					else{ echo json_encode(array( 'error' => ERROR_EXISTS , 'message' => 'No students are subscribed with current subject')); exit; }

					$queryStu = $this->dbClassObj->query("select U.id,U.fname,U.lname, TRIM(CONCAT(U.fname,' ',U.lname)) as student_name from ep_wsusers U ,ep_subscription_student S where U.user_type='student' and S.student_id=U.id and S.is_current='1' and U.parent_id= '".$parent_id."' AND U.id IN (".$students_string.")");
					
					// echo "select U.id, TRIM(CONCAT(U.fname,' ',U.lname)) as student_name from ep_wsusers U ,ep_subscription_student S where U.user_type='student' and S.student_id=U.id and S.is_current='1' and U.parent_id= '".$parent_id."' AND U.id IN (".$students_string.")"; exit;
					
					while($student = $this->dbClassObj->fetch_assoc($queryStu)){

						if(empty($student['student_name'])){ $student['student_name'] = $student['fname'].' '.$student['lname']; }
						
						$responseArray[$i] = $student;

						$queryRel = $this->dbClassObj->query("SELECT * FROM ep_wsassigned WHERE studentId = ".$student['id']." AND worksheetId = ".$worksheet_id." AND is_complete='0'");
						
						$worksheetDetails = $this->dbClassObj->fetch_assoc($queryRel);
						if(empty($worksheetDetails['id'])){$responseArray[$i]['assigned'] = 0;}
						else{$responseArray[$i]['assigned'] = 1;}
						$i++;
					}
					
					if(!empty($responseArray)){ $response = array( 'error' => STATUS_OK , 'message' => 'Student details' , 'response' => $responseArray);}
					else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Student details not found'); }
				}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Parent Token is required' , 'response' => NULL); }
			}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Post data (Worksheet ID) is required' , 'response' => NULL); }
			echo json_encode($response);
		}
		
		//Assigned the worksheets to the students
		public function AssignWorksheetToStudent(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				$student_ids = $data->student_ids;
				$worksheet_id = $data->worksheet_id;

				$student_arr = explode(',',$student_ids);

				//Setting worksheet information variables
				$worksheetInfo = $this->dbClassObj->query("SELECT id, worksheetCat, worksheetName, subject FROM ep_worksheets WHERE id = ".$worksheet_id);
				$worksheetInfo = $this->dbClassObj->fetch_assoc($worksheetInfo);
				$wsCat = $worksheetInfo['worksheetCat'];

				$responseArray = $message = array();
				$i=0;
				if (!empty($student_arr)) {
					foreach($student_arr as $studentId){
						//Setting parent information variables
						$parentInfo = $this->dbClassObj->query("SELECT CONCAT(fname,' ',lname) as student_name, parent_id FROM ep_wsusers WHERE id = ".$studentId);
						$parentInfo = $this->dbClassObj->fetch_assoc($parentInfo);
						$parentId = $parentInfo['parent_id'];
					
						//CHECK: the worksheet is already assigned or not
						$queryStu = $this->dbClassObj->query("select distinct(studentId) from ep_wsassigned where dateAppeared ='0000-00-00 00:00:00' and worksheetId=".$worksheet_id." and studentId=".$studentId);
						$queryVal = $this->dbClassObj->fetch_assoc($queryStu);
						if(empty($queryVal)){
							$sql = $this->dbClassObj->query("insert into ep_wsassigned set worksheetId=".$worksheet_id.", worksheetCat=".$wsCat.", studentId=".$studentId.", parentId=".$parentId.", dateAssigned='".date('Y-m-d H:i:s')."'");
							
							if(!empty($sql)){ 
								//$response  = 'Worksheet assigned to '.$parentInfo['student_name'].' successfully';
								$response  = 'Worksheet successfully assigned';
								$message['message'] = $response;
								//$message['message'][$i] = $response;
								//$i++;
							}
						}else{
							//$response  = 'Worksheet already assigned to '.$parentInfo['student_name'];
							$response  = 'Worksheet successfully assigned';
							$message['message'] = $response;
							// $message['message'][$i] = $response;
							//$i++;
						}
						
						//~ echo '<pre>';
						//~ print_r($worksheetInfo);
						//~ die;
						
						//CREATE: a response array having the assigned worksheet details.
						$responseArray = array();
						$responseArray['worksheet_id'] = $worksheetInfo['id'];
						$responseArray['worksheet_name'] = $worksheetInfo['worksheetName'];
						$responseArray['questions'] = $this->_getQuestionsCountByWorksheet($worksheetInfo['id']);
						$responseArray['subject'] = $this->_getSubjectByWorksheet($worksheetInfo['id']);
						$responseArray['topic'] = $this->_getTopicByWorksheet($worksheetInfo['id']);
						$responseArray['curriculum_topic'] = $this->_getCurriculumTopicByWorksheet($worksheetInfo['id']);
						$responseArray['curriculum_subtopic'] = $this->_getCurriculumSubTopicByWorksheet($worksheetInfo['id']);
						$responseArray['level'] = $this->_getLevelByWorksheet($worksheetInfo['id']);
						
						if(!empty($message)){ $response = array( 'error' => STATUS_OK , 'message' => $message, 'response'=>$responseArray);}
						else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Something went wrong. Please try again later!'); }
					}
				}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Student ID(s) are required' , 'response' => NULL); }
			}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Post data (Student ID(s) , Worksheet ID) are required' , 'response' => NULL); }
			echo json_encode($response); die;
		}
		
		//Remove assigned worksheet from the students section
		public function removeAssignWorksheetFromStudent(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parent_id = $this->getUidFromUserToken($token);
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				$student_id = $data->student_id;
				//$parent_id = $data->parent_id;
				$assignment_id = $data->assignment_id;
				
				if(!empty($student_id)){
					if(!empty($parent_id)){
						if(!empty($assignment_id)){
							//Find the worksheet is available or not.
							$queryFind = "SELECT * FROM ep_wsassigned WHERE id = ".$assignment_id." AND studentId = ".$student_id." AND parentId = ".$parent_id;
							$queryFind = $this->dbClassObj->query($queryFind);
							$queryFind = $this->dbClassObj->fetch_assoc($queryFind);
							if(!empty($queryFind)){
								//DELETE Query execution
								$query = "DELETE FROM ep_wsassigned WHERE id = ".$assignment_id." AND studentId = ".$student_id."
								AND parentId = ".$parent_id;
								$query = $this->dbClassObj->query($query);
								if(!empty($query)){ $response = array('error' => STATUS_OK,'message'=>'Worksheet unassigned successfully');}
								else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Something went wrong. Please try again later!'); }
							}else{$response = array( 'error' => ERROR_EXISTS , 'message' => 'Assignment not exists');}
						}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Assignment ID is required'); }
					}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Parent token is required'); }
				}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Student ID is required'); }
			}
					else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Post data (Student ID(s) , Worksheet ID) are required' , 'response' => NULL); }
			echo json_encode($response);
		}
		
		//Students details by parent id
		public function getStudentDetailByParent(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
				
				$parent_id = $this->getUidFromUserToken($token);					
					
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				
				//$parent_id = $data->parent_id;
				$responseArray = array(); $i=0;
				
				if (!empty($parent_id)) {
					//Setting parent information variables
					
					$studentInfo = $this->dbClassObj->query("SELECT u.id, TRIM(CONCAT(u.fname,' ',u.lname)) as student_name, u.password, u.user_name , u.address, u.town_city, u.county, u.telephone, u.email, u.subject, u.age, u.student_year, u.date_reg, u.date_exp, u.auto_assign_y_n FROM ep_wsusers u, ep_subscription_student s WHERE u.user_type='student' and s.student_id=u.id and s.is_current='1' and u.parent_id = ".$parent_id);
					
					while($studentDetails = $this->dbClassObj->fetch_assoc($studentInfo)){
						
						//CHECK: the full name is empty then user_name is used
						if(empty($studentDetails['student_name'])){ $studentDetails['student_name'] = $studentDetails['user_name']; }
						unset($studentDetails['user_name']);
						
						$responseArray[$i] = $studentDetails;
						$i++;
					}
					$response = array( 'error' => STATUS_OK , 'message' => 'Student details', 'response'=>$responseArray); 
				}else{
					$response = array( 'error' => ERROR_EXISTS , 'message' => 'Parent ID is required');
					}
				}else {
						if($this->isPrivate($token)) {
							// Error message of "Token has been expired"
							$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
						}
						else{ 
							// Error message of "Token has been expired"
							$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
						}
					}	
					
			}else{
				$response = array( 'error' => ERROR_EXISTS , 'message' => 'Post data (Parent ID) is required');
			}
			echo json_encode($response);
		}
		
		
		//Get student points by User-Token
		public function student_points(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$user_token = $this->getToken();
			   if($user_token && $this->validateToken($user_token) == TRUE ) {
				$student_id = $this->getUidFromUserToken($user_token);
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				//$student_id = $data->student_id;
				
				$responseArray = array();
				
				if (!empty($student_id)) {
					$checkUser = $this->dbClassObj->query("SELECT user_type FROM ep_wsusers WHERE id = ".$student_id);
					$checkUserResult = $this->dbClassObj->fetch_assoc($checkUser);
					if($checkUserResult['user_type'] == 'student'){					
						$joincondition = " usr.id = asg.studentId"; 
						$condition = " asg.studentId = ".$student_id;
					}else{
						$joincondition = " usr.id = asg.parentId";						
						$condition = " asg.parentId= ".$student_id;
					}
					
					
					$studentInfo = $this->dbClassObj->query("SELECT usr.fname,usr.lname, TRIM(CONCAT(usr.fname,' ',usr.lname)) as student_name, sum( asg.points_collected ) AS points_collected FROM ep_wsassigned as asg LEFT JOIN ep_wsusers as usr ON ". $joincondition ." WHERE ".$condition." AND asg.is_complete = '1' AND ( asg.worksheetCat = '1' OR ( asg.worksheetCat = '2' AND asg.dateChecked != '0000-00-00 00:00:00'))");
					$studentPoints = $this->dbClassObj->fetch_assoc($studentInfo);
					//CHECK: if fname is null or not
					if(!empty($studentPoints['fname'])){ 
						//CHECK: the full name is empty then user_name is used
						if(empty($studentPoints['student_name'])){ 
							$studentPoints['student_name'] = $studentPoints['fname'].' '.$studentPoints['lname']; 
						}
					}
					if(!empty($studentPoints)){
						$response = array( 'error' => STATUS_OK , 'message' => 'Student details', 'response'=>$studentPoints); 
					}else{$response = array( 'error' => STATUS_OK , 'message' => 'Student details', 'student_points'=>0); }
					
				}else{ $response = array( 'error' => USER_TOKEN_EXPIRED , 'message' => USER_TOKEN_EXPIRED_MSG); }
			}
			else {$response = array('error' => USER_TOKEN_EXPIRED, 'messsage' => USER_TOKEN_EXPIRED_MSG);}
			}//else{ $response = array( 'error' => 1 , 'message' => 'Post data (Student ID) is required'); }
			echo json_encode($response);
		}
		
		//Students dashboard by User-Token
		public function studentDashboard(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$user_token = $this->getToken();
			    if($user_token && $this->validateToken($user_token) == TRUE ) {
				$student_id = $this->getUidFromUserToken($user_token);
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				
				//$student_id = $data->student_id;
				//$parent_id = $data->parent_id;
				$responseArray = array();
				
				if (!empty($student_id)) {
					//Getting the ToDo worksheets
					$queryTodo = "SELECT count(*) as todo_worksheets FROM `ep_wsassigned` where studentId = ".$student_id." and is_complete = '0'";
					$queryTodo = $this->dbClassObj->query($queryTodo);
					$queryTodo = $this->dbClassObj->fetch_assoc($queryTodo);
					
					//Getting the Finished worksheets
					// $queryFinish = "SELECT count( A.id ) AS total_records FROM ep_wsassigned A, ep_worksheets B, ep_wssubjects D, ep_wslevel E, ep_wsusers C WHERE B.id = A.worksheetId AND D.id = B.subject AND E.id = B.level AND C.id = A.studentId AND A.studentId = '".$student_id."' AND A.dateAppeared != '0000-00-00 00:00:00' AND A.is_complete = '1' AND IF( A.worksheetCat =2, A.dateChecked !=  '0000-00-00 00:00:00', A.dateChecked =  '0000-00-00 00:00:00' ) ";
					$queryFinish = "SELECT count( A.id ) AS total_records FROM ep_wsassigned A, ep_worksheets B, ep_wssubjects D, ep_wslevel E, ep_wsusers C WHERE B.id = A.worksheetId AND D.id = B.subject AND E.id = B.level AND C.id = A.studentId AND A.studentId = '".$student_id."' AND A.dateAppeared != '0000-00-00 00:00:00' AND A.is_complete = '1'";
					
					$queryFinish = $this->dbClassObj->query($queryFinish);
					$queryFinish = $this->dbClassObj->fetch_assoc($queryFinish);

					//Total Badges
					$badges = $this->__countBadges($student_id);
					
					$query_pr = $this->dbClassObj->query("SELECT parent_id FROM ep_wsusers WHERE id = ".$student_id);
					$query_pr = $this->dbClassObj->fetch_assoc($query_pr);
					$parent_id = $query_pr['parent_id'];
					
					//First reward description
					$first_reward = $this->dbClassObj->query("SELECT rw.reward FROM ep_rewards AS rw LEFT JOIN ep_rewards_type AS rwtyp ON rwtyp.id = rw.reward_type_id WHERE rw.createdBy =".$parent_id." AND rw.student_id =".$student_id." AND rw.reward_status = 'Locked' ORDER BY rw.date_allocated ASC LIMIT 1,1");
					
					
					if($this->dbClassObj->num_rows($first_reward)>0){
						$first_reward = $this->dbClassObj->fetch_assoc($first_reward);
						$reward_desc = $first_reward['reward'];
					}else{$reward_desc = NULL;}
					
					//Total Rewards
					$rewards = $this->__count_rewardSet($student_id,$parent_id);
					
					//CODE STARTS - Getting the Scores by studentID
					$sql =  $this->dbClassObj->query("SELECT sum( score_collected ) AS score_collected, sum( score_total ) AS score_total, count( A.id ) AS worksheet_total FROM ep_wsassigned A, ep_worksheets B, ep_wssubjects D, ep_wslevel E, ep_wsusers C WHERE A.is_complete = '1' AND C.id = '".$student_id."' AND A.worksheetId = B.id AND B.subject = D.id AND B.level = E.id AND A.studentId = C.id and D.id in (1,2,3) AND ( B.worksheetCat='1' or ( B.worksheetCat='2' and A.dateChecked !='0000-00-00 00:00:00'))");
					$sqlArr = $this->dbClassObj->fetch_assoc($sql);

					if($sqlArr['score_total']>0){
						$score_percentage  = round((($sqlArr['score_collected']*100)/$sqlArr['score_total'])); 
					}
					else{ $score_percentage  = "0"; }
					
					$responseArray['todo_list'] = $queryTodo['todo_worksheets'];
					$responseArray['finish_list'] = $queryFinish['total_records'];
					$responseArray['total_badges'] = $badges;
					$responseArray['total_rewards'] = $rewards;
					if(!empty($reward_desc)){ $responseArray['reward_desc'] = urldecode($reward_desc); }else{ $responseArray['reward_desc'] = ""; }
					$responseArray['score_percentage'] = $score_percentage;
					$responseArray['average_percentage'] = $score_percentage.'%';
					
					//Latest notification
					$queryL = $this->dbClassObj->query("SELECT N.id,N.message,N.notic_type, N.other_related_fields FROM ep_student_notic_log AS N LEFT JOIN ep_reward_student_comment AS SC ON N.id = SC.notic_id WHERE 1 =1 AND N.notic_user = '".$student_id."' AND N.created_date >= ( now( ) - INTERVAL 365 DAY ) ORDER BY N.created_date DESC LIMIT 0 , 1");
					$queryF = $this->dbClassObj->fetch_assoc($queryL);
					//Setting the icon according to the notification type
					if($queryF['notic_type'] == 'badge'){ $responseArray['notification']['icon'] = 'images/badge_icon.png'; }
					if($queryF['notic_type'] == 'reward'){ $responseArray['notification']['icon'] = 'images/reward_icon.png'; }
					if($queryF['notic_type'] == 'score'){ 
						$responseArray['notification']['icon'] = 'images/score_icon.png'; 
						
						$score_collected = unserialize($queryF['other_related_fields']);
						$responseArray['score_percentage'] = ($score_collected['score_collected']*100)/$score_collected['score_total'];
					}
					$responseArray['notification']['message'] = $queryF['message'];
					
					if(!empty($responseArray)){
						$response = array( 'error' => STATUS_OK, 'message' => 'Student Dashboard', 'response'=>$responseArray);
					}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Results not found'); }

				}else{ $response = array( 'error' => USER_TOKEN_EXPIRED , 'message' => USER_TOKEN_EXPIRED_MSG); }
			}
			else {$response = array('error' => USER_TOKEN_EXPIRED, 'messsage' => USER_TOKEN_EXPIRED_MSG);}
			}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => POST_ERROR_MSG); }
			echo json_encode($response);
		}
		
		//Students ToDo worksheet list by student Token
		public function todoWorksheets(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$user_token = $this->getToken();
			    if($user_token && $this->validateToken($user_token) == TRUE ) {
				$student_id = $this->getUidFromUserToken($user_token);
				$data = file_get_contents('php://input');
				//$data = json_decode($data);
				
				//$student_id = $data->student_id;
				$response = $responseArray = array();
				
				if (!empty($student_id)) {
					
					$charset="SET CHARACTER SET utf8";
					$this->dbClassObj->query($charset);
					
					//Getting the ToDo worksheets
					$queryTodo = "SELECT wks.id,asg.id as assignment_id, wks.worksheetName, wks.is_worksheet_on_ios, wks.worksheetCat, wks.subject, wks.level, asg.dateAssigned, asg.dateAppeared, usr.self_assign_y_n,count(ques.id) as total_questions FROM ep_wsassigned AS asg LEFT JOIN ep_worksheets AS wks ON wks.id = asg.worksheetId LEFT JOIN ep_wsusers AS usr ON usr.id = asg.studentId LEFT JOIN ep_questions AS ques ON ques.worksheetId = asg.worksheetId WHERE asg.studentId =".$student_id." AND asg.is_complete = '0' GROUP BY asg.id ORDER BY assignment_id DESC";
					
					$queryTodo = $this->dbClassObj->query($queryTodo);
					$i = 0;
					while($queryRow = $this->dbClassObj->fetch_assoc($queryTodo)){
						
						//~ $queryRow['worksheetName'] = utf8_decode($queryRow['worksheetName']);
						
						//GET: number of questions associated with every worksheet
						$score_total = $this->dbClassObj->query("SELECT count(*) as score_total FROM `ep_questions` WHERE worksheetId =".$queryRow['id']);
						$score_total = $this->dbClassObj->fetch_assoc($score_total);
						$queryRow['score_total'] = $score_total['score_total'];
						
						$subject = $queryRow['subject_id'] = $queryRow['subject'];
						$sub = array('Maths','English','Science');
						$queryRow['subject'] = $sub[$subject-1];
						$responseArray[$subject][$i] = (array)$queryRow;
						$i++;
					}
					if(count($responseArray) > 0){
						$response['Maths'] = array_values($responseArray[1]);
						$response['English'] = array_values($responseArray[2]);
						$response['Science'] = array_values($responseArray[3]);
						unset($responseArray);
					}	
					/*if(count($responseArray) > 0){

						$response['Maths'] = $response['English'] = $response['Science'] = array();

						if(is_array($responseArray[1]) && count($responseArray[1])>0){
							$response['Maths'] = array_values($responseArray[1]);
						}
						
						if(is_array($responseArray[2]) && count($responseArray[2])>0){
							$response['English'] = array_values($responseArray[2]);
						}
						
						if(is_array($responseArray[3]) && count($responseArray[3])>0){
							$response['Science'] = array_values($responseArray[3]);
						}

						unset($responseArray);
					}	*/				
					if(!empty($response)){
						$response = array( 'error' => STATUS_OK , 'message' => 'ToDo Worksheet list', 'response'=>$response);
					}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'List not found'); }
				}else{ $response = array( 'error' => USER_TOKEN_EXPIRED , 'message' => USER_TOKEN_EXPIRED_MSG); }
			}
			else {$response = array('error' => USER_TOKEN_EXPIRED, 'messsage' => USER_TOKEN_EXPIRED_MSG);}
			}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => POST_ERROR_MSG); }
			echo json_encode($response);
		}
		
		//Students Finished worksheet list by student Token
		public function finishedWorksheets(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$user_token = $this->getToken();
			    if($user_token && $this->validateToken($user_token) == TRUE ) {
				$student_id = $this->getUidFromUserToken($user_token);
				$data = file_get_contents('php://input');
				//$data = json_decode($data);
				
				//$student_id = $data->student_id;
				$response = $responseArray = array();
				
				if (!empty($student_id)) {
					
					$charset="SET CHARACTER SET utf8";
					$this->dbClassObj->query($charset);
					
					//Getting the Finished worksheets
					//$queryFinish = "SELECT B.id, B.worksheetName, B.subject, B.level, A.score_total FROM ep_wsassigned A, ep_worksheets B, ep_wssubjects D, ep_wslevel E, ep_wsusers C WHERE B.id = A.worksheetId AND D.id = B.subject AND E.id = B.level AND C.id = A.studentId AND A.studentId = '".$student_id."' AND A.dateAppeared != '0000-00-00 00:00:00' AND A.is_complete = '1'";
					$queryFinish = "SELECT B.id,A.id as assignment_id, B.worksheetName, B.worksheetCat, B.subject, B.level, A.score_total, Y.year, A.score_collected, A.is_complete, B.topicTags, A.dateAssigned, A.dateAppeared FROM ep_wsassigned A, ep_wsyears Y, ep_worksheets B, ep_wssubjects D, ep_wslevel E, ep_wsusers C WHERE B.id = A.worksheetId AND D.id = B.subject AND E.id = B.level AND C.id = A.studentId AND A.studentId = '".$student_id."' AND A.dateAppeared != '0000-00-00 00:00:00' AND A.is_complete = '1' AND Y.id = B.year ORDER BY dateAppeared DESC";
					$queryFinish = $this->dbClassObj->query($queryFinish);
					$i = 0;
					if($this->dbClassObj->num_rows($queryFinish)>0){
						$totalRows = $this->dbClassObj->num_rows($queryFinish);
						while($queryRow = $this->dbClassObj->fetch_assoc($queryFinish)){
							
							//SET: isChecked value
							$queryRow['isChecked'] = 0;if($queryRow['worksheetCat']=='1' || ($queryRow['worksheetCat']=='2' && $queryRow['dateChecked']!='0000-00-00 00:00:00')){$queryRow['isChecked'] = 1;}else{$queryRow['isChecked'] = 0;}
							
							//~ $queryRow['worksheetName'] = utf8_decode($queryRow['worksheetName']);
							
							$subject = $queryRow['subject'];
							$responseArray[$subject][$i] = (array)$queryRow;
							$i++;
						}
					}
					if(count($responseArray) > 0){
						$response['Maths'] = array_values($responseArray[1]);
						$response['English'] = array_values($responseArray[2]);
						$response['Science'] = array_values($responseArray[3]);
						unset($responseArray);
					}
					if(!empty($response) || count($response)>0){
						$response = array( 'error' => STATUS_OK, 'message' => 'Finished Worksheet list', 'response'=>$response);
					}else{ $response = array( 'error' => STATUS_OK , 'message' => "You haven't completed any worksheets yet. They'll show up here once you have."); }
				}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'Student Token is required'); }
			}
			else {$response = array('error' => USER_TOKEN_EXPIRED, 'messsage' => USER_TOKEN_EXPIRED_MSG);}
			}else{ $response = array( 'error' => ERROR_EXISTS , 'message' => 'OOPS! Something Went Wrong.'); }
			echo json_encode($response);
		}
		
		function myTaskListPerformance($student_id,$start,$pagesize,$order,$subject){   
			$sno         = $start+1;
			$arr         = array();    
			$record_list = array();        
			$query = "SELECT sum( A.score_collected ) AS total_score_collected, sum( A.score_total ) AS total_score_sum, A.studentId, F.id AS topic_id, F.topicTags, F.subject_id, D.subject FROM ep_wsassigned A, ep_worksheets B, ep_wssubjects D, ep_wslevel E, ep_wsusers C, ep_wstopictags F WHERE A.worksheetId = B.id AND B.subject = D.id AND B.level = E.id AND A.studentId = C.id AND B.topic_id = F.id AND A.is_complete = '1' AND A.studentId = '".$student_id."' AND F.subject_id IN (".$subject.") GROUP BY topic_id Having total_score_collected <> 0 ORDER BY ((sum( A.score_collected )/sum( A.score_total ))*100) ".$order." LIMIT 0 , 5";  
			
			$result = $this->dbClassObj->query($query);       
			while($line = $this->dbClassObj->fetch_assoc($result))
			{
				$line['sno']   = $sno;       
				if($line['total_score_sum']>0){
					$line['total_score_percentage'] =  number_format( ($line['total_score_collected']*100)/$line['total_score_sum'], 0 )."%"; 
					$line['total_score_percentage_remaining'] =  number_format( 100- ( ($line['total_score_collected']*100)/$line['total_score_sum'] ), 0 )."%"; 
				}
				$record_list[] = $line;
				$sno++;
			}                     
			$arr     = $record_list;
			return $arr;
		} 
		
		//Best/Worst Performing Topics
		public function studentPerformanceForParent(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				$student_id = $data->student_id;
				$subject_id = $data->subject_id;
				$response = array();
				if (!empty($student_id)) {
				
					switch($subject_id){
						case 0:
							$response['best_topic_listing']  = $this->myTaskListPerformance($student_id, 0, 5,'desc','1,2,3');
							$response['worst_topic_listing']  = $this->myTaskListPerformance($student_id,0, 5,'asc','1,2,3');
							break;
						case 1:
							$response['best_topic_maths']  = $this->myTaskListPerformance($student_id, 0, 6,'desc','1');
							break;
						case 2:
							$response['best_topic_english']  = $this->myTaskListPerformance($student_id, 0, 6,'desc','2');
							break;
						case 3:
							$response['best_topic_science']  = $this->myTaskListPerformance($student_id, 0, 6,'desc','3');
							break;
						default:
							$response['best_topic_listing']  = $this->myTaskListPerformance($student_id, 0, 5,'desc','1,2,3');
							$response['worst_topic_listing']  = $this->myTaskListPerformance($student_id,0, 5,'asc','1,2,3');
							break;
					}
					if(!empty($response)){
						$response = array( 'error' => 0 , 'message' => 'Worksheet performance list', 'response'=>$response);
					}else{ $response = array( 'error' => 1 , 'message' => 'List not found'); }
				}else{ $response = array( 'error' => 1 , 'message' => 'Student ID is required'); }
			}else{ $response = array( 'error' => 1 , 'message' => 'Post data (Student ID) is required'); }
			echo json_encode($response);
		}
		
		//Get My Notifications details
		public function my_notifications(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parent_id = $this->getUidFromUserToken($token);
				$data = file_get_contents('php://input');
				//$data = json_decode($data);
				//$parent_id = $data->parent_id;
				$responseArray = array();
				
				if(!empty($parent_id)){
					$query = "SELECT * FROM `ep_parent_settings` WHERE parent_id = ".$parent_id;
					$query = $this->dbClassObj->query($query);
					$query = $this->dbClassObj->fetch_assoc($query);
					if(!empty($query)){
						$responseArray['worksheet_check'] = $query['worksheet_check'];
						$responseArray['reached_reward'] = $query['reached_reward'];
						$responseArray['progress_report'] = $query['progress_report'];
						$responseArray['worksheet_suggestion'] = $query['worksheet_suggestion'];
						
						//Adding the html with student names
						$str = 'Manage your email alerts, with \'My Notifications\' Check the boxes to receive an email when ';
						$query = "select U.id, TRIM(CONCAT(U.fname,' ',U.lname)) as student_name, U.auto_assign_year from ep_wsusers U ,ep_subscription_student S where U.user_type='student' and S.student_id=U.id and S.is_current='1' and U.parent_id= ".$parent_id;
						$query = $this->dbClassObj->query($query);
						$total_rows_or = $total_rows = $this->dbClassObj->num_rows($query);
						while($query_var = $this->dbClassObj->fetch_assoc($query)){
							
							if($total_rows_or > 1){
								if($total_rows == 1){
									$str = substr($str,0,-1);
									$str .= ' and '.$query_var['student_name'];
								}else{
									$str .= $query_var['student_name'].',';
								}$total_rows--;
							}else{
								$str .= $query_var['student_name'];
							}
						}
						$str .= ' reaches certain goals.';
						$responseArray['notification_html'] = $str;
						if(!empty($responseArray)){
							$response = array('error'=>STATUS_OK,'message'=>'Notifications Settings','response'=>$responseArray);
						}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Notifications settings not found');}
					}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Notifications settings not found');}
				}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Parent Token is required');}
			}
			else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
					echo json_encode($response);exit;
				}
			}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'OOPS! Something Went Wrong.');}
			echo json_encode($response);
		}
		
		//Update My Notifications details
		public function update_notifications(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parent_id = $this->getUidFromUserToken($token);
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				//$parent_id = $data->parent_id;
				$worksheet_check = $data->worksheet_check;
				$reached_reward = $data->reached_reward;
				$progress_report = $data->progress_report;
				$worksheet_suggestion = $data->worksheet_suggestion;
				$responseArray = array();
				
				if(!empty($parent_id)){
					$query = "SELECT * FROM `ep_parent_settings` WHERE parent_id = ".$parent_id;
					$query = $this->dbClassObj->query($query);
					$query = $this->dbClassObj->fetch_assoc($query);
					if(!empty($query)){
						$query2 = $this->dbClassObj->query("UPDATE ep_parent_settings SET worksheet_check = '".$worksheet_check."' , reached_reward = '".$reached_reward."' , progress_report = '".$progress_report."' , worksheet_suggestion = '".$worksheet_suggestion."' WHERE parent_id = ".$parent_id);
					}else{	
						$query2 = $this->dbClassObj->query("INSERT INTO ep_parent_settings( parent_id, worksheet_check, reached_reward, progress_report, worksheet_suggestion, pro_date_1, pro_date_2, pro_date_3, wk_sug_date ) VALUES ( ".$parent_id.", '".$worksheet_check."', '".$reached_reward."', '".$progress_report."', '".$worksheet_suggestion."',now(),now(),now(),now())");
					}
					if(!empty($query2)){
						$response = array('error'=>STATUS_OK,'message'=>'Notifications updated successfully','response'=>$responseArray);
					}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Something went wrong. Please try again!');}
				}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Parent Token is required');}
			}
			else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
					echo json_encode($response);exit;
				}
			}else{	$response = array('error'=>ERROR_EXISTS,'message'=>POST_ERROR_MSG);}
			echo json_encode($response);
		}
		
		//Get Countries
		public function countries(){
			$token = $this->getToken();
			if($token && $this->validateToken($token) == TRUE ) {
			$response = array(); $i=0;
			$query = "SELECT countries_id, countries_name FROM ep_countries";
			$query = $this->dbClassObj->query($query);
			while($row = $this->dbClassObj->fetch_assoc($query)){
				$response[$i]['country_id'] = $row['countries_id'];
				$response[$i]['country_name'] = $row['countries_name'];
				$i++;
			}
			echo json_encode(array('response'=>$response));
		}
		else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
					echo json_encode($response);exit;
				}
		}
		
		//Get My Details
		public function parent_my_details(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parent_id = $this->getUidFromUserToken($token);
				$data = file_get_contents('php://input');
				//$data = json_decode($data);
				//$parent_id = $data->parent_id;
				$responseArray = array();
				
				if(!empty($parent_id)){
					$query = "SELECT * FROM `ep_wsusers` WHERE id = ".$parent_id;
					$query = $this->dbClassObj->query($query);
					$query = $this->dbClassObj->fetch_assoc($query);
					if(!empty($query)){
						
						$responseArray['fname'] 	= $query['fname'];
						$responseArray['lname'] 	= $query['lname'];
						$responseArray['email'] 	= $query['email'];
						$responseArray['address'] 	= $query['address'];
						$responseArray['town_city']	= $query['town_city'];
						$responseArray['county'] 	= $query['county'];
						$responseArray['post_code'] = $query['post_code'];
						$responseArray['country_code'] = $query['country'];
						
						if(!empty($query['country'])){
							//Getting the name of country by country ID
							$queryCont = $this->dbClassObj->query("SELECT countries_name FROM ep_countries WHERE countries_id = ".$query['country']);
							$queryCont = $this->dbClassObj->fetch_assoc($queryCont);
							$responseArray['country'] 	= $queryCont['countries_name'];
						}else{
							$responseArray['country']=NULL;
						}
						$responseArray['telephone'] = $query['telephone'];
						
						if(!empty($responseArray)){
							$response = array('error'=>STATUS_OK,'message'=>'Parent Details','response'=>$responseArray);
						}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Details not found');}
					}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Details not found');}
				}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Parent Token is required');}
			}
			else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'OOPS! Something Went Wrong.');}
			echo json_encode($response);
		}
		
		//Update My Details
		public function parent_update_details(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parent_id = $this->getUidFromUserToken($token);
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				
				//$parent_id = $data->parent_id;
				$fname = $data->fname;
				$lname = $data->lname;
				$email = $data->email;
				$address = $data->address;
				$town_city = $data->town_city;
				$county = $data->county;
				$post_code = $data->post_code;
				$country = $data->country;
				$telephone = $data->telephone;
				
				$responseArray = array();
				
				if(!empty($parent_id)){
					$query = "SELECT * FROM `ep_wsusers` WHERE id = ".$parent_id;
					$query = $this->dbClassObj->query($query);
					$query = $this->dbClassObj->fetch_assoc($query);
					if(!empty($query)){
						$query2 = $this->dbClassObj->query("UPDATE ep_wsusers SET fname = '".$fname."',lname = '".$lname."', email = '".$email."',address = '".$address."',town_city = '".$town_city."',county = '".$county."',post_code = '".$post_code."',country = ".$country.",telephone = ".$telephone." WHERE id = ".$parent_id);
						if(!empty($query2)){
							$response = array('error'=>STATUS_OK,'message'=>'Details updated successfully');
						}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Something went wrong. Please try again!');}
					}else{ $response = array('error'=>ERROR_EXISTS,'message'=>'Parent details not exists'); }
				}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Parent Token is required');}
			}
			else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}else{	$response = array('error'=>ERROR_EXISTS,'message'=>POST_ERROR_MSG);}
			echo json_encode($response);
		}
		
		//Rewards by Student Token
		public function badges_by_student(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$user_token = $this->getToken();
			    if($user_token && $this->validateToken($user_token) == TRUE ) {
				$student_id = $this->getUidFromUserToken($user_token);
				$data = file_get_contents('php://input');
				//$data = json_decode($data);
				
				//$student_id = $data->student_id;
				$responseArray = array();
				
				if(!empty($student_id)){
					$badges = $this->__studentBadges($student_id);
					if(!empty($badges)){
						$response = array('error'=>STATUS_OK,'message'=>'Student Badges','response'=>$badges);
					}else{	$response = array("error"=>ERROR_EXISTS,"message"=>"You haven't unlocked any badges yet");}
				}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Student Token is required');}
			}
			else {$response = array('error' => USER_TOKEN_EXPIRED, 'messsage' => USER_TOKEN_EXPIRED_MSG);}
			}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'OOPS! Something wrong Occured.');}
			echo json_encode($response);
		}
		
		//Get every student's rewards list + details with parent ID
		public function rewards_by_parent(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				$parent_id = $data->parent_id;
				$responseArray = array();
				if(!empty($parent_id)){
					$rewards = $this->__rewardSetByParent($parent_id);
					if(!empty($rewards)){
						$response = array('error'=>0,'message'=>'Student Rewards','response'=>$rewards);
					}else{	$response = array('error'=>1,'message'=>'Rewards not exists');}
				}else{	$response = array('error'=>1,'message'=>'Parent ID is required');}
			}else{	$response = array('error'=>1,'message'=>'POST data ( Parent ID ) is required');}
			echo json_encode($response);
		}
		
		//Get every student's rewards list by student Token
		public function rewards_by_student(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$student_id = $this->getUidFromUserToken($token);
				$data = file_get_contents('php://input');
				//$data = json_decode($data);
				// $parent_id = $data->parent_id;
				//$student_id = $data->student_id;
				$responseArray = array();
				if(!empty($student_id)){
					$query = $this->dbClassObj->query("SELECT id, CONCAT(fname,' ',lname) as student_name, parent_id FROM ep_wsusers WHERE id = ".$student_id);
					// $query = mysql_query("SELECT id, CONCAT(fname,' ',lname) as student_name FROM ep_wsusers WHERE id = ".$student_id." AND parent_id = ".$parent_id);
					$info = $this->dbClassObj->fetch_assoc($query);
					$rewards['id'] = $info['id'];
					$rewards['student_name'] = $info['student_name'];
					$parent_id = $info['parent_id'];
					$rewards['rewards'] = $this->__rewardSetByStudent($parent_id,$student_id);
					if(!empty($rewards)){
						$response = array('error'=>STATUS_OK,'message'=>'Student Rewards','response'=>$rewards);
					}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Rewards not exists');}
				}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'Student Token is required');}
			}
			else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}else{	$response = array('error'=>ERROR_EXISTS,'message'=>'OOPS! Something Went Wrong.');}
			echo json_encode($response);
		}
		
		//Get the rewards categories
		public function rewards_types(){
			$responseArray = array(); 
			$inc = 0;			
			$token = $this->getToken();
			$this->validateToken($token);
			if($token && $this->validateToken($token) == TRUE ) {
				
				$query = $this->dbClassObj->query("SELECT * FROM `ep_rewards_type` WHERE STATUS = '1'");
				while($record = $this->dbClassObj->fetch_assoc($query)){
					$responseArray[$inc]['id'] = $record['id'];
					$responseArray[$inc]['reward_name'] = $record['reward_name'];
					$responseArray[$inc]['reward_icon'] = $record['reward_icon'];
					$responseArray[$inc]['reward_large_icon'] = $record['reward_large_icon'];
					$inc++;
				}
				if(!empty($responseArray)){
					$response = array('error'=> STATUS_OK,'message'=>'Rewards Types','response'=>$responseArray);
				}else{
					$response = array('error'=>ERROR_EXISTS,'message'=>'Rewards types not found');
				}
			}else {
				if($this->isPrivate($token)) {
					// Error message of "Token has been expired"
					$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
				}
				else{ 
					// Error message of "Token has been expired"
					$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
				}
			}
			echo json_encode($response);
		}
		
		//Add reward
		//Add reward
		public function add_reward(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
				$parent_id = $this->getUidFromUserToken($token);
				
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				$arr_details['createdBy']	=  $parent_id;
				$arr_details['student_id']	=  $data->student_id;
				$arr_details['reward']		=  addslashes(urldecode($data->description));
				$arr_details['link']		=  $data->website_link; 
				$arr_details['points_allocated']	=  $data->points; 
				$arr_details['reward_type_id']		=  $data->reward_type_id; 
				$arr_details['date_allocated']	=  date("Y-m-d H:i:s"); 
				$arr_details['dateCreated']	=  date("Y-m-d H:i:s"); 
				$replace_str 			= 	array('http://','https://');
				$replace_str_through 	= 	array('','');		
				if($arr_details['link']!=''){
					$arr_details['link']='http://'.str_replace($replace_str,$replace_str_through,$arr_details['link']);
				}
				if(!is_numeric($data->points)){ echo json_encode(array('error'=>1,'message'=>'Points are to be of integer format!')); exit; }
				
				$responseArray = array();
				if(!empty($arr_details)){
					$addQuery = $this->dbClassObj->query("INSERT INTO ep_rewards (createdBy, student_id, reward, link, points_allocated, reward_type_id, date_allocated,dateCreated) VALUES(".$arr_details['createdBy'].",".$arr_details['student_id'].",'".$arr_details['reward']."','".$arr_details['link']."',".$arr_details['points_allocated'].",".$arr_details['reward_type_id'].",'".$arr_details['date_allocated']."','".$arr_details['dateCreated']."')");
					
					$last_insert_id =  $this->dbClassObj->latest_id();
					
					if(!empty($addQuery)){
						
						//INSERT: timeline notification
							//GET: reward name
							$reward_name = $this->dbClassObj->query("SELECT * FROM ep_rewards_type WHERE id = ".$arr_details['reward_type_id']);
							$reward_name = $this->dbClassObj->fetch_assoc($reward_name);
							//GET: last inserted reward details
							// $reward_details = mysql_query("SELECT * FROM ep_rewards WHERE id = ".$last_insert_id);
							// $reward_details = mysql_fetch_assoc($reward_details);
							$reward_name['reward'] = $arr_details['reward'];
							$reward_name['link'] = $arr_details['link'];
							
							$reward_name['points_allocated'] = $arr_details['points_allocated'];
							$reward_name['points_collected'] = 0;
							
							//INSERT: insertion process
							$r_msg	= 'A new reward has been set for you';
							$r_notic_sql=$this->dbClassObj->query("INSERT INTO ep_student_notic_log(id, notic, message, notic_type, notic_action, notic_type_id, notic_user, is_viewed, created_date, modified_date, other_related_fields) values ('', '".$reward_name['reward_name']."', '".$r_msg."', 'reward','added', '".$arr_details['reward_type_id']."', '".$arr_details['student_id']."', '0', now(), now(), '".serialize($reward_name)."')");
						
						$response = array('error'=>STATUS_OK,'message'=>'Reward successfully created');
					}
					else{
						$response = array('error'=>ERROR_EXISTS,'message'=>'Something went wrong. Please try again later!');
						}
				}else{
					$response = array('error'=>ERROR_EXISTS,'message'=>'All fields are required');
				}
				}else {
						if($this->isPrivate($token)) {
							// Error message of "Token has been expired"
							$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
						}
						else{ 
							// Error message of "Token has been expired"
							$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
						}
					}
			}
			else{
				$response = array('error'=>ERROR_EXISTS,'message'=>POST_ERROR_MSG);
			}
			echo json_encode($response);
		}
		
		//Update reward
		public function update_reward(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
					$data = file_get_contents('php://input');
					$data = json_decode($data);
					$arr_details['id']	=  $data->reward_id;
					$arr_details['student_id']	=  $data->student_id;
					$arr_details['reward']		=  $data->description; 
					$arr_details['link']		=  $data->website_link; 
					$arr_details['points_allocated']	=  $data->points; 
					$arr_details['reward_type_id']		=  $data->reward_type_id; 
					$arr_details['date_allocated']	=  date("Y-m-d H:i:s"); 
					$arr_details['dateCreated']		=  date("Y-m-d H:i:s"); 
					$replace_str 			= 	array('http://','https://');
					$replace_str_through 	= 	array('','');		
					if($arr_details['link']!=''){
						$arr_details['link']='http://'.str_replace($replace_str,$replace_str_through,$arr_details['link']);
					}
					$responseArray = array();
					if(!empty($arr_details['id'])){
						if(!empty($arr_details)){
							//~ $updateQuery = mysql_query("UPDATE ep_rewards SET student_id = ".$arr_details['student_id'].", reward = '".$arr_details['reward']."', link = '".$arr_details['link']."', points_allocated = ".$arr_details['points_allocated'].", reward_type_id = ".$arr_details['reward_type_id'].", date_allocated = '".$arr_details['date_allocated']."', dateCreated = '".$arr_details['dateCreated']."' WHERE id = ".$arr_details['id']);
							
							$r_status = $this->_rewardStatus($arr_details['id']);
							
							if($r_status == 1){
								$updateQuery = $this->dbClassObj->query("UPDATE ep_rewards SET student_id = ".$arr_details['student_id'].", reward = '".$arr_details['reward']."', link = '".$arr_details['link']."', points_allocated = ".$arr_details['points_allocated'].", reward_type_id = ".$arr_details['reward_type_id']." WHERE id = ".$arr_details['id']);
								$message = 'Reward has been updated successfully';
							}else{ 
								$updateQuery = 'Update not required'; $message = 'Reward cannot be updated'; 
							}
							
							if(!empty($updateQuery)){
								$response = array('error'=>STATUS_OK,'message'=>$message);
							}
							else{
								$response = array('error'=>ERROR_EXISTS,'message'=>'Something went wrong. Please try again later!');
							}
							
						}else{	
							$response = array('error'=>ERROR_EXISTS,'message'=>'All fields are required');
						}
					}else{	
							$response = array('error'=>ERROR_EXISTS,'message'=>' ID is required');
					}
				}else {
						if($this->isPrivate($token)) {
							// Error message of "Token has been expired"
							$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
						}
						else{ 
							// Error message of "Token has been expired"
							$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
						}
					}
			}else{
				$response = array('error'=>ERROR_EXISTS,'message'=>POST_ERROR_MSG);
			}
			echo json_encode($response);
		}
		
		public function _rewardStatus($rewardId){
			$selectQuery = $this->dbClassObj->query("SELECT * FROM ep_rewards WHERE id =".$rewardId." AND points_collected = 0");
			if($this->dbClassObj->num_rows($selectQuery)>0){  return 1; }
			else{ return 0; }
		}
		
		//Get reward details
		public function get_reward_details(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				$id	=  $data->reward_id;
				$responseArray = array();
				if(!empty($id)){
					$query = $this->dbClassObj->query("SELECT * FROM ep_rewards WHERE id = ".$id);
					$arrayResult = $this->dbClassObj->fetch_assoc($query);
					if(!empty($arrayResult)){$response = array('error'=>0,'message'=>'Reward Details','response'=>$arrayResult);}
					else{	$response = array('error'=>0,'message'=>'Reward details not found');}
				}else{	$response = array('error'=>1,'message'=>' ID is required');}
			}else{	$response = array('error'=>1,'message'=>'POST data is required');}
			echo json_encode($response);
		}
		
		//Remove reward details
		public function remove_reward(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
					$data = file_get_contents('php://input');
					$data = json_decode($data);
					$id	=  $data->reward_id;
					$responseArray = array();
					if(!empty($id)){
						$query = $this->dbClassObj->query("DELETE FROM ep_rewards WHERE id = ".$id);
						if(!empty($query)) { 
							$response = array('error'=>0,'message'=>'Reward deleted successfully');
						}
						else {	
							$response = array('error'=>0,'message'=>'Reward details not found');
						}
					}
					else {
						$response = array('error'=>1,'message'=>' ID is required');
					}
				}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => 3, 'message' => "User Token has been expired.");
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => 2, 'message' => "Access Token has been expired.");
					}
				}
			}
			else { 
				$response = array('error'=>1,'message'=>'POST data is required');
			}
			echo json_encode($response);
		}
		
		//Getting student progress
		public function student_progress(){
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
				$student_id = $this->getUidFromUserToken($token);
				$json_data = file_get_contents('php://input');
				$json_data = json_decode($json_data);
				//$student_id = $json_data->student_id;
				$respnseArray = array();
				//$years = $json_data->years;

				if(!empty($student_id)){
					$query = $this->dbClassObj->query("SELECT id, CONCAT(fname,' ',lname) as student_name FROM ep_wsusers WHERE id = ".$student_id);
					$studentInfo = $this->dbClassObj->fetch_assoc($query);
					$respnseArray['student_id'] = $studentInfo['id'];
					$respnseArray['student_name'] = $studentInfo['student_name'];
					
					if(!empty($studentInfo['id'])){
						//For the individual subjects
						$str_subjects = array(
							'overall'	=>	'1,2,3',
							'math'		=>	'1',
							'english'	=>	'2',
							'science'	=>	'3'
						);
						
						foreach( $str_subjects as $key => $data )
						{
							//CODE STARTS - Getting the Scores by studentID
							
							  $sql = "
							  SELECT 
							  	sum( score_collected ) AS score_collected, 
							  	sum( score_total ) AS score_total, 
							  	count( A.id ) AS worksheet_total 
							  FROM 
							  	ep_wsassigned A, 
							  	ep_worksheets B, 
							  	ep_wssubjects D, 
							  	ep_wslevel E, 
							  	ep_wsusers C 
							  WHERE 
							  	A.is_complete = '1' AND 
							  	C.id = '".$student_id."' AND 
							  	A.worksheetId = B.id AND 
							  	B.subject = D.id AND 
							  	B.level = E.id AND 
							  	A.studentId = C.id AND 
							  	( 
							  		B.worksheetCat='1' or 
							  			( 
							  				B.worksheetCat='2' and 
							  				A.dateChecked !='0000-00-00 00:00:00'
							 			)
							  	)";
							 
							
							$count_ws = $this->dbClassObj->query("SELECT count( A.id ) AS worksheet_total FROM ep_wsassigned A, ep_worksheets B, ep_wssubjects D, ep_wslevel E, ep_wsusers C WHERE B.id = A.worksheetId AND D.id = B.subject AND E.id = B.level AND C.id = A.studentId AND A.studentId = '".$student_id."' AND A.dateAppeared != '0000-00-00 00:00:00' AND A.is_complete = '1'");
							$count_ws = $this->dbClassObj->fetch_assoc($count_ws);
							$count_ws = $count_ws['worksheet_total'];

							if($data!=''){	$sql .= " and D.id in (".$data.") "; }
							
							$sqlObj = $this->dbClassObj->query($sql);
							$sqlArr = $this->dbClassObj->fetch_assoc($sqlObj);
							
							//$respnseArray['scores'][$key]['worksheet_total']  = $sqlArr['worksheet_total'];
							$respnseArray['scores'][$key]['worksheet_total']  = $count_ws;
							
							if($sqlArr['score_total']>0){
								$respnseArray['scores'][$key]['score_percentage']  = round((($sqlArr['score_collected']*100)/$sqlArr['score_total'])); 
							}
							else{ $respnseArray['scores'][$key]['score_percentage']  = "0"; }       
							
							//Topics
							$respnseArray['best_topic_listing']  = $this->myTaskListPerformance($student_id, 0, 5,'desc','1,2,3');
							$respnseArray['worst_topic_listing']  = $this->myTaskListPerformance($student_id,0, 5,'asc','1,2,3');
						}
						$response = array('error'=>STATUS_OK,'message'=>'Scores details','response'=>$respnseArray);
					}else{ 
						$response = array('error'=> ERROR_EXISTS,'message'=>'Student does not  exists');
						}
				}else{
						$response = array('error'=> ERROR_EXISTS,'message'=>'POST data (Student ID) required');
						}
			}else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			}
			else{ 
				$response = array('error'=>ERROR_EXISTS,'message'=>'POST data (Student ID) required');
			}
			echo json_encode($response);
		}
		
		//Student Timeline
		public function student_timeline(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$id = $this->getUidFromUserToken($token);
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				//$id	=  $data->student_id;
				$notice_type	=  $data->notice_type;
				
				$this->update_timeline_notifications($id);
				
				if($notice_type == 'all'){ $notice_type = "badge','reward','score"; }
				$responseArray = array();
				if(!empty($id)){
					$inc=0;
					$query = $this->dbClassObj->query("SELECT N . * , SC.comments, SC.id AS comment_id FROM ep_student_notic_log AS N LEFT JOIN ep_reward_student_comment AS SC ON N.id = SC.notic_id WHERE 1 =1 AND N.notic_user = '".$id."' AND N.notic_type IN('".$notice_type."') AND N.created_date >= ( now( ) - INTERVAL 365 DAY ) ORDER BY N.created_date DESC");
					while($returnArray = $this->dbClassObj->fetch_assoc($query)){
						
						//SET: the scores variables (points collected,points allocated)
						$additionalInfo = unserialize($returnArray['other_related_fields']);
						//echo "<pre>"; print_r($returnArray); die;
						if($returnArray['notic_type'] == 'badge'){ 
							$no_badge_type	=array(4,5,6,10,11);
							$conditonBadge	=array(1,2,3);
							$nName		= $returnArray['notic'];
							$subjectarr		=array('1'=>'maths_','2'=>'english_','3'=>'science_');	
							$description	 = '';
							$nrelated		 = $additionalInfo['id'];
							$subject_id 	 = $additionalInfo['subject_id'];
							$badges_type_id	 = $additionalInfo['badges_type_id'];
							$star_count 	 = ($additionalInfo['is_found']==1)?$additionalInfo['badges_id']:0;
							$badges_level	 = (in_array($nrelated,$no_badge_type))?'':$nName;
							$subjectClass	 = ($subject_id)?$subjectarr[$subject_id]:'';
							$badge_star_type = $subjectClass.'badge_type'.$star_count;
							$badge_star_type = ($star_count)?$badge_star_type:'no_badge_type'; 
							$badge_star_type = (in_array($nrelated,$no_badge_type))?'no_badge_type':$badge_star_type;	
							$is_active 		 = ($star_count)?'':'_inactive';
							$b_in_image		 = SITE_URL.substr($additionalInfo['badges_icon'], 0, -4).$is_active.'.png';
							$b_image		 = ($star_count)?SITE_URL.$additionalInfo['badges_icon']:$b_in_image;		
							$description 	 = ($additionalInfo['is_found']==1)?stripslashes(base64_decode($additionalInfo['completed_description'])):$additionalInfo['badges_description'];
							$badges_subjects =(!in_array($badges_type_id,$conditonBadge))?$additionalInfo['badges_subjects']:$additionalInfo['badges_subjects'].' '.$additionalInfo['badge_title'];
							
							$returnArray['icon_path'] = 'images/badge_icon.png'; 
							// $returnArray['badge_title'] = $additionalInfo['badge_title'];
							$returnArray['badge_title'] = $badges_subjects;
							$returnArray['badges_icon'] = $additionalInfo['badges_icon'];
							// $description = ($additionalInfo['is_found']==1)?stripslashes(base64_decode($additionalInfo['completed_description'])):$additionalInfo['badges_description'];
							$returnArray['badges_description'] = $description;
							$returnArray['notic'] = $badges_level;
						}
						if($returnArray['notic_type'] == 'reward'){ 
							$returnArray['icon_path'] = 'images/reward_icon.png'; 
							$returnArray['reward_title'] = $additionalInfo['reward'];
							$returnArray['reward_image'] = $additionalInfo['reward_large_icon'];
							$points_allocated = $additionalInfo['points_allocated'];
							$points_completed = $additionalInfo['points_completed'];
							if($points_allocated == $points_completed) {
								$returnArray['points_required'] = $points_completed.' points';
							}
							else{
								$points_required = $points_allocated - $points_completed;
								$returnArray['points_required'] = $points_required.' points';
							}
						}
						if($returnArray['notic_type'] == 'score'){ 
							$returnArray['icon_path'] = 'images/score_icon.png'; 
							$returnArray['score_collected'] = $additionalInfo['score_collected'];
							$returnArray['score_total'] = $additionalInfo['score_total'];
						}
						
						$responseArray[$inc] = $returnArray;
						$inc++;
					}
					if(count($responseArray)>0){
						$response = array('error'=>STATUS_OK,'message'=>'Student Timeline','response'=>$responseArray);
						}
					else{	
						$response = array('error'=>ERROR_EXISTS,'message'=>'Reward details not found');
					}
				}else{
					$response = array('error'=>ERROR_EXISTS,'message'=>'User-Token is required');
				}
			}
			else {
				if($this->isPrivate($token)) {
					// Error message of "Token has been expired"
					$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);				
				}
				else{ 
					// Error message of "Token has been expired"
					$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
				}
				
				}
			}else{
				$response = array('error'=>ERROR_EXISTS,'message'=>POST_ERROR_MSG);
				}
			echo json_encode($response);
		}

		//Assigned the worksheets by the students
		public function worksheet_assigned_by_student(){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				$studentId = $data->student_id;
				$worksheet_id = $data->worksheet_id;

				//Setting worksheet information variables
				$worksheetInfo = $this->dbClassObj->query("SELECT * FROM ep_worksheets WHERE id = ".$worksheet_id);
				$worksheetInfo = $this->dbClassObj->fetch_assoc($worksheetInfo);
				$wsCat = $worksheetInfo['worksheetCat'];

				$responseArray = $message = array();
				$i=0;
				if (!empty($studentId)) {
					//Setting parent information variables
					$parentInfo = $this->dbClassObj->query("SELECT CONCAT(fname,' ',lname) as student_name, parent_id, self_assign_y_n FROM ep_wsusers WHERE id = ".$studentId);
					$parentInfo = $this->dbClassObj->fetch_assoc($parentInfo);
					$parentId = $parentInfo['parent_id'];
					$selfAssign = $parentInfo['self_assign_y_n'];
					
					if($selfAssign == '1'){
						//CHECK: the worksheet is already assigned or not
						$queryStu = $this->dbClassObj->query("select distinct(studentId) from ep_wsassigned where dateAppeared ='0000-00-00 00:00:00' and worksheetId=".$worksheet_id." and studentId=".$studentId);
						$queryVal = $this->dbClassObj->fetch_assoc($queryStu);
						if(empty($queryVal)){
							$sql = $this->dbClassObj->query("insert into ep_wsassigned set worksheetId=".$worksheet_id.", worksheetCat=".$wsCat.", studentId=".$studentId.", parentId=".$parentId.", dateAssigned='".date('Y-m-d H:i:s')."'");
							
							if(!empty($sql)){ 
								//$response  = 'Worksheet assigned to '.$parentInfo['student_name'].' successfully';
								$response  = 'Worksheet assigned successfully';
								$message['message'] = $response;
								//$message['message'][$i] = $response;
								//$i++;
							}
						}else{
							//$response  = 'Worksheet already assigned to '.$parentInfo['student_name'];
							$response  = 'Worksheet already assigned';
							$message['message'] = $response;
							// $message['message'][$i] = $response;
							//$i++;
						}
						
						if(!empty($message)){ $response = array( 'error' => 0 , 'message' => $message);}
						else{ $response = array( 'error' => 1 , 'message' => 'Something went wrong. Please try again later!'); }					
					}else{ $response = array( 'error' => 0 , 'message' => 'Student having no permission for the self assignment'); }					
				}else{
					$response = array( 'error' => 1 , 'message' => 'Student ID(s) are required' , 'response' => NULL);
				}
			}else{
				$response = array( 'error' => 1 , 'message' => 'Post data (Student ID(s) , Worksheet ID) are required' , 'response' => NULL);
			}
			echo json_encode($response);
		}
	
		public function change_password(){
			if($_SERVER['REQUEST_METHOD']=='POST') 
			{
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
					$parent_id = $this->getUidFromUserToken($token);
					
					$jsondata = file_get_contents('php://input');
					$jsondata = json_decode($jsondata);
					//$parent_id = $jsondata->parent_id;
					$old_pw = $this->hasherObj->createHash($jsondata->old_password);
					$new_pw = $this->hasherObj->createHash($jsondata->new_password);
					
					$parent_query = $this->dbClassObj->query("SELECT * FROM ep_wsusers WHERE id = ".$parent_id." AND user_type IN ('parent','teacher')");
					$parentInfo = $this->dbClassObj->fetch_assoc($parent_query);
					
					if(!empty($parentInfo['id'])){
						if(!empty($old_pw)){
							$parent_query = $this->dbClassObj->query("SELECT * FROM ep_wsusers WHERE id = ".$parent_id." AND password = '".$old_pw."'");
							$parentInfo = $this->dbClassObj->fetch_assoc($parent_query);
							if(!empty($parentInfo['id'])){
								if(!empty($new_pw)){
									$parent_query = $this->dbClassObj->query("UPDATE ep_wsusers SET password = '".$new_pw."' WHERE id = ".$parent_id);
									if(!empty($parent_query)){
										$response = array('error' => STATUS_OK,'message' => "Password updated successfully"); 
									}else{ 
										$response = array('error' => ERROR_EXISTS,'message' => 'Something went wrong. Please try again later!'); 
									}
								}else{ 
									$response = array('error' => ERROR_EXISTS, 'message' => 'New password required'); 
									
								}
							}else{
								$response = array('error' => ERROR_EXISTS, 'message' => 'Wrong old password');
								
							}
						}else{
							$response = array('error' => ERROR_EXISTS, 'message' => 'Old password required');

						}
					}else {
						$response = array('error' => ERROR_EXISTS, 'message' => PARENT_ERROR_MSG);
						
						}
				}				
				else {
						if($this->isPrivate($token)) {
							// Error message of "Token has been expired"
							$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
						}
						else{ 
							// Error message of "Token has been expired"
							$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
						}
					}
			}else{ 
						$response = array('error' => ERROR_EXISTS, 'message' => POST_ERROR_MSG); 
				}
			
			echo json_encode($response);
		}
		
		//Student Timeline
		public function parent_subscription() {
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
					$id = $this->getUidFromUserToken($token);
				
					$data = file_get_contents('php://input');
					$data = json_decode($data);
					//$id	  =  $data->parent_id;
					$responseArray = array();
					if(!empty($id)){
						$inc=0;
						
						//Adding the HTML line
						$htmlQuery = $this->dbClassObj->query("SELECT * FROM `ep_subscription_parent` where parent_id = ".$id);
						$htmlQuery = $this->dbClassObj->fetch_assoc($htmlQuery);
						$price = ceil($htmlQuery['plan_price']);
						
						//Change format of date from YYYY-MM-DD to DD/MM/YYYY
						$end_date = $htmlQuery['subs_end_date'];
						$temp_arr_1 = explode('-',$end_date);
						krsort($temp_arr_1);
						$end_date = implode('/',$temp_arr_1);
						
						
						/* $responseArray['title_line'] = "Your EdPlace subscription is Active. Your subscription will automatically renew and you will be charged £".$price.".00 on ".$end_date; */
						$diff_days 	= round((strtotime($htmlQuery['subs_end_date'])- strtotime(date("Y-m-d")))/(60*60*24));
						
						$renewal_amount ='£'. $htmlQuery['plan_price'];
						$renewal_date = date("d/m/Y", strtotime($htmlQuery['subs_end_date']));
						if($htmlQuery['subs_status']  == 2) {
							$responseArray['title_line'] = "Your EdPlace subscription was cancelled on ".date('d/m/Y', strtotime($htmlQuery['cancelled_date'])).". To continue using EdPlace select the plans that are right for you and enter your credit card information.";		
							} else if($htmlQuery['subs_status'] == 1) {
								if($htmlQuery['plan_type'] == 1) {
									if($htmlQuery['is_free_signup']=='1' && $diff_days >= 0)/* Free Trial Coupon Case */ {
										$responseArray['title_line'] = "Your account expires in ".$diff_days." days. To continue using EdPlace after your trial expires, select your subscription and enter your credit card information.";
									} else if($htmlQuery['is_free_signup']=='2' && $diff_days >= 0)/*Cancel Account being actived by Admin*/ {
										$responseArray['title_line'] = "Your EdPlace subscription was cancelled on ".date('d/m/Y', strtotime($htmlQuery['cancelled_date'])).". Your account will remain active till ".$renewal_date." (".$diff_days." days). To continue using EdPlace after this, please select your subscription below and click Confirm to pay by credit/debit card.";
									} else if($htmlQuery['is_free_signup']=='3' && $diff_days >= 0)/*Inactive Account being actived by Admin*/ {
										$responseArray['title_line'] = "Your account will remain active till ".$renewal_date." (".$diff_days." days). To continue using EdPlace after this, please select your subscription and pay by credit/debit card.";
									} else {
										$responseArray['title_line'] = "Your account has expired. To continue using EdPlace select the plans that are right for you and enter your credit card information.";
									}
								} else if($htmlQuery['plan_type']==3 || $htmlQuery['plan_type']==2) {
									if($diff_days >= 1) {
										$responseArray['title_line'] = "Your EdPlace subscription is Active. Your subscription will automatically renew and you will be charged ".$renewal_amount." on ".$renewal_date;
									} else if($diff_days <= 0 && $diff_days >=-3) {
										$renewal_date3 = date("d/m/Y", strtotime("3 days", strtotime($htmlQuery['subs_end_date'])));
										/* $not_in_critical_time=false;*/
										$responseArray['title_line'] = "Your EdPlace subscription is Active. Your subscription will automatically renew and you will be charged ".$renewal_amount." on ".$renewal_date;
										$responseArray['title_line'] .="<br/><b>Note:</b> We're still attempting to collect your payment. We'll try again until ".$renewal_date3." and your account will remain active during this time. If you have any questions please contact support@edplace.com.";
									} else {
									$responseArray['title_line'] = "Your account has expired. To continue using EdPlace select the plans that are right for you and enter your credit card information.";
								}	
							}	
						} else {
							$responseArray['title_line'] = "Your account has expired. To continue using EdPlace select the plans that are right for you and enter your credit card information.";
						}
						
						
						/* if ($htmlQuery['is_free_signup'] == 3 || $htmlQuery['is_free_signup'] == 1) {
							$responseArray['title_line'] = "Your account will remain active till".$end_date.". To continue using Edplace after this , please select your subscription and pay by credit/debit card";
							
						} */
						
						//Setting the Outer dictionary
						$plan_type_arr 	= array('7'=>'Weekly','30'=>'Monthly','91'=>'Quarterly','182'=>'Semi','365'=>'Annual');
						$query2 = $this->dbClassObj->query("SELECT SP . * , U.user_subs_days FROM ep_subscription_parent AS SP, ep_wsusers AS U WHERE SP.parent_id = U.id AND SP.parent_id =".$id);
						while($row = $this->dbClassObj->fetch_assoc($query2)){
							$responseArray['total_price'] = '£'.$row['plan_price'].'.00';
							$plan_days = $row['user_subs_days'];
							foreach($plan_type_arr as $key=>$value){
								if($key == $plan_days){ $responseArray['plan_type'] = $value; }
								else{ $responseArray['plan_type'] = ''; }
							}
							
							switch($row['plan_type']){
								case 1:
									$responseArray['plan_type'] = 'Free Trial';
									break;
								case 2: 
									$responseArray['plan_type'] = 'Monthly';
									break;
								case 3: 
									$responseArray['plan_type'] = 'Annual';
									break;
								case 4: 
									$responseArray['plan_type'] = 'Debit Card';
									break;
								default:
									$responseArray['plan_type'] = '';
							}
							
							if($row['subs_status'] == 2 || $row['subs_status'] == 0 || $row['is_free_signup']!='0') {
								if($row['plan_type'] == 1){
									$responseArray['plan_type'] = 'Annual';
								}
							}
						}

						//Setting the Student dictionary
						$total_price = 0;
						//~ $query = mysql_query("SELECT U.fname, U.lname, P.subs_status AS p_subs_status, S.* FROM ep_wsusers AS U LEFT JOIN ep_subscription_parent AS P ON P.parent_id = U.id LEFT JOIN ep_subscription_student AS S ON S.student_id = U.id WHERE U.parent_id =".$id." AND ( S.is_current = '1' OR S.is_addedby_parent_account = '1' ) AND U.delete_status = '0' AND S.is_drafted = '0'");

						$query = $this->dbClassObj->query("SELECT U.fname, U.lname, P.subs_status AS p_subs_status, S.* FROM ep_wsusers AS U LEFT JOIN ep_subscription_parent AS P ON P.parent_id = U.id LEFT JOIN ep_subscription_student AS S ON S.student_id = U.id WHERE U.parent_id =".$id." AND ( S.is_current = '1' ) AND U.delete_status = '0' AND S.is_drafted = '0'");
						
						while($returnArray = $this->dbClassObj->fetch_assoc($query)){
							$responseArray['students'][$inc]['student_id'] = $returnArray['student_id'];
							$responseArray['students'][$inc]['student_name'] = $returnArray['fname'].' '.$returnArray['lname'];
							$responseArray['students'][$inc]['plan_price'] = '£'.$returnArray['plan_price'].'.00';
							$responseArray['students'][$inc]['plan_subject'] = $this->plan_subject($returnArray['subject_id']);
							$total_price = $total_price + $returnArray['plan_price'];
							$inc++;
						}
						$responseArray['total_price'] = '£'.$total_price.'.00';
						if(count($responseArray)>0){
							$response = array('error'=>STATUS_OK,'message'=>'Parent subscription details','response'=>$responseArray);
						}
						else{	
							$response = array('error'=>ERROR_EXISTS,'message'=>'Information not found');
						}
					}else{
						$response = array('error'=>ERROR_EXISTS,'message'=>'Parent ID is required');
					}
			}else {
				if($this->isPrivate($token)) {
					// Error message of "Token has been expired"
					$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
				}
				else{ 
					// Error message of "Token has been expired"
					$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
				}
			}
			
			}else{	
					$response = array('error'=>ERROR_EXISTS,'message'=>POST_ERROR_MSG);
			}
			echo json_encode($response);
		}
		
		
		public function plan_subject($type = 0){
			switch($type){
				case 1: return 'Maths'; break;
				case 2: return 'English'; break;
				case 3: return 'Science'; break;
				case 9: return 'All Subjects'; break;
				default: return 'No Subject'; 
			}
		}
		
		public function getdiscountedprice($discount_plan_price,$plan_type)
		{
			$newPricearr =array();
			if($plan_type ==2 || $plan_type =='2')
			{
				$c_subscription_type='Monthly';
			}
			elseif($plan_type ==3 || $plan_type =='3')
			{
				$c_subscription_type='Annual';
			}
			else
			{
				$c_subscription_type='All';
			}

			/*following query will execute according to plan type*/	
			$coupon_qury =" SELECT c.coupons_discount_type AS discount_type, c.coupons_discount_amount AS discount_amount ";
			$coupon_qury.=" FROM ep_discount_coupons AS c, ep_discount_coupons_to_parent AS cp WHERE c.coupons_id = cp.coupons_id ";
			$coupon_qury.=" AND c.coupons_status = 'Active' AND datediff( c.coupons_end_date, NOW( ) ) >0 AND ";
			$coupon_qury.=" cp.parent_id =".$_SESSION['sessU_uid'] ." AND c.subscription_type like '%".$c_subscription_type."%'";
			$c_result	 = $this->dbClassObj->query($coupon_qury);
			$c_num_row 	 = $this->dbClassObj->num_rows($c_result);

			/*following query will execute for, all type spacific copon code*/	
			$coupon_all_qury =" SELECT c.coupons_discount_type AS discount_type, c.coupons_discount_amount AS discount_amount ";
			$coupon_all_qury.=" FROM ep_discount_coupons AS c, ep_discount_coupons_to_parent AS cp WHERE c.coupons_id = cp.coupons_id ";
			$coupon_all_qury.=" AND c.coupons_status = 'Active' AND datediff( c.coupons_end_date, NOW( ) ) >0 AND ";
			$coupon_all_qury.=" cp.parent_id =".$_SESSION['sessU_uid'] ." AND c.subscription_type like '%All%'";
			$c_all_result	 = $this->dbClassObj->query($coupon_all_qury);
			$c_all_num_row 	 = $this->dbClassObj->num_rows($c_all_result);

			$condition = 0;
			$condition_query_result='';

			if($c_num_row)
			{
				$condition = $c_num_row;
				$condition_query_result = $c_result;
			}
			if($c_all_num_row )
			{
				$condition =$c_all_num_row;
				$condition_query_result = $c_all_result;
			}
			
			$fixed_price = $discount_plan_price;
			$discount =0.00;
			if($condition)
			{
			$c_line 		= $this->dbClassObj->fetch_assoc($condition_query_result);
				if($c_line['discount_type']=='Percentage')
				{
				$discount = ($discount_plan_price * $c_line['discount_amount'])/100 ;
					if($discount<$discount_plan_price)
						{
							$discount_plan_price =($discount_plan_price - $discount) ;
						}
					else
						{
							$discount_plan_price =$fixed_price;
						}
				}
				else
				{
				$discount = $c_line['discount_amount'] ;		
					if($discount<$discount_plan_price)
						{
							$discount_plan_price = ($discount_plan_price - $discount) ;
						}
					else
						{
							$discount_plan_price =$fixed_price;
						}	
				}

			$newPricearr['discount_plan_price'] = number_format($discount_plan_price, 2, '.', '');
			$newPricearr['normal_price'] = number_format($fixed_price, 2, '.', '');
			$newPricearr['discount'] = number_format($discount, 2, '.', '');			
			}
			else
			{
				$newPricearr['discount_plan_price'] = number_format($discount_plan_price, 2, '.', '');
				$newPricearr['normal_price'] = number_format($fixed_price, 2, '.', '');
				$newPricearr['discount'] = number_format($discount, 2, '.', '');			
			}
			return $newPricearr;	
		}
		
		function updateAutoAssignWorksheetList($winfo, $worksheet_id, $student_id)
		{
			$unAssignedWkArr = array();
			$topicId = getTopicIdByStudentYear($student_id);
			$topicId = $topicId[0];
			$studentDetails = getStudentYearSubjectById();
			$studentYear = $studentDetails['auto_assign_year'];
			$studentYear = explode(',',$studentYear);
			$studentYear = implode("','",$studentYear);
			$rel_wk_sql="select id, subject, keyStage, year, topic_id from ep_worksheets where subject='".$winfo['subject']."' and keyStage='".$winfo['keyStage']."' and year='".$winfo['year']."' and topic_id = '".$topicId."' and status='1' order by level asc, id asc ";
			$rel_wk_res = $this->dbClassObj->query($rel_wk_sql);
			$gcount = $this->dbClassObj->num_rows($rel_wk_res);
		
			if($gcount==0){
				$rel_wk_sql="select id, subject, keyStage, year, topic_id from ep_worksheets where year IN('{$studentYear}') and topic_id = {$topicId} and status='1' order by level asc, id asc ";
				$rel_wk_res = $this->dbClassObj->query($rel_wk_sql);
				$gcount = $this->dbClassObj->num_rows($rel_wk_res);
			}
		
			if($gcount){
				while($line=$this->dbClassObj->fetch_assoc($rel_wk_res))
				{
					$is_allready_assigned_sql = $this->dbClassObj->query("select count(id) as nval from ep_wsassigned where  studentId='".$student_id."' and worksheetId='".$line['id']."' and is_complete='0'");
					$is_allready_assigned_res = $this->dbClassObj->fetch_assoc($is_allready_assigned_sql);
					$is_allready_assigned = $is_allready_assigned_res['nval'];
					
					$sqlravg ="select max(avg_total) as totmaxavg from (select (sum(score_collected)/sum(score_total)*100)  as avg_total from ep_wsassigned where  studentId='".$student_id."' and worksheetId='".$line['id']."' and is_complete='1' group by id) ta ";
					$re_avg_total_sql	= $this->dbClassObj->query($sqlravg);
					$re_avg_total_res	= $this->dbClassObj->fetch_assoc($re_avg_total_sql);
					$re_avg_total	= $re_avg_total_res['totmaxavg'];
					$re_avg_total	= number_format($re_avg_total,0);
					
					if(!$is_allready_assigned && $worksheet_id!=$line['id'] && $re_avg_total < 70){
						$unAssignedWkArr[] =$line;
					}
				}
				//RETURN: the array of worksheets which are next to assigned.
				return $unAssignedWkArr;
			}else{ return array(); }
		}
	
		function getTopicIdByStudentYear($student_id){
			$studentDetails = getStudentYearSubjectById($student_id);
			$studentYear = $studentDetails['auto_assign_year'];
			$studentSubject = $studentDetails['auto_assign_subject'];
			$studentSubject = ($studentSubject==9)?"1','2','3":$studentSubject;
			$studentYear = explode(',',$studentYear);
			$studentYear = implode("','",$studentYear);
			$allTopics = getAllTopicIdsByStudentYearSubject($studentYear, $studentSubject);
			$assignedTopics = getAssignedTopicIdsByStudent($student_id);
			$filteredTopics = array_diff($allTopics, $assignedTopics);
			$topicId = getTopicWithMoreWorksheets($studentYear, $studentSubject, $filteredTopics);
			return $topicId;
		}
	
	
		function getStudentYearSubjectById($student_id){
			$query = $this->dbClassObj->query("SELECT auto_assign_subject, auto_assign_year FROM ep_wsusers WHERE id = '".$student_id."'");
			$result = $this->dbClassObj->fetch_assoc($query);
			$result['auto_assign_year'] = ($result['auto_assign_year'])?$result['auto_assign_year']:0;
			$result['auto_assign_subject'] = ($result['auto_assign_subject'])?$result['auto_assign_subject']:0;
			return ($result)?$result:array();
		}
	
	
		function getAllTopicIdsByStudentYearSubject($studentYear, $studentSubject){
			$topicIds = array();
			$query = $this->dbClassObj->query("SELECT topic_id FROM ep_worksheets WHERE subject IN ('{$studentSubject}') and year IN ('{$studentYear}') group by topic_id");
			while($row = $this->dbClassObj->fetch_assoc($query)){
				$topicIds[] = $row['topic_id'];
			}
			//RETURN: array of all topic ids.
			return (count($topicIds)>0)?$topicIds:array();
		}
	
	
		function getAssignedTopicIdsByStudent($student_id){
			$worksheetsList = $topicIds = array();
			$query = $this->dbClassObj->query("SELECT worksheetId FROM ep_wsassigned where studentId = '".$student_id."' group by worksheetId order by id desc");
			while($row = $this->dbClassObj->fetch_assoc($query)){
				$worksheetsList[] = $row['worksheetId'];
			}
			$worksheetsList = implode("','",$worksheetsList);
			$query2 = $this->dbClassObj->query("SELECT topic_id FROM ep_worksheets where id in('{$worksheetsList}') group by topic_id");
			//FETCH: topic id and create new array of list.
			while($row2 = $this->dbClassObj->fetch_assoc($query2)){
				$topicIds[] = $row2['topic_id'];
			}
			//RETURN: the resultant array of already assigned topic ids.
			return (count($topicIds)>0)?$topicIds:array();
		}
	
	
		function getTopicWithMoreWorksheets($studentYear, $studentSubject, $filteredTopics){
			if(count($filteredTopics)>0){
				$topics = array();
				foreach($filteredTopics as $topicId){
					$query = $this->dbClassObj->query("SELECT count(*) as total FROM ep_worksheets where subject IN ('{$studentSubject}') and year in('{$studentYear}') and topic_id = {$topicId} and status = '1'");
					$result = $this->dbClassObj->fetch_assoc($query);
					$topics[$topicId] = $result['total'];
				}
				$finalTopicId = array_keys($topics, max($topics));
				return ($finalTopicId>0)?$finalTopicId:0;
			}else{ return 0; }
		}
		
		public function worksheet_result() {
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
					$student_id = $this->getUidFromUserToken($token);
					if(!$student_id) {
						$student_id = "guest";
					} 
						
					$jsondata = file_get_contents('php://input');
					//print_r($jsondata); exit;
					$jsondata = json_decode($jsondata);
					
					//SET: variables
					//$student_id = $jsondata->student_id;
					$worksheet_id = $jsondata->worksheet_id;
					$worksheetcheck_wid = $jsondata->worksheet_id;
					$answers = (array)$jsondata->answers;
					
					$charset="SET CHARACTER SET utf8";
					$this->dbClassObj->query($charset);
					
					//CHECK: student_id not empty
					if(empty($student_id)){$response = array('error' => ERROR_EXISTS, 'message' => 'Student Token is required');echo json_encode($response);exit;}

					//CHECK: worksheet_id not empty
					if(empty($worksheet_id)){$response = array('error' => ERROR_EXISTS, 'message' => 'Worksheet ID is required');echo json_encode($response);exit;}
					
					//CHECK + REDIRECT: if worksheet submission is done by the guest user then it will be calculated with different cases
					if($student_id == 'guest'){ $this->guest_worksheet_result($worksheet_id,$answers); exit; }
					
					//CHECK: student is exists or not
					$student_query = $this->dbClassObj->query("SELECT * FROM ep_wsusers WHERE id = ".$student_id." AND user_type = 'student'");
					$studentInfo = $this->dbClassObj->fetch_assoc($student_query);
					if(empty($studentInfo['id'])){$response = array('error' => ERROR_EXISTS, 'message' => 'Student not exists');echo json_encode($response);exit;}
					
					//CHECK: worksheet is assigned to student or not
					$assigned = $this->dbClassObj->query("SELECT * FROM `ep_wsassigned` WHERE worksheetId = ".$worksheet_id." AND studentId = ".$student_id." AND is_complete = '0'");
					$assigned = $this->dbClassObj->fetch_assoc($assigned);
					if(empty($assigned['id'])){$response = array('error' => ERROR_EXISTS, 'message' => 'Worksheet is not assigned');echo json_encode($response);exit;}
					
					//CHECK: questions are available in worksheet or not
					$questions = $this->dbClassObj->query("SELECT count(*) as total_ques FROM ep_questions WHERE worksheetId = ".$worksheet_id);
					$questions = $this->dbClassObj->fetch_assoc($questions);
					if(empty($questions['total_ques'])){$response = array('error' => ERROR_EXISTS, 'message' => 'Questions not available in worksheet');echo json_encode($response);exit;}
					
					//-------------CHECKPOINT 1: all minimum checks are passed---------------
					
					//SET: declaring the null value in the keys of responseArray because of the listing on top in json response
					$responseArray = array('student_id'=>$student_id , 'worksheet_id'=>$worksheet_id , 'total_points'=>0 , 'worksheet_points'=>0 , 'points_collected'=>0,'desc_message'=>'','title_message'=>'','rewards'=>'');

					$responseArray['worksheet_name'] = $this->_getWorksheetNameByWorksheet($worksheet_id);
					
					$responseArray['curriculum_topic'] = $this->_getCurriculumTopicByWorksheet($worksheet_id);
					$responseArray['curriculum_subtopic'] = $this->_getCurriculumSubTopicByWorksheet($worksheet_id);
					$responseArray['level'] = $this->_getLevelByWorksheet($worksheet_id);
					$responseArray['topic'] = $this->_getTopicByWorksheet($worksheet_id);
					$responseArray['subject'] = $this->_getSubjectByWorksheet($worksheet_id);
					$responseArray['worksheet_year'] = $this->_getYearByWorksheet($worksheet_id);

					//GET: the all questions details based upon the worksheet ID from variable '$questions'
					$points_collected = $i = 0;
					$questions = $this->dbClassObj->query("SELECT id, question, answerType, totalAnswerBoxes, correctAnswer, matrix_correct_answers, explanation, status, matrix_type, matrix_row, matrix_column, caseSensitive, apostrophes FROM ep_questions WHERE worksheetId = ".$worksheet_id." ORDER BY id ASC");
					while($singleQues = $this->dbClassObj->fetch_assoc($questions)){
						$singleQues['question'] = stripslashes($singleQues['question']);
						(array)$responseArray['questions'][$i] = $singleQues;	//SET: response array

							//CONDITION: question ID matches or not
							if( $answers[$i]->question_id == $singleQues['id']){ 
								
								$responseArray['questions'][$i]['std_answer'] = $answers[$i]->answer;
								
								if($singleQues['answerType'] != 5){
									//INSERT: insertion in the answers table
									$insert_query = $this->dbClassObj->query("INSERT INTO ep_answers(assignmentId,questionId,answer,dateAnswered) VALUES(".$assigned['id'].",".$singleQues['id'].",'".addslashes($answers[$i]->answer)."','".date('Y-m-d H:i:s')."')");
								}elseif($singleQues['answerType'] == 5){
									
									//CONDITION: question ID matches or not
									if( $answers[$i]->question_id == $singleQues['id']){ 
										
										if($singleQues['answerType']==5)
									{     
										if($singleQues['matrix_type']=='radio')
										{	
											$allset=0;
											$m1=$singleQues['matrix_correct_answers'];
											$m2 = unserialize($m1);
											$arr_matrix_options = array();
											if($singleQues['matrix_row']!='')
											{
												$arr_mrrr = explode(",", $singleQues['matrix_row']);
												if(count($arr_mrrr)>0)
												{
													for($ii=0; $ii<count($arr_mrrr);$ii++)
													{
														if(in_array($answers[$i]->answer[$ii], $m2) )
														{
															//$arr_matrix_options[] = $answers[$i]->answer[$ii];
															//$arr_matrix_options[] = $m2[$ii];
															$allset++;															
														}														
													}
													if($allset==count($arr_mrrr))
													{
															$arr_matrix_options = $m2;
													}
													else
													{
														for($ij=0; $ij<count($arr_mrrr);$ij++)
														{
															
																$arr_matrix_options[] = $answers[$i]->answer[$ij];
														}
															
													}
												}
											}

											$myAnswer = serialize($arr_matrix_options);
												$answers[$i]->answer = $myAnswer;		
										}
										else
										{
											$answers[$i]->answer = serialize($answers[$i]->answer);
										}
										}
											
									//SET: set the value of answer in the serialize manner
								//	$answers[$i]->answer = serialize($answers[$i]->answer);
									
									
									
									//SET: set the value of answer in the serialize manner
									//$answers[$i]->answer = serialize($answers[$i]->answer);
									
									//INSERT: insertion in the answers table
									$insert_query = $this->dbClassObj->query("INSERT INTO ep_answers(assignmentId,questionId,answer,dateAnswered) VALUES(".$assigned['id'].",".$singleQues['id'].",'".$answers[$i]->answer."','".date('Y-m-d H:i:s')."')");
									
									
									if($singleQues['matrix_type']=='radio')
									{
										$matrix_correct_ans=$singleQues['matrix_correct_answers'];
										$arr_yourAnswer = unserialize($myAnswer);
										$arr_matrix_correct_ans_text = unserialize($matrix_correct_ans);
										$arr_answer_is[] = array(); 
										if(count($arr_matrix_correct_ans_text)>0){
											foreach($arr_yourAnswer as $mk=>$mv){
												foreach($mv as $k=>$v){
													if(in_array(strtolower($v), array_map('strtolower',$arr_matrix_correct_ans_text[$mk]))){
														$arr_answer_is[] = 'Correct';
													}else{
														$arr_answer_is[] = 'Incorrect';
													}
												} 
											}
										}
										if(in_array('Correct', $arr_answer_is)){
											$answer_is = 'Correct';
											$responseArray['questions'][$i]['point'] = 1;
										}else{
											$answer_is = 'Incorrect'; 
											$responseArray['questions'][$i]['point'] = 0;
										}
									}
									else
									{
										
										
										//CONDITION: answer correct or not
										if( $answers[$i]->answer == $singleQues['matrix_correct_answers'])
										{
											 $responseArray['questions'][$i]['point'] = 1; /* $points_collected++; */
										}
										else
										{
											 $responseArray['questions'][$i]['point'] = 0; 
										}
										
									}
										
									}else{ $responseArray['questions'][$i]['message'] = 'Question not exists'; }
								}
								
								//CHECK: if the correct answer having more than 1 in the answer type 1
								$num=0;
								$answers_arr = explode("!~!",$singleQues['correctAnswer']);
								if(count($answers_arr)>1){
									while($num<count($answers_arr)){
										if( strtolower($answers[$i]->answer) == strtolower($answers_arr[$num])){ $singleQues['correctAnswer'] = $answers_arr[$num];}$num++;
									}
								}
								
								//STARTS: correct answer finding algo
									//SET: variables value
									$tempStudentAns = $studentAnswerArr = explode('!~!',$answers[$i]->answer);
									$correctAnswerArr = $answers_arr;
									$answerType = $singleQues['answerType'];
									$totalAnswerBoxes = $singleQues['totalAnswerBoxes'];
									
									//UPDATE & SORT: mapping of array values to the lower string and sorting the arrays by values
									if($answerType != 6 && $singleQues['caseSensitive'] == '0'){
										
										//CODE ADDED: 04-05-2015
										//if($answerType == 5 && $singleQues['matrix_type'] == 'textfield'){
											//$temStudAns = $studentAnswerArr;
										//}
										//CODE ADDED: 04-05-2015
										
										$studentAnswerArr = array_map('strtolower',$studentAnswerArr);
										asort($studentAnswerArr);
										$correctAnswerArr = array_map('strtolower',$correctAnswerArr);
										asort($correctAnswerArr);
									}
									
									//CHECK: answer type is not equal to 5 (matrix type)
									if($answerType < 5){
										if($answerType == 1){
											if($totalAnswerBoxes == 1){
												foreach($studentAnswerArr as $studentAnswer){
													//CHECK: is atudent answer is exists in the correct array
													//~ if(in_array(trim($studentAnswer),$correctAnswerArr)){ $point = 1; }
													//trim(htmlentities($studentAnswer, ENT_QUOTES, 'UTF-8')),
													
													//REPLACE: the £ with &pound; to overcome the pound special character problem.
													$studentAnswer = str_replace('£','&pound;',$studentAnswer);
													if(
														in_array(
															trim($studentAnswer),
															$correctAnswerArr
														)
													){ $point = 1; }
													else{ $point = 0; break; }
												}
											}else{
												//CHECK: is the values in the both array are equal
												if(count($studentAnswerArr) == count($correctAnswerArr)){
													//PROCESS: finding the student every answer in the corrected ones
													for($inc=0 ; $inc<count($correctAnswerArr) ; $inc++){
														if(trim($studentAnswerArr[$inc]) == trim($correctAnswerArr[$inc])){ $point = 1; }
														else{ $point = 0; break; }
													}
												}else{
													//SET: if both arrays count are not mathched
													$point = 0; 
												}
												$answArray['studentAnswerArr'] = $studentAnswerArr;
												$answArray['correctAnswerArr'] = $correctAnswerArr;
											}
										}elseif($answerType == 2){
											//CHECK: is the values in the both array are equal
											if(count($studentAnswerArr) == count($correctAnswerArr)){
												//PROCESS: finding the student every answer in the corrected ones
												for($inc=0 ; $inc<count($correctAnswerArr) ; $inc++){
													// if($studentAnswerArr[$inc] == $correctAnswerArr[$inc]){ $point = 1; }
													if(in_array($studentAnswerArr[$inc],$correctAnswerArr)){ $point = 1; }
													else{ $point = 0; break; }
												}
											}else{
												//SET: if both arrays count are not mathched
												$point = 0; 
											}
											$answArray['studentAnswerArr'] = (array)$studentAnswerArr;
											$answArray['correctAnswerArr'] = (array)$correctAnswerArr;
										}else{
											foreach($studentAnswerArr as $studentAnswer){
												//CHECK: is atudent answer is exists in the correct array
												if(in_array($studentAnswer,$correctAnswerArr)){ $point = 1; }
												else{ $point = 0; break; }
											}
										}
									}elseif($answerType == 6){ 
										
										//GET: student questions answers combinations
										$stdHtml = '<table><tr><td class="orange">COLUMN A</td><td class="orange">COLUMN B</td></tr>'; $numb=0;
										$options = $this->dbClassObj->query("SELECT * FROM `ep_options` WHERE questionId = ".$singleQues['id']." ORDER BY id ASC");
										while($option = $this->dbClassObj->fetch_assoc($options)){
											if($numb%2 == 0){ $color = 'grey'; }else{ $color = ''; }
											$stdHtml .= '<tr><td class="'.$color.'">'.$option['answerOption'].'</td><td class="'.$color.'">'.$studentAnswerArr[$numb].'</td></tr>';
											$numb++;
										}
										$stdHtml .= '</table><style>.orange{background-color:#FF6600;color:#FFFFFF}.grey{background-color:#999999;}</style>';
										$responseArray['questions'][$i]['studentHtml'] = $stdHtml;
										
										//GET: correct questions answers combinations
										$stdHtml = '<table><tr><td class="orange">COLUMN A</td><td class="orange">COLUMN B</td></tr>'; $numb=0;
										$options = $this->dbClassObj->query("SELECT * FROM `ep_options` WHERE questionId = ".$singleQues['id']." ORDER BY id ASC");
										while($option = $this->dbClassObj->fetch_assoc($options)){
											if($numb%2 == 0){ $color = 'grey'; }else{ $color = ''; }
											$stdHtml .= '<tr><td class="'.$color.'">'.$option['answerOption'].'</td><td class="'.$color.'">'.$correctAnswerArr[$numb].'</td></tr>';
											$numb++;
										}
										$stdHtml .= '</table><style>.orange{background-color:#FF6600;color:#FFFFFF}.grey{background-color:#999999;}</style>';
										$responseArray['questions'][$i]['correctHtml'] = $stdHtml;
										
										if($studentAnswerArr == $correctAnswerArr){ $point = 1; }
										else{ $point = 0; }
									}else{ 
										$correctAnswerArr = $singleQues['matrix_correct_answers'];
										$studentAnswerArr = $answers[$i]->answer;
										
										/***********CODE STARTS: HTML FOR THE CORRECT AND STUDENT ANSWERS**********/
										//SET: the correct answers html
										$mquery = $this->dbClassObj->query("SELECT matrix_type, matrix_row, matrix_column FROM `ep_questions` WHERE worksheetId = ".$worksheet_id." AND answerType =5 AND id=".$singleQues['id']);
										$mobject = $this->dbClassObj->fetch_assoc($mquery);
										
										//SET: variables
										$type = $mobject['matrix_type'];
										$array_rows = explode(',',$mobject['matrix_row']);
										$array_columns = explode(',',$mobject['matrix_column']);
										// $array_columns = array_map('trim',$array_columns);
										$total_rows = count($array_rows);
										$total_columns = count($array_columns);
										
										if($singleQues['answerType'] == 5 && $type=="checkbox"){
											$studentAnswerArr = unserialize($studentAnswerArr);
											$studentAnswerArr = array_map('array_filter', $studentAnswerArr);
											$studentAnswerArr = array_filter($studentAnswerArr);
											$studentAnswerArr = serialize($studentAnswerArr);
										}
										
										if($singleQues['answerType'] == 5 && $type=="textfield"){
											$studentAnswerArr = unserialize($studentAnswerArr);
											foreach($studentAnswerArr as $key=>$value){
												$studentAnswerArr[$key] = array_map('strtolower',$value);
											}
											$studentAnswerArr = serialize($studentAnswerArr);
										}
										
										$studentAns = unserialize($studentAnswerArr);
										
										//CODE ADDED: 05-05-2015
										if($singleQues['answerType'] == 5 && $type=="textfield"){
											$studentAns = unserialize($answers[$i]->answer);
										}
										//CODE ADDED: 05-05-2015
										
										//SET: student answer HTML
										$answer_html = '';
										if(!empty($type)){
											if(empty($total_rows)){ $total_rows=0; }
											if(empty($total_columns)){ $total_columns=0; }
											//Generating a matrix in the form of HTML table
											$answer_html .= '<table id="ans_table">';
												for( $x = 0 ; $x < $total_rows ; $x++ ){
													
													if($singleQues['answerType'] == 5 && $type=="radio"){ $columnIndexArrSt[$x] = 9; }
													
													if( $x == 0 ){
														$answer_html .= '<tr><td></td>';
														for( $q = 0 ; $q < $total_columns ; $q++ ){ $answer_html .= '<td>'.$array_columns[$q].'</td>'; }
														$answer_html .= '</tr>';
													}
													//PROCESS: generating the radio buttons nature
													if($singleQues['answerType'] < 5){
														$studentOption = $studentAns[$x];
														$studentOption = explode('~sep~',$studentOption);
														$rowIndex = array_search($studentOption[0],$array_rows);
														$columnIndex = array_search(trim($studentOption[1]),$array_columns);
													}		
														
													//CHECK: if the question type is checkbox
													if($singleQues['answerType'] == 5 && $type=="checkbox"){
														$numbr=0;
														foreach($studentAns[$x] as $studentOption){
															$studentOption = explode('~sep~',$studentOption);
															$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
															$columnIndexArrSt[$x][$numbr] = array_search($studentOption[1],$array_columns);
															$numbr++;
														}
													}
													
													//CHECK: if the question type is radio and answer type = 5
													if($singleQues['answerType'] == 5 && $type=="radio"){
														$numbr=0;
														
														if(!is_array($studentAns[$x])){ $studentAns[$x] = array('0'=>$studentAns[$x]); }

														foreach($studentAns[$x] as $studentOption){
															if(count($studentOption) > 0){
																$studentOption = explode('~sep~',$studentOption);
																$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
																$columnIndexArrSt[$x] = array_search($studentOption[1],$array_columns);
															}elseif(!empty($studentOption)){
																$studentOption = explode('~sep~',$studentOption);
																$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
																$columnIndexArrSt[$x] = array_search($studentOption[1],$array_columns);
															}else{
																$rowIndexArr[$x][] = 9;
																$columnIndexArrSt[$x] = 9;
															}
														}
													}
													
													//CHECK: if the question type is textfield
													if($singleQues['answerType'] == 5 && $type=="textfield"){
														$numbr=0;
														foreach($studentAns[$x] as $studentOption){
															$text_value_st[$numbr] = $studentOption;
															$numbr++;
														}
													}
												
													$answer_html .= '<tr><td>'.$array_rows[$x].'</td>';
													for( $k = 0 ; $k < $total_columns ; $k++ ){
														$checked = '';
														//SET: checked variable
														// if(empty($studentOption[1])){$columnIndex=2;}
														
														if($singleQues['answerType'] < 5){ if($columnIndex == $k){ $checked = 'checked'; }else{ $checked = ''; } }
														
														//CHECK: if the question type is checkbox
														if($singleQues['answerType'] == 5 && $type=="checkbox"){ if(in_array($k,$columnIndexArrSt[$x])){ $checked = 'checked'; }else{ $checked = ''; } }
														
														//CHECK: if the question type is radio
														if($singleQues['answerType'] == 5 && $type=="radio"){ if($k == $columnIndexArrSt[$x]){ $checked = 'checked'; }else{ $checked = ''; } }
														
														//PROCESS: generating the HTML
														$answer_html .= '<td>';
															if($type=="checkbox"){ $answer_html .= '<input type="checkbox" name="checkbox_'.$x.'_'.$k.'" class="checkboxes" '.$checked.' disabled />'; }
															elseif($type=="radio"){ $answer_html .= '<input type="radio" name="radiogroup_'.$x.'" class="radio" '.$checked.' disabled />'; }
															elseif($type=="textfield"){ $answer_html .= '<input type="text" name="text_'.$x.'" class="text" value="'.$text_value_st[$k].'" disabled />'; }
														$answer_html .= '</td>';
														$checked= '';
													}
													$answer_html .= '</tr>';
													
													if($singleQues['answerType'] == 5 && $type=="checkbox"){ $columnIndexArrSt[$x] = array(); }
													
												}
											$answer_html .= '</table>';
										}
										$responseArray['questions'][$i]['studentHtml'] = $answer_html;
										
										//SET: correct answer HTML
										$correctAns = unserialize($correctAnswerArr);
									
										$answer_html = '';
										$columnIndexArr = $rowIndexArr = array();
										if(!empty($type)){
											if(empty($total_rows)){ $total_rows=0; }
											if(empty($total_columns)){ $total_columns=0; }
											//Generating a matrix in the form of HTML table
											$answer_html .= '<table id="ans_table">';
												for( $x = 0 ; $x < $total_rows ; $x++ ){
													if( $x == 0 ){
														$answer_html .= '<tr><td></td>';
														for( $q = 0 ; $q < $total_columns ; $q++ ){ $answer_html .= '<td>'.$array_columns[$q].'</td>'; }
														$answer_html .= '</tr>';
													}
													
													//PROCESS: generating the radio buttons nature
													$studentOption = $correctAns[$x];
													$studentOption = explode('~sep~',$studentOption);
													$rowIndex = array_search($studentOption[0],$array_rows);
													$columnIndex = array_search($studentOption[1],$array_columns);
												
													//CHECK: if the question type is checkbox
													if($singleQues['answerType'] == 5 && $type=="checkbox"){
														foreach($correctAns[$x] as $studentOption){
															$studentOption = explode('~sep~',$studentOption);
															$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
															$columnIndexArr[$x][] = array_search($studentOption[1],$array_columns);
														}
													}
												
													//CHECK: if the question type is textfield
													if($singleQues['answerType'] == 5 && $type=="textfield"){
														$numbr=0;
														foreach($correctAns[$x] as $studentOption){
															$text_value[$numbr] = $studentOption;
															$numbr++;
														}
													}
												
													$answer_html .= '<tr><td>'.$array_rows[$x].'</td>';
													for( $k = 0 ; $k < $total_columns ; $k++ ){
														//SET: checked variable
														if($columnIndex == $k){ $checked = 'checked'; }else{ $checked = ''; }
														
														//CHECK: if the question type is checkbox
														if($singleQues['answerType'] == 5 && $type=="checkbox"){ if(in_array($k,$columnIndexArr[$x])){ $checked = 'checked'; }else{ $checked = ''; } }
													
														//PROCESS: generating the HTML
														$answer_html .= '<td>';
															if($type=="checkbox"){ $answer_html .= '<input type="checkbox" name="checkbox_'.$x.'_'.$k.'" class="checkboxes" '.$checked.' disabled />'; }
															elseif($type=="radio"){ $answer_html .= '<input type="radio" name="radiogroup_'.$x.'" class="radios" '.$checked.' disabled />'; }
															elseif($type=="textfield"){ $answer_html .= '<input type="text" name="text_'.$x.'" class="text" value="'.$text_value[$k].'" disabled />'; }
														$answer_html .= '</td>';
													}
													$answer_html .= '</tr>';
												}
											$answer_html .= '</table>';
										}
										$responseArray['questions'][$i]['correctHtml'] = $answer_html;

										/***********CODE ENDS: HTML FOR THE CORRECT AND STUDENT ANSWERS**********/
										
										//CODE ADDED: 05-05-2015
										if($singleQues['answerType'] == 5 && $type=="textfield"){
											$studentAnswerArr = unserialize($studentAnswerArr);
											foreach($studentAnswerArr as $key=>$value){
												$studentAnswerArr[$key] = array_map('strtolower',$value);
											}
											$studentAnswerArr = serialize($studentAnswerArr);

											$correctAnswerArr = unserialize($correctAnswerArr);
											foreach($correctAnswerArr as $key=>$value){
												$correctAnswerArr[$key] = array_map('strtolower',$value);
											}
											$correctAnswerArr = serialize($correctAnswerArr);
										}
										//CODE ADDED: 05-05-2015
										
										if($studentAnswerArr == $correctAnswerArr){ $point = 1; }
										else{ $point = 0; }
									}
									$responseArray['questions'][$i]['correctAnswerArr'] = $correctAnswerArr;
									$responseArray['questions'][$i]['studentAnswerArr'] = $studentAnswerArr;
									
									if($answerType == 1 ){
										$responseArray['questions'][$i]['correctAnswerArr'] = array_values((array)$correctAnswerArr);
									}
									if($answerType == 1 || $answerType == 3){
										$responseArray['questions'][$i]['studentAnswerArr'] = $tempStudentAns;
									}
									
									if($answerType == 2){
										(array)$responseArray['questions'][$i]['correctAnswerArr'] = array_values((array)$correctAnswerArr);
										(array)$responseArray['questions'][$i]['studentAnswerArr'] = array_values((array)$studentAnswerArr);
									}
									
									$responseArray['questions'][$i]['point'] = $point;
									
									// print_r($responseArray['questions'][$i]['point']);exit('here');
									
									$points_collected = $points_collected + $point;
								//ENDS: correct answer finding algo
								
							}else{ $responseArray['questions'][$i]['message'] = 'Question not exists'; }
						// }
						$i++; //INCREEMENT: for the next question
					}
					$responseArray['points_collected'] = $points_collected;	//SET: points collected variable in responseArray
					$responseArray['worksheet_points'] = $i;	//SET: worksheet points variable in responseArray
					
					
					//-------------CHECKPOINT 2: responseArray is created---------------
					
					//SET: a variable having the result details for all the questions
					$ques_score = 'a:'.count($responseArray['questions']).':{'; 
					foreach($responseArray['questions'] as $question){
						$ques_score .= "i:".$question['id'].";i:".$question['point'].";";
					}
					$ques_score .= "}";
					
					//UPDATE: assignment_table and rewards_table
					$dateAppeared = date('Y-m-d H:i:s');
					$assignment = $this->dbClassObj->query("UPDATE ep_wsassigned SET ques_completed = '".$i."', is_complete = '1', points_collected = '".$points_collected."', score_collected = '".$points_collected."', score_total = '".$i."', sz_ques_score = '".$ques_score."', timestamp = '".time()."', dateAppeared = '".$dateAppeared."' WHERE id = ".$assigned['id']);
					
					//GET: total points by student ID
					$total_points = $this->dbClassObj->query("SELECT sum(points_collected) as total_points FROM `ep_wsassigned` where studentId = ".$student_id." and is_complete = '1'");
					$total_points = $this->dbClassObj->fetch_assoc($total_points);
					$responseArray['total_points'] = $total_points['total_points'];
					
					//Messages
					$score = ($points_collected * 100)/$i;
					$studentName	=ucfirst($studentInfo['fname']);
					$arr_ans_summary="";
					if($score==100){
						$str_ans_summary  ="Congratulations";
					}elseif($score>=90 && $score<=99){ 
						$str_ans_summary = "Well done";
					}elseif($score>=80 && $score<=89){ 
						$str_ans_summary = "Great work";
					}elseif($score>=70 && $score<=79){ 
						$str_ans_summary = "Keep up the good work";
					}elseif($score>=50 && $score<=69){  
						$str_ans_summary = "Good effort";
					}elseif($score>=0 && $score<=49){
						$str_ans_summary = "Practice makes perfect";
					}
					$responseArray['title_message'] = $str_ans_summary ;
					
					$arr_ans_summary="";
					if($score==100){
						$str_ans_summary  ="You got a perfect score!";
					}elseif($score>=90 && $score<=99){ 
						$str_ans_summary = "You got an excellent score!";
					}elseif($score>=80 && $score<=89){ 
						$str_ans_summary = "You got a great score!";
					}elseif($score>=70 && $score<=79){ 
						$str_ans_summary = "You got a good score";
					}elseif($score>=50 && $score<=69){  
						$str_ans_summary = "Nice try! Keep going!";
					}elseif($score>=0 && $score<=49){
						$str_ans_summary = "Try again for a better score";
					}
					$responseArray['desc_message'] = $str_ans_summary ;
					
					//GET: rewards which is completed
					$rw = 0;
					$total_rewards = $this->dbClassObj->query("SELECT * FROM `ep_rewards` where student_id = ".$student_id." and status = 'Active' and points_collected < points_allocated ORDER BY date_allocated ASC");
					$date_collected = date('Y-m-d H:i:s');
					while( $single_reward = $this->dbClassObj->fetch_assoc($total_rewards)){
						$points_gained = $points_collected + $single_reward['points_collected'];
						if( $points_gained >= $single_reward['points_allocated'] )
						{
							$remaining_points = $points_collected - ( $single_reward['points_allocated'] - $single_reward['points_collected'] );
							$points_gained = $single_reward['points_collected'] + ( $points_collected - $remaining_points );
							$points_collected = $remaining_points;
							
							$update_query = $this->dbClassObj->query("UPDATE ep_rewards SET points_collected = ".$points_gained.", date_collected = '".$date_collected."',reward_status='Unlocked' WHERE id = ".$single_reward['id']	);

							/***********CODE STARTS: for the timeline entry***********/
								//GET: the icon url of the relative reward type
								$icon = $this->dbClassObj->query("SELECT * FROM ep_rewards_type WHERE id = ".$single_reward['reward_type_id']);
								$icon = $this->dbClassObj->fetch_assoc($icon);
								
								$icon['reward'] = $single_reward['reward'];
								$icon['link'] = $single_reward['link'];
								$icon['points_allocated'] = $single_reward['points_allocated'];
								$icon['points_collected'] = $points_collected;
								
								//INSERT: timeline notification
								$rn_msg='Congratulations, you just earned a reward. <img src="http://'.$_SERVER['SERVER_NAME'].'/'.$icon['reward_white_icon'].'" alt="'.$icon['reward_name'].'" />';
								//SET: set the variable to the serialize form
								$rns = serialize($icon);
								
								$r_notic_sql=$this->dbClassObj->query("INSERT INTO ep_student_notic_log(id,notic,message,notic_type,notic_type_id, notic_user, is_viewed,created_date,modified_date,other_related_fields) VALUES('','".$single_reward['reward_name']."','".$rn_msg."','reward','".$icon['id']."','".$student_id."','0',now(),now(),'".$rns."')");
							
						}else{
							if($points_gained > 0){
								$temp_points_allocated = $single_reward['points_allocated'];
								$temp_points_collected = $single_reward['points_collected'];
								
								$update_query = $this->dbClassObj->query("UPDATE ep_rewards SET points_collected = ".$points_gained.", date_modified = '".$date_collected."' WHERE id = ".$single_reward['id']	);
								
								$remaining_points = $points_collected - ( $temp_points_allocated - $temp_points_collected );
								$points_gained = $temp_points_collected + ( $points_collected - $remaining_points );
								$points_collected = $remaining_points;
							}
						}
					}
					$updated_rewards = $this->dbClassObj->query("SELECT * FROM `ep_rewards` where student_id = ".$student_id." and status = 'Active' AND date_collected = '".$date_collected."'");
					while( $record = $this->dbClassObj->fetch_assoc($updated_rewards)){
						$responseArray['rewards'][$rw] = $record;
						$rw++;
					}
					//$responseArray['badges'] = array();
					///sleep(10);
					//$responseArray['badges'] = $this->studentFoundBadges($student_id);
					

					//INSERT: timeline notification
						//GET: worksheet details
						$worksheet_details = $this->dbClassObj->query("SELECT * FROM ep_worksheets WHERE id = ".$worksheet_id);
						$worksheet_details = $this->dbClassObj->fetch_assoc($worksheet_details);
						//GET: assignment details
						$w_assigned = $this->dbClassObj->query("SELECT * FROM `ep_wsassigned` WHERE id = ".$assigned['id']);
						$w_assigned = $this->dbClassObj->fetch_assoc($w_assigned);
						//SET: arrays values of rns
						$rns2['id'] = $assigned['id'];
						$rns2['worksheetId'] = $worksheet_details['id'];
						$rns2['assignmentId'] = $assigned['id'];
						$rns2['worksheetName'] = base64_encode($worksheet_details['worksheetName']);
						$rns2['subject'] = $worksheet_details['subject'];
						$rns2['dateAppeared'] = date('Y-m-d',$assigned['dateAppeared']);
						$rns2['points_collected'] = $w_assigned['points_collected'];
						$rns2['score_collected'] = $w_assigned['score_collected'];
						$rns2['score_total'] = $w_assigned['score_total'];
						
						$worksheet_details['worksheetName'] = addslashes($worksheet_details['worksheetName']);
						
						$r_notic_sql=$this->dbClassObj->query("INSERT INTO ep_student_notic_log(id,notic,message,notic_type,notic_action, notic_type_id,notic_user,is_viewed,created_date,modified_date,other_related_fields) VALUES('','".$worksheet_details['worksheetName']."','".$str_ans_summary."','score','completed','".$assigned['id']."','".$student_id."','0',now(),now(),'".serialize($rns2)."')");
					
					
						/*Auto assign code starts*/
					
					
					
					$parent_sql= $this->dbClassObj->query("select parent_id from ep_wsusers where id='".$student_id."'");
					$parent_result=$this->dbClassObj->fetch_assoc($parent_sql);
					$parentid=$parent_result['parent_id'];
					
					
					$auto_assign_y_n_sql = $this->dbClassObj->query("select auto_assign_y_n from ep_wsusers where id='".$student_id."'");
					$auto_assign_y_n_result = $this->dbClassObj->fetch_assoc($auto_assign_y_n_sql);
					$auto_assign_y_n=$auto_assign_y_n_result['auto_assign_y_n'];
					
					$pworksheetCat_sql = $this->dbClassObj->query("select worksheetCat from ep_wsassigned where id='".$assigned['id']."'");
					$pworksheetCat_result = $this->dbClassObj->fetch_assoc($pworksheetCat_sql);
					$pworksheetCat=$pworksheetCat_result['worksheetCat'];
					
					if($pworksheetCat!=2 && ($auto_assign_y_n=='1' || $auto_assign_y_n==1))
					{
					
						$sqlavg =$this->dbClassObj->query("select max(avg_total) as masavg from (select (sum(score_collected)/sum(score_total)*100)  as avg_total from ep_wsassigned where  studentId='".$student_id."' and worksheetId='".$worksheet_id."' and is_complete='1' group by id) ta ");
						$sqlavg_result = $this->dbClassObj->fetch_assoc($sqlavg);
						$avg_total	= $sqlavg_result['masavg'];
						$avg_total	= number_format($avg_total,0);
						$byPass=0;
					
						if($avg_total<70)
						{
							$this->dbClassObj->query("UPDATE ep_wsassigned SET attempts = 1 WHERE id=".$assigned['id']);
							$query_check = $this->dbClassObj->query("SELECT SUM(attempts) as total_attempts FROM ep_wsassigned WHERE studentId ='".$student_id."' AND worksheetId = '".$worksheet_id."' AND attempts = 1 ORDER BY id DESC");
							$result_check = $this->dbClassObj->fetch_assoc($query_check);
							$totalAttempts = $result_check['total_attempts'];
							if($totalAttempts>2)
							{ 
								$byPass=1; 
								$this->dbClassObj->query("INSERT INTO ep_parent_notifications(parent_id, student_id, worksheet_id, created_date) VALUES('".$parentid."', '".$student_id."', '".$worksheet_id."', NOW())");
							}
							
						}
						else
						{
							$assgId = $assigned['id'];
							$this->dbClassObj->query("UPDATE ep_wsassigned SET attempts = 0 WHERE id=".$assigned['id']);
						}
						
						if($avg_total >=70 || $byPass==1 )
						{
							$wsql="select id, subject, keyStage, year, topic_id from ep_worksheets where id=".$worksheet_id;
							$wqry_res = $this->dbClassObj->query($wsql);
							$winfo=$this->dbClassObj->fetch_assoc($wqry_res);
							
							$rel_wk_sql="select id, subject, keyStage, year, topic_id from ep_worksheets where subject=".$winfo['subject']." and keyStage=".$winfo['keyStage']." and year=".$winfo['year']." and topic_id =".$winfo['topic_id']." and status='1' order by level, id ";
							$rel_wk_res = $this->dbClassObj->query($rel_wk_sql);
							$gcount = $this->dbClassObj->num_rows($rel_wk_res);
							if($gcount)
							{
								$unAssignedWkArr =array();
								while($line=$this->dbClassObj->fetch_assoc($rel_wk_res))
								{

									$is_allready_assigned_sql = $this->dbClassObj->query("select count(id) as nval from ep_wsassigned where  studentId='".$student_id."' and worksheetId='".$line['id']."' and is_complete='0'");
									$is_allready_assigned_result = $this->dbClassObj->fetch_assoc($is_allready_assigned_sql);
									$is_allready_assigned =$is_allready_assigned_result['nval'];
									
									$sqlravg =$this->dbClassObj->query("select max(avg_total) as maxavgt from (select (sum(score_collected)/sum(score_total)*100)  as avg_total from ep_wsassigned where  studentId='".$student_id."' and worksheetId='".$line['id']."' and is_complete='1' group by id) ta ");
									$re_avg_total_res = $this->dbClassObj->fetch_assoc($sqlravg);
									$re_avg_total = $re_avg_total_res['maxavgt'];
									$re_avg_total = number_format($re_avg_total,0);
									
									$query = $this->dbClassObj->query("SELECT SUM(attempts) as total_attempts FROM ep_wsassigned WHERE studentId = '".$student_id."' AND worksheetId = '".$line['id']."' AND attempts = 1 ORDER BY id DESC");
									$result = $this->dbClassObj->fetch_assoc($query);
									$totalAttempts = $result['total_attempts'];
									
									if(!$is_allready_assigned && $worksheet_id!=$line['id'] && $re_avg_total < 70 && $totalAttempts<3)
									{
										$unAssignedWkArr[] =$line;
									}
								}
							}
							
							if(count($unAssignedWkArr)<1){
								$unAssignedWkArr = updateAutoAssignWorksheetList($winfo, $worksheet_id, $student_id);
							}							
							
						}
						else
						{
							$is_allready_assigned_sql = $this->dbClassObj->query("select count(id) as nval from ep_wsassigned where  
							studentId='".$student_id."' and worksheetId='".$worksheet_id."' and is_complete='0'");
							$is_allready_assigned_res = $this->dbClassObj->fetch_assoc($is_allready_assigned_sql);
							$is_allready_assigned = $is_allready_assigned_res['nval'];
							
							$wsql="select id, subject, keyStage, year, topic_id from ep_worksheets where id='".$worksheet_id."' and status='1' ";
							$wqry_res = $this->dbClassObj->query($wsql);
							$winfo = $this->dbClassObj->fetch_assoc($wqry_res);
							$wcount = $this->dbClassObj->num_rows($wqry_res);
							
							
							if(!$is_allready_assigned && $wcount>0)
							{
								$unAssignedWkArr[] = $winfo;
							}
						}	
						$parent_active_sql = $this->dbClassObj->query("select subs_status from ep_subscription_parent where parent_id='".$parentid."'");
						$parent_active = $this->dbClassObj->fetch_assoc($parent_active_sql);
						
						
						if($parent_active['subs_status']==1)  
						{
							if(sizeof($unAssignedWkArr))
							{
								$autoAssignWId = $unAssignedWkArr[0]['id'];										
								$worksheetCat_sql = $this->dbClassObj->query("select worksheetCat from ep_worksheets where id='".$autoAssignWId."'");	
								$worksheetCat = $this->dbClassObj->fetch_assoc($worksheetCat_sql);
								
								if($avg_total <70  || ($avg_total >=70 &&  ($autoAssignWId!=$worksheetcheck_wid)))
								{
								$sql = "insert into ep_wsassigned set worksheetId='".$autoAssignWId."', worksheetCat='".$worksheetCat['worksheetCat']."', studentId='".$student_id."', parentId='".$parentid."', dateAssigned='".date('Y-m-d H:i:s')."'";
								$this->dbClassObj->query($sql);
								}
							}
						}
						
						
						
					}
					
				
					
				/*Auto assign code ends*/
				
					/* Badges assignment code */
					/*Assigne badges to Students*/
					require_once('../../student/includes/classes/badges.class.php');
					$objBadges = new Badges(TB_BADGES_CONDITION);

					$sresult=$this->dbClassObj->query("select auto_assign_year, auto_assign_subject,student_year from ".TB_USERS." where id='".$student_id."' and user_type='student' limit 0,1");
					$sYearSubject	= $this->dbClassObj->fetch_assoc($sresult);

					$student_year_id	=($sYearSubject['auto_assign_subject']!='')?$sYearSubject['auto_assign_year']:'';
					$student_subject_id	=($sYearSubject['auto_assign_subject']==9)?'1,2,3':$sYearSubject['auto_assign_subject'];

					

					if($student_year_id !='' && $student_subject_id!=''){
						$prompt = $objBadges->assigneBadgesToStudent($student_id, $student_year_id, $student_subject_id,"");
					}
					/*Assigne badges to Students End*/

					/*Students Found Badges*/	
					$prompt .= $objBadges->studentFoundBadges($student_id,"");
					/*Students Found Badges End*/

					/* Badges assignment code ends here */
					
					
					//READY: result is ready for json encode
					//SET: the response array
					$response = array('error' => STATUS_OK, 'message' => 'Worksheet Results','response'=>$responseArray); 
				}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
				
			}else{ $response = array('error' => ERROR_EXISTS, 'message' => POST_ERROR_MSG); }
			echo json_encode($response);
		}
		
		public function guest_worksheet_result($worksheet_id,$answers){
				//CHECK: questions are available in worksheet or not
				$questions = $this->dbClassObj->query("SELECT count(*) as total_ques FROM ep_questions WHERE worksheetId = ".$worksheet_id);
				$questions = $this->dbClassObj->fetch_assoc($questions);
				if(empty($questions['total_ques'])){$response = array('error' => 1, 'message' => 'Questions not available in worksheet');echo json_encode($response);exit;}
				
				//-------------CHECKPOINT 1: all minimum checks are passed---------------
				
				//SET: declaring the null value in the keys of responseArray because of the listing on top in json response
				$responseArray = array('worksheet_id'=>$worksheet_id , 'total_points'=>0 , 'worksheet_points'=>0 , 'points_collected'=>0,'desc_message'=>'','title_message'=>'','rewards'=>'');
				
				$responseArray['worksheet_name'] = $this->_getWorksheetNameByWorksheet($worksheet_id);
				$responseArray['curriculum_topic'] = $this->_getCurriculumTopicByWorksheet($worksheet_id);
				$responseArray['curriculum_subtopic'] = $this->_getCurriculumSubTopicByWorksheet($worksheet_id);
				$responseArray['level'] = $this->_getLevelByWorksheet($worksheet_id);
				$responseArray['topic'] = $this->_getTopicByWorksheet($worksheet_id);
				$responseArray['subject'] = $this->_getSubjectByWorksheet($worksheet_id);
				$responseArray['worksheet_year'] = $this->_getYearByWorksheet($worksheet_id);
				
				//GET: the all questions details based upon the worksheet ID from variable '$questions'
				$points_collected = $i = 0;
				$questions = $this->dbClassObj->query("SELECT id, question, answerType, totalAnswerBoxes, correctAnswer, matrix_correct_answers, explanation, status, matrix_type, matrix_row, matrix_column, caseSensitive, apostrophes FROM ep_questions WHERE worksheetId = ".$worksheet_id." ORDER BY id ASC");
				while($singleQues = $this->dbClassObj->fetch_assoc($questions)){
					$singleQues['question'] = stripslashes($singleQues['question']);
					(array)$responseArray['questions'][$i] = $singleQues;	//SET: response array
					//CONDITION: answerType not equals to matrix(5)
					// if($singleQues['answerType'] != 5){
						//CONDITION: question ID matches or not
						if( $answers[$i]->question_id == $singleQues['id']){ 
							
							$responseArray['questions'][$i]['std_answer'] = $answers[$i]->answer;
							
							if($singleQues['answerType'] != 5){
								//INSERT: insertion in the answers table
								// $insert_query = $this->dbClassObj->query("INSERT INTO ep_answers(assignmentId,questionId,answer,dateAnswered) VALUES(".$assigned['id'].",".$singleQues['id'].",'".$answers[$i]->answer."','".date('Y-m-d H:i:s')."')");
							}elseif($singleQues['answerType'] == 5){
								//CONDITION: question ID matches or not
								if( $answers[$i]->question_id == $singleQues['id']){ 
									//SET: set the value of answer in the serialize manner
									$answers[$i]->answer = serialize($answers[$i]->answer);
									
									//CONDITION: answer correct or not
									if( $answers[$i]->answer == $singleQues['matrix_correct_answers']){ $responseArray['questions'][$i]['point'] = 1; /* $points_collected++; */}
									else{ $responseArray['questions'][$i]['point'] = 0; }
								}else{ $responseArray['questions'][$i]['message'] = 'Question not exists'; }
							}
							
							//CHECK: if the correct answer having more than 1 in the answer type 1
							$num=0;
							$answers_arr = explode("!~!",$singleQues['correctAnswer']);
							if(count($answers_arr)>1){
								while($num<count($answers_arr)){
									if( strtolower($answers[$i]->answer) == strtolower($answers_arr[$num])){ $singleQues['correctAnswer'] = $answers_arr[$num];}$num++;
								}
							}
							
							//STARTS: correct answer finding algo
								//SET: variables value
								$tempStudentAns = $studentAnswerArr = explode('!~!',$answers[$i]->answer);
								$correctAnswerArr = $answers_arr;
								$answerType = $singleQues['answerType'];
								$totalAnswerBoxes = $singleQues['totalAnswerBoxes'];
								
								//UPDATE & SORT: mapping of array values to the lower string and sorting the arrays by values
								if($answerType != 6 && $singleQues['caseSensitive'] == '0'){
									$studentAnswerArr = array_map('strtolower',$studentAnswerArr);
									asort($studentAnswerArr);
									$correctAnswerArr = array_map('strtolower',$correctAnswerArr);
									asort($correctAnswerArr);
								}
								
								//~ echo 'Student:<br/><pre>',print_r($studentAnswerArr),'</pre>--------';
								//~ echo 'Correct::<br/><pre>',print_r($correctAnswerArr),'</pre>';
								
								//CHECK: answer type is not equal to 5 (matrix type)
								if($answerType < 5){
									if($answerType == 1){
										if($totalAnswerBoxes == 1){
											foreach($studentAnswerArr as $studentAnswer){
												//CHECK: is student answer is exists in the correct array
												//trim(htmlentities($studentAnswer, ENT_QUOTES, 'UTF-8'))
												
												//REPLACE: the £ with &pound; to overcome the pound special character problem.
												$studentAnswer = str_replace('£','&pound;',$studentAnswer);
												if(
													in_array(
														$studentAnswer,
														$correctAnswerArr
													)
												){ $point = 1; }
												else{ $point = 0; break; }
											}
										}else{
											//CHECK: is the values in the both array are equal
											if(count($studentAnswerArr) == count($correctAnswerArr)){
												//PROCESS: finding the student every answer in the corrected ones
												for($inc=0 ; $inc<count($correctAnswerArr) ; $inc++){
													if(trim($studentAnswerArr[$inc]) == trim($correctAnswerArr[$inc])){ $point = 1; }
													else{ $point = 0; break; }
												}
											}else{
												//SET: if both arrays count are not mathched
												$point = 0; 
											}
											$answArray['studentAnswerArr'] = $studentAnswerArr;
											$answArray['correctAnswerArr'] = $correctAnswerArr;
										}
									}elseif($answerType == 2){
										//CHECK: is the values in the both array are equal
										if(count($studentAnswerArr) == count($correctAnswerArr)){
											//PROCESS: finding the student every answer in the corrected ones
											for($inc=0 ; $inc<count($correctAnswerArr) ; $inc++){
												// if($studentAnswerArr[$inc] == $correctAnswerArr[$inc]){ $point = 1; }
												if(in_array($studentAnswerArr[$inc],$correctAnswerArr)){ $point = 1; }
												else{ $point = 0; break; }
											}
										}else{
											//SET: if both arrays count are not mathched
											$point = 0; 
										}
										
										$answArray['studentAnswerArr'] = (array)$studentAnswerArr;
										$answArray['correctAnswerArr'] = (array)$correctAnswerArr;
									}else{
										foreach($studentAnswerArr as $studentAnswer){
											//CHECK: is atudent answer is exists in the correct array
											if(in_array($studentAnswer,$correctAnswerArr)){ $point = 1; }
											else{ $point = 0; break; }
										}
									}
								}elseif($answerType == 6){ 
								
									// $correctAnswerArr = $correctAnswerArr;
									// $studentAnswerArr = serialize($studentAnswerArr);
									
									//GET: student questions answers combinations
									$stdHtml = '<table><tr><td class="orange">COLUMN A</td><td class="orange">COLUMN B</td></tr>'; $numb=0;
									$options = $this->dbClassObj->query("SELECT * FROM `ep_options` WHERE questionId = ".$singleQues['id']." ORDER BY id ASC");
									while($option = $this->dbClassObj->fetch_assoc($options)){
										if($numb%2 == 0){ $color = 'grey'; }else{ $color = ''; }
										$stdHtml .= '<tr><td class="'.$color.'">'.$option['answerOption'].'</td><td class="'.$color.'">'.$studentAnswerArr[$numb].'</td></tr>';
										$numb++;
									}
									$stdHtml .= '</table><style>.orange{background-color:#FF6600;color:#FFFFFF}.grey{background-color:#999999;}</style>';
									$responseArray['questions'][$i]['studentHtml'] = $stdHtml;
									
									//GET: correct questions answers combinations
									$stdHtml = '<table><tr><td class="orange">COLUMN A</td><td class="orange">COLUMN B</td></tr>'; $numb=0;
									$options = $this->dbClassObj->query("SELECT * FROM `ep_options` WHERE questionId = ".$singleQues['id']." ORDER BY id ASC");
									while($option = $this->dbClassObj->fetch_assoc($options)){
										if($numb%2 == 0){ $color = 'grey'; }else{ $color = ''; }
										$stdHtml .= '<tr><td class="'.$color.'">'.$option['answerOption'].'</td><td class="'.$color.'">'.$correctAnswerArr[$numb].'</td></tr>';
										$numb++;
									}
									$stdHtml .= '</table><style>.orange{background-color:#FF6600;color:#FFFFFF}.grey{background-color:#999999;}</style>';
									$responseArray['questions'][$i]['correctHtml'] = $stdHtml;
									
									if($studentAnswerArr == $correctAnswerArr){ $point = 1; }
									else{ $point = 0; }
								}else{ 
									$correctAnswerArr = $singleQues['matrix_correct_answers'];
									$studentAnswerArr = $answers[$i]->answer;
									
									//PROCESS: code executes if the format of answer is in nester arrays format
									/* if($studentAnswerArr != $correctAnswerArr){ 
										if($studentAnswerArr[0] == $correctAnswerArr){ 
											$studentAnswerArr = $studentAnswerArr[0];
										}else{
											$studentAnswerArr = unserialize($studentAnswerArr[0]);
											foreach($studentAnswerArr as $key=>$value){
												$studentAnswerArr[$key] = $value[0];
											}
											$studentAnswerArr = serialize($studentAnswerArr);
										}
									} */
									
									/***********CODE STARTS: HTML FOR THE CORRECT AND STUDENT ANSWERS**********/
									//SET: the correct answers html
									$mquery = $this->dbClassObj->query("SELECT matrix_type, matrix_row, matrix_column FROM `ep_questions` WHERE worksheetId = ".$worksheet_id." AND answerType =5 AND id=".$singleQues['id']);
									$mobject = $this->dbClassObj->fetch_assoc($mquery);
									
									//SET: variables
									$type = $mobject['matrix_type'];
									$array_rows = explode(',',$mobject['matrix_row']);
									$array_columns = explode(',',$mobject['matrix_column']);
									// $array_columns = array_map('trim',$array_columns);
									$total_rows = count($array_rows);
									$total_columns = count($array_columns);
									
									if($singleQues['answerType'] == 5 && $type=="checkbox"){
										$studentAnswerArr = unserialize($studentAnswerArr);
										$studentAnswerArr = array_map('array_filter', $studentAnswerArr);
										$studentAnswerArr = array_filter($studentAnswerArr);
										$studentAnswerArr = serialize($studentAnswerArr);
									}
									
									if($singleQues['answerType'] == 5 && $type=="textfield"){
										$studentAnswerArr = unserialize($studentAnswerArr);
										foreach($studentAnswerArr as $key=>$value){
											$studentAnswerArr[$key] = array_map('strtolower',$value);
										}
										$studentAnswerArr = serialize($studentAnswerArr);
									}
									
									$studentAns = unserialize($studentAnswerArr);
									// $studentAns = unserialize($correctAnswerArr);
									
									/* echo '<pre>';
									print_r($studentAns);
									echo '</pre>';
									echo '---------------------------<br/>'; */

									
									//SET: student answer HTML
									$answer_html = '';
									if(!empty($type)){
										if(empty($total_rows)){ $total_rows=0; }
										if(empty($total_columns)){ $total_columns=0; }
										//Generating a matrix in the form of HTML table
										$answer_html .= '<table id="ans_table">';
											for( $x = 0 ; $x < $total_rows ; $x++ ){
												
												if($singleQues['answerType'] == 5 && $type=="radio"){ $columnIndexArrSt[$x] = 9; }
												
												if( $x == 0 ){
													$answer_html .= '<tr><td></td>';
													for( $q = 0 ; $q < $total_columns ; $q++ ){ $answer_html .= '<td>'.$array_columns[$q].'</td>'; }
													$answer_html .= '</tr>';
												}
												//PROCESS: generating the radio buttons nature
												if($singleQues['answerType'] < 5){
													$studentOption = $studentAns[$x];
													$studentOption = explode('~sep~',$studentOption);
													$rowIndex = array_search($studentOption[0],$array_rows);
													$columnIndex = array_search(trim($studentOption[1]),$array_columns);
												}		
													
												//CHECK: if the question type is checkbox
												if($singleQues['answerType'] == 5 && $type=="checkbox"){
													$numbr=0;
													foreach($studentAns[$x] as $studentOption){
														$studentOption = explode('~sep~',$studentOption);
														$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
														$columnIndexArrSt[$x][$numbr] = array_search($studentOption[1],$array_columns);
														$numbr++;
													}
												}
												
												//CHECK: if the question type is radio and answer type = 5
												if($singleQues['answerType'] == 5 && $type=="radio"){
													$numbr=0;
													
													if(!is_array($studentAns[$x])){ $studentAns[$x] = array('0'=>$studentAns[$x]); }

													foreach($studentAns[$x] as $studentOption){
														if(count($studentOption) > 0){
															$studentOption = explode('~sep~',$studentOption);
															$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
															$columnIndexArrSt[$x] = array_search($studentOption[1],$array_columns);
														}elseif(!empty($studentOption)){
															$studentOption = explode('~sep~',$studentOption);
															$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
															$columnIndexArrSt[$x] = array_search($studentOption[1],$array_columns);
														}else{
															$rowIndexArr[$x][] = 9;
															$columnIndexArrSt[$x] = 9;
														}
													}
												}
												
												//CHECK: if the question type is textfield
												if($singleQues['answerType'] == 5 && $type=="textfield"){
													$numbr=0;
													foreach($studentAns[$x] as $studentOption){
														$text_value_st[$numbr] = $studentOption;
														$numbr++;
													}
												}
											
												$answer_html .= '<tr><td>'.$array_rows[$x].'</td>';
												for( $k = 0 ; $k < $total_columns ; $k++ ){
													$checked = '';
													//SET: checked variable
													// if(empty($studentOption[1])){$columnIndex=2;}
													
													if($singleQues['answerType'] < 5){ if($columnIndex == $k){ $checked = 'checked'; }else{ $checked = ''; } }
													
													//CHECK: if the question type is checkbox
													if($singleQues['answerType'] == 5 && $type=="checkbox"){ if(in_array($k,$columnIndexArrSt[$x])){ $checked = 'checked'; }else{ $checked = ''; } }
													
													//CHECK: if the question type is radio
													if($singleQues['answerType'] == 5 && $type=="radio"){ if($k == $columnIndexArrSt[$x]){ $checked = 'checked'; }else{ $checked = ''; } }
													
													//PROCESS: generating the HTML
													$answer_html .= '<td>';
														if($type=="checkbox"){ $answer_html .= '<input type="checkbox" name="checkbox_'.$x.'_'.$k.'" class="checkboxes" '.$checked.' disabled />'; }
														elseif($type=="radio"){ $answer_html .= '<input type="radio" name="radiogroup_'.$x.'" class="radio" '.$checked.' disabled />'; }
														elseif($type=="textfield"){ $answer_html .= '<input type="text" name="text_'.$x.'" class="text" value="'.$text_value_st[$k].'" disabled />'; }
													$answer_html .= '</td>';
													$checked= '';
												}
												$answer_html .= '</tr>';
												
												if($singleQues['answerType'] == 5 && $type=="checkbox"){ $columnIndexArrSt[$x] = array(); }
												
											}
										$answer_html .= '</table>';
									}
									$responseArray['questions'][$i]['studentHtml'] = $answer_html;
									
									//SET: correct answer HTML
									$correctAns = unserialize($correctAnswerArr);
								
									$answer_html = '';
									$columnIndexArr = $rowIndexArr = array();
									if(!empty($type)){
										if(empty($total_rows)){ $total_rows=0; }
										if(empty($total_columns)){ $total_columns=0; }
										//Generating a matrix in the form of HTML table
										$answer_html .= '<table id="ans_table">';
											for( $x = 0 ; $x < $total_rows ; $x++ ){
												if( $x == 0 ){
													$answer_html .= '<tr><td></td>';
													for( $q = 0 ; $q < $total_columns ; $q++ ){ $answer_html .= '<td>'.$array_columns[$q].'</td>'; }
													$answer_html .= '</tr>';
												}
												
												//PROCESS: generating the radio buttons nature
												$studentOption = $correctAns[$x];
												$studentOption = explode('~sep~',$studentOption);
												$rowIndex = array_search($studentOption[0],$array_rows);
												$columnIndex = array_search($studentOption[1],$array_columns);
											
												//CHECK: if the question type is checkbox
												if($singleQues['answerType'] == 5 && $type=="checkbox"){
													foreach($correctAns[$x] as $studentOption){
														$studentOption = explode('~sep~',$studentOption);
														$rowIndexArr[$x][] = array_search($studentOption[0],$array_rows);
														$columnIndexArr[$x][] = array_search($studentOption[1],$array_columns);
													}
												}
											
												//CHECK: if the question type is textfield
												if($singleQues['answerType'] == 5 && $type=="textfield"){
													$numbr=0;
													foreach($correctAns[$x] as $studentOption){
														$text_value[$numbr] = $studentOption;
														$numbr++;
													}
												}
											
												$answer_html .= '<tr><td>'.$array_rows[$x].'</td>';
												for( $k = 0 ; $k < $total_columns ; $k++ ){
													//SET: checked variable
													if($columnIndex == $k){ $checked = 'checked'; }else{ $checked = ''; }
													
													//CHECK: if the question type is checkbox
													if($singleQues['answerType'] == 5 && $type=="checkbox"){ if(in_array($k,$columnIndexArr[$x])){ $checked = 'checked'; }else{ $checked = ''; } }
												
													//PROCESS: generating the HTML
													$answer_html .= '<td>';
														if($type=="checkbox"){ $answer_html .= '<input type="checkbox" name="checkbox_'.$x.'_'.$k.'" class="checkboxes" '.$checked.' disabled />'; }
														elseif($type=="radio"){ $answer_html .= '<input type="radio" name="radiogroup_'.$x.'" class="radios" '.$checked.' disabled />'; }
														elseif($type=="textfield"){ $answer_html .= '<input type="text" name="text_'.$x.'" class="text" value="'.$text_value[$k].'" disabled />'; }
													$answer_html .= '</td>';
												}
												$answer_html .= '</tr>';
											}
										$answer_html .= '</table>';
									}
									$responseArray['questions'][$i]['correctHtml'] = $answer_html;
									/***********CODE ENDS: HTML FOR THE CORRECT AND STUDENT ANSWERS**********/
									
									/* if($singleQues['answerType'] == 5 && $type=="checkbox"){
										$studentAnswerArr = unserialize($studentAnswerArr);
										$studentAnswerArr = array_map('array_filter', $studentAnswerArr);
										$studentAnswerArr = array_filter($studentAnswerArr);
										$studentAnswerArr = serialize($studentAnswerArr);
									} */
									
									if($studentAnswerArr == $correctAnswerArr){ $point = 1; }
									else{ $point = 0; }
									
									// echo 'Hello--------CorrectAnswerArr-------<br/>';
									// echo '<pre>',print_r($correctAnswerArr),'</pre>';
									// echo '--------StudentAnswerArr-------<br/>';
									// echo '<pre>',print_r($studentAnswerArr),'</pre>';
									// exit;
									
								}
								
								$responseArray['questions'][$i]['correctAnswerArr'] = $correctAnswerArr;
								$responseArray['questions'][$i]['studentAnswerArr'] = $studentAnswerArr;
								
								if($answerType == 3){
									$responseArray['questions'][$i]['studentAnswerArr'] = $tempStudentAns;
								}
								
								if($answerType == 2){
									(array)$responseArray['questions'][$i]['correctAnswerArr'] = array_values((array)$correctAnswerArr);
									(array)$responseArray['questions'][$i]['studentAnswerArr'] = array_values((array)$studentAnswerArr);
								}
								
								$responseArray['questions'][$i]['point'] = $point;
								
								// print_r($responseArray['questions'][$i]['point']);exit('here');
								
								$points_collected = $points_collected + $point;
							//ENDS: correct answer finding algo
							
							//CONDITION: answer correct or not
							// if( strtolower($answers[$i]->answer) == strtolower($singleQues['correctAnswer'])){ $responseArray['questions'][$i]['point'] = 1; $points_collected++;}
							// else{ $responseArray['questions'][$i]['point'] = 0; }
							
						}else{ $responseArray['questions'][$i]['message'] = 'Question not exists'; }
					// }
					$i++; //INCREEMENT: for the next question
				}
				$responseArray['points_collected'] = $points_collected;	//SET: points collected variable in responseArray
				$responseArray['worksheet_points'] = $i;	//SET: worksheet points variable in responseArray
				

				//Messages
				$score = ($points_collected * 100)/$i;
				$studentName	=ucfirst($studentInfo['fname']);
				$arr_ans_summary="";
				if($score==100){
					$str_ans_summary  ="Congratulations";
				}elseif($score>=90 && $score<=99){ 
					$str_ans_summary = "Well done";
				}elseif($score>=80 && $score<=89){ 
					$str_ans_summary = "Great work";
				}elseif($score>=70 && $score<=79){ 
					$str_ans_summary = "Keep up the good work";
				}elseif($score>=50 && $score<=69){  
					$str_ans_summary = "Good effort";
				}elseif($score>=0 && $score<=49){
					$str_ans_summary = "Practice makes perfect";
				}
				$responseArray['title_message'] = $str_ans_summary ;
				
				$arr_ans_summary="";
				if($score==100){
					$str_ans_summary  ="You got a perfect score!";
				}elseif($score>=90 && $score<=99){ 
					$str_ans_summary = "You got an excellent score!";
				}elseif($score>=80 && $score<=89){ 
					$str_ans_summary = "You got a great score!";
				}elseif($score>=70 && $score<=79){ 
					$str_ans_summary = "You got a good score";
				}elseif($score>=50 && $score<=69){  
					$str_ans_summary = "Nice try! Keep going!";
				}elseif($score>=0 && $score<=49){
					$str_ans_summary = "Try again for a better score";
				}
				$responseArray['desc_message'] = $str_ans_summary ;
				
				//READY: result is ready for json encode
				//SET: the response array
				$response = array('error' => 0, 'message' => 'Worksheet Results','response'=>$responseArray); 
			echo json_encode($response);
		}
		
		//SELF ASSIGN WS
		public function self_assign_by_student(){
			if($_SERVER['REQUEST_METHOD']=='POST') 
			{
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$student_id = $this->getUidFromUserToken($token);
				$jsondata = file_get_contents('php://input');
				$jsondata = json_decode($jsondata);
				
				//SET: variables
				//$student_id = $jsondata->student_id;
				$worksheet_id = $jsondata->worksheet_id;
				
				//CHECK: student_id not empty
				if(empty($student_id)){$response = array('error' => ERROR_EXISTS, 'message' => 'Student Token is required');echo json_encode($response);exit;}

				//CHECK: worksheet_id not empty
				if(empty($worksheet_id)){$response = array('error' => ERROR_EXISTS, 'message' => 'Worksheet ID is required');echo json_encode($response);exit;}
				
				//CHECK: student is exists or not
				$student_query = $this->dbClassObj->query("SELECT * FROM ep_wsusers WHERE id = ".$student_id." AND user_type = 'student'");
				$studentInfo = $this->dbClassObj->fetch_assoc($student_query);
				if(empty($studentInfo['id'])){$response = array('error' => ERROR_EXISTS, 'message' => 'Student not exists');echo json_encode($response);exit;}
				else{ $parent_id = $studentInfo['parent_id']; }
				
				//GET: years ids on the basis of student ID
				$yearsArr="";
				$years_query = $this->dbClassObj->query("SELECT * FROM ep_wsyears WHERE year = ".$studentInfo['student_year']);
				while($yearsInfo = $this->dbClassObj->fetch_assoc($years_query)){
					$yearsArr .= "'".$yearsInfo['id']."',";
				}
				$yearsArr = substr($yearsArr,0,-1);
				if(empty($yearsArr)){$response = array('error' => ERROR_EXISTS, 'message' => 'Associated years not exists');echo json_encode($response);exit;}
				
				//CHECK: worksheet is assigned to student or not
				$assigned = $this->dbClassObj->query("SELECT * FROM `ep_wsassigned` WHERE worksheetId = ".$worksheet_id." AND studentId = ".$student_id." AND is_complete = '0'");
				$assigned = $this->dbClassObj->fetch_assoc($assigned);
				if(!empty($assigned['id'])){$response = array('error' => ERROR_EXISTS, 'message' => 'Worksheet is already assigned');echo json_encode($response);exit;}
				else{
					//GET: Worksheet category ID
					$get_query = $this->dbClassObj->query("SELECT * FROM `ep_worksheets` WHERE id = ".$worksheet_id." AND year IN (".$yearsArr.")");
					$worksheetsCount = $this->dbClassObj->num_rows($get_query);
					$result = $this->dbClassObj->fetch_assoc($get_query);
					if($worksheetsCount == 0){ $response = array('error' => 1, 'message' => 'Worksheet can only be assigned for year '.$studentInfo['student_year'].'.');echo json_encode($response);exit; }
				
					//INSERT: insert assigned ws
					$insert_query = $this->dbClassObj->query("insert into `ep_wsassigned` set worksheetId=".$worksheet_id.", worksheetCat=".$result['worksheetCat'].", studentId=".$student_id.", parentId=".$parent_id.", dateAssigned='".date('Y-m-d H:i:s')."'");
									
					if(!$this->dbClassObj->error()){$response = array('error' => STATUS_OK, 'message' => 'Worksheet assigned successfully');echo json_encode($response);exit;}
					else{$response = array('error' => ERROR_EXISTS, 'message' => 'Something went wrong. Please try again later!');echo json_encode($response);exit;}
				}
			}
			else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
					echo json_encode($response);exit;
				}
			}else{$response = array('error' => ERROR_EXISTS, 'message' => POST_ERROR_MSG);echo json_encode($response);exit;}
		}
		
		//ADD STUDENT SUBSCRIPTION
		public function add_student_subscription(){
			if($_SERVER['REQUEST_METHOD']=='POST') 
			{
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parent_id = $this->getUidFromUserToken($token);
				$jsondata = file_get_contents('php://input');
				$jsondata = json_decode($jsondata);
				
				//SET: variables
				//$parent_id = $jsondata->parent_id;
				$name = $jsondata->name;
				$username = $jsondata->username;
				$password = $this->hasherObj->createHash($jsondata->password);
				$subject_id = $jsondata->subject_id;
				$years = $jsondata->years;
				
				//CHECK: parent_id is not empty
				if(empty($parent_id)){$response = array('error' => ERROR_EXISTS, 'message' => 'Parent Token is required');echo json_encode($response);exit;}
				//CHECK: name is not empty
				if(empty($name)){$response = array('error' => ERROR_EXISTS, 'message' => 'Student name is required');echo json_encode($response);exit;}
				//CHECK: username is not empty
				if(empty($username)){$response = array('error' => ERROR_EXISTS, 'message' => 'Student username is required');echo json_encode($response);exit;}
				//CHECK: password not empty
				if(empty($password)){$response = array('error' => ERROR_EXISTS, 'message' => 'Password is required');echo json_encode($response);exit;}
				//CHECK: subject_id not empty
				if(empty($subject_id)){$response = array('error' => ERROR_EXISTS, 'message' => 'Subject ID is required');echo json_encode($response);exit;}

				//CHECK: parent subscription is active or not
				$parent_query = $this->dbClassObj->query("select subs_status from ep_subscription_parent where parent_id='".$parent_id."'");
				$parentInfo = $this->dbClassObj->fetch_assoc($parent_query);
				if(empty($parentInfo['subs_status'])){$response = array('error' => ERROR_EXISTS, 'message' => 'You cannot add new child because your subscription is Inactive');echo json_encode($response);exit;}

				//GET: subscription years
				if(empty($years)){
					$years_query = $this->dbClassObj->query("select k.year from ep_wsyears as k where k.id in (".$years.") order by k.id desc limit 0,1 ");
					$yearsInfo = $this->dbClassObj->fetch_assoc($years_query);
					$yearsInfo = $yearsInfo['year'];
					$student_age  = date('Y')-($yearsInfo+5).':'.date('m');
					$insert_string=", age='".$student_age."', student_year=".$yearsInfo;
				}else{ $insert_string=""; }
				
				//CHECK: worksheet is assigned to student or not
				//---------------------------------
				$qry	= "select id from ep_wsusers where ( email='".strtolower($username)."' or user_name='".strtolower($username)."')";
				$sql	= $this->dbClassObj->query($qry);
				$count	= $this->dbClassObj->num_rows($sql);	
				if($count){
					$response = array('error' => 1, 'message' => 'This Username is already registered with us');echo json_encode($response);exit;
				}else{
					$pSql = $this->dbClassObj->query("select plan_type from ep_subscription_parent where parent_id='".$parent_id."'");
					$pSql = $this->dbClassObj->fetch_assoc($pSql);
					$parent_plan = $pSql['plan_type'];
					
					$spSql = $this->dbClassObj->query("select user_subs_days from ep_wsusers where id='".$parent_id."'");
					$spSql = $this->dbClassObj->fetch_assoc($spSql);
					$plan_days = $spSql['user_subs_days'];

					$pSql = $this->dbClassObj->query("select price from ep_subscription_plan where plan_type='".$parent_plan."' and plan_days=".$plan_days." and subject='".$subject_id."'");
					$pSql = $this->dbClassObj->fetch_assoc($pSql);
					$plan_price = $pSql['price'];
					
					$fName	= substr($name,0,strpos($name," "));
					$lName	= trim(substr($name,strpos($name," "),strlen($name)));
					
					$qry	 = "insert into ep_wsusers set fname='".$fName."', lname='".$lName."'";
					$qry	.= ", email='".strtolower($username)."',user_name='".strtolower($username)."', password='".$password."'";
					$qry	.= ", user_type='student', parent_id='".$parent_id."',  auto_assign_year='".$years."'";
					$qry	.= ", subject='".$subject_id."', auto_assign_subject='".$subject_id."' ".$insert_string.", date_created='".date('Y-m-d H:i:s')."', status='1'";
					$this->dbClassObj->query($qry);
					$student_id = $this->dbClassObj->insert_id();
					if(!$this->dbClassObj->error()){
						/* inserting record in child subscription table */		
						$qry  = "insert into ep_subscription_student set student_id=".$student_id.",parent_id='".$parent_id."',  subject_id='".$subject_id."', plan_type='".$parent_plan."', plan_price='".$plan_price."',subs_start_date='".date('Y-m-d')."', subs_end_date='".date('Y-m-d')."',is_current='0', is_paid='0', subs_status='0', is_addedby_parent_account='1', confirm_update='1'";			
						$this->dbClassObj->query($qry);
						/*update parent*/
						 $this->dbClassObj->query("update ep_subscription_parent set confirm_update='1' where parent_id='".$parent_id."'");
						/*update parent End*/
						if(!$this->dbClassObj->error()){$response = array('error' => STATUS_OK, 'message' => 'Child account created successfully');echo json_encode($response);exit;
						}else{$response = array('error' => ERROR_EXISTS, 'message' => 'Failed! Due to some technical reason, Please try again');echo json_encode($response);exit; }
					}else{$response = array('error' => ERROR_EXISTS, 'message' => 'Failed! Due to some technical reason, Please try again');echo json_encode($response);exit;}
				}	
				//---------------------------------
			}
			else {
						if($this->isPrivate($token)) {
							// Error message of "Token has been expired"
							$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
						}
						else{ 
							// Error message of "Token has been expired"
							$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
						}
						echo json_encode($response);exit;
					}
			}else{$response = array('error' => ERROR_EXISTS, 'message' => POST_ERROR_MSG);echo json_encode($response);exit;}
		}
		
		public function get_years(){
			
			if($_SERVER['REQUEST_METHOD']=='POST') 
			{
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$jsondata = file_get_contents('php://input');
				$jsondata = json_decode($jsondata);
				$subject_id = $jsondata->subject_id;
				
				//CHECK: subject_id is not empty
				if(empty($subject_id)){$response = array('error' => ERROR_EXISTS, 'message' => 'Subject ID is required');echo json_encode($response);exit;}
				
				if($subject_id==9 || $subject_id==''){
					$sql = "select GROUP_CONCAT(id) as yr_ids, year, age from ep_wsyears group by year order by year";
				} else {
					$sql = "select id as yr_ids, year, age from ep_wsyears where keystage_id in (select id from ep_wskeystage where subject_id in (".$subject_id.")) order by year";
				}
				$res = $this->dbClassObj->query($sql);
				$i=0; $temp=0;
				while($line = $this->dbClassObj->fetch_assoc($res)){	
					/* $year_id=@explode(',',$line['yr_ids']); 
					
					print_r($year_id);exit;
					
					asort($year_id);
					$line['yr_ids']=@implode(',',$year_id);
					if(in_array($line['yr_ids'],$selectedYear)){
						$sel = 'selected="selected"';
					}elseif(in_array($selected,$year_id)){
						$sel = 'selected="selected"';
					}elseif($line['yr_ids']==$selected){
						$sel = 'selected="selected"';
					}else{
					$sel = '';
					} */
					
					if($temp == $line['year'] || $temp == 0){
						$str_cmb[$i]['year'] = $line['year'];
						$str_cmb[$i]['age'] = $line['age'];
						$str_cmb[$i]['year_ids'] .= $line['yr_ids'].',';
						$temp = $line['year'];
					}else{
						$str_cmb[$i]['year_ids'] = substr($str_cmb[$i]['year_ids'],0,-1);
						$i++;
						$str_cmb[$i]['year_ids'] .= $line['yr_ids'].',';
						$str_cmb[$i]['year'] = $line['year'];
						$str_cmb[$i]['age'] = $line['age'];
						$temp = $line['year'];
					}
				}
				$response = array('error' => STATUS_OK, 'message' => 'Years by Subject ID','response'=>$str_cmb);echo json_encode($response);exit;
				return $str_cmb;
			}
			else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
					echo json_encode($response);exit;
				}
			}else{$response = array('error' => ERROR_EXISTS, 'message' => 'POST data is required');echo json_encode($response);exit;}
		}

		//REQUEST REWARD WS
		public function request_reward(){
			if($_SERVER['REQUEST_METHOD']=='POST') 
			{
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$student_id = $this->getUidFromUserToken($token);
				$jsondata = file_get_contents('php://input');
				//$jsondata = json_decode($jsondata);
				
				//SET: variables
				//$student_id = $jsondata->student_id;
				
				//CHECK: student_id not empty
				if(empty($student_id)){$response = array('error' => ERROR_EXISTS, 'message' => 'Student Token is required');echo json_encode($response);exit;}

				//CHECK: student is exists or not
				$student_query = $this->dbClassObj->query("SELECT * FROM ep_wsusers WHERE id = ".$student_id." AND user_type = 'student'");
				$studentInfo = $this->dbClassObj->fetch_assoc($student_query);
				if(empty($studentInfo['id'])){$response = array('error' => ERROR_EXISTS, 'message' => 'Student not exists');echo json_encode($response);exit;}
				
				//CHECK: worksheet is assigned to student or not
				$reuest_query = $this->dbClassObj->query("SELECT * FROM `ep_reward_student_comment` WHERE student_id = ".$student_id." AND request_type = 'Requested reward'");
				$reuest_object = $this->dbClassObj->fetch_assoc($reuest_query);
				if(!empty($reuest_object['id'])){$response = array('error' => ERROR_EXISTS, 'message' => 'You already requested a reward');echo json_encode($response);exit;}
				else{
					//SET: variables
					$student_name = $studentInfo['fname'];
					$parent_id = $studentInfo['parent_id'];
					$request_type = 'Requested reward';
					$comments = $student_name.' has asked for a reward';
					$created_date = date('Y-m-d H:i:s');
					
					//GET: Worksheet category ID
					$get_query = $this->dbClassObj->query("INSERT INTO ep_reward_student_comment (parent_id, student_id, reward_id, request_type, comments, created_date, status, is_viewed ) VALUES(".$parent_id.",".$student_id.",'0','".$request_type."','".$comments."','".$created_date."','1','0')");
					
					if(!$this->dbClassObj->error()){$response = array('error' => STATUS_OK, 'message' => 'Reward requested successfully');echo json_encode($response);exit;}
					else{$response = array('error' => ERROR_EXISTS, 'message' => 'Something went wrong. Please try again later!');echo json_encode($response);exit;}
				}
			}
			else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
					echo json_encode($response);exit;
				}
			}else{$response = array('error' => ERROR_EXISTS, 'message' => 'OOPS! Something Wrong Occured.');echo json_encode($response);exit;}
		}
		
		//GET TIMELINE NOTIFICATIONS WS
		public function get_timeline_notifications(){
			if($_SERVER['REQUEST_METHOD']=='POST') 
			{
		        $user_token = $this->getToken();
			    if($user_token && $this->validateToken($user_token) == TRUE ) {
				$student_id = $this->getUidFromUserToken($user_token);
				$jsondata = file_get_contents('php://input');
				//$jsondata = json_decode($jsondata);
				
				//SET: variables
				//$student_id = $jsondata->student_id;
				
				//CHECK: student_id not empty
				if(empty($student_id)){$response = array('error' => ERROR_EXISTS, 'message' => 'Student Token is required');echo json_encode($response);exit;}

				//CHECK: student is exists or not
				$student_query = $this->dbClassObj->query("SELECT * FROM ep_wsusers WHERE id = ".$student_id." AND user_type = 'student'");
				$studentInfo = $this->dbClassObj->fetch_assoc($student_query);
				if(empty($studentInfo['id'])){$response = array('error' => ERROR_EXISTS, 'message' => 'Student not exists');echo json_encode($response);exit;}
				
				//GET: notifications is_viewed = 0
				$request_query = $this->dbClassObj->query("SELECT COUNT(id) AS notifications FROM ep_student_notic_log WHERE notic_user = ".$student_id." AND is_viewed='0' AND created_date >= (NOW() - interval 30 day)");
				$request_object = $this->dbClassObj->fetch_assoc($request_query);
				$notifications = $request_object['notifications'];
				// $this->update_timeline_notifications($student_id);
				$response = array('error' => STATUS_OK, 'message' => 'Timeline notifications','response'=>$notifications);echo json_encode($response);exit;
			}
			else {$response = array('error' => USER_TOKEN_EXPIRED, 'messsage' => USER_TOKEN_EXPIRED_MSG);echo json_encode($response);exit;}
			}else{$response = array('error' => ERROR_EXISTS, 'message' => POST_ERROR_MSG);echo json_encode($response);exit;}
		}
		
		//EP_MOBILE_LEAD_USERS WS for more details
		public function ep_mobile_lead_users_ws(){
			if($_SERVER['REQUEST_METHOD']=='POST') 
			{
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
				
					$jsondata = file_get_contents('php://input');
					$jsondata = json_decode($jsondata);
					
					//SET: variables
					$name = $jsondata->name;
					$email = $jsondata->email;
					
					//CHECK: guest name not empty
					if(empty($name)){
						$response = array('error' => ERROR_EXISTS, 'message' => 'Guest name is required');						
					}
					//CHECK: guest email not empty
					if(empty($email)){
						$response = array('error' => ERROR_EXISTS, 'message' => 'Guest email is required');						
					}

					//CHECK: student is exists or not
					/* $select_query = $this->dbClassObj->query("SELECT * FROM ep_mobile_lead_users WHERE email = '".$email."'");
					$select_query = mysql_fetch_assoc($select_query);
					if(!empty($select_query['email'])){$response = array('error' => 1, 'message' => 'User already visited');echo json_encode($response);exit;} */

					
					//CHECK: student is exists or not
					$insert_query = $this->dbClassObj->query("INSERT INTO ep_mobile_lead_users(name,email,date_created) VALUES('".$name."','".$email."','".date('Y-m-d H:i:s')."')");
					if($this->dbClassObj->error()){
						$response = array('error' => ERROR_EXISTS, 'message' => 'Something went wrong. Please try again!');
					}
					else{
						$response = array('error' => STATUS_OK, 'message' => 'Thanks!');
					}
				}else {
						if($this->isPrivate($token)) {
							// Error message of "Token has been expired"
							$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
						}
						else{ 
							// Error message of "Token has been expired"
							$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
						}
					}
			}else{
				$response = array('error' => ERROR_EXISTS, 'message' => POST_ERROR_MSG);
				
			}
			echo json_encode($response);
				
		}
		//UPDATE TIMELINE NOTIFICATIONS WS
		public function update_timeline_notifications($student_id){
			/* if($_SERVER['REQUEST_METHOD']=='POST') 
			{
				$jsondata = file_get_contents('php://input');
				$jsondata = json_decode($jsondata); */
				
				//SET: variables
				// $student_id = $jsondata->student_id;
				
				//CHECK: student_id not empty
				// if(empty($student_id)){$response = array('error' => 1, 'message' => 'Student ID is required');echo json_encode($response); return 0; /* exit; */}

				//CHECK: student is exists or not
				// $student_query = mysql_query("SELECT * FROM ep_wsusers WHERE id = ".$student_id." AND user_type = 'student'");
				// $studentInfo = mysql_fetch_assoc($student_query);
				// if(empty($studentInfo['id'])){/* $response = array('error' => 1, 'message' => 'Student not exists');echo json_encode($response); */return 0;/* exit; */}
				
				//GET: notifications is_viewed = 0
				$request_query = $this->dbClassObj->query("UPDATE ep_student_notic_log SET is_viewed='1' WHERE notic_user = ".$student_id." AND is_viewed='0' AND created_date >= (NOW() - interval 30 day)");
				// $response = array('error' => 0, 'message' => 'Timeline notifications','response'=>'0');echo json_encode($response);return 1;/* exit; */
			// }else{$response = array('error' => 1, 'message' => 'POST data is required');echo json_encode($response);exit;}
		}
		
		//GET SUBJECTS WS
		public function get_subjects(){
			//GET: all subject details
			$token = $this->getToken();
			if($token && $this->validateToken($token) == TRUE ) {
			$get_query = $this->dbClassObj->query("SELECT * FROM `ep_wssubjects`");
			$total_rows = $this->dbClassObj->num_rows($get_query);
			if( $total_rows == 0 ){$response = array('error' => ERROR_EXISTS, 'message' => 'No subejcts are available');echo json_encode($response);exit;}
			else{
				$i=0;
				$responseArray[$i]['id'] = "9";
				$responseArray[$i]['subject'] = "All Subjects";
				$i++;
				while($records = $this->dbClassObj->fetch_assoc($get_query)){
					$responseArray[$i]['id'] = $records['id'];
					$responseArray[$i]['subject'] = $records['subject'];
					$i++;
				}
				$response = array('error' => STATUS_OK, 'message' => 'Subject details','response'=>$responseArray);echo json_encode($response);exit;
			}
		}
		else {
					if($this->isPrivate($token)) {
							// Error message of "Token has been expired"
							$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
						}
						else{ 
							// Error message of "Token has been expired"
							$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
						}
						echo json_encode($response);exit;
					}
		}
		
		function demo(){
			echo '<pre>';
				print_r($this->studentFoundBadges(1505));
			echo '</pre>';
		}
		
		//Update the badges on worksheet completion 
		function studentFoundBadges($student_id){
			$found =0;$badges_inc=0;/* $badgesArr=array(); */
			$completed_description='';
			$conditonBadge	= array(1,2,3);
			$SubjectBadge	= array('1'=>'maths','2'=>'english','3'=>'science');

			$studentBadges ="SELECT BC.*, AB.id AS ab_id FROM ep_badges_condition as BC, ep_badges_assigned AS AB WHERE BC.id=AB.assigned_badges_id AND BC.status='1' AND AB.student_id='".$student_id."' and AB.is_found='0' and AB.status='1'";
			$studentbadgesResult =$this->dbClassObj->query($studentBadges);
			
			if($this->dbClassObj->num_rows($studentbadgesResult)>0){
				while($studentbadge=$this->dbClassObj->fetch_assoc($studentbadgesResult)){
				$is_found =0;	
				$sbadge_year_id=$studentbadge['year_id'];
				$sbadge_subject_id=$studentbadge['subject_id'];
				$sbadge_type_id=$studentbadge['badges_type_id'];
				/*Assigne badge array*/
					if(in_array($sbadge_type_id,$conditonBadge)){
						if($sbadge_type_id==1){ /*Subject*/
							
							//UPDATION STARTS//
							$subject_worksheets=$this->dbClassObj->query("select group_concat(W.id) as ids from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.status='1'");
							$subject_worksheets = $this->dbClassObj->fetch_assoc($subject_worksheets);
							$subject_worksheets = $subject_worksheets['ids'];
							//UPDATION ENDS//
							
							$subject_worksheets_count=count(@explode(',',$subject_worksheets))-1;					
							if($subject_worksheets_count){
								if($studentbadge['worksheets_completed']!='' && $studentbadge['worksheets_completed']!=0 && $studentbadge['score_over']!='' && $studentbadge['score_over']!=0){
								/*25% subject worksheets in year completed with score over 75% in each*/
									
									//UPDATION STARTS//
									$sw_score_count=$this->dbClassObj->query("select count(*) as count from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
									$sw_score_count = $this->dbClassObj->fetch_assoc($sw_score_count);
									$sw_score_count = $sw_score_count['count'];
									//UPDATION ENDS//
									
									// $sw_score_count =$this->get_single_result("select count(*) from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
									
									
									$psw_count =($subject_worksheets_count*$studentbadge['worksheets_completed'])/100;	
									/* echo "<br/> subject_worksheets_count: ".$subject_worksheets_count."<br/>select group_concat(W.id) from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.status='1'";
									echo "<br/> psw_count: ".$psw_count."<br/>select count(*) from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M "; */
									if($sw_score_count>=$psw_count){
										$is_found=1;
										$completed_description="Wow! You've scored ".$studentbadge['score_over']."% or more in ".$studentbadge['worksheets_completed']."% ".$SubjectBadge[$sbadge_subject_id]." worksheets";
									}
								}elseif($studentbadge['average_score_over']!='' && $studentbadge['average_score_over']!=0){
								/*Completed all worksheets in a single subject with an average score over 75%*/
									
									//UPDATION STARTS//
									$sw_avscore_count=$this->dbClassObj->query("select T.wcount as wcount from (select count(distinct(worksheetId)) as wcount, (sum(score_collected)/sum(score_total))*100 as avg_score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and is_complete='1' group by studentId) as T where T.avg_score_over>=".$studentbadge['average_score_over']);
									$sw_avscore_count = $this->dbClassObj->fetch_assoc($sw_avscore_count);
									$sw_avscore_count = $sw_avscore_count['wcount'];
									//UPDATION ENDS//
									
									// $sw_avscore_count =$this->get_single_result("select T.wcount from (select count(distinct(worksheetId)) as wcount, (sum(score_collected)/sum(score_total))*100 as avg_score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and is_complete='1' group by studentId) as T where T.avg_score_over>=".$studentbadge['average_score_over']);
									
									/* echo "<br/> subject_worksheets_count: ".$subject_worksheets_count."<br/>select group_concat(W.id) from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.status='1'";
									echo "<br/> sw_avscore_count: ".$sw_avscore_count."<br/>select T.wcount from (select count(distinct(worksheetId)) as wcount, (sum(score_collected)/sum(score_total))*100 as avg_score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and is_complete='1' group by studentId) as T where T.avg_score_over>=".$studentbadge['average_score_over'];  */
									if($sw_avscore_count>=$subject_worksheets_count){
										$is_found=1;
										$completed_description="Wow! You've scored ".$studentbadge['average_score_over']."% or more in all ".$SubjectBadge[$sbadge_subject_id]." worksheets";
									}
								}
							}
						}elseif($sbadge_type_id==2){ /*Topic*/
						
							//UPDATION STARTS//
							$topic_worksheets=$this->dbClassObj->query("select group_concat(W.id) as wid from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.topic_id='".$studentbadge['topic_id']."' and W.status='1'");
							$topic_worksheets = $this->dbClassObj->fetch_assoc($topic_worksheets);
							$topic_worksheets = $topic_worksheets['wid'];
							//UPDATION ENDS//
						
							// $topic_worksheets=$this->get_single_result("select group_concat(W.id) from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.topic_id='".$studentbadge['topic_id']."' and W.status='1'");
							
							
							$topic_worksheets_count=count(@explode(',',$topic_worksheets))-1;
							if($topic_worksheets_count){
							
								//UPDATION STARTS//
								$tw_score_count=$this->dbClassObj->query("select count(*) as count from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$topic_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
								$tw_score_count = $this->dbClassObj->fetch_assoc($tw_score_count);
								$tw_score_count = $tw_score_count['count'];
								//UPDATION ENDS//
							
								// $tw_score_count =$this->get_single_result("select count(*) from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$topic_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
								
								if($tw_score_count>=$topic_worksheets_count){
									$is_found=1;
									$completed_description="Great work! You've scored ".$studentbadge['score_over']."% or over in all ".$studentbadge['badges_subjects']." worksheets.";
								}
							}
						}elseif($sbadge_type_id==3){ /*Curriculum*/
						
							//UPDATION STARTS//
							$curriculum_worksheets=$this->dbClassObj->query("select group_concat(W.id) as gcwid from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.ksu='".$studentbadge['curriculum_id']."' and W.status='1'");
							$curriculum_worksheets = $this->dbClassObj->fetch_assoc($curriculum_worksheets);
							$curriculum_worksheets = $curriculum_worksheets['gcwid'];
							//UPDATION ENDS//
						
							// $curriculum_worksheets=$this->get_single_result("select group_concat(W.id) from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.ksu='".$studentbadge['curriculum_id']."' and W.status='1'");
						
							$curriculum_worksheets_count=count(@explode(',',$curriculum_worksheets))-1;
							if($curriculum_worksheets_count){
								
								//UPDATION STARTS//
								$cw_score_count=$this->dbClassObj->query("select count(*) as count from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$curriculum_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
								$cw_score_count = $this->dbClassObj->fetch_assoc($cw_score_count);
								$cw_score_count = $cw_score_count['count'];
								//UPDATION ENDS//
							
								// $cw_score_count =$this->get_single_result("select count(*) from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$curriculum_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
								
								if($cw_score_count>=$curriculum_worksheets_count){
									$is_found=1;
									$completed_description="Amazing! You've scored ".$studentbadge['score_over']."% or over in all ".$studentbadge['badges_subjects']." worksheets.";
								}
							}
						}
					}elseif(!in_array($sbadge_type_id,$conditonBadge)){
						if($sbadge_type_id==4){ /* Worksheets */
							/*Completed total of 50,200,500 worksheets*/
							
							//UPDATION STARTS//
							$student_worksheets_count=$this->dbClassObj->query("select count(id) as count from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
							$student_worksheets_count = $this->dbClassObj->fetch_assoc($student_worksheets_count);
							$student_worksheets_count = $student_worksheets_count['count'];
							//UPDATION ENDS//
							
							
							// $student_worksheets_count=$this->get_single_result("select count(id) from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
						
							if($student_worksheets_count >= $studentbadge['worksheets_completed']){
								$is_found=1;
								$completed_description="Congratulations! You've completed your first ".$studentbadge['worksheets_completed']." worksheets.";
							}
						}elseif($sbadge_type_id==5){ /* Points */
							/* Gained 100,500,1000 points */
							
							//UPDATION STARTS//
							$totalPoints=$this->dbClassObj->query("select sum(points_collected) as points from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
							$totalPoints = $this->dbClassObj->fetch_assoc($totalPoints);
							$totalPoints = $totalPoints['points'];
							//UPDATION ENDS//
							
							// $totalPoints = $this->get_single_result("select sum(points_collected) from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
							
							if($totalPoints >= $studentbadge['total_points']){
								$is_found=1;
								$completed_description="Great work! You've scored your first ".$studentbadge['total_points']." points.";
							}
						}elseif($sbadge_type_id==6){ /* Consistency */
							/* Got 75% + in each of last 10,15,25 worksheets*/
							
							//UPDATION STARTS//
							$latest_worksheets_over_score=$this->dbClassObj->query("select count(T.id) as count from (select (score_collected/score_total)*100  as score_over, id, worksheetId from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00')) order by dateAppeared desc limit 0, ".$studentbadge['worksheets_completed'].") as T where score_over >=".$studentbadge['score_over']);
							$latest_worksheets_over_score = $this->dbClassObj->fetch_assoc($latest_worksheets_over_score);
							$latest_worksheets_over_score = $latest_worksheets_over_score['count'];
							//UPDATION ENDS//
							
							// $latest_worksheets_over_score=$this->get_single_result("select count(T.id) from (select (score_collected/score_total)*100  as score_over, id, worksheetId from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00')) order by dateAppeared desc limit 0, ".$studentbadge['worksheets_completed'].") as T where score_over >=".$studentbadge['score_over']);
							
							if($latest_worksheets_over_score >= $studentbadge['worksheets_completed']){
								$is_found=1;
								$promo_txt=($studentbadge['worksheets_completed']==50)?' Amazing!':' Great work!';
								$completed_description="You got ".$studentbadge['score_over']."% or more in your last ".$studentbadge['worksheets_completed']." worksheets.".$promo_txt;
							}
						}elseif($sbadge_type_id==7){ /* Persistance */
							/* Passed worksheet with over 75% after three or more tries */
							
							//UPDATION STARTS//
							$tryer_worksheets_over_score=$this->dbClassObj->query("select count(T.id) as count from (select (sum(score_collected)/sum(score_total)*100) as score_over, id, worksheetId, count(id) as number_of_attempt from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00')) and date(dateAppeared) between date(now() -interval 1 month)and date(now()) group by worksheetId) as T where score_over >=".$studentbadge['score_over']." and number_of_attempt >=".$studentbadge['number_of_attempt']);
							$tryer_worksheets_over_score = $this->dbClassObj->fetch_assoc($tryer_worksheets_over_score);
							$tryer_worksheets_over_score = $tryer_worksheets_over_score['count'];
							//UPDATION ENDS//
							
							// $tryer_worksheets_over_score=$this->get_single_result("select count(T.id) from (select (sum(score_collected)/sum(score_total)*100) as score_over, id, worksheetId, count(id) as number_of_attempt from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00')) and date(dateAppeared) between date(now() -interval 1 month)and date(now()) group by worksheetId) as T where score_over >=".$studentbadge['score_over']." and number_of_attempt >=".$studentbadge['number_of_attempt']);
							
							if($tryer_worksheets_over_score){
								$is_found = 1;
								$completed_description="You passed a worksheet after three or more tries. Practice makes perfect!";
							}
						}elseif($sbadge_type_id==8){ /* Frequency */
							if($studentbadge['number_of_attempt']!='' && $studentbadge['number_of_attempt']!=0){ 
								/*Completed first worksheet*/
								
								//UPDATION STARTS//
								$is_first_worksheet_completed=$this->dbClassObj->query("select count(id) as is_first_worksheet_completed from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
								$is_first_worksheet_completed = $this->dbClassObj->fetch_assoc($is_first_worksheet_completed);
								$is_first_worksheet_completed = $is_first_worksheet_completed['is_first_worksheet_completed'];
								//UPDATION ENDS//
								
								// $is_first_worksheet_completed=$this->get_single_result("select count(id) as is_first_worksheet_completed from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
								
								if($is_first_worksheet_completed){
									$is_found=1;
									$completed_description="You completed your first worksheet. Welcome to the site and keep up the good work!";
								}
							}elseif($studentbadge['member_upto']!='' && $studentbadge['worksheets_completed']!='' && $studentbadge['member_upto']!=0 && $studentbadge['worksheets_completed']!=0){
								/*Member for 6,12,24 months and completed at least 50,100,200 worksheets*/
								
								//UPDATION STARTS//
								$member_completed_worksheet = $this->dbClassObj->query("select count(W.id) as completed, DATEDIFF(CURDATE(), U.date_created) as member_upto from ep_wsassigned as W, ep_wsusers as U where W.studentId='".$student_id."' and W.is_complete='1' and (W.worksheetCat='1' or (W.worksheetCat='2' and W.dateChecked !='0000-00-00 00:00:00')) and U.id=W.studentId and DATEDIFF(CURDATE(), U.date_created)>=".$studentbadge['member_upto']."*30");
								$member_completed_worksheet = $this->dbClassObj->fetch_assoc($member_completed_worksheet);
								$member_completed_worksheet = $member_completed_worksheet['completed'];
								//UPDATION ENDS//
								
								// $member_completed_worksheet=$this->get_single_result("select count(W.id) as completed, DATEDIFF(CURDATE(), U.date_created) as member_upto from ep_wsassigned as W, ep_wsusers as U where W.studentId='".$student_id."' and W.is_complete='1' and (W.worksheetCat='1' or (W.worksheetCat='2' and W.dateChecked !='0000-00-00 00:00:00')) and U.id=W.studentId and DATEDIFF(CURDATE(), U.date_created)>=".$studentbadge['member_upto']."*30");
								
								if($member_completed_worksheet >= $studentbadge['worksheets_completed']){
									$is_found=1;
									$completed_description="Wow! You've been an EdPlace member for ".$studentbadge['member_upto']." months and you've completed at least ".$studentbadge['worksheets_completed']." worksheets.";
								}	
							} elseif($studentbadge['is_login_everyday'] !='' && $studentbadge['score_over']!='' && $studentbadge['is_login_everyday'] !=0 && $studentbadge['score_over'] != 0) {
								/*Logged in and completed worksheet with at least 75% score every day for 5,10,15 days*/
								
								//UPDATION STARTS//
								$worksheet_completed_everyday=$this->dbClassObj->query("select count(M.attempt_date) as count from(select count(T.attempt_date) as each_day_attempt,T.attempt_date,T.score_over from(select (W.score_collected/W.score_total)*100 as score_over, date(U.attempt_date) as attempt_date from ep_wsassigned as W, ep_login_attempt as U where W.studentId='".$student_id."' and W.is_complete='1' and (W.worksheetCat='1' or (W.worksheetCat='2' and W.dateChecked !='0000-00-00 00:00:00')) and U.user_id=W.studentId and date(U.attempt_date) between date(now() -interval ".$studentbadge['is_login_everyday']." day)and date(now()) and (W.score_collected/W.score_total)*100>=".$studentbadge['score_over']." group by U.attempt_date) as T group by T.attempt_date)as M");
								$worksheet_completed_everyday = $this->dbClassObj->fetch_assoc($worksheet_completed_everyday);
								$worksheet_completed_everyday = $worksheet_completed_everyday['count'];
								//UPDATION ENDS//
								
								// $worksheet_completed_everyday=$this->get_single_result("select count(M.attempt_date) from(select count(T.attempt_date) as each_day_attempt,T.attempt_date,T.score_over from(select (W.score_collected/W.score_total)*100 as score_over, date(U.attempt_date) as attempt_date from ep_wsassigned as W, ep_login_attempt as U where W.studentId='".$student_id."' and W.is_complete='1' and (W.worksheetCat='1' or (W.worksheetCat='2' and W.dateChecked !='0000-00-00 00:00:00')) and U.user_id=W.studentId and date(U.attempt_date) between date(now() -interval ".$studentbadge['is_login_everyday']." day)and date(now()) and (W.score_collected/W.score_total)*100>=".$studentbadge['score_over']." group by U.attempt_date) as T group by T.attempt_date)as M");
								
								if($worksheet_completed_everyday>=$studentbadge['is_login_everyday']){
									$is_found=1;
									$completed_description="You scored at least ".$studentbadge['score_over']."% in a worksheet every day for ".$studentbadge['is_login_everyday']." days";
								}
							}
						}
					}
					if($is_found) {
						
						$badgesArr[$badges_inc] = $studentbadges;
						$badgesArr[$badges_inc]['b_msg']="Hooray! You've unlocked a new badge!";					
						
						$b_msg = "Hooray! You've unlocked a new badge!";					
						$completed_description = $completed_description;
						$prompt=$this->dbClassObj->query("update ep_badges_assigned set is_found='".$is_found."', completed_description='".$completed_description."', modified_date=now() where id='".$studentbadge['ab_id']."'");
						$studentbadge['completed_description']=base64_encode($completed_description);
						$studentbadge['is_found']=$is_found;
						$b_notic_sql="insert into ep_student_notic_log (id, notic, message, notic_type, notic_type_id, notic_user, is_viewed, created_date, modified_date, other_related_fields) values ('', '".$studentbadge['badges']."', '".$b_msg."', 'badge', '".$studentbadge['ab_id']."', '".$student_id."', '0', now(), now(), '".serialize($studentbadge)."')";
						$this->dbClassObj->query($b_notic_sql);
						$found++;
						$badges_inc++;
					}
				}
			}
			if(count($badgesArr)>0){ return $badgesArr;}else{ return array();}
			// print_r($badgesArr);
		}
		
		function studentFoundBadgesTest(){
			   $token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
			    $response = array('error' => ERROR_EXISTS, 'message' => 'No badges for you yet');
		      }
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						$response = array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG);
					}
					else{ 
						// Error message of "Token has been expired"
						$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					}
				}
			echo json_encode($response);exit;
		}
		

		/*function studentFoundBadgesTest(){
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$jsondata = file_get_contents('php://input');
				$student = json_decode($jsondata);
				$student_id = $student->student_id;
				
					require_once('../student/includes/define.php');	
					require_once('../student/includes/classes/badges.class.php');
					$objBadges	  = new Badges(TB_BADGES_CONDITION);
					$message = $objBadges->studentFoundBadges($student_id,"");
					if($message){
						$response = array('error' => 1, 'message' => $message);
					}else{
						$response = array('error' => 1, 'message' => 'No badges for you yet');
					}	
				echo json_encode($response);exit;
			}else{
				$response = array('error' => 1, 'message' => 'POST data is required');
				echo json_encode($response);exit;
			}	
		}
*/
		//Update the badges on worksheet completion 
		function studentFoundBadgesTestRenamed(){

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$jsondata = file_get_contents('php://input');
				$student = json_decode($jsondata);
				$student_id = $student->student_id;	
			$found =0;$badges_inc=0;/* $badgesArr=array(); */
			$completed_description='';
			$conditonBadge	= array(1,2,3);
			$SubjectBadge	= array('1'=>'maths','2'=>'english','3'=>'science');
			$studentBadges ="SELECT BC.*, AB.id AS ab_id FROM ep_badges_condition as BC, ep_badges_assigned AS AB WHERE BC.id=AB.assigned_badges_id AND BC.status='1' AND AB.student_id='".$student_id."' and AB.is_found='0' and AB.status='1'";
			$studentbadgesResult =$this->dbClassObj->query($studentBadges);
			if($this->dbClassObj->num_rows($studentbadgesResult)>0){
				while($studentbadge=$this->dbClassObj->fetch_assoc($studentbadgesResult)){
				$is_found =0;	
				$sbadge_year_id=$studentbadge['year_id'];
				$sbadge_subject_id=$studentbadge['subject_id'];
				$sbadge_type_id=$studentbadge['badges_type_id'];
				/*Assigne badge array*/
					if(in_array($sbadge_type_id,$conditonBadge)){
						if($sbadge_type_id==1){ /*Subject*/
							
							//UPDATION STARTS//
							$subject_worksheets=$this->dbClassObj->query("select group_concat(W.id) as ids from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.status='1'");
							$subject_worksheets = $this->dbClassObj->fetch_assoc($subject_worksheets);
							$subject_worksheets = $subject_worksheets['ids'];
							//UPDATION ENDS//
							
							$subject_worksheets_count=count(@explode(',',$subject_worksheets))-1;					
							if($subject_worksheets_count){
								if($studentbadge['worksheets_completed']!='' && $studentbadge['worksheets_completed']!=0 && $studentbadge['score_over']!='' && $studentbadge['score_over']!=0){
								/*25% subject worksheets in year completed with score over 75% in each*/
									
									//UPDATION STARTS//
									$sw_score_count=$this->dbClassObj->query("select count(*) as count from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
									$sw_score_count = $this->dbClassObj->fetch_assoc($sw_score_count);
									$sw_score_count = $sw_score_count['count'];
									//UPDATION ENDS//
									
									// $sw_score_count =$this->get_single_result("select count(*) from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
									
									
									$psw_count =($subject_worksheets_count*$studentbadge['worksheets_completed'])/100;	
									/* echo "<br/> subject_worksheets_count: ".$subject_worksheets_count."<br/>select group_concat(W.id) from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.status='1'";
									echo "<br/> psw_count: ".$psw_count."<br/>select count(*) from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M "; */
									if($sw_score_count>=$psw_count){
										$is_found=1;
										$completed_description="Wow! You've scored ".$studentbadge['score_over']."% or more in ".$studentbadge['worksheets_completed']."% ".$SubjectBadge[$sbadge_subject_id]." worksheets";
									}
								}elseif($studentbadge['average_score_over']!='' && $studentbadge['average_score_over']!=0){
								/*Completed all worksheets in a single subject with an average score over 75%*/
									
									//UPDATION STARTS//
									$sw_avscore_count=$this->dbClassObj->query("select T.wcount as wcount from (select count(distinct(worksheetId)) as wcount, (sum(score_collected)/sum(score_total))*100 as avg_score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and is_complete='1' group by studentId) as T where T.avg_score_over>=".$studentbadge['average_score_over']);
									$sw_avscore_count = $this->dbClassObj->fetch_assoc($sw_avscore_count);
									$sw_avscore_count = $sw_avscore_count['wcount'];
									//UPDATION ENDS//
									
									// $sw_avscore_count =$this->get_single_result("select T.wcount from (select count(distinct(worksheetId)) as wcount, (sum(score_collected)/sum(score_total))*100 as avg_score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and is_complete='1' group by studentId) as T where T.avg_score_over>=".$studentbadge['average_score_over']);
									
									/* echo "<br/> subject_worksheets_count: ".$subject_worksheets_count."<br/>select group_concat(W.id) from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.status='1'";
									echo "<br/> sw_avscore_count: ".$sw_avscore_count."<br/>select T.wcount from (select count(distinct(worksheetId)) as wcount, (sum(score_collected)/sum(score_total))*100 as avg_score_over from ep_wsassigned where worksheetId in (".$subject_worksheets.") and studentId='".$student_id."' and is_complete='1' group by studentId) as T where T.avg_score_over>=".$studentbadge['average_score_over'];  */
									if($sw_avscore_count>=$subject_worksheets_count){
										$is_found=1;
										$completed_description="Wow! You've scored ".$studentbadge['average_score_over']."% or more in all ".$SubjectBadge[$sbadge_subject_id]." worksheets";
									}
								}
							}
						}elseif($sbadge_type_id==2){ /*Topic*/
						
							//UPDATION STARTS//
							$topic_worksheets=$this->dbClassObj->query("select group_concat(W.id) as wid from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.topic_id='".$studentbadge['topic_id']."' and W.status='1'");
							$topic_worksheets = $this->dbClassObj->fetch_assoc($topic_worksheets);
							$topic_worksheets = $topic_worksheets['wid'];
							//UPDATION ENDS//
						
							// $topic_worksheets=$this->get_single_result("select group_concat(W.id) from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.topic_id='".$studentbadge['topic_id']."' and W.status='1'");
							
							
							$topic_worksheets_count=count(@explode(',',$topic_worksheets))-1;
							if($topic_worksheets_count){
							
								//UPDATION STARTS//
								$tw_score_count=$this->dbClassObj->query("select count(*) as count from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$topic_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
								$tw_score_count = $this->dbClassObj->fetch_assoc($tw_score_count);
								$tw_score_count = $tw_score_count['count'];
								//UPDATION ENDS//
							
								// $tw_score_count =$this->get_single_result("select count(*) from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$topic_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
								
								if($tw_score_count>=$topic_worksheets_count){
									$is_found=1;
									$completed_description="Great work! You've scored ".$studentbadge['score_over']."% or over in all ".$studentbadge['badges_subjects']." worksheets.";
								}
							}
						}elseif($sbadge_type_id==3){ /*Curriculum*/
						
							//UPDATION STARTS//
							$curriculum_worksheets=$this->dbClassObj->query("select group_concat(W.id) as gcwid from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.ksu='".$studentbadge['curriculum_id']."' and W.status='1'");
							$curriculum_worksheets = $this->dbClassObj->fetch_assoc($curriculum_worksheets);
							$curriculum_worksheets = $curriculum_worksheets['gcwid'];
							//UPDATION ENDS//
						
							// $curriculum_worksheets=$this->get_single_result("select group_concat(W.id) from ep_worksheets as W where W.subject='".$sbadge_subject_id."' and W.year='".$sbadge_year_id."' and W.ksu='".$studentbadge['curriculum_id']."' and W.status='1'");
						
							$curriculum_worksheets_count=count(@explode(',',$curriculum_worksheets))-1;
							if($curriculum_worksheets_count){
								
								//UPDATION STARTS//
								$cw_score_count=$this->dbClassObj->query("select count(*) as count from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$curriculum_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
								$cw_score_count = $this->dbClassObj->fetch_assoc($cw_score_count);
								$cw_score_count = $cw_score_count['count'];
								//UPDATION ENDS//
							
								// $cw_score_count =$this->get_single_result("select count(*) from (select max(T.score_over), T.worksheetId from (select worksheetId, (score_collected/score_total)*100 as score_over from ep_wsassigned where worksheetId in (".$curriculum_worksheets.") and studentId='".$student_id."' and (score_collected/score_total)*100 >=".$studentbadge['score_over']." and is_complete='1' group by id ) as T group by T.worksheetId) as M ");
								
								if($cw_score_count>=$curriculum_worksheets_count){
									$is_found=1;
									$completed_description="Amazing! You've scored ".$studentbadge['score_over']."% or over in all ".$studentbadge['badges_subjects']." worksheets.";
								}
							}
						}
					}elseif(!in_array($sbadge_type_id,$conditonBadge)){
						if($sbadge_type_id==4){ /* Worksheets */
							/*Completed total of 50,200,500 worksheets*/
							
							//UPDATION STARTS//
							$student_worksheets_count=$this->dbClassObj->query("select count(id) as count from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
							$student_worksheets_count = $this->dbClassObj->fetch_assoc($student_worksheets_count);
							$student_worksheets_count = $student_worksheets_count['count'];
							//UPDATION ENDS//
							
							
							// $student_worksheets_count=$this->get_single_result("select count(id) from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
						
							if($student_worksheets_count >= $studentbadge['worksheets_completed']){
								$is_found=1;
								$completed_description="Congratulations! You've completed your first ".$studentbadge['worksheets_completed']." worksheets.";
							}
						}elseif($sbadge_type_id==5){ /* Points */
							/* Gained 100,500,1000 points */
							
							//UPDATION STARTS//
							$totalPoints=$this->dbClassObj->query("select sum(points_collected) as points from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
							$totalPoints = $this->dbClassObj->fetch_assoc($totalPoints);
							$totalPoints = $totalPoints['points'];
							//UPDATION ENDS//
							
							// $totalPoints = $this->get_single_result("select sum(points_collected) from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
							
							if($totalPoints >= $studentbadge['total_points']){
								$is_found=1;
								$completed_description="Great work! You've scored your first ".$studentbadge['total_points']." points.";
							}
						}elseif($sbadge_type_id==6){ /* Consistency */
							/* Got 75% + in each of last 10,15,25 worksheets*/
							
							//UPDATION STARTS//
							$latest_worksheets_over_score=$this->dbClassObj->query("select count(T.id) as count from (select (score_collected/score_total)*100  as score_over, id, worksheetId from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00')) order by dateAppeared desc limit 0, ".$studentbadge['worksheets_completed'].") as T where score_over >=".$studentbadge['score_over']);
							$latest_worksheets_over_score = $this->dbClassObj->fetch_assoc($latest_worksheets_over_score);
							$latest_worksheets_over_score = $latest_worksheets_over_score['count'];
							//UPDATION ENDS//
							
							// $latest_worksheets_over_score=$this->get_single_result("select count(T.id) from (select (score_collected/score_total)*100  as score_over, id, worksheetId from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00')) order by dateAppeared desc limit 0, ".$studentbadge['worksheets_completed'].") as T where score_over >=".$studentbadge['score_over']);
							
							if($latest_worksheets_over_score >= $studentbadge['worksheets_completed']){
								$is_found=1;
								$promo_txt=($studentbadge['worksheets_completed']==50)?' Amazing!':' Great work!';
								$completed_description="You got ".$studentbadge['score_over']."% or more in your last ".$studentbadge['worksheets_completed']." worksheets.".$promo_txt;
							}
						}elseif($sbadge_type_id==7){ /* Persistance */
							/* Passed worksheet with over 75% after three or more tries */
							
							//UPDATION STARTS//
							$tryer_worksheets_over_score=$this->dbClassObj->query("select count(T.id) as count from (select (sum(score_collected)/sum(score_total)*100) as score_over, id, worksheetId, count(id) as number_of_attempt from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00')) and date(dateAppeared) between date(now() -interval 1 month)and date(now()) group by worksheetId) as T where score_over >=".$studentbadge['score_over']." and number_of_attempt >=".$studentbadge['number_of_attempt']);
							$tryer_worksheets_over_score = $this->dbClassObj->fetch_assoc($tryer_worksheets_over_score);
							$tryer_worksheets_over_score = $tryer_worksheets_over_score['count'];
							//UPDATION ENDS//
							
							// $tryer_worksheets_over_score=$this->get_single_result("select count(T.id) from (select (sum(score_collected)/sum(score_total)*100) as score_over, id, worksheetId, count(id) as number_of_attempt from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00')) and date(dateAppeared) between date(now() -interval 1 month)and date(now()) group by worksheetId) as T where score_over >=".$studentbadge['score_over']." and number_of_attempt >=".$studentbadge['number_of_attempt']);
							
							if($tryer_worksheets_over_score){
								$is_found = 1;
								$completed_description="You passed a worksheet after three or more tries. Practice makes perfect!";
							}
						}elseif($sbadge_type_id==8){ /* Frequency */
							if($studentbadge['number_of_attempt']!='' && $studentbadge['number_of_attempt']!=0){ 
								/*Completed first worksheet*/
								
								//UPDATION STARTS//
								$is_first_worksheet_completed=$this->dbClassObj->query("select count(id) as is_first_worksheet_completed from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
								$is_first_worksheet_completed = $this->dbClassObj->fetch_assoc($is_first_worksheet_completed);
								$is_first_worksheet_completed = $is_first_worksheet_completed['is_first_worksheet_completed'];
								//UPDATION ENDS//
								
								// $is_first_worksheet_completed=$this->get_single_result("select count(id) as is_first_worksheet_completed from ep_wsassigned where studentId='".$student_id."' and is_complete='1' and (worksheetCat='1' or (worksheetCat='2' and dateChecked !='0000-00-00 00:00:00'))");
								
								if($is_first_worksheet_completed){
									$is_found=1;
									$completed_description="You completed your first worksheet. Welcome to the site and keep up the good work!";
								}
							}elseif($studentbadge['member_upto']!='' && $studentbadge['worksheets_completed']!='' && $studentbadge['member_upto']!=0 && $studentbadge['worksheets_completed']!=0){
								/*Member for 6,12,24 months and completed at least 50,100,200 worksheets*/
								
								//UPDATION STARTS//
								$member_completed_worksheet = $this->dbClassObj->query("select count(W.id) as completed, DATEDIFF(CURDATE(), U.date_created) as member_upto from ep_wsassigned as W, ep_wsusers as U where W.studentId='".$student_id."' and W.is_complete='1' and (W.worksheetCat='1' or (W.worksheetCat='2' and W.dateChecked !='0000-00-00 00:00:00')) and U.id=W.studentId and DATEDIFF(CURDATE(), U.date_created)>=".$studentbadge['member_upto']."*30");
								$member_completed_worksheet = $this->dbClassObj->fetch_assoc($member_completed_worksheet);
								$member_completed_worksheet = $member_completed_worksheet['completed'];
								//UPDATION ENDS//
								
								// $member_completed_worksheet=$this->get_single_result("select count(W.id) as completed, DATEDIFF(CURDATE(), U.date_created) as member_upto from ep_wsassigned as W, ep_wsusers as U where W.studentId='".$student_id."' and W.is_complete='1' and (W.worksheetCat='1' or (W.worksheetCat='2' and W.dateChecked !='0000-00-00 00:00:00')) and U.id=W.studentId and DATEDIFF(CURDATE(), U.date_created)>=".$studentbadge['member_upto']."*30");
								
								if($member_completed_worksheet >= $studentbadge['worksheets_completed']){
									$is_found=1;
									$completed_description="Wow! You've been an EdPlace member for ".$studentbadge['member_upto']." months and you've completed at least ".$studentbadge['worksheets_completed']." worksheets.";
								}	
							} elseif($studentbadge['is_login_everyday'] !='' && $studentbadge['score_over']!='' && $studentbadge['is_login_everyday'] !=0 && $studentbadge['score_over'] != 0) {
								/*Logged in and completed worksheet with at least 75% score every day for 5,10,15 days*/
								
								//UPDATION STARTS//
								$worksheet_completed_everyday=$this->dbClassObj->query("select count(M.attempt_date) as count from(select count(T.attempt_date) as each_day_attempt,T.attempt_date,T.score_over from(select (W.score_collected/W.score_total)*100 as score_over, date(U.attempt_date) as attempt_date from ep_wsassigned as W, ep_login_attempt as U where W.studentId='".$student_id."' and W.is_complete='1' and (W.worksheetCat='1' or (W.worksheetCat='2' and W.dateChecked !='0000-00-00 00:00:00')) and U.user_id=W.studentId and date(U.attempt_date) between date(now() -interval ".$studentbadge['is_login_everyday']." day)and date(now()) and (W.score_collected/W.score_total)*100>=".$studentbadge['score_over']." group by U.attempt_date) as T group by T.attempt_date)as M");
								$worksheet_completed_everyday = $this->dbClassObj->fetch_assoc($worksheet_completed_everyday);
								$worksheet_completed_everyday = $worksheet_completed_everyday['count'];
								//UPDATION ENDS//
								
								// $worksheet_completed_everyday=$this->get_single_result("select count(M.attempt_date) from(select count(T.attempt_date) as each_day_attempt,T.attempt_date,T.score_over from(select (W.score_collected/W.score_total)*100 as score_over, date(U.attempt_date) as attempt_date from ep_wsassigned as W, ep_login_attempt as U where W.studentId='".$student_id."' and W.is_complete='1' and (W.worksheetCat='1' or (W.worksheetCat='2' and W.dateChecked !='0000-00-00 00:00:00')) and U.user_id=W.studentId and date(U.attempt_date) between date(now() -interval ".$studentbadge['is_login_everyday']." day)and date(now()) and (W.score_collected/W.score_total)*100>=".$studentbadge['score_over']." group by U.attempt_date) as T group by T.attempt_date)as M");
								
								if($worksheet_completed_everyday>=$studentbadge['is_login_everyday']){
									$is_found=1;
									$completed_description="You scored at least ".$studentbadge['score_over']."% in a worksheet every day for ".$studentbadge['is_login_everyday']." days";
								}
							}
						}
					}
					if($is_found) {
						
						$badgesArr[$badges_inc] = $studentbadges;
						$badgesArr[$badges_inc]['b_msg']="Hooray! You've unlocked a new badge!";					
						
						$b_msg = "Hooray! You've unlocked a new badge!";					
						$completed_description = $completed_description;
						$prompt=$this->dbClassObj->query("update ep_badges_assigned set is_found='".$is_found."', completed_description='".$completed_description."', modified_date=now() where id='".$studentbadge['ab_id']."'");
						$studentbadge['completed_description']=base64_encode($completed_description);
						$studentbadge['is_found']=$is_found;
						$b_notic_sql="insert into ep_student_notic_log (id, notic, message, notic_type, notic_type_id, notic_user, is_viewed, created_date, modified_date, other_related_fields) values ('', '".$studentbadge['badges']."', '".$b_msg."', 'badge', '".$studentbadge['ab_id']."', '".$student_id."', '0', now(), now(), '".serialize($studentbadge)."')";
						$this->dbClassObj->query($b_notic_sql);
						$found++;
						$badges_inc++;
					}
				}
			}
			if(count($badgesArr)>0){
				$response = array('error' => 0, 'badges' => $badgesArr);
				echo json_encode($response);exit;
			}else{ 
				/*$badges_inc =0;
				$badgesArr[$badges_inc]['b_msg']="Hooray! You've unlocked a new badge!";
				$response = array('error' => 0, 'badges' => $badgesArr);*/
				$response = array('error' => 1, 'message' => 'No badges for you yet');
				echo json_encode($response);exit;
			}
		}else{

			$response = array('error' => 1, 'message' => 'POST data is required');
				echo json_encode($response);exit;
		}
	}
		//Worksheets suggested to the students on the basis of performance in completed worksheets on basis of parent token
		public function suggestedWorksheets(){
			if($_SERVER['REQUEST_METHOD']=='POST') 
			{
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parent_id = $this->getUidFromUserToken($token);
				$jsondata = file_get_contents('php://input');
				$jsondata = json_decode($jsondata);
				
				//SET: variables
				$student_id = $jsondata->student_id;
				//$parent_id = $jsondata->parent_id;
				
				//CHECK: student_id not empty
				if(empty($student_id)){$response = array('error' => ERROR_EXISTS, 'message' => 'Student ID is required');echo json_encode($response);exit;}

				//CHECK: worksheet_id not empty
				if(empty($parent_id)){$response = array('error' => ERROR_EXISTS, 'message' => 'Parent Token is required');echo json_encode($response);exit;}

				//CHECK: if the auto assign is ON or OFF
				$qryD="select U.id as student_id, U.fname, U.lname, U.user_name, U.auto_assign_y_n, S.student_id from ep_wsusers U ,ep_subscription_student S where U.user_type='student' and U.parent_id='".$parent_id."'  and U.id='".$student_id."' and U.id=S.student_id and U.parent_id=S.parent_id and S.is_current='1'";	
				$sql=$this->dbClassObj->query($qryD);
				$user_data = $this->dbClassObj->fetch_assoc($sql); 
				//$autoassignVal= $user_data['auto_assign_y_n'];
				$autoassignVal= ($user_data['auto_assign_y_n'])?$user_data['auto_assign_y_n']:'0';
				if($autoassignVal == 1){
					$response = array('error' => 0, 'message' => 'Worksheet suggestions are being automatically assigned. To review worksheet suggestions and manually assign them, switch Auto Assign Off.','response'=>array('auto_assign_y_n'=>$autoassignVal,'worksheets'=>array()));echo json_encode($response);exit;
				}
				
				/*******following query is used to get the all completed worksheets of a particular parent child *******/
				$sugsql  = "select DISTINCT(a.worksheetId) as p_w_id, a.studentId, a.dateAppeared, a.dateChecked, w.id, w.worksheetCat, w.worksheetName, w.subject as subID, w.topicTags, w.dateCreated, w.topic_id, w.keyStage, w.year, w.level, k.key_stage, s.subject, y.year as year_label from ep_worksheets as w, ep_wssubjects as s, ep_wsassigned as a, ep_wskeystage as k, ep_wsyears as y where w.subject=s.id and w.id=a.worksheetId and w.year=y.id and w.keyStage=k.id and a.is_complete='1' and a.is_remove ='0' and a.parentId ='".$parent_id."' and a.studentId='".$student_id."' and (a.worksheetCat='1' or (a.worksheetCat='2' and a.dateChecked !='0000-00-00 00:00:00')) GROUP BY a.worksheetId ";  
				$sresult		= $this->dbClassObj->query($sugsql);
				
				//CHECK: if the completed worksheets are equal to 0. Then there are no any suggestions available.
				$completed_worksheets = $this->dbClassObj->num_rows($sresult);
				
				//SET: an array for the student names
				$responseArrErr = array();
				$responseArrErr['studentName'] = $user_data['fname'].' '.$user_data['lname'];
				$responseArrErr['student_id'] = $user_data['student_id'];
				if($completed_worksheets == 0 || empty($completed_worksheets)){$response = array('error' => 0, 'message' => 'No suggestion worksheets found','response'=>$responseArrErr);echo json_encode($response);exit;}
				
				
				$mysuggestion 	= array();
				$unique_arr 	= array();
				while($sresultarr = $this->dbClassObj->fetch_assoc($sresult)){ 
					
					$sqlavg =$this->dbClassObj->query("select max(avg_total) as max_avg from (select (sum(score_collected)/sum(score_total)*100)  as avg_total from ep_wsassigned where  studentId='".$sresultarr['studentId']."' and worksheetId='".$sresultarr['id']."' and is_complete='1' group by id ) ta ");
					$avg_total	= $this->dbClassObj->fetch_assoc($sqlavg);
					$avg_total	= $avg_total['max_avg'];
					
					$avg_total	= number_format($avg_total,0);
					if($avg_total >=70){
						/****following query is used to get the related worksheets according to completed worksheets topic/year/subject****/
						$sgsql  = "select w.id, w.worksheetCat, w.worksheetName, w.subject as subID, w.topicTags, w.dateCreated, w.topic_id, w.level, k.key_stage, s.subject, y.year as year_label from ep_worksheets as w, ep_wssubjects as s, ep_wskeystage as k, ep_wsyears as y where w.subject=s.id and w.year=y.id and w.keyStage=k.id and w.status='1' and w.topic_id ='".$sresultarr['topic_id']."' and w.keyStage =".$sresultarr['keyStage']." and w.year =".$sresultarr['year']." and w.subject = '".$sresultarr['subID']."'"; 
						$sgresult= $this->dbClassObj->query($sgsql);
						$nmRows	= $this->dbClassObj->num_rows($sgresult);					
						if($nmRows){
							$unAssignedWkArr =array();
							while($related_sgwarr	= $this->dbClassObj->fetch_assoc($sgresult)){
								if($sresultarr['p_w_id']!=$related_sgwarr['id']){
									$related_sgwarr['p_w_id'] = $sresultarr['p_w_id'];
									/*********Check related worksheets is allready assigned to child or not*******/
									$is_allready_assigned = $this->dbClassObj->query("select count(id) as nval from ep_wsassigned where  studentId='".$sresultarr['studentId']."' and worksheetId='".$related_sgwarr['id']."' and is_complete='0'");
									$is_allready_assigned = $this->dbClassObj->fetch_assoc($is_allready_assigned);
									$is_allready_assigned = $is_allready_assigned['nval'];
									
									/*Related Worksheet Score Check*/
									$sqlravg =$this->dbClassObj->query("select max(avg_total) as max_total from (select (sum(score_collected)/sum(score_total)*100)  as avg_total from ep_wsassigned where  studentId='".$sresultarr['studentId']."' and worksheetId='".$related_sgwarr['id']."' and is_complete='1' group by id ) ta ");
									$sqlravg = $this->dbClassObj->fetch_assoc($sqlravg);
									$re_avg_total = $sqlravg['max_total'];
									
									$re_avg_total	= number_format($re_avg_total,0);
									/*Related Worksheet Score Check End*/				
									if(!$is_allready_assigned && $re_avg_total < 70 && !in_array($related_sgwarr['id'], $unique_arr)){
										$unAssignedWkArr[] =$related_sgwarr;
									}
								}
							}
							
							/*******following code is used to select 1 random worksheet from same topic multiple worksheets***********/
							
							if(sizeof($unAssignedWkArr)>1){
								$randomval = array_rand($unAssignedWkArr,1);
								if(empty($unique_arr)){
									$unique_arr[] =$unAssignedWkArr[$randomval]['id'];					
									$mysuggestion[] =$unAssignedWkArr[$randomval];
								}else if(in_array($unAssignedWkArr[$randomval]['id'], $unique_arr)){
									continue;
								}else{
									$unique_arr[] =$unAssignedWkArr[$randomval]['id'];					
									$mysuggestion[] =$unAssignedWkArr[$randomval];
								}
							}else{
								if(sizeof($unAssignedWkArr)){
									if(empty($unique_arr)){
										$unique_arr[] =$unAssignedWkArr[0]['id'];					
										$mysuggestion[] =$unAssignedWkArr[0];
									}else if(in_array($unAssignedWkArr[0]['id'], $unique_arr)){
										continue;
									}else{
										$unique_arr[] =$unAssignedWkArr[0]['id'];					
										$mysuggestion[] =$unAssignedWkArr[0];
									}
								}	
							}
						}
					} /** close numrows **/
					/* if average score less then 70 % */
					else{
						
					
						$checksql	= $this->dbClassObj->query("select count(id) as nval from ep_wsassigned where  studentId='".$sresultarr['studentId']."' and worksheetId='".$sresultarr['id']."' and is_complete='0'");
						$is_allready_assigned= $this->dbClassObj->fetch_assoc($checksql);
						$is_allready_assigned = $is_allready_assigned['nval'];
						
						
						if(!$is_allready_assigned && !in_array($sresultarr['id'], $unique_arr)){
							$unique_arr[] = $sresultarr['id'];
							$mysuggestion[] = $sresultarr;
						}
					}
					if(count($mysuggestion)>=15){
						break;	
					}
				}
				
				//UPDATE: update the array with sub arrays subject wise
				$m_key = $e_key = $s_key = 0;
				foreach($mysuggestion as $singleRecord){
					if($singleRecord['subID'] == '1'){ $mysuggestions['maths'][$m_key] = $singleRecord; $m_key++;}
					if($singleRecord['subID'] == '2'){ $mysuggestions['english'][$e_key] = $singleRecord; $e_key++; }
					if($singleRecord['subID'] == '3'){ $mysuggestions['science'][$s_key] = $singleRecord; $s_key++; }
				}
				
				if(count($mysuggestion) > 0){$response = array('error' => STATUS_OK, 'message' => 'Worksheet suggestions', 'response' => array('auto_assign_y_n'=>$autoassignVal,'worksheets'=>$mysuggestions));echo json_encode($response);exit;}
			}
				else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						echo json_encode(array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG));exit;
					}
					else{ 
						// Error message of "Token has been expired"
						echo json_encode(array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG));exit;
					}
				}
			}else{$response = array('error' => ERROR_EXISTS, 'message' => POST_ERROR_MSG);echo json_encode($response);exit;}
			exit;
		} 
		
		/*** Service to recover the password
		** URL : http://epl-edplace.netsol.local/webservice/?s=forgot_password
		** INPUT : {"email":"email_address"}
		**/ 
		public function forgot_password() {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$token = $this->getToken();
				if($token && $this->validateToken($token) == TRUE ) {
				$data = file_get_contents('php://input');
				$data = json_decode($data);
				if (!empty($data)) {
					
					//SET: set the email variable
					$email = $data->email;
					
					//CHECK: that email is not to be empty
					if(empty($email)){ echo json_encode(array('error' => ERROR_EXISTS,'message' => 'Error','response' => 'Email is required')); exit; }
					
					//GET: get the user detail on the basis of email
					$query_select	= $this->dbClassObj->query("SELECT * FROM ep_wsusers WHERE email = '".$email."'");
					if($this->dbClassObj->num_rows($query_select) > 0){
						//GET: user details
						$user_data = $this->dbClassObj->fetch_assoc($query_select);
						
						$email			= $user_data['email'];
						$f_name 		= $user_data['fname'];
						$reset_id		= $user_data['id'];
						$reset_year		= rand(1,10);
						$reset_month	= rand(11,20);
						$reset_day		= rand(21,30);
						$reset_combine	= $reset_year.'~'.$reset_month.'~'.$reset_day;
						$reset_md5		= md5($reset_year*$reset_day*$reset_month);
						$reset_tail		= $reset_md5.base64_encode($reset_id);
						$reset_link		= self::SITE_URL.'parent/resetpassword.php?rpof='.$reset_tail;
						
						$update_query	= $this->dbClassObj->query("UPDATE ep_wsusers SET reset_password= '".$reset_tail."' , reset_password_rand='".$reset_combine."' WHERE id='".$reset_id."'");

$txt = "Hi {$f_name}

Sorry you're having trouble logging in to EdPlace. Please click on the link below to reset your password.

Your password reset link: {$reset_link}

If you didn't request a password reset please let us know by replying to this email.

Thanks,

The EdPlace Team";						
						//SET: variables used for the email process
						$to = $user_data['email'];
						$subject = "Forgot Password";
						//$txt = "Hello ".$user_data['fname'].", Your password for the EdPlace account is <b>".$user_data['password']."</b>. Thanks for contacting us! EdPlace Team";
						//$headers = "From: edplace@support.com";
$headers = "From: support@edplace.com";
						
						//CHECK: if email seent then it execute the success message.
						if(mail($to,$subject,$txt,$headers)){
							echo json_encode(array('error' => STATUS_OK,'message' => 'Success','response' => "Thanks. We've sent password recovery instructions to your email address."));
						}else{ echo json_encode(array('error' => ERROR_EXISTS,'message' => 'error','response' => 'Something went wrong. Please try again!')); }
					}else{ echo json_encode(array('error' => ERROR_EXISTS,'message' => 'Error','response' => 'User does not exist for the specified email address. Please check and enter correct email id.')); exit; }
				}else{ echo json_encode(array('error' => ERROR_EXISTS,'message' => 'Error','response' => 'Null input receiving'));exit; }
				}
				else {  // Error message of "Token has been expired"
					$response = array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG);
					echo json_encode($response);
				}
			}
		}
		
		//METHOD: is used to get the full student details.
		public function _getStudentDetails($studentId){
			$query_select	= $this->dbClassObj->query("SELECT * FROM `ep_wsusers` WHERE id = ".$studentId);
			if($this->dbClassObj->num_rows($query_select) > 0){
				$studentDetails = $this->dbClassObj->fetch_assoc($query_select);
				return $studentDetails;
			}else{ return 0; }
		}
		
		//METHOD: is used to get the full parent details.
		public function _getParentDetailsByStudent($studentId){
			$query_select	= $this->dbClassObj->query("SELECT * FROM `ep_wsusers` WHERE id = ( SELECT parent_id FROM ep_wsusers WHERE id = ".$studentId." ) ");
			if($this->dbClassObj->num_rows($query_select) > 0){
				$parentDetails = $this->dbClassObj->fetch_assoc($query_select);
				return $parentDetails;
			}else{ return 0; }
		}
		
		//METHOD: used to get all the Upcoming rewards by student.
		public function _getUpcomingRewards($studentId){
			$i=0;
			$parentDetails = $this->_getParentDetailsByStudent($studentId);
			$parentId = $parentDetails['id'];
			
			//GET: all the rewards with 'locked' status.
			$query_select	= $this->dbClassObj->query("SELECT rw . * , rwtyp.reward_name, rwtyp.reward_icon, rwtyp.reward_white_icon, rwtyp.reward_large_icon FROM ep_rewards AS rw LEFT JOIN ep_rewards_type AS rwtyp ON rwtyp.id = rw.reward_type_id WHERE rw.createdBy =".$parentId." AND rw.student_id =".$studentId." AND rw.reward_status = 'Locked' ORDER BY rw.date_allocated ASC ");
			if($this->dbClassObj->num_rows($query_select) > 0){
				while($reward = $this->dbClassObj->fetch_assoc($query_select)){
					$responseArray[$i] = $reward;
					$i++;
				}
				return $responseArray;
			}else{
				return array();
			}
		}
		
		//METHOD: used to get all the Completed rewards by student.
		public function _getCompletedRewards($studentId){
			$i=0;
			$parentDetails = $this->_getParentDetailsByStudent($studentId);
			$parentId = $parentDetails['id'];
			
			$query_select	= $this->dbClassObj->query("SELECT rw . * , rwtyp.reward_name, rwtyp.reward_icon, rwtyp.reward_white_icon, rwtyp.reward_large_icon FROM ep_rewards AS rw LEFT JOIN ep_rewards_type AS rwtyp ON rwtyp.id = rw.reward_type_id WHERE rw.createdBy =".$parentId." AND rw.student_id =".$studentId." AND rw.reward_status = 'Unlocked' ORDER BY rw.date_allocated DESC ");
			if($this->dbClassObj->num_rows($query_select) > 0){
				while($reward = $this->dbClassObj->fetch_assoc($query_select)){
					$responseArray[$i] = $reward;
					$i++;
				}
				return $responseArray;
			}else{
				return array();
			}
		}
		
		public function _getStudentsListByParent($parentId){
			$i=0;
			$query_select	= $this->dbClassObj->query("select U.* from ep_wsusers U ,ep_subscription_student S where U.user_type = 'student' and S.student_id=U.id and S.is_current='1' and U.parent_id= ".$parentId);
			if($this->dbClassObj->num_rows($query_select)>0){
				while($list = $this->dbClassObj->fetch_assoc($query_select)){
					$responseArray[$i] = $list;
					$i++;
				}
				return $responseArray;
			}else{ return array(); }
		}
		
		public function _userType($userId = null){
			if(!isset($userId)){
				return '';
			}
			$query_select	= $this->dbClassObj->query("SELECT user_type FROM `ep_wsusers` WHERE id = ".$userId);
			if($this->dbClassObj->num_rows($query_select)>0){
				$userType = $this->dbClassObj->fetch_assoc($query_select);
				return $userType = $userType['user_type'];
			}else{ return ''; }
		}
		
		/*	METHOD: Get all the rewards(Upcoming+Completed)
		 * 	URL: http://www.edplace.com/webservices/?s=rewardsList
		 * 	POST-DATA: { User-Token }
		 * */
		public function rewardsList(){
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
				$parentId = $this->getUidFromUserToken($token);
				$data = file_get_contents('php://input');
				//$data = (array)json_decode($data);
				if (count($data)>0) {
					//$parentId = $data['parent_id'];
					
					//CHECK: usertype of the user.
					$userType = $this->_userType($parentId);
					if($userType != 'parent'){
						echo json_encode(array('error' => ERROR_EXISTS,'message' => 'error','response' => 'User is of non-parent type.'));exit;
					}
					
					//GET: students by parent ID.
					$studentsList = $this->_getStudentsListByParent($parentId);
					if(count($studentsList) == 0){
						echo json_encode(array('error' => ERROR_EXISTS,'message' => 'error','response' => 'Parent not having any student.'));exit;
					}
					
					//SET: a response array with required values
					$i=0;
					foreach($studentsList as $student){
						$studentId = $student['id'];
						
						//GET: all the relevant rewards.
						$upcomingRewards = $this->_getUpcomingRewards($studentId);
						$completedRewards = $this->_getCompletedRewards($studentId);
						
						$responseArray[$i]['id'] = $studentId;
						$responseArray[$i]['student_name'] = $student['fname'].' '.$student['lname'];
						$responseArray[$i]['rewards']['upcoming_rewards'] = $upcomingRewards;
						$responseArray[$i]['rewards']['completed_rewards'] = $completedRewards;
						
						$i++;
					}
					echo json_encode(array('error' => STATUS_OK,'message' => 'success','response' => $responseArray));exit;
				}else{ echo json_encode(array('error' => ERROR_EXISTS,'message' => 'Error','response' => POST_ERROR_MSG));exit; }
			}
			else {
					if($this->isPrivate($token)) {
						// Error message of "Token has been expired"
						echo json_encode(array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG));
					}
					else{ 
						// Error message of "Token has been expired"
						echo json_encode(array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG));
					}
				}
			}else{ echo json_encode(array('error' => ERROR_EXISTS,'message' => 'Error','response' => POST_ERROR_MSG));exit; }
		}
		
		public function _isUserExistsByEmail($email){
			$query_select	= $this->dbClassObj->query("SELECT * FROM `ep_wsusers` WHERE email = '".$email."' OR username = '".$email."'");
			if($this->dbClassObj->num_rows($query_select) > 0){
				 echo json_encode(array('error' => 111,'message' => 'error','response' => 'User already registered with this email. Please try with another one!')); exit;
			}
		}
		
		/*	METHOD: used to register as free user.
		 * 	URL: http://www.edplace.com/webservices/?s=freeUserSignup
		 * 	POST-DATA: { "name":"test user", "email":"testemail@edplace.com", "password":"testpassword" }
		 * */
		public function freeUserSignup(){
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				
				$token = $this->getToken();
			    if($token && $this->validateToken($token) == TRUE ) {
		        $data = file_get_contents('php://input');
				$data = (array)json_decode($data);
				if (count($data)>0) {
					$name = trim($data['name']);
					$email = trim($data['email']);
					$password = $this->hasherObj->createHash($data['password']);
					
					if(empty($name)){ echo json_encode(array('error' => ERROR_EXISTS,'message' => 'error','response' => 'Name is required.'));exit; }
					if(empty($email)){ echo json_encode(array('error' => ERROR_EXISTS,'message' => 'error','response' => 'Email is required.'));exit; }
					if(empty($password)){ echo json_encode(array('error' => ERROR_EXISTS,'message' => 'error','response' => 'Password is required.'));exit; }
					if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo json_encode(array('error' => ERROR_EXISTS,'message' => 'error','response' => 'Invalid email. Please enter valid email address'));exit; }
					
					//CHECK: is user already exists or not.
					$this->_isUserExistsByEmail($email);
					
					//SET: variables for the CRUD processing commands.
					$user_names = explode(' ',$name);
					
					$fname = $user_names['0'];
					if(count($user_names) == 1){ $lname = ''; }else{ $lname = $user_names['1']; }
					$password = $password;
					$email = $email;
					$user_name = $email;
					$user_type = 'parent';
					$email_verified = '1';
					$first_added = '0';
					$status = '1';
					$date_created = date('Y-m-d H:i:s');
					$date_modified = date('Y-m-d H:i:s');
					
					//QUERY: to insert the new user.
					$this->dbClassObj->query("INSERT INTO ep_wsusers ( fname, lname, address, town_city, post_code, county, country, telephone, email, user_name, password, user_type, school_type_id, subject, parent_id, age, student_year, date_reg, date_exp, date_created, date_modified, email_verified, first_added, is_addedby_admin, status, auto_assign_y_n, auto_assign_subject, auto_assign_year, user_subs_days, consider_user_subs_days, auto_login, wk_sug_ntf_date, is_link_verified, worksheet_assigned, worksheet_completed, is_child_password_updated, is_age_updated, cdu_tour_flag, delete_status, self_assign_y_n ) VALUES ( '".$fname."', '".$lname."', '', '', '', '', 0, 0, '".$email."', '".$user_name."', '".$password."', '".$user_type."', 0, '', 0, '', 0, '', '', '".$date_created."', '".$date_modified."', '".$email_verified."', '".$first_added."', '', '".$status."', '', 0, '', 0, '', '', '', '', 0, 0, '0', '0', '0', '0', '0' ) ");
					
					//EXECUTE: the insertion query.
					if(!$this->dbClassObj->error()){
						echo json_encode(array('error' => STATUS_OK,'message' => 'success','response'=>'Account created successfully!'));exit;
					}else{
						echo json_encode(array('error' => ERROR_EXISTS,'message' => 'error','response' => 'Something went wrong. Please try again later!'));exit;
					}
				}else{ echo json_encode(array('error' => ERROR_EXISTS,'message' => 'error','response' => POST_ERROR_MSG));exit; }
			}
			else {
						if($this->isPrivate($token)) {
							// Error message of "Token has been expired"
							echo json_encode(array('error' => USER_TOKEN_EXPIRED, 'message' => USER_TOKEN_EXPIRED_MSG));exit;
						}
						else{ 
							// Error message of "Token has been expired"
							echo json_encode(array('error' => ACCESS_TOKEN_EXPIRED, 'message' => ACCESS_TOKEN_EXPIRED_MSG));exit;
						}
					}
			}else{ echo json_encode(array('error' => ERROR_EXISTS,'message' => 'error','response' => POST_ERROR_MSG));exit; }
		}
		
		/*	METHOD: used to register student as free user.
		 * 	URL: http://www.edplace.com/webservices/?s=freeStudentSignup
		 * 	POST-DATA: { "name":"test user", "email":"testemail@edplace.com", "password":"testpassword" }
		 * */
		public function freeStudentSignup(){
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				
				$data = file_get_contents('php://input');
				$data = (array)json_decode($data);
				if (count($data)>0) {
					$name = $data['name'];
					$username = $data['username'];
					$password = $this->hasherObj->createHash($data['password']);
					$aa_year = $data['aayear'];
					$year = $data['year'];
					$parent_id = $data['parent_id'];
					
					if(empty($name)){ echo json_encode(array('error' => 1,'message' => 'error','response' => 'Name is required.'));exit; }
					if(empty($username)){ echo json_encode(array('error' => 1,'message' => 'error','response' => 'Username is required.'));exit; }
					if(empty($password)){ echo json_encode(array('error' => 1,'message' => 'error','response' => 'Password is required.'));exit; }
					if(empty($aa_year)){ echo json_encode(array('error' => 1,'message' => 'error','response' => 'Auto-Assign years are required.'));exit; }
					if(empty($year)){ echo json_encode(array('error' => 1,'message' => 'error','response' => 'Year is required.'));exit; }
					
					//CHECK: is user already exists or not.
					$this->_isUserExistsByEmail($username);
					
					//SET: variables for the CRUD processing commands.
					$user_names = explode(' ',$name);
					
					$fname = $user_names['0'];
					if(count($user_names) == 1){ $lname = ''; }else{ $lname = $user_names['1']; }
					$password = $password;
					$email = $username;
					$user_name = $username;
					$user_type = 'student';
					$student_year = $year;
					$auto_assign_year = $aa_year;
					$email_verified = '0';
					$first_added = '0';
					$status = '1';
					$subject = '9';
					$date_created = date('Y-m-d H:i:s');
					$date_modified = date('Y-m-d H:i:s');
					
					//QUERY: to insert the new user.
					$this->dbClassObj->query(" INSERT INTO ep_wsusers ( fname, lname, address, town_city, post_code, county, country, telephone, email, user_name, password, user_type, school_type_id, subject, parent_id, age, student_year, date_reg, date_exp, date_created, date_modified, email_verified, first_added, is_addedby_admin, status, auto_assign_y_n, auto_assign_subject, auto_assign_year, user_subs_days, consider_user_subs_days, auto_login, wk_sug_ntf_date, is_link_verified, worksheet_assigned, worksheet_completed, is_child_password_updated, is_age_updated, cdu_tour_flag, delete_status, self_assign_y_n ) VALUES ( '".$fname."', '".$lname."', '', '', '', '', 0, 0, '".$email."', '".$user_name."', '".$password."', '".$user_type."', 0, '".$subject."', ".$parent_id.", '', ".$student_year.", '', '', '".$date_created."', '".$date_modified."', '".$email_verified."', '".$first_added."', '', '".$status."', '', 0, '".$auto_assign_year."', 0, '', '', '', '', 0, 0, '0', '0', '0', '0', '0' )");
					
					//EXECUTE: the insertion query.
					if(!$this->dbClassObj->error()){
						$this->_updateParentFirstAdded($parent_id);
						echo json_encode(array('error' => 0,'message' => 'success','response'=>'Student account created successfully!'));exit;
					}else{
						echo json_encode(array('error' => 1,'message' => 'error','response' => 'Something went wrong. Please try again later!'));exit;
					}
				}else{ echo json_encode(array('error' => 1,'message' => 'error','response' => 'POST data is required.'));exit; }
			}else{ echo json_encode(array('error' => 1,'message' => 'error','response' => 'POST data is required.'));exit; }
		}
	}
	$api = new API($dbClassObj);
	$function = $_REQUEST['s'];
	$api->$function();
?>
