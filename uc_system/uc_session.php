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
					// Check user status
					if($user_data["user_status"] == 1){
						// Set session information
						$_SESSION = $user_data;
						// Get privileges
						$_SESSION["privileges"] = $this->getUserPrivilegesByGroup($user_data["user_groups"]);
						// Set result value to 1
						$_SESSION['activation'] = "ok";
						$this->addEvent("Пользователь авторизовался", "Пользователь " . $_SESSION["user_name"] . " авторизовался");
					}else{
						$_SESSION['activation'] = "unactive";
						$this->addEvent("Попытка авторизации (неверный пароль)", "Пользователь " . $name . " неактивен");
					}

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

		public function appendUserEmail($user_id){
			$sql = "UPDATE `uc_users` SET `user_status` = '2' WHERE `user_id` = $user_id";
			$this->query($sql);
			$_SESSION['activation'] = "acceptEmail";
		}

		public function changeUserStatus($user_id, $status){
			$sql = "UPDATE `uc_users` SET `user_status` = '".$status."' WHERE `user_id` = $user_id";
			$this->query($sql);
		}

		public function newUserPassword($user_id, $password){
			$sql = "UPDATE `uc_users` SET `user_password` = '".$password."' WHERE `user_id` = $user_id";
			$this->query($sql);
		}

		public function recoveryUser($data){
			$user_data = $this->getUserByData($data);
			if($user_data != 0){
				$ucSystemPipe = new uCrewSystemPipe();

				$_SESSION['activation'] = "recovery";
				$this->addEvent("Восстановление пароля", "Пользователю " . $user_data['user_name'] . " ("  . $user_data['user_email'] . ') отправлены данные для восстановления пароля');

				$message = "<p>Уважаемый <b>" .$user_data['user_name'] . "</b>, вы запросили восстановление пароля</p><br><p><a href=\"http://".$this->system['main_domain']."/?page=uCrewAuthorization/newpassword&user=".$user_data['user_id']."\">Перейдите по ссылке для изменения пароля</a></p>";

				$ucSystemPipe->sendEmail($user_data['user_email'], 'Восстановление доступа к системе', $message);
				$this->changeUserStatus($user_data['user_id'], 4);
			}else{
				$_SESSION['activation'] = "unrecovery";
				$this->addEvent("Восстановление пароля", "Попытка сброса пароля с данными " . $data);
			}
		}

		public function registerUser($data){
			// Check if user isset
			$user_data = $this->getUserByData($data['user_email']);
			// If not isset, do register
			if($user_data == 0){
				$ucSystemPipe = new uCrewSystemPipe();
				$_SESSION['activation'] = "register";
				$user_id = $this->addUser($data);
				$message = "<p>Уважаемый <b>" .$data['user_name'] . "</b>, регистрация прошла успешно, ожидайте ответа администратора на одобрение заявки.</p><br><p><a href=\"http://".$this->system['main_domain']."/?handler=activation&user=$user_id\">Подтвердите адрес электроной почты</a></p>";
				$ucSystemPipe->sendEmail($data['user_email'], 'Регистрация в системе', $message);
				$this->addEvent("Регистрация успешна", "Пользователь " . $data['user_name'] . " ("  . $data['user_email'] . ') зарегистрирован, требуется активация');
			}else{
				$_SESSION['activation'] = "unregister";
				$this->addEvent("Регистрация неуспешна", "Пользователь " . $data['user_name'] . " ("  . $data['user_email'] . ') не зарегистрирован, такой пользователь уже есть');
			}
		}
	}
?>