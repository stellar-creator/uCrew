<?php
	/**
	 * uCrew session code.
	 */
	class uCrewSession {

		function __construct() {
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

	}
?>