<?php
	/**
	 * uCrew configuration file.
	 */
	class uCrewConfiguration extends uCrewVersion {
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
			"main_domain" => "*",
			"template" => "uCrewBase",
			"organization" => "uCrew",
			"update_server" => "https://github.com/stellar-creator/uCrew"
		);
		// System configuration data
		public $directories = array(
			"modules" => "uc_modules/",
			"resources" => "uc_resources/",
			"templates" => "uc_templates/",
			"temporary" => "uc_temporary/"
		);
		// System configuration data
		public $modules = array(
			"uCrewSetup" => 1,
			"uCrewAuthorization" => 1,
			"uCrewProjects" => 1,
			"uCrewProjectsUploader" => 1
		);
	}
?>
