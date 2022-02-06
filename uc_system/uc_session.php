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
			// Return value
			$_SESSION['activation'] = "no";
			// If user isset
			if($user_data != 0){
				// Check user password
				if($user_data["user_password"] == $password){
					// Set session information
					$_SESSION = $user_data;
					// Get privileges
					$_SESSION["privileges"] = $this->getUserPrivilegesByGroup($user_data["user_groups"]);
					// Set result value to 1
					$_SESSION['activation'] = "ok";
					$this->addEvent("Пользователь авторизовался", "Пользователь " . $_SESSION["user_name"] . " авторизовался");

				}else{
					$_SESSION['activation'] = "wrongpass";
					$this->addEvent("Попытка авторизации (неверный пароль)", "Некий пользователь хотел авторизоватся под именем " . $name);
				}
			}else{
				$_SESSION['activation'] = "nouser";
				$this->addEvent("Попытка авторизации (такого пользователя нет в системе)", "Некий пользователь хотел авторизоватся под именем " . $name);
			}
		}

		public function unauthorizeUser(){
			// Add event
			$this->addEvent("Пользователь вышел", "Пользователь " . $_SESSION["user_name"] . " вышел из системы самостоятельно");
			session_unset();
		    session_destroy();
		    session_write_close();
		    setcookie(session_name(),'',0,'/');
		}
	}
?>