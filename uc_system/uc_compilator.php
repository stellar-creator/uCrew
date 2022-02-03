<?php 
	/**
	 * uCrew compilator code.
	 */

	class uCrewCompilatorData {
		public $javascript = array();
		public $css = array();
		public $header = array();
		public $template_header = "";
		public $template_footer = "";

		public function addJavaScript($script){
			array_push($this->javascript, $script);
		}

		public function addCSS($css){
			array_push($this->css, $css);
		}

		public function header($header){
			array_push($this->header, $header);
		}

		public function getJavaScripts(){
			// Buffer variable
			$data = "";
			// Append data to string
			foreach ($this->javascript as $javascript) {
			    $data .= "\t\t" . '<script src="' . $javascript . '" crossorigin="anonymous"></script>' . "\n";
			}
			// Return value
			return $data;
		}

		public function getCSS(){
			// Buffer variable
			$data = "";
			// Append data to string
			foreach ($this->css as $css) {
			    $data .= "\t\t" . '<link href="' . $css . '" rel="stylesheet" />' . "\n";
			}
			// Return value
			return $data;
		}

		public function getTitle($title){
			return "\t\t<title>$title</title>\n";
		}
	}

	class uCrewCompilator extends uCrewConfiguration {
		// Init CompilatorData
		public $uc_CompilatorData;
		// In this array keep pages from system (default) and modules
		public $pages = array(
			// Main system page
			"uCrew/main" => array(
				"content" => "uc_pages/main.php",
				"module" => "uCrewSystem",
				"title" => "Главная страница"
			),
			// User page
			"uCrew/user" => array(
				"content" => "uc_pages/user.php",
				"module" => "uCrewSystem",
				"title" => "Пользователь"
			),
			// Common settings page
			"uCrew/settings" => array(
				"content" => "uc_pages/settings.php",
				"module" => "uCrewSystem",
				"title" => "Настройки"
			)
		);
		// Included file as page
		private $selected_content = "";
		private $selected_title = "";
		private $template_folder =  "";

		function __construct() {
			// Init classes
			$this->uc_CompilatorData = new uCrewCompilatorData(); 
			// Init variables
			$this->template_folder =  $this->directories["templates"] . $this->system["template"] . '/';
		}

		// Set page function
		public function setPage($page_name){
			// Set page content file
			$this->selected_content = $this->pages[$page_name]["content"];
			// Set page title
			$this->selected_title = $this->pages[$page_name]["title"] . " / uCrew";
		}
		// Add page to system
		public function addPage($page_name, $page_file){
			// Push to array content file and configuration
			$this->pages[$page_name] = $page_file;
		}
		// Compile page function
		public function compilePage(){
			// Require template data
			require_once($this->template_folder . "setup.php");
			// Add header
			echo $this->uc_CompilatorData->template_header;
			// Add page title
			echo $this->uc_CompilatorData->getTitle($this->selected_title);
			// Add JavaScripts
			echo $this->uc_CompilatorData->getJavaScripts();
			// Add styles
			echo $this->uc_CompilatorData->getCSS();
			// On the end, compile page
			require_once($this->selected_content);
		}

	}
?>