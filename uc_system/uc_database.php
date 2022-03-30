<?php
	/**
	 * uCrew database code.
	 */
	class uCrewDatabase extends uCrewConfiguration {
		// Connection object
		private $connection;
		// Construct class function
		function __construct($database = '') {
			// Connect to database
			if($database == ''){
				$database = $this->database["database"];
			}
			$this->connection = new mysqli($this->database["server"], $this->database["user"], $this->database["password"], $database);
			// Set encoding (charset)
			$this->connection->set_charset("utf8");
			// If connection falied, go down
			if ($this->connection->connect_error) {
  				die("Database connection error: <b>" . $this->connection->connect_error . "</b>");
			}
		}
		// Get data from database
		public function getData($query){
			// Do query
			$result = $this->connection->query($query);
			// Check result and return
			if ($result->num_rows > 0) {
				return $result->fetch_assoc();
			}else{
				return 0;
			}
		}
		// Get data from database
		public function getAllData($query){
			// Do query
			$result = $this->connection->query($query);
			// Check result and return
			if ($result->num_rows > 0) {
				$data = array();
				while($rows = $result->fetch_assoc()) {
					array_push($data, $rows);
				}
				return $data;
			}else{
				return 0;
			}
		}
		// Destruct class function
		function __destruct() {
			// Close connection
       		$this->connection->close();
   		}

   		public function getUserByData($data){
   			// Generate request string
   			$request = 'SELECT * FROM `uc_users` WHERE `user_name` = "' . $data . '" OR `user_email` = "' . $data . '" OR `user_phone` = "' . $data . '"';
   			// Select by name
   			return $this->getData($request);
   		} 

   		public function getUserPrivilegesByGroup($group){
   			// Generate request string
   			$request = 'SELECT * FROM `uc_groups` WHERE `group_name` = "'.$group.'"';
   			// Select by name
   			return explode(";", $this->getData($request)["group_privileges"]);
   		}

   		// Get main page text
   		public function getMainPageContent(){
   			$query = "SELECT * FROM `uc_settings` WHERE `setting_name` = 'page' AND `setting_value` = 'main'";
   			return $this->getData($query)['setting_text'];
   		}

   		// Get main page text
   		public function setMainPageContent($content){
   			$content = str_replace('"', '\"', $content);
   			$query = "UPDATE `uc_settings` SET `setting_text` = '$content' WHERE `setting_name` = 'page' AND `setting_value` = 'main'";
   			return $this->query($query);
   		}

   		// Get settings data
   		public function getSettingsData($name){
   			$query = "SELECT * FROM `uc_settings` WHERE `setting_name` = '$name'";
   			return $this->getData($query);
   		}

   		// Add event to database
   		public function addEvent($event_name, $event_text){
   			// Check if isset user
   			$user = 0;
   			if(isset($_SESSION["user_id"])){
   				$user = $_SESSION["user_id"]; 
   			}
   			// Generate request string
   			$query = 'INSERT INTO `uc_events` (`event_id`, `event_user_id`, `event_name`, `event_text`, `event_timestamp`, `event_wasviewed`) VALUES (NULL, "'.$user.'", "'.$event_name.'", "'.$event_text.'", CURRENT_TIMESTAMP, "0")';
   			// Select by name
   			$this->connection->query($query);
   		}

   		// Add event to database
   		public function getEvents($page, $total, $key = ''){
   			// Calcutate data
   			$start = ($page * $total) - $total;
			$end = $total;
   			$sufix = '';
   			// Check keys
   			if($key != ''){
   				$sufix = " WHERE `event_name` LIKE '%$key%' OR `event_text` LIKE '%$key%' ";
   			}
   			// Get count of records
   			$query_count = 'SELECT COUNT(`event_id`) AS "total_events" FROM `uc_events`' . $sufix;
   			// Get records
   			$query_data = 'SELECT * FROM `uc_events`'.$sufix.' ORDER BY `uc_events`.`event_id` DESC LIMIT '.$start.', '.$end;
   			// Add data
   			$retutn_data = array(
   				"total" => $this->getData($query_count)['total_events'],
   				"data" => $this->getAllData($query_data)
   			);
   			// Count max pages
   			$retutn_data["total_pages"] = round($retutn_data["total"] / $total);
   			// Return data
   			return $retutn_data;
   		} 
		// Get users posts
		public function getPosts(){
			// Query
			$query = 'SELECT * FROM `uc_posts` ORDER BY `uc_posts`.`post_id` ASC';
			// Return values
			return $this->getAllData($query);
		}
		// Get users locations
		public function getLocations(){
			// Query
			$query = 'SELECT * FROM `uc_locations` ORDER BY `uc_locations`.`location_id` ASC';
			// Return values
			return $this->getAllData($query);
		}	
		// Get users locations
		public function query($query){
			// Query
			$this->connection->query($query);
			// Return last id
			return $this->connection->insert_id;
		}	
		// Get users lists
		public function getUsers(){
			// Get users data
			$query = "SELECT `user_id`, `user_name`, `user_email`, `user_status`, `user_image`, `user_phone`, `user_location`, `user_post`, `user_groups` FROM `uc_users` ORDER BY `uc_users`.`user_id` DESC";
			// Return data
			return $this->getAllData($query);
		}
		// Get user by id
		public function getUser($user_id){
			// Get users data
			$query = "SELECT `user_id`, `user_name`, `user_email`, `user_status`, `user_image`, `user_phone`, `user_location`, `user_post`, `user_groups` FROM `uc_users` WHERE `user_id` = $user_id";
			// Return data
			return $this->getData($query);
		}	

		// Get user by id
		public function getRecordsCount($table){
			// Get count of records
			$sql = "SELECT COUNT(*) FROM `".$table."`";
			// Return count
			return $this->getAllData($sql)[0]['COUNT(*)'];
		}	

		// Get user by id
		public function getRecordsCountSpecific($table, $sql_add){
			// Get count of records
			$sql = "SELECT COUNT(*) FROM `".$table."` " . $sql_add;
			// Return count
			return $this->getAllData($sql)[0]['COUNT(*)'];
		}	

		// Get users lists
		public function createChat($from, $to){
			// Check if chat exists
			$sql = "SELECT * FROM `uc_chats` WHERE `chat_from` = $from AND `chat_to` = $to";
			if( $this->getAllData($sql) == 0){ 
				// Get users data
				$query = "INSERT INTO `uc_chats` (`chat_id`, `chat_from`, `chat_to`, `chat_primary`, `chat_activated`) VALUES (NULL, '$from', '$to', '$from', '1')";
				// Return data
				return $this->query($query);
			}else{
				return 0;
			}
		}		
		// Get chat lists with data
		public function getChatList($from, $to = 0){
			// Query
			$query = "SELECT * FROM `uc_chats` WHERE `chat_from` = $from OR `chat_to` = $from";
			// Get chats
			$chats = $this->getAllData($query);
			// Get user data
			$users = $this->getUsers();
			
			foreach ($chats as $index => &$data) {
				if($data['chat_activated'] == 1){
					foreach ($users as $user_index => $user_data) {

						if($data['chat_from'] == $data['chat_primary'] && $data['chat_to'] == $user_data['user_id']){
							$data['user_to'] = $user_data;
						}
						if($data['chat_from'] == $user_data['user_id']){
							$data['chat_from'] = $user_data;
						}
						if($data['chat_to'] == $user_data['user_id']){
							$data['chat_to'] = $user_data;
						}

					}
				}
			}
			return $chats;
		}	
	}
?>