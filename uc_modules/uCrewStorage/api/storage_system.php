<?php
	/**
	 * uCrew Storage Class
	 */
	class uCrewStorage {
		private $ucs_Database ;

		function __construct(){
			$this->ucs_Database = new uCrewDatabase('ucrew_storage');
		}

		public function addCategory($name, $description, $subcategory, $template, $image){

			$target_dir = "uc_resources/images/uCrewStorage/categories/";
			$target_file = $target_dir . basename($_FILES["image"]["name"]);
			$path_parts = pathinfo($target_file);
			$target_new = $target_dir . bin2hex(random_bytes(15)) . '.' . $path_parts['extension'];

			$query = "INSERT INTO `ucs_categories` (`category_id`, `category_name`, `category_description`, `category_for`, `category_image`, `category_status`) VALUES (NULL, '$name', '$description', '$subcategory', '$target_new', '1')";
	
			$image_name = $this->ucs_Database->query($query);

			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_new,PATHINFO_EXTENSION));

			// Check if image file is a actual image or fake image
			$check = getimagesize($_FILES["image"]["tmp_name"]);
			if($check !== false) {
			  //echo "File is an image - " . $check["mime"] . ".";
			  $uploadOk = 1;
			} else {
			  //echo "File is not an image.";
			  $uploadOk = 0;
			}

			$message = '<div class="row mb-3"><div class="alert alert-danger" role="alert">
			  <h4 class="alert-heading">Категория '. $name .' не добавлена</h4>
			  <p>Данная категория не добавлена</p>
			 
			</div></div>';

			if($uploadOk == 1){
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_new)) {
				  $uploadOk = 1;
				  $message = '<div class="row mb-3"><div class="alert alert-success" role="alert">
			  <h4 class="alert-heading">Категория '. $name .' добавлена</h4>
			  <p>Категория успешно добавлена</p>
			 
			</div></div>';
				} else {
				  $uploadOk = 0;
				}
			}


			return $message;
		}

		public function getCategoriesSelect($tree, $r = 0, $p = null){
		    foreach ($tree as $i => $t) {
		        $dash = ($t['category_for'] == 0) ? '' : str_repeat('-', $r) .' ';
		        printf("\t<option value='%d'>%s%s</option>\n", $t['category_id'], $dash, $t['category_name']);
		       
		        if (isset($t['subcategories'])) {
		            $this->getCategoriesSelect($t['subcategories'], ++$r, $t['category_for']);
		            --$r;
		        }
		    }
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

		public function collectCategories($category = 0){
			// Build categories
			return $this->buildCategoriesRecursive($this->ucs_Database->getAllData('SELECT * FROM `ucs_categories`'), $category);
		}

		public function getItemsSuppliers(){
			// Build suppliers
			return $this->ucs_Database->getAllData('SELECT * FROM `ucs_suppliers` ORDER BY `ucs_suppliers`.`supplier_id` DESC');
		}

		public function getItemsLocation(){
			// Build locations
			return $this->ucs_Database->getAllData('SELECT * FROM `ucs_locations` ORDER BY `ucs_locations`.`location_id` DESC');
		}

		public function getCategoryItems($category, $page = 0, $max_show = 25){
			// Get locations 
			$locations = $this->getItemsLocation();
			// Get suppliers
			$suppliers = $this->getItemsSuppliers();
			// Get items
			$data = $this->ucs_Database->getAllData('SELECT * FROM `ucs_items` WHERE `item_category` = ' . $category);
			if($data != 0){
				foreach ($data as $datindex => &$datinfo) {
					// Append item location
					foreach ($locations as $locindex => $locinfo) {
						if($locinfo['location_id'] == $datinfo['item_location']){
							$datinfo['item_location'] = $locinfo;
						}
					}

					$item_suppliers = explode(';', $datinfo['item_suppliers']);

					$datinfo['item_suppliers'] = array();

					foreach ($item_suppliers as $supplier_id) {
						foreach ($suppliers as $supindex => $supinfo) {
							if($supplier_id == $supinfo['supplier_id']){
								array_push($datinfo['item_suppliers'], $supinfo);
							}
						}
					}

					$datinfo['item_data'] = json_decode($datinfo['item_data'], true);

				}
			}
			return $data;
		}
	}
?>