<?php
	/**
	 * uCrew Storage Class
	 */
	class uCrewStorage {
		private $ucs_Database ;
		public $categories_options;

		function __construct(){
			$this->ucs_Database = new uCrewDatabase('ucrew_storage');
			$this->categories_options = "";
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

			$message = '<div class="row mb-3">
				<div class="alert alert-danger" role="alert">
			  		<h4 class="alert-heading">Категория не добавлена</h4>
			  		<p>Ошибка, категория «'. $name .'» добавлена</p>
				</div>
			</div>';

			if($uploadOk == 1){
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_new)) {
				  $uploadOk = 1;
				  $message = '<div class="row mb-3">
				 <div class="alert alert-success" role="alert">
			  		<h4 class="alert-heading">Категория успешно добавлена</h4>
			  		<p>Категория «'. $name .'» добавлена</p>
					</div>
				</div>';
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

		public function getCategoriesSelectText($tree, $r = 0, $p = null){
		    foreach ($tree as $i => $t) {
		    	$buffer = "";
		        $dash = ($t['category_for'] == 0) ? '' : str_repeat('-', $r) .' ';
		        
		       $this->categories_options .= sprintf("\t<option value='%d'>%s%s</option>\n", $t['category_id'], $dash, $t['category_name']);

		        if (isset($t['subcategories'])) {
		            $this->getCategoriesSelectText($t['subcategories'], ++$r, $t['category_for']);
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

		public function getItem($item_id){
			// Build locations
			$data = $this->ucs_Database->getAllData('SELECT * FROM `ucs_items` WHERE `item_id` = ' . $item_id)[0];
			$data['item_data'] = json_decode($data['item_data'], true);
			$data['item_location'] = $this->appendItemLocation($data['item_location']);
			$data['item_suppliers'] = $this->appendItemSuppliers($data['item_suppliers']);
			return $data;
		}

		public function releaseItem($item, $number, $current_count, $comment){
			$query = "INSERT INTO `usc_releases` (`release_id`, `release_item`, `release_count`, `release_timestamp`, `release_user`, `release_comment`) VALUES (NULL, '$item', '$count', CURRENT_TIMESTAMP, '".$_SESSION['user_id']."', '$comment')";
			$this->ucs_Database->query($query);
			$count = $current_count - $number;
			$query = "UPDATE `ucs_items` SET `item_count` = '$count' WHERE `ucs_items`.`item_id` = $item";
			$this->ucs_Database->query($query);
		}

		public function appendItemLocation($location)
		{
			$data = "";
			$locations = $this->getItemsLocation();
			foreach ($locations as $locindex => $locinfo) {
				if($locinfo['location_id'] == $location){
					$data = $locinfo;
				}
			}
			return $data;
		}

		public function appendItemSuppliers($suppliers_data)
		{
			$suppliers = $this->getItemsSuppliers();

			$item_suppliers = explode(';', $suppliers_data);

			$suppliers_data = array();

			foreach ($item_suppliers as $supplier_id) {
				foreach ($suppliers as $supindex => $supinfo) {
					if($supplier_id == $supinfo['supplier_id']){
						array_push($suppliers_data, $supinfo);
					}
				}
			}

			return $suppliers_data;
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

		// Get category templates
		public function getCategoryTemplate($category_id){
			$template = $this->ucs_Database->getData('SELECT * FROM `ucs_templates` WHERE `template_category` = ' . $category_id);
			if($template != 0){
				$template['template_data'] = json_decode($template['template_data'], true);
			}
			return $template;
		}

		public function templateEditorBuilder($data){
			// Complete text buffer
			$complete = "";
			// Index counter
			$index = 1;
			$this->getCategoriesSelectText($this->collectCategories());
			$js = 'categories = "' . trim(preg_replace('/\s+/', ' ', $this->categories_options)). '";';
			// Check template data
			foreach ($data['template_data'] as $name => $type) {
				$list = "";
				$category = "";
				$text = "";
				// Check type
				if(isset($type['list'])){
					$list = "selected";
				}
				if(isset($type['category'])){
					$category = "selected";
				}
				if($type['type'] == "text"){
					$text = "selected";
				}
				$complete .= '
						<!-- START -->
						<div class="input-group mb-3" id="group'.$index.'">	
							<label for="field'.$index.'[name]" class="col-sm-2 col-form-label">Параметр '.$index.':</label>
							<div class="col-sm-10" style="padding-bottom: 10px">
								<div class="input-group" id="field'.$index.'-div">
									<span class="input-group-text">Название / Тип</span>
									<input type="text" aria-label="field'.$index.'[name]" name="field'.$index.'[name]" id="field'.$index.'[name]" class="form-control" placeholder="Введите название параметра..." value="'.$name.'">
									<select class="form-control" name="field'.$index.'[type]" id="field'.$index.'[type]" >
										<option value="unknown" selected>Выберите тип...</option>
										<option value="text" '.$text.'>Текст</option>
										<option value="select_list" '.$list.'>Произвольный список</option>
										<option value="select_category" '.$category.'>Позиции из категории</option>
									</select>
								</div>
							</div>' . "\n";

				switch ($type['type']) {

					case 'select':

						if(isset($type['list'])){
							$values = "";
							foreach ($type['list'] as $key => $value) {
								$values .= $value . ';';
							}
							$complete .= '
								<label for="field'.$index.'[values]" id="field'.$index.'-value-lable"  class="col-sm-2 col-form-label">Значения:</label>
								<div class="col-sm-10" id="field'.$index.'-values">
								   	<input type="text" aria-label="field'.$index.'-value" name="field'.$index.'[values]" id="field'.$index.'[value]" class="form-control" placeholder="Введите значения" value="'.$values.'">
								</div>
								' . "\n";
						}


						if(isset($type['category'])){
							$complete .= '
								<label for="field'.$index.'[value]" id="field'.$index.'-value-lable"  class="col-sm-2 col-form-label">Категория:</label>
								<div class="col-sm-10" id="field'.$index.'-values">
									
									<select class="form-control" name="field'.$index.'[value]" id="field'.$index.'[value]" >
										<option value="0" selected>Выберите категорию...</option>
										'.$this->categories_options.'
									</select>	   	

								</div>
								' . "\n";
						}

						break;
					default:
							
						break;
				}

				$complete .= '
							<div class="col-sm-12 float-end" style="padding-top: 10px">
								<div class="col float-end">
									<button type="button" class="btn btn-danger" onClick="removeElement(\'group'.$index.'\')">Удалить</button>
								</div>
							</div>
						</div>
						<hr id="group'.$index.'hr">
	     				<!-- END -->' . "\n";
	     		
	     		$this->getCategoriesSelectText($this->collectCategories());

	     		$js .= '
			    $(\'select[name="field'.$index.'[type]"]\').on(\'change\', function() {
			        value = $(\'select[name="field'.$index.'[type]"]\').val();
			        if(value == \'text\'){
			        	$(\'#field'.$index.'-value-lable\').fadeOut(100, function(){ $(\'#field'.$index.'-value-lable\').empty(); });
			        	$(\'#field'.$index.'-values\').fadeOut(100, function(){ $(\'#field'.$index.'-values\').empty(); });
			        }
			        if(value == \'select_list\'){
			        	$(\'#field'.$index.'-value-lable\').fadeOut(100, function(){ $(\'#field'.$index.'-value-lable\').empty(); });
			        	$(\'#field'.$index.'-values\').fadeOut(100, function(){ $(\'#field'.$index.'-values\').empty(); });

			        	$(\'#field'.$index.'-value-lable\').fadeIn(100, function(){ $(\'#field'.$index.'-value-lable\').append(\'Значения:\'); });
			        	$(\'#field'.$index.'-values\').fadeIn(100, function(){ $(\'#field'.$index.'-values\').append(\'	<input type="text" aria-label="field'.$index.'-value" name="field'.$index.'[values]" id="field'.$index.'[value]" class="form-control" placeholder="Введите значения" value="'.$values.'">\'); });
			        }
			        if(value == \'select_category\'){
			        	$(\'#field'.$index.'-value-lable\').fadeOut(100, function(){ $(\'#field'.$index.'-value-lable\').empty(); });
			        	$(\'#field'.$index.'-values\').fadeOut(100, function(){ $(\'#field'.$index.'-values\').empty(); });

			        	$(\'#field'.$index.'-value-lable\').fadeIn(100, function(){ $(\'#field'.$index.'-value-lable\').append(\'Категория:\'); });
			        	$(\'#field'.$index.'-values\').fadeIn(100, function(){ $(\'#field'.$index.'-values\').append(\' <select class="form-control" name="field'.$index.'[value]" id="field'.$index.'[value]" ><option value="0" selected>Выберите категорию...</option>\' + categories + \'</select>	 \'); });
			        }
			    });
	     		' . "\n";

				$index = $index + 1;
			}
			$complete .= '
						<script type="text/javascript">
							lastField = '.$index.';
						</script>
			';
			return ["complete" => $complete, "js" => $js];
		}
	}
?>