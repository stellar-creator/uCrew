<?php
	/**
	 * uCrew system code.
	 */
	class uCrewSystem extends uCrewDatabase {
		
		private $ucSession;
		private $ucCompilator;

		function __construct() {
			// Construct uCrewDatabase (parent class)
			parent::__construct();
			// Check session
			$this->ucSession = new uCrewSession();
			$this->ucCompilator = new uCrewCompilator();
			// Check if user auth
			if(!$this->ucSession->checkAuthorization()){
				// If user is unknown, redirect to authorization
				$this->ucCompilator->setPage("authorization");
			}
		}
	}
?>