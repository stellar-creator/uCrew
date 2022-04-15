<?php
	/**
	 * uCrew configuration file.
	 */
	class uCrewConfiguration extends uCrewVersion {
		// Database configuration data
		public $database = array(
			"server" => "localhost",
			"user" => "root",
			"password" => "4d92f93WATCH",
			"database" => "ucrew_database"
		);
		// System configuration data
		public $system = array(
			"main_directory" => "/home/pavel/Works/Software/Cross Platform/Web/uCrew/",
			"main_domain" => "94.51.83.132",
			"template" => "uCrewBase",
			"organization" => "uCrew",
			"update_server" => "https://github.com/stellar-creator/uCrew"
		);
		// System configuration data
		public $directories = array(
			"modules" => "uc_modules/",
			"resources" => "uc_resources/",
			"templates" => "uc_templates/",
		);
		// System configuration data
		public $modules = array(
			"uCrewSetup" => 1,
			"uCrewAuthorization" => 1,
			"uCrewProjects" => 1
		);
	}
?>