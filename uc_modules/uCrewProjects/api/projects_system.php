<?php
	/**
	 * uCrew Projects Class
	 */
	class uCrewProjects	extends uCrewConfiguration{

		public $ucs_Database;
		public $ucp_mount;
		public $ucs_CommonDatabase;
		public $ucs_Directories;
		public $ucs_DirectoriesNames;
		public $ucs_DirectoriesTemplates;
		public $ucs_DirectoriesPath;

		function __construct(){
			$this->ucs_Database = new uCrewDatabase('ucrew_projects');
			$this->ucs_CommonDatabase = new uCrewDatabase();
			$this->ucp_mount = 'uc_resources/projects/mount/';

			// Init default directories
	
			$this->ucs_DirectoriesNames = array(
				'develop_documentation' => 'Конструкторская документация',
				'mechanics' => 'Механические изделия',
				'images' => 'Изображения',
				'3dmodels' => '3D модели',
				'drawings' => 'Чертежи',
				'vectors' => 'Векторные файлы',
				'photos' => 'Фотографии',
				'marks' => 'Маркировка и наклейки',
				'annotations' => 'Аннотации'
			);

			$typicalTemplate_imgae = array(
				$this->ucs_DirectoriesNames['photos'], 
				$this->ucs_DirectoriesNames['marks']
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
					$this->ucs_DirectoriesNames['3dmodels'],
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
					$this->ucs_DirectoriesNames['images'] => $typicalTemplate_imgae,
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
					$this->ucs_DirectoriesNames['images'] => $typicalTemplate_imgae,
					'Устройства и модули',
					'Для производства' =>array(
						'Сборочная документация',
						'Аннотации'
					),
				),

				'mechanics' => array(
					$this->ucs_DirectoriesNames['3dmodels'],
					$this->ucs_DirectoriesNames['drawings'],
					$this->ucs_DirectoriesNames['vectors'],
					$this->ucs_DirectoriesNames['images'] => $typicalTemplate_imgae
				),

				'cables' => array(
					'Спецификация',
					'Чертёж',
					'Маркировка',
					$this->ucs_DirectoriesNames['images'] => $typicalTemplate_imgae,
				),

				'pcbs' => array(
					$this->ucs_DirectoriesNames['3dmodels'],
					'Исходники',
					'Спецификация',
					$this->ucs_DirectoriesNames['images'] => $typicalTemplate_imgae,
					'Файлы для производства' => array(
						'Gerber',
						'Файлы позиций',
						'Сборочный чертёж'
					)
				)
			);

			// Directories watchdog
			$this->checkDirectories($this->ucs_Directories);

			$directory_data = $this->getProjectDirectoryData();

			// Make directories path
			$this->ucs_DirectoriesPath = array(
				'mechanics' => array(
					'local' => $directory_data['directory'] .  $this->ucs_DirectoriesNames['develop_documentation'] . '/' . $this->ucs_DirectoriesNames['mechanics'] . '/',
					'smb' => $directory_data['mask'] . $this->ucs_DirectoriesNames['develop_documentation'] . '\\' . $this->ucs_DirectoriesNames['mechanics'] . '\\',
					'web' => 'http://' . $this->system['main_domain'] . '/uc_resources/projects/mount/' . $this->ucs_DirectoriesNames['develop_documentation'] . '/' . $this->ucs_DirectoriesNames['mechanics'] . '/',
				)
			); 
			///uc_resources/projects/mount/
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
		public function generateFolderTree($dirdata, $mask){
			
			foreach ($dirdata as $index => $folder) {
				print_r($folder);
			}
			
		}

		public function directoryToArray($dir){
		     $result = array();
			 $cdir = scandir($dir);

			 foreach ($cdir as $key => $value)
			 {
			    if (!in_array($value,array(".",".."))){
			        if (is_dir($dir . DIRECTORY_SEPARATOR . $value)){
			        	$result[$value] = $this->directoryToArray($dir . DIRECTORY_SEPARATOR . $value);
			        }else{
			        	$result[] = $value;
			        }
			    }
			}

			return $result;
		}

		// Mechanics data
		public function addMechanic($data, $files){
			// Init json
			$mechanic_data = array(
				"fullname" => "",
				"material" => "",
				"projects" => array(),
				"changes" => array()
			);
			// Get upload directory
			$upload_directory = 
			$this->getProjectDirectoryData()['directory'] . 
			$this->ucs_DirectoriesNames['develop_documentation'] . '/' .
			$this->ucs_DirectoriesNames['mechanics'] . '/' . $data['mechanic_fullname'] . '/';
			
			// If directory exists, remove
			if(file_exists($upload_directory)){
				$this->deleteDirectory($upload_directory);
			}else{
				$state = false;
			}

			// Create new directory
			$this->createDirectorySpecial($upload_directory);

			// Write description
			$description_file = fopen($upload_directory . 'Описание ' . $data['mechanic_fullname'] . '.txt', "w");
			fwrite($description_file, $data['mechanic_description']);
			fclose($description_file);

			// Move image
			$f_name = 'Изображение ' . $data['mechanic_fullname'] . '.jpeg';
			$f_dir = $upload_directory . $this->ucs_DirectoriesNames['images'] . '/';
			$f_mount =  $f_dir . $f_name;
			$this->createDirectorySpecial($f_dir);
			move_uploaded_file($files["mechanic_image"]["tmp_name"], $f_mount);

			// Move 3D model source (*.a3d)
			$f_name = 'Исходник ' . $data['mechanic_fullname'] . '.m3d';
			$f_dir = $upload_directory . $this->ucs_DirectoriesNames['3dmodels'] . '/';
			$f_mount =  $f_dir . $f_name;
			$this->createDirectorySpecial($f_dir);
			move_uploaded_file($files["mechanic_3dsource"]["tmp_name"], $f_mount);

			// Move 3D model (*.step)
			$f_name = '3D модель ' . $data['mechanic_fullname'] . '.step';
			$f_dir = $upload_directory . $this->ucs_DirectoriesNames['3dmodels'] . '/';
			$f_mount =  $f_dir . $f_name;
			$this->createDirectorySpecial($f_dir);
			move_uploaded_file($files["mechanic_3dstep"]["tmp_name"], $f_mount);
			
			// Move 3D model (*.stl)
			if( $files["mechanic_3dstl"]["tmp_name"] != '' ){
				$f_name = 'Для печати ' . $data['mechanic_fullname'] . '.stl';
				$f_dir = $upload_directory . $this->ucs_DirectoriesNames['3dmodels'] . '/';
				$f_mount =  $f_dir . $f_name;
				$this->createDirectorySpecial($f_dir);
				move_uploaded_file($files["mechanic_3dstl"]["tmp_name"], $f_mount);
			}

			// Move drawings source if isset (*.cdw)
			if( $files["mechanic_drawsource"]["tmp_name"] != '' ){
				$f_name = 'Исходник ' . $data['mechanic_fullname'] . '.cdw';
				$f_dir = $upload_directory . $this->ucs_DirectoriesNames['drawings'] . '/';
				$f_mount =  $f_dir . $f_name;
				$this->createDirectorySpecial($f_dir);
				move_uploaded_file($files["mechanic_drawsource"]["tmp_name"], $f_mount);
			}

			// Move drawings if isset (*.pdf)
			if( $files["mechanic_drawpdf"]["tmp_name"] != '' ){
				$f_name = 'Чертёж ' . $data['mechanic_fullname'] . '.pdf';
				$f_dir = $upload_directory . $this->ucs_DirectoriesNames['drawings'] . '/';
				$f_mount =  $f_dir . $f_name;
				$this->createDirectorySpecial($f_dir);
				move_uploaded_file($files["mechanic_drawpdf"]["tmp_name"], $f_mount);
			}

			// Move vector if isset (*.dxf)
			if( $files["mechanic_drawlaser"]["tmp_name"] != '' ){
				$f_name = 'Векторный файл ' . $data['mechanic_fullname'] . '.dxf';
				$f_dir = $upload_directory . $this->ucs_DirectoriesNames['vectors'] . '/';
				$f_mount =  $f_dir . $f_name;
				$this->createDirectorySpecial($f_dir);
				move_uploaded_file($files["mechanic_drawlaser"]["tmp_name"], $f_mount);
			}

			// Move images
			if( $files["mechanic_photos"]["tmp_name"][0] != '' ){
				for($i = 0; $i < count($files["mechanic_photos"]["tmp_name"]); $i++) { 
					$f_name = $files["mechanic_photos"]["name"][$i];
					$f_dir = $upload_directory . $this->ucs_DirectoriesNames['images'] . '/' . $this->ucs_DirectoriesNames['photos'] . '/';
					$f_mount =  $f_dir . $f_name;
					$this->createDirectorySpecial($f_dir);
					move_uploaded_file($files["mechanic_photos"]["tmp_name"][$i], $f_mount);
				}
			}

			// Move marks
			if( $files["mechanic_marks"]["tmp_name"][0] != '' ){
				for($i = 0; $i < count($files["mechanic_marks"]["tmp_name"]); $i++) { 
					$f_name = $files["mechanic_marks"]["name"][$i];
					$f_dir = $upload_directory . $this->ucs_DirectoriesNames['images'] . '/' . $this->ucs_DirectoriesNames['marks'] . '/';
					$f_mount =  $f_dir . $f_name;
					$this->createDirectorySpecial($f_dir);
					move_uploaded_file($files["mechanic_marks"]["tmp_name"][$i], $f_mount);
				}
			}

			// Move annotations
			if( $files["mechanic_annotations"]["tmp_name"][0] != '' ){
				for($i = 0; $i < count($files["mechanic_annotations"]["tmp_name"]); $i++) { 
					$f_name = $files["mechanic_annotations"]["name"][$i];
					$f_dir = $upload_directory . $this->ucs_DirectoriesNames['annotations'] . '/';
					$f_mount =  $f_dir . $f_name;
					$this->createDirectorySpecial($f_dir);
					move_uploaded_file($files["mechanic_annotations"]["tmp_name"][$i], $f_mount);
				}
			}

			// Set data
			$mechanic_data['material'] = $data['mechanic_material'];
			$mechanic_data['fullname'] =  $data['mechanic_fullname'];

			// Create query
			$sql = "INSERT INTO `ucp_mechanics` (`mechanic_id`, `mechanic_name`, `mechanic_description`, `mechanic_codename`, `mechanic_author_id`, `mechanic_create_timestamp`, `mechanic_status`, `mechanic_image`, `mechanic_data`, `mechanic_activation`) VALUES (NULL, '".$data['mechanic_name']."', '".$data['mechanic_description']."', '".$data['mechanic_codename']."', '".$_SESSION['user_id']."', CURRENT_TIMESTAMP, '".$data['mechanic_status']."', '', '".json_encode($mechanic_data, JSON_UNESCAPED_UNICODE)."', '1')";
			
			// Add data
			$this->ucs_Database->query($sql);

			// Change codename if auto
			if($data['mechanic_codename_state'] == 'auto'){
				// Get codename
				$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'mechanics_codename'";
				$data = $this->ucs_Database->getAllData($sql)[0]['data_value'];
				// Update codename
				$data = $data + 1;
				$sql = "UPDATE `ucp_data` SET `data_value` = '".$data."' WHERE `data_name` = 'mechanics_codename'";
				$this->ucs_Database->query($sql);
			}
		}

		// Cables data
		public function addCable($data, $files){
			print_r($data);
			print_r($files);
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