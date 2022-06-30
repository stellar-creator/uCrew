<?php
	/**
	 * KiCad Class
	 */

	class KiCadConverter {

		public $project;
		public $structure;

		function __construct($location){
			//echo "# KiCad Converter v 0.1 \n";
			
			// Init array
			$this->project = array(
				'directory' => '',
				'name' => '',
				'author' => '',
				'revision' => '',
				'date' => '',
				'codename' => '',
				'approver' => '',
				'files' => array(
					'unformatted' => array(),
					'schematics' => array(),
					'pcbs' => array(),
					'libraries' => array(),
					'templates' => array(),
					'project' => '',
					'settings' => '',
					'caches' => array(
						'fp-info' => '',
						'fp-lib' => '',
						'sym-lib' => ''
					),
					'backups' => array(),
					'symbols' => array()
				)
			);

			// Init directory structure
			$this->structure = array(
				'templates',
				'libraries',
				'schematics',
				'symbols',
				'board',
				'3d model'
			);

			// Set project directory
			$this->project['directory'] = $location;
			// Find files
			$this->project['files']['unformatted'] = $this->getFiles($this->project['directory']);
			// Format files
			$this->formatFiles($this->project['files']['unformatted']);
		}

		private function getFiles($directory, $allFiles = []) {
			$files = array_diff(scandir($directory), ['.', '..']);
		    foreach ($files as $file) {
		        $fullPath = $directory . DIRECTORY_SEPARATOR . $file;
		        if( is_dir($fullPath) )
		            $allFiles += $this->getFiles($fullPath, $allFiles);
		        else
		            $allFiles[] = $directory . '/' . $file;
		    }
		    return $allFiles;
		}

		private function formatFiles(&$files){
			foreach ($files as $index => $file) {
				
				if (strpos($file, '.kicad_sch') !== false) {
				    array_push($this->project['files']['schematics'], $file);
				    unset($files[$index]);
				}

				if (strpos($file, '.pretty') !== false) {
				    array_push($this->project['files']['libraries'], $file);
				    unset($files[$index]);
				}

				if (strpos($file, '.kicad_wks') !== false) {
				    array_push($this->project['files']['templates'], $file);
				    unset($files[$index]);
				}

				if (strpos($file, '.kicad_pcb') !== false) {
				    array_push($this->project['files']['pcbs'], $file);
				    unset($files[$index]);
				}

				if (strpos($file, '.kicad_pro') !== false) {
				    $this->project['files']['project'] = $file;
				    unset($files[$index]);
				}

				if (strpos($file, '.kicad_sym') !== false) {
				    array_push($this->project['files']['symbols'], $file);
				    unset($files[$index]);
				}

				if (strpos($file, '.kicad_prl') !== false) {
				    $this->project['files']['settings'] = $file;
				    unset($files[$index]);
				}

				if (strpos($file, '.bak') !== false) {
					array_push($this->project['files']['backups'], $file);
				    unset($files[$index]);
				}

				if (strpos($file, 'fp-info-cache') !== false) {
				    $this->project['files']['caches']['fp-info'] = $file;
				    unset($files[$index]);
				}

				if (strpos($file, 'fp-lib-table') !== false) {
				    $this->project['files']['caches']['fp-lib'] = $file;
				    unset($files[$index]);
				}

				if (strpos($file, 'sym-lib-table') !== false) {
				    $this->project['files']['caches']['sym-lib'] = $file;
				    unset($files[$index]);
				}

			}
		}
	}

	/**
	 * 
	 */

	class KiBotConverter {

		public $kidata;
		public $projectName;
		public $settings;
		public $colors;

		function __construct($project, $name, $pcb_settings = array()) {
			//echo "# KiBot Converter v 0.1 \n";
			// Set data
			$this->kidata = array(
				'location' => $project,
				'yaml' => $project . '/.kibot.yaml'
			);

			// Set data
			$this->settings = array(
				'colors' => array(
					'core' => "",
					'silkscreen' => "",
					'mask' => "",
					'surface' => ""
				)
			);

			// Set data
			$this->colors = array(
				"Зелёный" => "#0D680B",
				"Чёрный, оттенок матовый" => "#0B0B0B",
				"Чёрный, оттенок глянцевый" => "#1A1A1A",
				"Синий" => "#023BA2",
				"Красный" => "#D2280E",
				"Жёлтый" => "#C2C300",
				"Белый, оттенок матовый" => "#E4E4E4",
				"Белый, оттенок глянцевый" => "#F5F5F5",
				"Медь" => "#B87332",
				"Золото" => "#B29C00",
				"Серебро" => "#D5D5D5",
				"Олово" => "#A0A0A0"
			);

			if(isset($pcb_settings['mask'])){
				$this->settings['colors']['mask'] = $this->colors[$pcb_settings['mask']];
			}

			if(isset($pcb_settings['mask'])){
				$this->settings['colors']['silkscreen'] = $this->colors[$pcb_settings['silkscreen']];
			}

			if(isset($pcb_settings['surface'])){
				if(strpos($pcb_settings['surface'], "Голая медь") !== false){
				    $this->settings['colors']['surface'] = $this->colors['Медь'];
				}
				if(strpos($pcb_settings['surface'], "золото") !== false){
				    $this->settings['colors']['surface'] = $this->colors['Золото'];
				}
				if(strpos($pcb_settings['surface'], "серебро") !== false){
				    $this->settings['colors']['surface'] = $this->colors['Серебро'];
				}
				if(strpos($pcb_settings['surface'], "ПОС") !== false){
				    $this->settings['colors']['surface'] = $this->colors['Олово'];
				}
				if(strpos($pcb_settings['surface'], "припой") !== false){
				    $this->settings['colors']['surface'] = $this->colors['Олово'];
				}
			}

			$this->projectName = $name;

			// Generate yaml file
			$this->generateYaml();

			// Execute script
			$this->execute();
		}

		public function execute(){
			
			$uc_Configuration = new uCrewConfiguration();

			$uc_SystemPipe = new uCrewSystemPipe();
			
			$workdir = $uc_Configuration->system["main_directory"] . $uc_Configuration->directories["temporary"] . 'pcb/';

			echo "$workdir";

			$result = $uc_SystemPipe->sh(
				array(
					// Remove workdir
					'sudo rm -rf "' . $workdir . '"',
					// Change KiCad project mode
					'chmod 777 -R "' . $this->kidata['location'] . '"', 
					// Go to directory with project
					'cd "' . $this->kidata['location'] . '"', 
					// Make workdir
					'sudo mkdir "' . $workdir . '"',
					// Make workdir mode
					'sudo chmod 777 -R "' . $workdir . '"', 
					// Copy project to workdir
					'sudo cp -a "$(pwd)/." "' . $workdir . '"',
					// Go to workdir
					'cd "' . $workdir . '"',
					// Run docker container
					'sudo ucrew-kicad', // kibot -v -c .kibot.yaml
					'sudo cp -a "'.$workdir.'". "' . $this->kidata['location'] . '"',
					'cd "'.$this->kidata['location'].'"',
					'ls',
					//'sudo rm -rf "' . $workdir . '"',
					'cd ..',
					'mkdir Изображения',
					'cp Исходники/Images/image.jpg "Изображения/Изображение ' . $this->projectName . '.jpeg"',
					'cp Исходники/3D/*.png "Изображения/Изображение 3D модели ' . $this->projectName . '.png"',
					'convert "Изображения/Изображение 3D модели ' . $this->projectName . '.png" -quality 80 "Изображения/Изображение 3D модели ' . $this->projectName . '.jpeg"',
					'mkdir "3D модели"',
					'cp Исходники/3D/*.step "3D модели/3D модель ' . $this->projectName . '.step"',
					'mkdir "Файлы для производства"',
					'mkdir "Файлы для производства/Gerber/"',
					'mkdir "Файлы для производства/Файлы позиций/"',
					'mkdir "Файлы для производства/Сборочный чертёж/"',
					'mkdir "Файлы для производства/Векторные файлы/"',
					'sudo chmod 777 -R Исходники/',
					'cp -a Исходники/DXF/. "Файлы для производства/Векторные файлы/"',
					'cp -a Исходники/Gerber/. "Файлы для производства/Gerber/"',
					'cp Исходники/Bom/*.html "Файлы для производства/Сборочный чертёж/Интерактивынй сборочный чертёж ' . $this->projectName . '.html"',
					'rm -rf Исходники/3D/',
					'rm -rf Исходники/DXF/',
					'rm -rf Исходники/Gerber/',
					'rm -rf Исходники/Bom/',
					'rm -rf Исходники/Images/'
				)
			);

			// echo $uc_SystemPipe->setTerminalToHtml($result);
		}

		public function generateYaml(){

			$data = "kibot:
  version: 1

preflight:
  run_drc: false

outputs:
  - name: 'gerbers'
    comment: \"Generate gerber files for production\"
    type: gerber
    dir: Gerber
    options:
      # generic layer options
      exclude_edge_layer: false
      exclude_pads_from_silkscreen: true
      plot_sheet_reference: false
      plot_footprint_refs: true
      plot_footprint_values: false
      force_plot_invisible_refs_vals: false
      tent_vias: true
      line_width: 0.15

      # gerber options
      use_aux_axis_as_origin: false
      subtract_mask_from_silk: true
      use_protel_extensions: false
      gerber_precision: 4.6
      create_gerber_job_file: false
      use_gerber_x2_attributes: true
      use_gerber_net_attributes: true

    layers: 'selected'

  - name: 'drill'
    comment: \"Generate drill file for production\"
    type: excellon
    dir: Gerber
  
  - name: 'dxf'
    comment: \"Generate dxf file for production\"
    type: dxf
    layers: 'selected'
    dir: DXF
    options:
      metric_units: true

  - name: 'pcbdraw'
    comment: \"Print PCB image\"
    type: pcbdraw
    dir: Images
    options:
      format: 'jpg'
      show_components: 'none'
      output: 'image.%x'
      style:
        outline: '#ffffff'
        board: '" . $this->settings['colors']['mask'] . "'
        silk: '" . $this->settings['colors']['silkscreen'] . "'
        pads: '" . $this->settings['colors']['surface'] . "' 

  - name: 'ibom'
    comment: \"Generate ibom files for production\"
    type: ibom
    dir: Bom
    options:
      # generic layer options
      dark_mode: true
      highlight_pin1: true

  - name: 'render_3d'
    comment: \"Generate 3D file\"
    type: render_3d
    dir: 3D
    options:
      ray_tracing: true
      wait_render: -120
      wait_ray_tracing: -120
      zoom: 5
      background1: '#ffffff'
      background2: '#ffffff'
      width: 1920
      height: 1080
      solder_mask: '" . $this->settings['colors']['mask'] . "'
      silk: '" . $this->settings['colors']['silkscreen'] . "'
      solder_paste: '" . $this->settings['colors']['surface'] . "' 

  - name: 'step'
    comment: \"Generate 3D file\"
    type: step
    dir: 3D
    options:
      origin: 'drill'

";

			file_put_contents($this->kidata['yaml'], $data, LOCK_EX);
		}
	}
?>