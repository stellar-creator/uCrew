<?php
	/**
	 * uCrew database code.
	 */
	class uCrewModules extends uCrewConfiguration {
		public $pages = array();
		public $applets = array();
		public $modules_data = array();
		// This function add module data to system
		public function addModule($name, $description, $category, $author, $pages, $configuraion, $section, $icon = ["icon" => "fa fa-question-circle"]){ 
			// Activation check, if module not in configuration, activation is 0
			$activation = 0;
			// Check if module in configuration
			if(isset($this->modules[$name])){
				// Add pages to system
				foreach ($pages as $page => $title) {
				    $this->pages[$name . "/" . $page] =  [
				    	"content" => $this->directories["modules"] . $name . "/pages/" . $page . ".php",
				    	"module" => $name,
				    	"title" => $title,
				    	"configuration" => $configuraion["configuration"],
				    	"section" => $section["section"],
				    	"icon" => $icon["icon"]
				    ];
				}
				// If module in configuration, do activation with value
				$activation = $this->modules[$name];
			}
			// Add module to system
			$this->modules_data[$name] = [
				$activation, 
				$description, 
				$category, 
				$author, 
				$pages, 
				$configuraion
			];
		}
		// Add applet to system function
		public function addApplet($file, $class, $functions){
			array_push($this->applets, [$file, $class, $functions]);
		}
		// Read modules from directory
		public function getModules(){
			// Scan module directory
			$modules = array_values(
				array_diff(
					scandir($this->directories["modules"]), array('..', '.')
				)
			);
			// Return all modules
			return $modules;
		}

	}
?>