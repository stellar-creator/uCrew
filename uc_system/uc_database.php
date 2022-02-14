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
   			$end = $page * $total;
   			$start = $end - $total;
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
	}
?>