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
		public $uc_SystemPipe;

		function __construct(){
			$this->ucs_Database = new uCrewDatabase('ucrew_projects');
			$this->ucs_CommonDatabase = new uCrewDatabase();
			$this->ucp_mount = 'uc_resources/projects/mount/';
			$this->uc_SystemPipe = new uCrewSystemPipe();
			$this->directory_data = $this->getProjectDirectoryData();

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
				'annotations' => 'Аннотации',
				'cables' => 'Провода и кабели',
				'pcbs' => 'Печатные платы',
				'sources' => 'Исходники',
				'devices' => 'Устройства'
			);

			$typicalTemplate_imgae = array(
				$this->ucs_DirectoriesNames['photos'], 
				$this->ucs_DirectoriesNames['marks']
			);

			$this->ucs_Directories = array(
				$this->ucs_DirectoriesNames['develop_documentation'] => array(
					'Проекты',
					'Устройства и модули',
					$this->ucs_DirectoriesNames['mechanics'],
					$this->ucs_DirectoriesNames['cables'],
					$this->ucs_DirectoriesNames['pcbs']
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
					$this->ucs_DirectoriesNames['mechanics'],
					$this->ucs_DirectoriesNames['pcbs'],
					$this->ucs_DirectoriesNames['cables'],
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
					$this->ucs_DirectoriesNames['mechanics'],
					$this->ucs_DirectoriesNames['pcbs'],
					$this->ucs_DirectoriesNames['cables'],
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
					'Чертёж',
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

			// Make directories path
			$this->ucs_DirectoriesPath = array(
				'mechanics' => array(
					'local' => $this->directory_data['directory'] .  $this->ucs_DirectoriesNames['develop_documentation'] . '/' . $this->ucs_DirectoriesNames['mechanics'] . '/',
					'smb' => $this->directory_data['mask'] . $this->ucs_DirectoriesNames['develop_documentation'] . '\\' . $this->ucs_DirectoriesNames['mechanics'] . '\\',
					'web' => 'http://' . $this->system['main_domain'] . '/uc_resources/projects/mount/' . $this->ucs_DirectoriesNames['develop_documentation'] . '/' . $this->ucs_DirectoriesNames['mechanics'] . '/',
				),
				'cables' => array(
					'local' => $this->directory_data['directory'] .  $this->ucs_DirectoriesNames['develop_documentation'] . '/' . $this->ucs_DirectoriesNames['cables'] . '/',
					'smb' => $this->directory_data['mask'] . $this->ucs_DirectoriesNames['develop_documentation'] . '\\' . $this->ucs_DirectoriesNames['cables'] . '\\',
					'web' => 'http://' . $this->system['main_domain'] . '/uc_resources/projects/mount/' . $this->ucs_DirectoriesNames['develop_documentation'] . '/' . $this->ucs_DirectoriesNames['cables'] . '/',
				),
				'pcbs' => array(
					'local' => $this->directory_data['directory'] .  $this->ucs_DirectoriesNames['develop_documentation'] . '/' . $this->ucs_DirectoriesNames['pcbs'] . '/',
					'smb' => $this->directory_data['mask'] . $this->ucs_DirectoriesNames['develop_documentation'] . '\\' . $this->ucs_DirectoriesNames['pcbs'] . '\\',
					'web' => 'http://' . $this->system['main_domain'] . '/uc_resources/projects/mount/' . $this->ucs_DirectoriesNames['develop_documentation'] . '/' . $this->ucs_DirectoriesNames['pcbs'] . '/',
				),
				'devices' => array(
					'local' => $this->directory_data['directory'] .  $this->ucs_DirectoriesNames['develop_documentation'] . '/' . $this->ucs_DirectoriesNames['devices'] . '/',
					'smb' => $this->directory_data['mask'] . $this->ucs_DirectoriesNames['develop_documentation'] . '\\' . $this->ucs_DirectoriesNames['devices'] . '\\',
					'web' => 'http://' . $this->system['main_domain'] . '/uc_resources/projects/mount/' . $this->ucs_DirectoriesNames['develop_documentation'] . '/' . $this->ucs_DirectoriesNames['devices'] . '/',
				)
			); 
			///uc_resources/projects/mount/
		}

		// Common projects data

		public function getCommonData($prefix, $id){

			$data = $this->getCommonItem("${prefix}", $_GET['id']);

			return array(
				"data" => $data,
				"user_name" => $this->ucs_CommonDatabase->getUser($data["${prefix}_author_id"])["user_name"],
				"date" => date_format(date_create($data["${prefix}_create_timestamp"]),"d.m.Y в H:i"),
				"directory_data" => $this->getProjectDirectoryData(),
				"statuses" => $this->getStatuses()
			);

		}

		public function getProjectDirectoryData(){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'work_directory'";
			$work_directory = $this->ucs_Database->getAllData($sql)[0]['data_text'];

			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'work_directory_mask'";
			$work_directory_mask = $this->ucs_Database->getAllData($sql)[0]['data_text'];
			
			return [ "directory" => $work_directory, "mask" => $work_directory_mask];
		}

		// Mechanics data
		public function generateFolderTree($dirdata, $mask){
			
			foreach ($dirdata as $index => $folder) {
				//print_r($folder);
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

		// Add mechanics data
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
			
			// Prepare special characteristics
			$data['mechanic_fullname'] = $this->uc_SystemPipe->setSpecialCharacters($data['mechanic_fullname']);
			$data['mechanic_codename'] = $this->uc_SystemPipe->setSpecialCharacters($data['mechanic_codename']);

			// If directory exists, remove
			if(file_exists($upload_directory)){
				$this->uc_SystemPipe->deleteDirectory($upload_directory);
			}else{
				$state = false;
			}

			// Create new directory
			$this->uc_SystemPipe->createDirectorySpecial($upload_directory);

			// Write description
			$description_file = fopen($upload_directory . 'Описание ' . $data['mechanic_fullname'] . '.txt', "w");
			fwrite($description_file, $data['mechanic_description']);
			fclose($description_file);

			// Prepare special characteristics description
			$data['mechanic_description'] = $this->uc_SystemPipe->setSpecialCharacters($data['mechanic_description']);

			// Move image
			$this->uc_SystemPipe->uploadFile(
				'mechanic_image',
				'Изображение ' . $data['mechanic_fullname'] . '.jpeg',
				$upload_directory . $this->ucs_DirectoriesNames['images'] . '/',
				$files
			);

			// Move 3D model source (*.a3d)
			$this->uc_SystemPipe->uploadFile(
				'mechanic_3dsource',
				'Исходник ' . $data['mechanic_fullname'] . '.m3d',
				$upload_directory . $this->ucs_DirectoriesNames['3dmodels'] . '/',
				$files
			);

			// Move 3D model (*.step)
			$this->uc_SystemPipe->uploadFile(
				'mechanic_3dstep',
				'3D модель ' . $data['mechanic_fullname'] . '.step',
				$upload_directory . $this->ucs_DirectoriesNames['3dmodels'] . '/',
				$files
			);

			// Move drawings source if isset (*.cdw)
			$this->uc_SystemPipe->uploadFile(
				'mechanic_drawsource',
				'Исходник ' . $data['mechanic_fullname'] . '.cdw',
				$upload_directory . $this->ucs_DirectoriesNames['drawings'] . '/',
				$files
			);

			// Move drawings if isset (*.pdf)
			$this->uc_SystemPipe->uploadFile(
				'mechanic_drawpdf',
				'Чертёж ' . $data['mechanic_fullname'] . '.pdf',
				$upload_directory . $this->ucs_DirectoriesNames['drawings'] . '/',
				$files
			);

			// Move vector if isset (*.dxf)
			$this->uc_SystemPipe->uploadFile(
				'mechanic_drawlaser',
				'Векторный файл ' . $data['mechanic_fullname'] . '.dxf',
				$upload_directory . $this->ucs_DirectoriesNames['vectors'] . '/',
				$files
			);

			// Move images
			$this->uc_SystemPipe->uploadFiles(
				'mechanic_photos',
				$upload_directory . $this->ucs_DirectoriesNames['images'] . '/' . $this->ucs_DirectoriesNames['photos'] . '/',
				$files
			);

			// Move marks
			$this->uc_SystemPipe->uploadFiles(
				'mechanic_marks',
				$upload_directory . $this->ucs_DirectoriesNames['images'] . '/' . $this->ucs_DirectoriesNames['marks'] . '/',
				$files
			);

			// Move annotations
			$this->uc_SystemPipe->uploadFiles(
				'mechanic_annotations',
				$upload_directory . $this->ucs_DirectoriesNames['annotations'] . '/',
				$files
			);

			// Set data
			$mechanic_data['material'] = $this->uc_SystemPipe->setSpecialCharacters($data['mechanic_material']);
			$mechanic_data['fullname'] =  $this->uc_SystemPipe->setSpecialCharacters($data['mechanic_fullname']);

			// Converte 3D files
			$this->uc_SystemPipe->stepConverter(
				$upload_directory . $this->ucs_DirectoriesNames['3dmodels'] . '/',
				$mechanic_data['fullname']
			);

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
			// Init json
			$cable_data = array(
				"fullname" => "",
				"material" => "",
				"projects" => array(),
				"changes" => array()
			);

			// Get upload directory
			$upload_directory = 
			$this->getProjectDirectoryData()['directory'] . 
			$this->ucs_DirectoriesNames['develop_documentation'] . '/' .
			$this->ucs_DirectoriesNames['cables'] . '/' . $data['cable_fullname'] . '/';
			
			// Prepare special characteristics
			$data['cable_fullname'] = $this->uc_SystemPipe->setSpecialCharacters($data['cable_fullname']);
			$data['cable_codename'] = $this->uc_SystemPipe->setSpecialCharacters($data['cable_codename']);

			// If directory exists, remove
			if(file_exists($upload_directory)){
				$this->uc_SystemPipe->deleteDirectory($upload_directory);
			}else{
				$state = false;
			}

			// Create new directory
			$this->uc_SystemPipe->createDirectorySpecial($upload_directory);

			// Write description
			$description_file = fopen($upload_directory . 'Описание ' . $data['cable_fullname'] . '.txt', "w");
			fwrite($description_file, $data['cable_description']);
			fclose($description_file);

			// Prepare special characteristics description
			$data['cable_description'] = $this->uc_SystemPipe->setSpecialCharacters($data['cable_description']);

			// Move drawings source if isset (*.cdw)
			$this->uc_SystemPipe->uploadFile(
				'cable_drawsource',
				'Исходник ' . $data['cable_fullname'] . '.cdw',
				$upload_directory . $this->ucs_DirectoriesNames['drawings'] . '/',
				$files
			);

			// Move drawings if isset (*.pdf)
			$this->uc_SystemPipe->uploadFile(
				'cable_drawpdf',
				'Чертёж ' . $data['cable_fullname'] . '.pdf',
				$upload_directory . $this->ucs_DirectoriesNames['drawings'] . '/',
				$files
			);

			// Move images
			$this->uc_SystemPipe->uploadFiles(
				'cable_photos',
				$upload_directory . $this->ucs_DirectoriesNames['images'] . '/' . $this->ucs_DirectoriesNames['photos'] . '/',
				$files
			);

			// Move marks
			$this->uc_SystemPipe->uploadFiles(
				'cable_marks',
				$upload_directory . $this->ucs_DirectoriesNames['images'] . '/' . $this->ucs_DirectoriesNames['marks'] . '/',
				$files
			);

			// Move annotations
			$this->uc_SystemPipe->uploadFiles(
				'cable_annotations',
				$upload_directory . $this->ucs_DirectoriesNames['annotations'] . '/',
				$files
			);

			// Converte pdf to jpeg (image)
			$this->uc_SystemPipe->pdfToJpeg(
				$upload_directory . $this->ucs_DirectoriesNames['drawings'] . '/' . 'Чертёж ' . $data['cable_fullname'] . '.pdf',
				$upload_directory . $this->ucs_DirectoriesNames['images'] . '/' . 'Изображение ' . $data['cable_fullname'], 75, 76
			);

			// Converte pdf to jpeg (draw)
			$this->uc_SystemPipe->pdfToJpeg(
				$upload_directory . $this->ucs_DirectoriesNames['drawings'] . '/' . 'Чертёж ' . $data['cable_fullname'] . '.pdf',
				$upload_directory . $this->ucs_DirectoriesNames['drawings'] . '/' . 'Чертёж ' . $data['cable_fullname']
			);

			// Set data
			$cable_data['fullname'] =  $this->uc_SystemPipe->setSpecialCharacters($data['cable_fullname']);

			// Create query
			$sql = "INSERT INTO `ucp_cables` (`cable_id`, `cable_name`, `cable_description`, `cable_codename`, `cable_author_id`, `cable_create_timestamp`, `cable_status`, `cable_image`, `cable_data`, `cable_activation`) VALUES (NULL, '".$data['cable_name']."', '".$data['cable_description']."', '".$data['cable_codename']."', '".$_SESSION['user_id']."', CURRENT_TIMESTAMP, '".$data['cable_status']."', '', '".json_encode($cable_data, JSON_UNESCAPED_UNICODE)."', '1')";
			
			// Add data
			$this->ucs_Database->query($sql);

			$code = 0;

			// Change codename if auto
			if($data['cable_codename_state'] == 'auto'){
				// Get codename
				$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'cables_codename'";
				$data = $this->ucs_Database->getAllData($sql)[0]['data_value'];
				$code = $data;
				// Update codename
				$data = $data + 1;
				$sql = "UPDATE `ucp_data` SET `data_value` = '".$data."' WHERE `data_name` = 'cables_codename'";
				$this->ucs_Database->query($sql);
			}

			// Generate lables
			$this->generateDymoLables(
				$upload_directory . $this->ucs_DirectoriesNames['images'] . '/' . $this->ucs_DirectoriesNames['marks'] . '/',
				$cable_data['fullname'],
				$this->prepareCodename($code, '')
			);
		}

		// pcbs data
		public function addPcb($data, $files, $revision = 1){

			$data['pcb_isnew'] = intval($data['pcb_isnew']);

			$pcbs = $this->getCommonJsonData('pcb');

			// Init json
			$pcb_data = array(
				"fullname" => "",
				"material" => "",
				"silkscreen" => "",
				"mask" => "",
				"surface" => "",
				"projects" => array(),
				"changes" => array(),
				"revision" => $data['pcb_revision']
			);


			// Set data
			$pcb_data['fullname'] =  $this->uc_SystemPipe->setSpecialCharacters($data['pcb_fullname']);
			$pcb_data['material'] =  $data['pcb_material'];
			$pcb_data['silkscreen'] =  $data['pcb_silk'];
			$pcb_data['mask'] =  $data['pcb_mask'];
			$pcb_data['surface'] =  $data['pcb_surface'];

			$revision_directory = "Ревизия " . $data['pcb_revision'];
			$revision_name = $data['pcb_codename'] . '.' .  $data['pcb_revision'] . ' - ' . $data['pcb_name'];

			// Get upload directory
			$upload_directory = 
			$this->getProjectDirectoryData()['directory'] . 
			$this->ucs_DirectoriesNames['develop_documentation'] . '/' .
			$this->ucs_DirectoriesNames['pcbs'] . '/' . $data['pcb_fullname'] . '/' . $revision_directory . '/';
			
			// Prepare special characteristics
			$data['pcb_fullname'] = $this->uc_SystemPipe->setSpecialCharacters($data['pcb_fullname']);
			$data['pcb_codename'] = $this->uc_SystemPipe->setSpecialCharacters($data['pcb_codename']);

			// If directory exists, remove
			if(file_exists($upload_directory)){
				$this->uc_SystemPipe->deleteDirectory($upload_directory);
			}else{
				$state = false;
			}

			// Create new directory
			$this->uc_SystemPipe->createDirectorySpecial($upload_directory);

			// Write description
			$description_file = fopen($upload_directory . 'Описание ' . $revision_name . '.txt', "w");
			$description = "Описание:\n\t". $data['pcb_description'] . "\n\nМатериал: " . $data['pcb_material'] . ";\nЦвет шелкографии: " . $data['pcb_silk'] . ";\nЦвет паяльной маски: " . $data['pcb_mask'] . ";\n". ";\nПокрытие: " . $data['pcb_surface'] . ";\n";
			fwrite($description_file, $description);
			fclose($description_file);

			// Prepare special characteristics description
			$data['pcb_description'] = $this->uc_SystemPipe->setSpecialCharacters($data['pcb_description']);

			// Move zip arcive
			$this->uc_SystemPipe->uploadFile(
				'pcb_archive',
				'tmp.zip',
				$upload_directory . $this->ucs_DirectoriesNames['sources'] . '/',
				$files
			);

			// Unzip archive
			$this->uc_SystemPipe->unzip(
				$upload_directory . $this->ucs_DirectoriesNames['sources'] . '/tmp.zip',
				$upload_directory . $this->ucs_DirectoriesNames['sources'] . '/'
			);

			// Remove archive
			unlink($upload_directory . $this->ucs_DirectoriesNames['sources'] . '/tmp.zip');

			// Prepare KiCad Project
			require_once('uc_resources/applications/kicad/kicad.php');
			
			// Run KiCad Converter
			$kicad = new KiCadConverter($upload_directory . $this->ucs_DirectoriesNames['sources']);
			
			// Run KiBot Converter
			$kibot = new KiBotConverter($upload_directory . $this->ucs_DirectoriesNames['sources'], $revision_name, $pcb_data);

			// Move images
			$this->uc_SystemPipe->uploadFiles(
				'pcb_photos',
				$upload_directory . $this->ucs_DirectoriesNames['images'] . '/' . $this->ucs_DirectoriesNames['photos'] . '/',
				$files
			);

			// Move marks
			$this->uc_SystemPipe->uploadFiles(
				'pcb_marks',
				$upload_directory . $this->ucs_DirectoriesNames['images'] . '/' . $this->ucs_DirectoriesNames['marks'] . '/',
				$files
			);

			// Move annotations
			$this->uc_SystemPipe->uploadFiles(
				'pcb_annotations',
				$upload_directory . $this->ucs_DirectoriesNames['annotations'] . '/',
				$files
			);

			// Converte 3D files
			$this->uc_SystemPipe->stepConverter(
				$upload_directory . $this->ucs_DirectoriesNames['3dmodels'] . '/',
				$revision_name
			);

			// Create query
			$sql = "INSERT INTO `ucp_pcbs` (`pcb_id`, `pcb_name`, `pcb_description`, `pcb_codename`, `pcb_author_id`, `pcb_create_timestamp`, `pcb_status`, `pcb_image`, `pcb_data`, `pcb_activation`) VALUES (NULL, '".$data['pcb_name']."', '".$data['pcb_description']."', '".$data['pcb_codename'].".".$data['pcb_revision']."', '".$_SESSION['user_id']."', CURRENT_TIMESTAMP, '".$data['pcb_status']."', '', '".json_encode($pcb_data, JSON_UNESCAPED_UNICODE)."', '1')";
			
			// Add data
			$this->ucs_Database->query($sql);

			$code = 0;

			// Change codename if auto
			if($data['pcb_codename_state'] == 'auto'){
				// Get codename
				$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'pcbs_codename'";
				$data_num = $this->ucs_Database->getAllData($sql)[0]['data_value'];
				$code = $data_num;
				// Update codename
				$data_num = $data_num + 1;
				$sql = "UPDATE `ucp_data` SET `data_value` = '".$data_num."' WHERE `data_name` = 'pcbs_codename'";
				$this->ucs_Database->query($sql);
			}

			// Update pcb data
			if($data['pcb_isnew'] == 1){
				// Init board info
				$pcb_info = array(
					"name" => $data['pcb_name'],
					"description" => $data['pcb_description'],
					"revisions" => array( "1" => "true" ),
				);

				$pcbs[$data['pcb_codename']] = $pcb_info;
			}else{
				array_push($pcbs[$data['pcb_codename']]["revisions"], $data['pcb_revision'] = "true" );
			}

			$this->setCommonJsonData('pcb', $pcbs);
		}

		// Get mechanic materials
		public function getMechanicMaterials(){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'mechanics_materials'";
			$materials = $this->ucs_Database->getAllData($sql)[0]['data_text'];
			$materials = json_decode($materials, true)['materials'];
			$result = array();

			foreach ($materials as $material => $data) {
				if(empty($data)){
					array_push($result, $material);
				}else{
					foreach ($data as $index => $value) {
						array_push($result, $material . ', толщиной ' . $value . ' (мм)');
					}
				}
			}

			return $result;
		}

		// Get mechanic materials
		public function getPcbMaterials(){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'pcbs_materials'";
			$materials = $this->ucs_Database->getAllData($sql)[0]['data_text'];
			$materials = json_decode($materials, true)['materials'];
			$result = array();

			foreach ($materials as $material => $data) {
				if(empty($data)){
					array_push($result, $material);
				}else{
					foreach ($data as $index => $value) {
						array_push($result, $material . ', толщиной ' . $value . ' (мм)');
					}
				}
			}

			return $result;
		}

		// Get mechanic materials
		public function getPcbColors(){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'pcbs_colors'";
			$materials = $this->ucs_Database->getAllData($sql)[0]['data_text'];
			$materials = json_decode($materials, true)['colors'];
			$result = array();

			foreach ($materials as $material => $data) {
				if(empty($data)){
					array_push($result, $material);
				}else{
					foreach ($data as $index => $value) {
						array_push($result, $material . ', оттенок ' . $value);
					}
				}
			}

			return $result;
		}

		// Get mechanic materials
		public function getPcbSurfaces(){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'pcbs_surfaces'";
			$materials = $this->ucs_Database->getAllData($sql)[0]['data_text'];
			$materials = json_decode($materials, true)['surfaces'];
			$result = array();

			foreach ($materials as $material => $data) {
				if(empty($data)){
					array_push($result, $material);
				}else{
					foreach ($data as $index => $value) {
						array_push($result, $material . ', медь толщиной ' . $value . ' µm');
					}
				}
			}

			return $result;
		}

		// Get mechanic materials
		public function getCommonJsonData($prefix, $raw = false){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = '${prefix}s_data'";
			$pcbs_json = $this->ucs_Database->getAllData($sql)[0]['data_text'];

			if(!isset(json_decode($pcbs_json, true)["${prefix}s"])){
				$pcbs = array();
			    $pcbs_json = "{}";
			}else{
				$pcbs = json_decode($pcbs_json, true)["${prefix}s"];
			}

			if($raw == true){
				return $pcbs_json;
			}else{
				return $pcbs;
			}
		}

		// Get mechanic materials
		public function setCommonJsonData($prefix, $data_array){
			$data["${prefix}s"] = $data_array;
			$data = json_encode($data, JSON_UNESCAPED_UNICODE);
			$sql = "UPDATE `ucp_data` SET `data_text` = '$data' WHERE `data_name` = '${prefix}s_data'";
			$this->ucs_Database->query($sql);
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

		public function getCommonList($prefix, $page, $count, $like = "", $getCount = false){
			
			$start = ($page * $count) - $count;
			$end = $count;

			$sql = "";

			if($like == ""){
				$sql = "SELECT * FROM `ucp_${prefix}s` ORDER BY `ucp_${prefix}s`.`${prefix}_codename` DESC LIMIT $start,$end";
			}else{
				$sql = "SELECT * FROM `ucp_${prefix}s` WHERE `${prefix}_name` LIKE '%" . $like . "%' OR `${prefix}_description` LIKE '%" . $like . "%' OR `${prefix}_codename` LIKE '%" . $like . "%' ORDER BY `ucp_${prefix}s`.`${prefix}_codename` DESC LIMIT $start,$end";
			}

			$list = $this->ucs_Database->getAllData($sql);
			
			if($list != 0){
				foreach ($list as $key => &$value) {
					$value["${prefix}_data"] = json_decode($value["${prefix}_data"], true);
				}
			}

			if($getCount == false){
				return $list;
			}else{
				if($like == ""){
					$sql = "SELECT COUNT(*) FROM `ucp_${prefix}s` ORDER BY `ucp_${prefix}s`.`${prefix}_codename` DESC";
				}else{
					$sql = "SELECT COUNT(*) FROM `ucp_${prefix}s` WHERE `${prefix}_name` LIKE '%" . $like . "%' OR `${prefix}_description` LIKE '%" . $like . "%' OR `${prefix}_codename` LIKE '%" . $like . "%' ORDER BY `ucp_${prefix}s`.`${prefix}_codename` DESC";
				}
				return array(
					"count" => $this->ucs_Database->getQueryRecordsCount($sql), 
					"list" => $list
				);
			}
		}


		public function getCommonItem($prefix, $item_id){
			$sql = "SELECT * FROM `ucp_${prefix}s` WHERE `${prefix}_id` = $item_id";
			$data = $this->ucs_Database->getData($sql);
			$data["${prefix}_data"] = json_decode($data["${prefix}_data"], true);
			return $data;
		}

		public function prepareCodename($code, $sufix){
			
			if($code >= 0 and $code < 10 ){
				$code = $sufix . '000' . $code;
			}
			if($code >= 10 and $code < 100 ){
				$code = $sufix . '00' . $code;
			}
			if($code >= 100 and $code < 1000){
				$code = $sufix . '0' . $code;
			}
			if($code >= 1000 and $code < 10000){
				$code = $sufix . '00' . $code;
			}
			if($code >= 10000 and $code < 100000){
				$code = $sufix . '000' . $code;
			}

			return $code;
		}

		public function getLastCodeName($codename, $sufix){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = '$codename'";
			$data = $this->ucs_Database->getAllData($sql)[0]['data_value'];
			return $this->prepareCodename($data, $sufix);
		}

		public function getStatuses(){
			$sql = "SELECT * FROM `ucp_data` WHERE `data_name` = 'mechanics_statuses'";
			return json_decode($this->ucs_Database->getData($sql)['data_text'], true);
		}


		public function checkDirectories($dirdata){
			// Directories watchdog
			$work_directory = $this->getProjectDirectoryData()['directory'];
			// Get all base directories
			foreach ($dirdata as $subdirectory => $directories) {
				$base_dir = $work_directory . $subdirectory . '/';
				foreach ($directories as $directory => $subdirs) {
					if(is_array($subdirs)){
						$this->uc_SystemPipe->createDirectorySpecial($base_dir . $directory);
						foreach ($subdirs as $sd => $sdv) {
							$this->uc_SystemPipe->createDirectorySpecial($base_dir . $directory . '/' . $sdv);
						}
					}else{
						$this->uc_SystemPipe->createDirectorySpecial( $base_dir . $subdirs);
					}
				}
			}
		}

		function formatBytes($bytes, $precision = 2) { 
		    $units = array('(Б)', '(кБ)', '(МБ)', '(ГБ)', '(ТБ)'); 

		    $bytes = max($bytes, 0); 
		    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		    $pow = min($pow, count($units) - 1); 

		    // Uncomment one of the following alternatives
		    // $bytes /= pow(1024, $pow);
		    // $bytes /= (1 << (10 * $pow)); 

		    return round($bytes, $precision) . ' ' . $units[$pow]; 
		} 

		function generateDymoLables($location, $filename, $code = '0001'){
			$sizes = array(6, 9, 12, 19, 24);
			foreach ($sizes as $size) {
				$this->generateDymoLable($location, 'Маркировка ' . $size . ' мм для ' . $filename . '.label', $code, $size);
			}
		}

		function generateDymoLable($location, $filename, $code = '0001', $size = 6){
			$template = file_get_contents($this->system['main_directory'] . 'uc_modules/uCrewProjects/data/dymo/data.label');
			$template = str_replace("%code%", $code, $template);
			$template = str_replace("%size%", $size, $template);
			$template = str_replace("%length%", round($size * 755.9), $template);
			file_put_contents($location . $filename, $template);
			return $location . $filename;
		}

	}
?>