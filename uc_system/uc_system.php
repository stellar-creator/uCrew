<?php
	/**
	 * uCrew system code.
	 */

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
					}else if($_GET['page'] == 'uCrewAuthorization/recovery'){
						$this->ucCompilator->setPage("uCrewAuthorization/recovery");
					}else if($_GET['page'] == 'uCrewAuthorization/newpassword'){
						$this->ucCompilator->setPage("uCrewAuthorization/newpassword");
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
					case 'registration':
						$this->ucSession->registerUser($_POST);
						break;
					// Session logout handler
					case 'search':
						$_GET["page"] = 'uCrew/search';
						break;	
					// Session logout handler
					case 'activation':
						$this->ucSession->appendUserEmail($_GET["user"]);
						break;
					// Session logout handler
					case 'recovery':
						$this->ucSession->recoveryUser($_POST["user"]);
						break;
					case 'newpassword':
						$this->ucSession->newUserPassword($_POST["user"], $_POST["password"]);
						$this->ucSession->changeUserStatus($_POST["user"], 1);
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
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'uc_resources/applications/PHPMailer/src/Exception.php';
	require 'uc_resources/applications/PHPMailer/src/PHPMailer.php';
	require 'uc_resources/applications/PHPMailer/src/SMTP.php';

	class uCrewSystemPipe extends uCrewDatabase {

		public function sendEmail($to, $subject, $body){
	   		$smtp = $this->getSettingsData('smtp');
	  		$smtp['setting_text'] = json_decode($smtp['setting_text'], true);

			$mail = new PHPMailer(true);                                

			try {
			    //Server settings
			    $mail->CharSet = 'UTF-8';
			    $mail->SMTPDebug = 0;                                   
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

		public function setTerminalToHtml($str){
			$characters = array(
				array("1", "2"),
				array("1", "#")
			);

			foreach ($characters as $data) {
				$str = str_replace($data[0], $data[1], $str);
			}

			return $str;
		}

		public function setSpecialCharacters($str){
			$characters = array(
				array('\'', '&#39;'),
				array('"', '&#34;'),
				array('\\', '&#92'),
				array('\\', '&#92'),
				array('*', '&#42;')
			);

			foreach ($characters as $data) {
				$str = str_replace($data[0], $data[1], $str);
			}

			return $str;
		}

		public function deleteDirectory($dir){
	    	if (!file_exists($dir)) {
	        	return true;
	    	}

	    	if (!is_dir($dir)) {
	        	return unlink($dir);
	    	}

	    	foreach (scandir($dir) as $item) {
	        	if ($item == '.' || $item == '..') {
	            	continue;
	        	}

	        	if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
	            	return false;
	       		}
	   
			}
		}

		public function uploadFile($remote_name, $file_name, $directory, $files){
			if($files[$remote_name]["tmp_name"] != '' ){
				$f_name = $file_name;
				$f_dir = $directory;
				$f_mount =  $f_dir . $f_name;
				$this->createDirectorySpecial($f_dir);
				move_uploaded_file($files[$remote_name]["tmp_name"], $f_mount);
			}
		}

		public function uploadFiles($remote_name, $directory, $files){
			if($files[$remote_name]["tmp_name"][0] != ''){
				for($i = 0; $i < count($files[$remote_name]["tmp_name"]); $i++) { 
					$f_name = $files[$remote_name]["name"][$i];
					$f_dir = $directory;
					$f_mount =  $f_dir . $f_name;
					$this->createDirectorySpecial($f_dir);
					move_uploaded_file($files[$remote_name]["tmp_name"][$i], $f_mount);
				}
			}
		}

		public function pdfToJpeg($input, $output, $quality = 95, $dpi = 300){
			echo $this->sh(
				array(
					"pdftoppm -singlefile -f 1 -r $dpi -jpeg -jpegopt quality=$quality \"$input\" \"$output\"",
					"cp \"$output.jpg\" \"$output.jpeg\"",
					"rm \"$output.jpg\"",
				)
			);
		}

		public function stepConverter($location, $filename){

			$workdir = $this->system['main_directory'] . $this->directories['temporary'] . '3d';
			$source = $location .  '3D модель ' .  $filename  . '.step';
			$stl = $location . 'Для печати ' . $filename  . '.stl';
			$x3d = $location . 'Веб 3D модель ' . $filename  . '.x3d';

			$this->sh(
				array(
					"sudo mkdir -p \"$location\"",
					"cd \"$workdir\"/",
					"ls \"$workdir\"/",
					"ls \"$location\"",
					"sudo cp \"$source\" \"$workdir/file.step\"",
					"sudo ucrew-freecad",
					"cp \"$workdir/file.stl\" \"$stl\"",
					"cp \"$workdir/file.x3d\" \"$x3d\"",
					"sudo rm -rf \"$workdir/\""
				)
			);

		}

		public function createDirectorySpecial($directory){
			if(!file_exists($directory)){
				mkdir($directory, 0777, true);
				return true;
			}
			return false;
		}

		public function unzip($file, $path){
			$archive = new ZipArchive;
			$result = $archive->open($file);
			if ($result === true) {
				$archive->extractTo($path);
				$archive->close();
			}
		}

		public function sh($commands){
			$result = "";
			if(is_array($commands)){
				foreach ($commands as $index => $command) {
					if($index != count($commands) - 1){
						$result .= $command . " && ";
					}else{
						$result .= $command . " ";
					}
				}
			}else{
				$result = $commands;
			}
			$result .= " 2>&1";
			return shell_exec($result);
		}

		public function updateSystem(){
			$result = $this->sh(
				array(
					// Change directory
					'cd ..',
					// Get fresh version
					'git clone ' . $this->system['update_server'] . ' uCrewUpdate',
					// Remove default configuration from
					'rm uCrewUpdate/uc_system/uc_configuration.php',
					// Copy old configuration
					'cp uCrew/uc_system/uc_configuration.php uCrewUpdate/uc_system/uc_configuration.php',
					// Copy applications
					'cp -a uCrewUpdate/uc_resources/applications/. uCrew/uc_resources/applications',
					// Dont touch resources
					'rm -rf uCrewUpdate/uc_resources',
					// Dont touch modules
					//'rm -rf uCrewUpdate/uc_modules',
					// Dont touch templates
					//'rm -rf uCrewUpdate/uc_templates',
					// Copy all files
					'cp -a uCrewUpdate/. uCrew/',
					// Remove temp folder
					'rm -rf uCrewUpdate/',
					// Change mode
					'chown -R www-data:www-data uCrew/',
					// Change privileges
					'chmod 777 -R uCrew/',
					// Show result
					'ls'
				)
			);
			return $result;	
		}
	}
?>