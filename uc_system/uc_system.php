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
				$this->ucCompilator->setPage("uCrewAuthorization/authorization");
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

		// System request handler
		public function handler(){
			// If isset handler request
			if(isset($_GET['handler'])){
				switch ($_GET['handler']) {
					case 'authorization':
						$this->ucSession->authorizeUser($_POST['user'], $_POST['password']);
						break;
					
					default:
						print("handler is empty");
						break;
				}
			}
		}
	}
?>