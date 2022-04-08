<?php
	/**
	 * uCrew Projects Class
	 */
	class uCrewProjects	{

		public $ucs_Database;
		public $ucp_mount;
		public $ucs_CommonDatabase;
		public $ucs_Directories;
		public $ucs_DirectoriesNames;
		public $ucs_DirectoriesTemplates;

		function __construct(){
			$this->ucs_Database = new uCrewDatabase('ucrew_projects');
			$this->ucs_CommonDatabase = new uCrewDatabase();
			$this->ucp_mount = 'uc_resources/projects/mount/';

			// Init default directories
			$typicalTemplate_imgae = array(
				'Фотографии',
				'Наклейки'
			);

			$this->ucs_DirectoriesNames = array(
				'develop_documentation' => 'Конструкторская документация',
				'mechanics' => 'Механические изделия'
			);

			$this->ucs_Directories = array(
				$this->ucs_DirectoriesNames['develop_documentation'] => array(
					'Проекты',
					'Устройства и модули',
					'Механические изделия',
					'Провода и кабели',
					'Печатные платы'
				), 
				'Программное обеспечение' => array(
					'Внешнее ПО',
					'Внутренее ПО',
				), 
				'Библиотеки' => array(
					'3D модели',
					'Для KiCad' => array(
						'Посадочные места',
						'Символы',
						'Готовые схемотехнические решения'
					),
					'Листы данных'
				)
			);

			$this->ucs_DirectoriesTemplates = array(
				// Проект -> Категория -> Подкатегория -> Ревизиия
				'projects' => array(
					'Механические изделия',
					'Печатные платы',
					'Провода и кабели',
					'Программное обеспечение',
					'Спецификации',
					'Изображения' => $typicalTemplate_imgae,
					'Устройства и модули',
					'Для производства' =>array(
						'Сборочная документация',
						'Аннотации'
					),
				),

				'modules' => array(
					'Механические изделия',
					'Печатные платы',
					'Провода и кабели',
					'Программное обеспечение',
					'Спецификации',
					'Изображения' => $typicalTemplate_imgae,
					'Устройства и модули',
					'Для производства' =>array(
						'Сборочная документация',
						'Аннотации'
					),
				),

				'mechanics' => array(
					'3D модели',
					'Чертежи',
					'Векторные файлы',
					'Изображения' => $typicalTemplate_imgae
				),

				'cables' => array(
					'Спецификация',
					'Чертёж',
					'Маркировка',
					'Изображения' => $typicalTemplate_imgae,
				),

				'pcbs' => array(
					'3D модель',
					'Исходники',
					'Спецификация',
					'Изображения' => $typicalTemplate_imgae,
					'Файлы для производства' => array(
						'Gerber',
						'Файлы позиций',
						'Сборочный чертёж'
					)
				)
			);


			$this->checkDirectories($this->ucs_Directories);
		}

		// Common projects data

		public function getProjectDirectoryData(){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'work_directory'";
			$work_directory = $this->ucs_Database->getAllData($sql)[0]['data_text'];

			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'work_directory_mask'";
			$work_directory_mask = $this->ucs_Database->getAllData($sql)[0]['data_text'];
			
			return [ "directory" => $work_directory, "mask" => $work_directory_mask];
		}

		public function deleteDirectory($dir){
	    	if (!file_exists($dir)) {
	        	return true;
	    	}

	    	if (!is_dir($dir)) {
	        	return unlink($dir);
	    	}

	    	foreach (scandir($dir) as $item) {
	        	if ($item == '.' || $item == '..') {
	            	continue;
	        	}

	        	if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
	            	return false;
	       		}
	   
			}
		}


		// Mechanics data
		public function addMechanic($data, $files){
			// Init data array
			$mechanic_data = array(
				"fullname" => "",
				"files" => array(
					"3dstl" => 0,
					"draw" => 0,
					"vector" => 0,
					"other" => array()
				 ),
				"material" => "",
				"projects" => array()
			);
			$mechanic_dir_name = 'Механические изделия';
			$image_dir = '/Изображениe/';
			$model_dir = '/3D модель/';
			$draw_dir = '/Чертёж/';
			$documenation_dir = '/Документация/';
			$source_dir = '/Исходные файлы/';
			$vector_dir = '/Векторные файлы/';
			// Init state
			$state = true;
			// Set upload directory
			$upload_directory = $this->getProjectDirectoryData()['directory'] . $mechanic_dir_name . '/' . $data['mechanic_fullname'];

			$mechanic_data['fullname'] = $data['mechanic_fullname'];
			// Check if directory exists
			if(file_exists($upload_directory)){
				$this->deleteDirectory($upload_directory);
			}else{
				$state = false;
			}
			// Create main directory
			mkdir($upload_directory, 0777, true);
			// Add image directory
			mkdir($upload_directory . $image_dir, 0777, true);
			// Copy image file
			$image_name = $image_dir . 'Изображение ' . $data['mechanic_fullname'] . '.jpeg';

			$image_mount = $this->ucp_mount . $mechanic_dir_name . '/' . $data['mechanic_fullname'] . $image_dir . 'Изображение ' . $data['mechanic_fullname'] . '.jpeg';

			move_uploaded_file($files["mechanic_image"]["tmp_name"], $upload_directory . $image_name);
			// Add documentation dir
			mkdir($upload_directory . $documenation_dir, 0777, true);
			$description_file = fopen($upload_directory . $documenation_dir . 'Описание ' . $data['mechanic_fullname'] . '.txt', "w");
			fwrite($description_file, $data['mechanic_description']);
			fclose($description_file);
			// Create 3d dir
			mkdir($upload_directory . $model_dir, 0777, true);
			// Copy 3d file
			$model_name = $model_dir . '3D модель ' . $data['mechanic_fullname'] . '.step';
			move_uploaded_file($files["mechanic_3dstep"]["tmp_name"], $upload_directory . $model_name);
			// Create source dir
			mkdir($upload_directory . $source_dir, 0777, true);
			// Copy source file
			$source_name = $source_dir . '3D модель ' . $data['mechanic_fullname'] . '.m3d';
			move_uploaded_file($files["mechanic_3dsource"]["tmp_name"], $upload_directory . $source_name);

			if( $files['mechanic_drawpdf']['tmp_name'] != "" ){
				mkdir($upload_directory . $draw_dir, 0777, true);
				
				$drawpdf_name = $draw_dir . 'Чертёж ' . $data['mechanic_fullname'] . '.pdf';
				move_uploaded_file($files["mechanic_drawpdf"]["tmp_name"], $upload_directory . $drawpdf_name);


				$drawsource_name = $source_dir . 'Чертёж ' . $data['mechanic_fullname'] . '.cdw';
				move_uploaded_file($files["mechanic_drawsource"]["tmp_name"], $upload_directory . $drawsource_name);

				$mechanic_data['draw'] = 1;
			}

			if( $files['mechanic_drawlaser']['tmp_name'] != "" ){
				mkdir($upload_directory . $vector_dir, 0777, true);

				$drawvector_name = $vector_dir . 'Векторный файл ' . $data['mechanic_fullname'] . '.dxf';
				move_uploaded_file($files["mechanic_drawlaser"]["tmp_name"], $upload_directory . $drawvector_name);

				$mechanic_data['vector'] = 1;
			}

			$mechanic_data['material'] = $data['mechanic_material'];

			$sql = "INSERT INTO `ucp_mechanics` (`mechanic_id`, `mechanic_name`, `mechanic_description`, `mechanic_codename`, `mechanic_author_id`, `mechanic_create_timestamp`, `mechanic_status`, `mechanic_image`, `mechanic_data`, `mechanic_activation`) VALUES (NULL, '".$data['mechanic_name']."', '".$data['mechanic_description']."', '".$data['mechanic_codename']."', '".$_SESSION['user_id']."', CURRENT_TIMESTAMP, '".$data['mechanic_status']."', '".$image_mount."', '".json_encode($mechanic_data, JSON_UNESCAPED_UNICODE)."', '1')";


			// Add data
			$this->ucs_Database->query($sql);
			// Get codename
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'mechanics_codename'";
			$data = $this->ucs_Database->getAllData($sql)[0]['data_value'];
			$data = $data + 1;
			$sql = "UPDATE `ucp_data` SET `data_value` = '".$data."' WHERE `data_name` = 'mechanics_codename'";
			$this->ucs_Database->query($sql);
		}

		// Cables data
		public function addCable($data, $files){
			// Init data array
			$cable_data = array(
				"fullname" => "",
				"connectors" => array(),
				"wires" => array(),
				"files" => array(
					"other" => array()
				 ),
				"projects" => array()
			);
			$cable_dir_name = 'Кабели и провода';
			$image_dir = '/Изображениe/';
			$draw_dir = '/Чертёж/';
			$documenation_dir = '/Документация/';
			$source_dir = '/Исходные файлы/';
			// Init state
			$state = true;
			// Set upload directory
			$upload_directory = $this->getProjectDirectoryData()['directory'] . $cable_dir_name . '/' . $data['cable_fullname'];
			$cable_data['fullname'] = $data['cable_fullname'];
			// Check if directory exists
			if(file_exists($upload_directory)){
				$this->deleteDirectory($upload_directory);
			}else{
				$state = false;
			}
			// Create main directory
			mkdir($upload_directory, 0777, true);
			// Add image directory
			mkdir($upload_directory . $image_dir, 0777, true);
			// Copy image file
			$image_name = $image_dir . 'Изображение ' . $data['cable_fullname'] . '.jpeg';

			$image_mount = $this->ucp_mount . $cable_dir_name . '/' . $data['cable_fullname'] . $image_dir . 'Изображение ' . $data['cable_fullname'] . '.jpeg';

			move_uploaded_file($files["cable_image"]["tmp_name"], $upload_directory . $image_name);
			// Add documentation dir
			mkdir($upload_directory . $documenation_dir, 0777, true);
			$description_file = fopen($upload_directory . $documenation_dir . 'Описание ' . $data['cable_fullname'] . '.txt', "w");
			fwrite($description_file, $data['cable_description']);
			fclose($description_file);
			// Create 3d dir
			mkdir($upload_directory . $draw_dir, 0777, true);
			// Copy 3d file
			$model_name = $draw_dir . 'Чертёж ' . $data['cable_fullname'] . '.cdw';
			move_uploaded_file($files["cable_drawpdf"]["tmp_name"], $upload_directory . $model_name);
			// Create source dir
			mkdir($upload_directory . $source_dir, 0777, true);
			// Copy source file
			$source_name = $source_dir . 'Чертёж '. $data['cable_fullname'] . '.cdw';
			move_uploaded_file($files["cable_drawsource"]["tmp_name"], $upload_directory . $source_name);

			$sql = "INSERT INTO `ucp_cables` (`cable_id`, `cable_name`, `cable_description`, `cable_codename`, `cable_author_id`, `cable_create_timestamp`, `cable_status`, `cable_image`, `cable_data`, `cable_activation`) VALUES (NULL, '".$data['cable_name']."', '".$data['cable_description']."', '".$data['cable_codename']."', '".$_SESSION['user_id']."', CURRENT_TIMESTAMP, '".$data['cable_status']."', '".$image_mount."', '".json_encode($cable_data, JSON_UNESCAPED_UNICODE)."', '1')";

			// Add data
			$this->ucs_Database->query($sql);
			// Get codename
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'cables_codename'";
			$data = $this->ucs_Database->getAllData($sql)[0]['data_value'];
			$data = $data + 1;
			$sql = "UPDATE `ucp_data` SET `data_value` = '".$data."' WHERE `data_name` = 'cables_codename'";
			$this->ucs_Database->query($sql);
		}

		// Get mechanic materials
		public function getMechanicMaterials(){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'mechanics_materials'";
			$materials = $this->ucs_Database->getAllData($sql)[0]['data_text'];
			return json_decode($materials, true)['materials'];
		}


		public function getPager($page, $count, $table, $url){
			$sql = "SELECT COUNT(*) FROM `".$table."`";
			$data = $this->ucs_Database->getAllData($sql)[0]['COUNT(*)'];
			$number = $data / $count;

			$total_pages = floor($number);     
			$fraction = $number - $total_pages;

			if($total_pages == 0 AND $fraction == 0){
				return "";
			}

			if($fraction > 0){
				$total_pages++;
			}

			$pages = '';

			for ($i = 1; $i < $total_pages + 1; $i++) { 
				$class = '';

				if($page == $i){
					$class = 'active';
				}

				$pages .= '<li class="page-item '.$class.'"><a class="page-link" href="'.$url.'&p='.$i.'&c='.$count.'">'.$i.'</a></li>';
			}

			return '
			<nav aria-label="Page navigation example">
			  <ul class="pagination justify-content-end">
			    <li class="page-item">
			      <a class="page-link" href="'.$url.'&p=1&c='.$count.'" aria-label="Начало">
			        <span aria-hidden="true">&laquo;</span>
			      </a>
			    </li>
			   	'.$pages.'
			    <li class="page-item">
			      <a class="page-link" href="'.$url.'&p='.$total_pages.'&c='.$count.'" aria-label="Конец">
			        <span aria-hidden="true">&raquo;</span>
			      </a>
			    </li>
			  </ul>
			</nav>
			';
		}

		public function getMechanicsList($page, $count){
			
			$start = ($page * $count) - $count;
			$end = $count;

			$sql = "SELECT * FROM `ucp_mechanics` ORDER BY `ucp_mechanics`.`mechanic_codename` DESC LIMIT $start,$end";
			$list = $this->ucs_Database->getAllData($sql);
			
			if($list != 0){
				foreach ($list as $key => &$value) {
					$value['mechanic_data'] = json_decode($value['mechanic_data'], true);
				}
			}
			return $list;
		}

		public function getCablesList($page, $count){
			
			$start = ($page * $count) - $count;
			$end = $count;

			$sql = "SELECT * FROM `ucp_cables` ORDER BY `ucp_cables`.`cable_codename` DESC LIMIT $start,$end";
			$list = $this->ucs_Database->getAllData($sql);
			
			if($list != 0){
				foreach ($list as $key => &$value) {
					$value['cable_data'] = json_decode($value['cable_data'], true);
				}
			}
			return $list;
		}

		public function getPcbsList($page, $count){
			
			$start = ($page * $count) - $count;
			$end = $count;

			$sql = "SELECT * FROM `ucp_pcbs` ORDER BY `ucp_pcbs`.`pcb_id` DESC LIMIT $start,$end";
			$list = $this->ucs_Database->getAllData($sql);
			
			if($list != 0){
				foreach ($list as $key => &$value) {
					$value['cable_data'] = json_decode($value['pcb_data'], true);
				}
			}
			return $list;
		}

		public function getMechanicItem($item_id){
			$sql = "SELECT * FROM `ucp_mechanics` WHERE `mechanic_id` = $item_id";
			$data = $this->ucs_Database->getData($sql);
			$data['mechanic_data'] = json_decode($data['mechanic_data'], true);
			return $data;
		}

		public function getLastCodeName($codename, $sufix){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = '$codename'";
			$data = $this->ucs_Database->getAllData($sql)[0]['data_value'];
			
			if($data >= 0 and $data < 10 ){
				$data = $sufix . '000' . $data;
			}
			if($data >= 10 and $data < 100 ){
				$data = $sufix . '00' . $data;
			}
			if($data >= 100 and $data < 1000){
				$data = $sufix . '0' . $data;
			}
			if($data >= 1000 and $data < 10000){
				$data = $sufix . '00' . $data;
			}
			if($data >= 10000 and $data < 100000){
				$data = $sufix . '000' . $data;
			}
			return $data;
		}

		public function getStatuses(){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'mechanics_statuses'";
			return json_decode($this->ucs_Database->getData($sql)['data_text'], true);
		}

		public function createDirectorySpecial($directory){
			if(!file_exists($directory)){
				mkdir($directory, 0777, true);
				return true;
			}
			return false;
		}

		public function checkDirectories($dirdata){
			// Directories watchdog
			$work_directory = $this->getProjectDirectoryData()['directory'];
			// Get all base directories
			foreach ($dirdata as $subdirectory => $directories) {
				$base_dir = $work_directory . $subdirectory . '/';
				foreach ($directories as $directory => $subdirs) {
					if(is_array($subdirs)){
						$this->createDirectorySpecial($base_dir . $directory);
						foreach ($subdirs as $sd => $sdv) {
							$this->createDirectorySpecial($base_dir . $directory . '/' . $sdv);
						}
					}else{
						$this->createDirectorySpecial( $base_dir . $subdirs);
					}
				}
			}
		}

	}
?>