<?php
	/**
	 * uCrew session code.
	 */
	class uCrewSession extends uCrewDatabase {

		function __construct() {
			// Construct uCrewDatabase (parent class)
			parent::__construct();
			// Run session
			session_start();
		}

		public function checkAuthorization(){
			// Check if isset user in session
			if(isset($_SESSION["user_id"])){
				// Check if session variable not empty
				if(!empty($_SESSION["user_id"])){
					return $_SESSION["user_id"];
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}

		public function authorizeUser($name, $password){
			// Try to found user in database
			$user_data = $this->getUserByData($name);
			// If user isset
			if($user_data != 0){
				// Check user password
				if($user_data["user_password"] == $password){
					// Set session information
					$_SESSION = $user_data;
				}
			}
		}

	}
?>