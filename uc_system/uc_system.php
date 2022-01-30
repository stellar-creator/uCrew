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
			// Check if user auth
			if(!$this->ucSession->checkAuthorization()){
				// If user is unknown, redirect to authorization
				$this->ucCompilator->setPage("uCrewAuthorization/authorization");
			}
			// Compile result page
			$this->ucCompilator->compilePage();
		}
	}
?>