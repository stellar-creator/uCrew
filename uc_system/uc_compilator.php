<?php 
	/**
	 * uCrew compilator code.
	 */

	class uCrewCompilatorData {
		public $javascript = array();
		public $css = array();
		public $header = array();
	}

	class uCrewCompilator extends uCrewConfiguration {
		// In this array saved pages from system and modules
		public $pages = array();
		// Included file as page
		private $selected_content = "";

		function __construct() {
			// code...
		}
		// Set page function
		public function setPage($page_name){
			$this->selected_content = $this->pages[$page_name]["content"];
		}
		// Add page to system
		public function addPage($page_name, $page_file){
			// Push to array content file and configuration
			$this->pages[$page_name] = $page_file;
		}

		public function compilePage(){
			/*
			*
			* TODO: add header, footer and template handler
			*
			*/
			// On the end, compile page
			require_once($this->selected_content);
		}

	}
?>