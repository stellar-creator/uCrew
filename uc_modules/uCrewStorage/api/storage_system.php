<?php
	/**
	 * uCrew Storage Class
	 */
	class uCrewStorage {
		private $ucs_Database ;

		function __construct(){
			$this->ucs_Database = new uCrewDatabase('ucrew_storage');
		}

		private function buildCategoriesRecursive(Array $data, $parent = 0) {
		    $tree = array();
		    foreach ($data as $d) {
		        if ($d['category_for'] == $parent) {
		            $children = $this->buildCategoriesRecursive($data, $d['category_id']);
		            if (!empty($children)) {
		                $d['subcategories'] = $children;
		            }
		            $tree[] = $d;
		        }
		    }
		    return $tree;
		}

		public function collectCategories($category){
			// Build categories
			return $this->buildCategoriesRecursive($this->ucs_Database->getAllData('SELECT * FROM `ucs_categories`'), $category);
		}
	}
?>