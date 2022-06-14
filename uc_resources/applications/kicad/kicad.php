<?php
	/**
	 * KiCad Class
	 */

	class KiCadConverter {

		public $project;
		public $structure;

		function __construct($location){
			echo "# KiCad Converter v 0.1 \n";
			
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

		function __construct($project, $name) {
			echo "# KiBot Converter v 0.1 \n";
			// Set data
			$this->kidata = array(
				'location' => $project,
				'yaml' => $project . '/.kibot.yaml'
			);
			$this->projectName = $name;
			// Generate yaml file
			$this->generateYaml();
			// Execute script
			$this->execute();
		}

		public function execute(){
			
			$uc_SystemPipe = new uCrewSystemPipe();
			
			$result = $uc_SystemPipe->sh(
				array(
					'sudo rm -rf /backup/pcb/',
					'chmod 777 -R "' . $this->kidata['location'] . '"', 
					'cd "' . $this->kidata['location'] . '"', 
					//'pwd',
					'sudo mkdir /backup/pcb/',
					'sudo chmod 777 -R /backup/pcb/', 
					'sudo cp -a "$(pwd)/." /backup/pcb/',
					//'ls',
					'cd /backup/pcb/',
					//'pwd',
					'sudo kibot -v -c .kibot.yaml',
					'sudo cp -a /backup/pcb/. "' . $this->kidata['location'] . '"',
					'cd "'.$this->kidata['location'].'"',
					'ls',
					'sudo rm -rf /backup/pcb/',
					'cd ..',
					'mkdir Изображения',
					'cp Исходники/Images/image.jpg "Изображения/Изображение ' . $this->projectName . '.jpeg"'
				)
			);

			echo $uc_SystemPipe->setTerminalToHtml($result);
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

  - name: 'step'
    comment: \"Generate 3D file\"
    type: step
    dir: 3D
";

			file_put_contents($this->kidata['yaml'], $data, LOCK_EX);
		}
	}
?>