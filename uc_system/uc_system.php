<?php
	/**
	 * uCrew system code.
	 */

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'uc_resources/applications/PHPMailer/src/Exception.php';
	require 'uc_resources/applications/PHPMailer/src/PHPMailer.php';
	require 'uc_resources/applications/PHPMailer/src/SMTP.php';

	class uCrewSystem extends uCrewDatabase {
		
		// Session data
		private $ucSession;
		// Page compilator class
		private $ucCompilator;
		// Modules data
		private $ucModules;

		function __construct() {
			// Construct uCrewDatabase (parent class)
			parent::__construct();
			// Set error handler
			set_error_handler(array($this, 'errorHandler'));
			// Check session
			$this->ucSession = new uCrewSession();
			// Init page compilator
			$this->ucCompilator = new uCrewCompilator();
			// Init modules
			$this->ucModules = new uCrewModules();
			// Read modules to module class and check them
			foreach ($this->ucModules->getModules() as $module) {
				// Generate path
				$require_file = $this->directories["modules"] . $module . "/setup.php";
				// Check if setup file exists
			    if( file_exists($require_file) ){
			    	// If file exists, include (rquire once)
			    	require_once($require_file);
			    }
			}
			// Append modules to system
			$this->ucCompilator->pages += $this->ucModules->pages;
			// Request handler
			$this->handler();
			// Check if user auth
			if(!$this->ucSession->checkAuthorization()){
				// If user is unknown, redirect to authorization
				if(isset($_GET['page'])){
					if($_GET['page'] == 'uCrewAuthorization/registration'){
						$this->ucCompilator->setPage("uCrewAuthorization/registration");
					}else{
						header("Location: /");
						die();
					}
				}else{
					$this->ucCompilator->setPage("uCrewAuthorization/authorization");
				}
							}else{
				// Check if isset requst on page
				if(isset($_GET["page"])){
					// Set page if isset
					$this->ucCompilator->setPage($_GET["page"]);
				}else{
					// If page not isset, set mainpage
					$this->ucCompilator->setPage("uCrew/main");
				}
			}
			// Compile result page
			$this->ucCompilator->compilePage();
		}

		public function errorHandler($errno, $errstr, $errfile, $errline) {
		    $err = "<b>Ошибка:</b> [$errno] $errstr<br>\nОшибка на линии $errline в файле $errfile<br>\n";
		    $this->addEvent("Ошибка системы", $err);
		    echo $err;
		}

		// System request handler
		public function handler(){
			// If isset handler request
			if(isset($_GET['handler'])){
				// Select handler
				switch ($_GET['handler']) {
					// Login authorization
					case 'authorization':
						// Check if variables not empty
						if(isset($_POST['user']) and isset($_POST['password'])){
							$this->ucSession->authorizeUser($_POST['user'], $_POST['password']);
						}
						break;
					// Session logout handler
					case 'logout':
						$this->ucSession->unauthorizeUser();
						break;
					// Session logout handler
					case 'search':
						$_GET["page"] = 'uCrew/search';
						break;					
					default:
						print("handler is empty");
						break;
				}
			}
		}
	}

	/**
	 * uCrew system pipe handler.
	*/

	class uCrewSystemPipe extends uCrewDatabase {
		
		public function sendEmail($to, $subject, $body){
	   		$smtp = $this->getSettingsData('smtp');
	  		$smtp['setting_text'] = json_decode($smtp['setting_text'], true);

			$mail = new PHPMailer(true);                                

			try {
			    //Server settings
			    $mail->SMTPDebug = 2;                                   
			    $mail->isSMTP();                                        
			    $mail->Host = $smtp['setting_text']['server'];          
			    $mail->SMTPAuth = true;                                 
			    $mail->Username = $smtp['setting_text']['user'];       
			    $mail->Password = $smtp['setting_text']['password'];    
			    $mail->SMTPSecure = 'ssl';                              
			    $mail->Port = $smtp['setting_text']['port'];            
			        
			    //Recipients
			    $mail->setFrom($smtp['setting_text']['user'], 'uCrew');
			    $mail->addAddress($to);             
			       
			    //Content
			    $mail->isHTML(true);                                    
			    $mail->Subject = $subject;
			    $mail->Body    = $body;
			    $mail->send();
			    echo 'Message has been sent';
			} catch (Exception $e) {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			}
		}

		public function checkUpdates(){
			$server_file = $this->getSettingsData('version_remote')['setting_text'];
			$remote_version_content = file_get_contents($server_file . 'uc_system/uc_version.php');
			$version_data = array();
			preg_match('/"([A-Z0-9.]+)"/', $remote_version_content, $version_data);
			$remote_version = $version_data[1];
			return $this->version != $remote_version ? array('state' => true, 'version' => $remote_version) : array('state' => false, 'version' => $remote_version); 
		}

		public function updateSystem(){
			$cmd = shell_exec("cd .. && git clone " . $this->system['update_server'] . " uCrewUpdate && rm uCrewUpdate/uc_system/uc_configuration.php && cp uCrew/uc_system/uc_configuration.php uCrewUpdate/uc_system/uc_configuration.php 2>&1");
			echo $cmd;	
		}
	}
?>