<?php 
	/**
	 * uCrew compilator code.
	 */

	class uCrewCompilatorData {
		// Public variables
		public $javascript = array();
		public $css = array();
		public $header = array();
		public $template_header = "";
		public $template_body = "";
		public $template_topbar = "";
		public $template_sidebar = "";
		public $template_footer = "";
		public $page_end = "";
		// Top bar constructor array
		public $topbar = array(
			"header" => "", 
			"footer" => "",  
			"item" => "",
			"divider" => ""
		);
		// Top bar constructor array
		public $sidebar = array(
			"header" => "", 
			"footer" => "",  
			"once_item" => "", 
			"subitems_header" => "",
			"subitem" => "",
			"subitems_footer" => "",
			"divider" => ""
		);
		// Top bar constructor array
		public $page = array(
			"header" => "", 
			"footer" => ""
		);
		// Add JS to template
		public function addJavaScript($script, $position = 'top'){
			$this->javascript[$script] = $position;
		}
		// Add CSS to template
		public function addCSS($css){
			array_push($this->css, $css);
		}
		// Add header data to template
		public function header($header){
			array_push($this->header, $header);
		}
		// Add topbar to template
		public function getTopBar($data){
			$this->template_topbar .= $this->topbar["header"];
			foreach($data as $title => $page){
				if($page != "divider"){
					$_temp = str_replace("%name%", $title, $this->topbar["item"]);
					$this->template_topbar .= str_replace("%page%", $page, $_temp);
				}else{
					$this->template_topbar .= $this->topbar["divider"];
				}
			}
			
			$this->template_topbar .= $this->topbar["footer"];
			return $this->template_topbar;
		}
		// Add sidebar to template
		public function getSideBar($pages, $selected_page_section){
			// Reverse array
			$pages = array_reverse($pages);
			// Buffer array
			$sections = array();
			// Check all submenu items
			foreach($pages as $page => $data){
				// If need to add in menu, and check privileges
				if($data["configuration"]["menu"] == 1 and ( in_array($data["configuration"]["privileges"], $_SESSION['privileges']) or in_array("all", $_SESSION['privileges']) ) ){
					// Check if section isset
					if(!isset( $sections[$data["section"]] )){
						// If not, append
						$sections[$data["section"]] = array();
					}
					// Add page
					$data["page"] = $page;
					// Add data to buffer
					array_push($sections[$data["section"]], $data);
				}
			}

			// Add sidebar header
			$this->template_sidebar .= $this->sidebar["header"];
			// Check all section and information
			foreach($sections as $section => $information){
				// Set section name
				$_temp = str_replace("%subitems_title_tag%", $section, $this->sidebar["subitems_header"]);
				// Set items icon
				$_temp = str_replace("%subitems_icon%", $information[0]["icon"], $_temp);
				// Check section
				$show = "";
				if($section == $selected_page_section){
					$show = " show";
				}
				$_temp = str_replace("%show%", $show, $_temp);
				$this->template_sidebar .= str_replace("%subitems_title%", $section, $_temp);
				// Get all subitems (reversed)
				$information = array_reverse($information);
				// Append all data
				foreach($information as $links){
					// Append links if title didn't have virtual link
					if (strpos($links["title"], "&") !== false) {
						  
					}else{
						 $_temp = str_replace("%page%", $links["page"], $this->sidebar["subitem"]);
						$this->template_sidebar .= str_replace("%subitem%", $links["title"], $_temp);
					}
					
				}
				// Add items footer
				$this->template_sidebar .= $this->sidebar["subitems_footer"];
			}
			// Add sidebar footer
			$this->template_sidebar .= $this->sidebar["footer"];
			// Return result
			return $this->template_sidebar;
		}
		// Get JS from data
		public function getJavaScripts($position = 'top'){
			// Buffer variable
			$data = "";
			// Append data to string
			foreach ($this->javascript as $javascript => $pos) {
				if($position == $pos){
			    	$data .= "\t\t" . '<script src="' . $javascript . '" crossorigin="anonymous"></script>' . "\n";
			    }
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

		public function getPageHeader($page, $page_pipe){
			$_temp = str_replace("%page%", $page, $this->page["header"]);
			return str_replace("%page_pipe%", $page_pipe, $_temp);
		}
	}

	class uCrewCompilator extends uCrewConfiguration {
		// Add CompilatorData
		public $uc_CompilatorData;
		// Add Database Class
		public $ucDatabase;
		// Top bar items array
		public $topbar_data = array(
			"Сообщения" => "/?page=uCrew/messages",
			"Оповещения" => "/?page=uCrew/notofications",
			"divider" => "divider",
			"Выйти из системы" => "/?handler=logout"
		);
		// In this array keep pages from system (default) and modules
		public $pages = array(
			// Main system page
			"uCrew/main" => array(
				"content" => "uc_pages/main.php",
				"module" => "uCrewSystem",
				"title" => "Главная страница",
				"configuration" => array(
			    	"menu" => 0,
			    	"content" => 1,
			    	"privileges" => "user"
				),
				"section" => "Системное",	
				"icon" => "fas fa-tachometer-alt"
			),
			// User page
			"uCrew/user" => array(
				"content" => "uc_pages/user.php",
				"module" => "uCrewSystem",
				"title" => "Пользователь",
				"configuration" => array(
			    	"menu" => 1,
			    	"content" => 1,
			    	"privileges" => "user"
				),
				"section" => "Системное",	
				"icon" => "fa-solid fa-sliders"
			),
			// Common settings page
			"uCrew/events" => array(
				"content" => "uc_pages/events.php",
				"module" => "uCrewSystem",
				"title" => "События",
				"configuration" => array(
			    	"menu" => 1,
			    	"content" => 1,
			    	"privileges" => "admin"
				),
				"section" => "Системное",	
				"icon" => "fa-solid fa-sliders"
			),
			// Common settings page
			"uCrew/settings" => array(
				"content" => "uc_pages/settings.php",
				"module" => "uCrewSystem",
				"title" => "Настройки",
				"configuration" => array(
			    	"menu" => 1,
			    	"content" => 1,
			    	"privileges" => "admin"
				),
				"section" => "Системное",	
				"icon" => "fa-solid fa-sliders"
			),
			// Common settings page
			"uCrew/messages" => array(
				"content" => "uc_pages/messages.php",
				"module" => "uCrewSystem",
				"title" => "Сообщения",
				"configuration" => array(
			    	"menu" => 0,
			    	"content" => 1,
			    	"privileges" => "user"
				),
				"section" => "Системное",	
				"icon" => "fa-solid fa-sliders"
			),
			// Common settings page
			"uCrew/notofications" => array(
				"content" => "uc_pages/notofications.php",
				"module" => "uCrewSystem",
				"title" => "Оповещения",
				"configuration" => array(
			    	"menu" => 0,
			    	"content" => 1,
			    	"privileges" => "user"
				),
				"section" => "Системное",	
				"icon" => "fa-solid fa-sliders"
			),
			// Common settings page
			"uCrew/search" => array(
				"content" => "uc_pages/search.php",
				"module" => "uCrewSystem",
				"title" => "Поиск",
				"configuration" => array(
			    	"menu" => 0,
			    	"content" => 1,
			    	"privileges" => "user"
				),
				"section" => "Системное",	
				"icon" => "fa-solid fa-sliders"
			)
		);
		// Included file as page
		private $selected_content = "";
		private $selected_title = "";
		private $selected_configuration;
		private $template_folder =  "";
		private $selected_page =  "";
		private $selected_page_pipe =  "";
		private $selected_page_section = "";

		function __construct() {
			// Init classes
			$this->uc_CompilatorData = new uCrewCompilatorData(); 
			// Init database class
			$this->ucDatabase = new uCrewDatabase();
			// Init variables
			$this->template_folder =  $this->directories["templates"] . $this->system["template"] . '/';
		}

		private function clearVirtualLink($string){
			return str_replace("&", "", $string);
		}

		// Set page function
		public function setPage($page_name){
			// Set page content file
			$this->selected_content = $this->pages[$page_name]["content"];
			// Set page title
			$this->selected_title = $this->clearVirtualLink($this->pages[$page_name]["title"]) . " / uCrew";
			// Set page configuration
			$this->selected_configuration = $this->pages[$page_name]["configuration"];
			// Set page name
			$this->selected_page =  $this->clearVirtualLink($this->pages[$page_name]["title"]);
			// Set page name pipe
			$this->selected_page_pipe =  $this->pages[$page_name]["section"] . ' / ' . $this->clearVirtualLink($this->pages[$page_name]["title"]);
			// Set page section
			$this->selected_page_section = $this->pages["$page_name"]["section"];
		}
		// Add page to system
		public function addPage($page_name, $page_file){
			// Push to array content file and configuration
			$this->pages[$page_name] = $page_file;
		}
		// Compile page function
		public function compilePage(){
			// Check if isset configuration
			if($this->selected_configuration["content"] != 0){
				// Require template data
				require_once($this->template_folder . "setup.php");
				// Add header
				echo $this->uc_CompilatorData->template_header;
				// Add page title
				echo $this->uc_CompilatorData->getTitle($this->selected_title);
				// Add styles
				echo $this->uc_CompilatorData->getCSS();
				// Add top JavaScripts
				echo $this->uc_CompilatorData->getJavaScripts();
				// Add body
				echo $this->uc_CompilatorData->template_body;
				// Add topbar
				echo $this->uc_CompilatorData->getTopBar($this->topbar_data);
				// Add sidebar
				echo $this->uc_CompilatorData->getSideBar($this->pages, $this->selected_page_section);
				// Add page header data
				echo $this->uc_CompilatorData->getPageHeader($this->selected_page, $this->selected_page_pipe);
			}
			// Append content
			if(file_exists($this->selected_content)){
				// If content exists, require it
				require_once($this->selected_content);
			}else{
				// If content not found, show 404 page eroor
				$this->ucDatabase->addEvent("Ошибка 404", "Пользователь " . $_SESSION["user_name"] . " попал на страницу <b>" . $_SERVER['REQUEST_URI'] . "</b>");
				require_once('uc_pages/404.php');
			}
			// Check if isset configuration
			if($this->selected_configuration["content"] != 0){
				// Add page footer data
				echo $this->uc_CompilatorData->page["footer"];
				// Add footer
				echo $this->uc_CompilatorData->template_footer;
				// Add bottom
				echo $this->uc_CompilatorData->getJavaScripts('bottom');
				// Add end of page
				echo $this->uc_CompilatorData->page_end;
			}

		}

	}
?>