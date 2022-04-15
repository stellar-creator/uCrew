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
			$this->formatFiles($this->project['files']['unformatted']);
			// Format files
			print_r($this);
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
?>