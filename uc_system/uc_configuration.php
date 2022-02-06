<?php
	/**
	 * uCrew configuration file.
	 */
	class uCrewConfiguration {
		// Database configuration data
		public $database = array(
			"server" => "*",
			"user" => "*",
			"password" => "*",
			"database" => "*"
		);
		// System configuration data
		public $system = array(
			"main_directory" => "*",
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
			"uCrewStorage" => 1
		);
		// System version
		public $version = "0.0.1";
	}
?>