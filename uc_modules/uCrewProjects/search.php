<?php
	/**
	 * uCrewProjects search API class
	 */

	require_once('uc_modules/uCrewProjects/api/projects_system.php');

	class uCrewProjectsSearchApi extends uCrewProjects
	{

		public $uc_CompilatorData;

		function __construct()
		{
			parent::__construct();
			$this->uc_CompilatorData = new uCrewCompilatorData();
			$this->findCablesInDataBase($_GET['data']);
			$this->findMechanicsInDataBase($_GET['data']);
			$this->findPcbsInDataBase($_GET['data']);
		}

		public function findCablesInDataBase($text)
		{
			// Buffer data
			$page = 1;
			$count = 10;
			// Check values
			if(isset($_GET['cp'])){
				$page = $_GET['cp'];
			}
			if(isset($_GET['cc'])){
				$count = $_GET['cc'];
			}

			// Get mechanics list
			$list = $this->getCablesList($page, $count, $text);

			// Get pager
			$pager = $this->uc_CompilatorData->generatePager(
				$page,
				$count,
				$this->getCablesList($page, $count, $text, true)["count"],
				'/?handler=search&data=' . $_GET['data'],
				'cp',
				'cc'
			);

			echo '<div class="row"><h4>Поиск по кабелям</h4>';

			$cols = array(
				'№' => array('class' => 'text-center', 'width' => '5%'),
				'Изображение' => array('class' => 'text-center', 'width' => '150px'),
				'Шифр' => array('class' => 'text-center', 'width' => '10%'),
				'Краткая информация' => array('width' => '35%'),
				'Статус' => array('class' => 'text-center'),
				'Управление' => array('class' => 'text-center')
			);

			$count = ($page * $count) - $count;
		  	$statuses = $this->getStatuses();
		  	$rows = array();

		  	if($list != 0){
				foreach ($list as $item => $values) {

					$cable_paths = $this->ucs_DirectoriesPath['cables'];

					$cable_paths['web'] = $cable_paths['web'] . $values['cable_data']['fullname'] . '/';
					$cable_image = $cable_paths['web'] . $this->ucs_DirectoriesNames['images'] . '/' . 'Изображение ' . $values['cable_data']['fullname'] . '.jpeg' ;
					$count++;
					$status = $statuses[$values['cable_status']][0];
					$class = $statuses[$values['cable_status']][1];
					$inprojects = "";
					if( isset($values['cable_data']['projects']) ){
						if( count($values['cable_data']['projects']) > 0 ){
							$inprojects = '<p>
									<figcaption class="blockquote-footer">
										Применятся в проектах:
										<cite title="Source Title">КИПТ "Азимут 4 - Моноблок"</cite>  
									</figcaption>
								</p>';
							// Тут надо вписывать, в каких проектах
						}
					}
					$dropdown = '<div class="dropdown">
								<button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenuButton'.$values['cable_id'].'" data-bs-toggle="dropdown" aria-expanded="false">Действие</button>
									<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$values['cable_id'].'">
										<li><a class="dropdown-item" href="/?page=uCrewProjects/cablesItemEdit&id='.$values['cable_id'].'">Редактировать</a></li>
										<li><a class="dropdown-item" href="/?page=uCrewProjects/cablesItemRemove&id='.$values['cable_id'].'">Архивировать</a></li>
									</ul>
								</div>';

					array_push($rows, 
						array(
							$count => array('class' => 'align-middle text-center'),
							'<img src="'.$cable_image.'" class="img-thumbnail imagecat zoom" alt="'.$values['cable_codename'].'">' => array('class' => 'align-middle text-center'),
							'<a href="/?page=uCrewProjects/cablesItem&id='.$values['cable_id'].'" class="link-dark" style="text-decoration:none">'.$values['cable_codename'].'</a>' => array('class' => 'align-middle text-center'),
							'<p>'.$values['cable_name'].'</p><p>
							<figcaption class="blockquote-footer">
							Описание:
							<cite title="Source Title">'.$values['cable_description'].'</cite>  
							</figcaption></p>'.$inprojects => array('class' => 'align-middle'),
							$status => array('class' => 'align-middle text-center '.$class),
							$dropdown => array('class' => 'align-middle text-center')
						)
					);
				}
			}
			
			$table = $this->uc_CompilatorData->generateTable($cols, $rows);
			
			print($table);

			echo $pager;
			echo '</div>';

		}


		public function findMechanicsInDataBase($text)
		{
			// Buffer data
			$page = 1;
			$count = 10;
			// Check values
			if(isset($_GET['mp'])){
				$page = $_GET['mp'];
			}
			if(isset($_GET['mc'])){
				$count = $_GET['mc'];
			}

			// Get mechanics list
			$list = $this->getMechanicsList($page, $count, $text);
			// Get pager
			$pager = $this->uc_CompilatorData->generatePager(
				$page,
				$count,
				$this->getMechanicsList($page, $count, $text, true)["count"],
				'/?handler=search&data=' . $_GET['data'],
				'mp',
				'mc'
			);

			echo '<div class="row"><h4>Поиск по механическим изделиям</h4>';

				$cols = array(
					'№' => array('class' => 'text-center', 'width' => '5%'),
					'Изображение' => array('class' => 'text-center', 'width' => '150px'),
					'Шифр' => array('class' => 'text-center', 'width' => '10%'),
					'Краткая информация' => array('width' => '35%'),
					'Статус' => array('class' => 'text-center'),
					'Материал',
					'Управление' => array('class' => 'text-center'),
				);

				$count = ($page * $count) - $count;
			  	$statuses = $this->getStatuses();
			  	$rows = array();

			  	if($list != 0){
					foreach ($list as $item => $values) {

						$mechanic_paths = $this->ucs_DirectoriesPath['mechanics'];

						$mechanic_paths['web'] = $mechanic_paths['web'] . $values['mechanic_data']['fullname'] . '/';
						$mechanic_image = $mechanic_paths['web'] . $this->ucs_DirectoriesNames['images'] . '/' . 'Изображение ' . $values['mechanic_data']['fullname'] . '.jpeg' ;
						$count++;
						$status = $statuses[$values['mechanic_status']][0];
						$class = $statuses[$values['mechanic_status']][1];
						$inprojects = "";
						if( isset($values['mechanic_data']['projects']) ){
							if( count($values['mechanic_data']['projects']) > 0 ){
								$inprojects = '<p>
										<figcaption class="blockquote-footer">
											Применятся в проектах:
											<cite title="Source Title">КИПТ "Азимут 4 - Моноблок"</cite>  
										</figcaption>
									</p>';
								// Тут надо вписывать, в каких проектах
							}
						}
						$dropdown = '<div class="dropdown">
									<button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenuButton'.$values['mechanic_id'].'" data-bs-toggle="dropdown" aria-expanded="false">Действие</button>
										<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$values['mechanic_id'].'">
											<li><a class="dropdown-item" href="/?page=uCrewProjects/mechanicsItemEdit&id='.$values['mechanic_id'].'">Редактировать</a></li>
											<li><a class="dropdown-item" href="/?page=uCrewProjects/mechanicsItemRemove&id='.$values['mechanic_id'].'">Архивировать</a></li>
										</ul>
									</div>';

						array_push($rows, 
							array(
								$count => array('class' => 'align-middle text-center'),
								'<img src="'.$mechanic_image.'" class="img-thumbnail imagecat zoom" alt="'.$values['mechanic_codename'].'">' => array('class' => 'align-middle text-center'),
								'<a href="/?page=uCrewProjects/mechanicsItem&id='.$values['mechanic_id'].'" class="link-dark" style="text-decoration:none">'.$values['mechanic_codename'].'</a>' => array('class' => 'align-middle text-center'),
								'<p>'.$values['mechanic_name'].'</p><p>
								<figcaption class="blockquote-footer">
								Описание:
								<cite title="Source Title">'.$values['mechanic_description'].'</cite>  
								</figcaption></p>'.$inprojects => array('class' => 'align-middle'),
								$status => array('class' => 'align-middle text-center '.$class),
								$values['mechanic_data']['material'] => array('class' => 'align-middle'),
								$dropdown => array('class' => 'align-middle text-center')
							)
						);
					}
				}
				
				$table = $this->uc_CompilatorData->generateTable($cols, $rows);
				
				print($table);

				echo $pager . '</div>';

		}

		public function findPcbsInDataBase($text)
		{
			// Buffer data
			$page = 1;
			$count = 10;
			// Check values
			if(isset($_GET['pp'])){
				$page = $_GET['pp'];
			}
			if(isset($_GET['pc'])){
				$count = $_GET['pc'];
			}

			// Get mechanics list
			$list = $this->getPcbsList($page, $count, $text);
			// Get pager
			$pager = $this->uc_CompilatorData->generatePager(
				$page,
				$count,
				$this->getPcbsList($page, $count, $text, true)["count"],
				'/?handler=search&data=' . $_GET['data'],
				'pp',
				'pc'
			);

			echo '<div class="row"><h4>Поиск по печатным платам</h4>';

			$cols = array(
					'№' => array('class' => 'text-center', 'width' => '5%'),
					'Изображение' => array('class' => 'text-center', 'width' => '150px'),
					'Шифр' => array('class' => 'text-center', 'width' => '10%'),
					'Краткая информация' => array('width' => '35%'),
					'Статус' => array('class' => 'text-center'),
					'Материал' => array('class' => 'text-center'),
					'Управление' => array('class' => 'text-center')
				);

				$count = ($page * $count) - $count;
			  	$statuses = $this->getStatuses();
			  	$rows = array();

			  	if($list != 0){
					foreach ($list as $item => $values) {

						$pcb_paths = $this->ucs_DirectoriesPath['pcbs'];

						$pcb_paths['web'] = $pcb_paths['web'] . $values['pcb_data']['fullname'] . '/';
						
						$pcb_image = $pcb_paths['web'] . 'Ревизия '.$values['pcb_data']['revision'].'/' . $this->ucs_DirectoriesNames['images'] . '/Изображение 3D модели ' . $values['pcb_codename'] . ' - ' . $values['pcb_name'] . '.jpeg' ;
					

						$count++;
						$status = $statuses[$values['pcb_status']][0];
						$class = $statuses[$values['pcb_status']][1];
						$material = $values['pcb_data']['material'];
						$silkscreen = $values['pcb_data']['silkscreen'];
						$mask = $values['pcb_data']['mask'];
						$surface = $values['pcb_data']['surface'];
						$inprojects = "";

						if( isset($values['pcb_data']['projects']) ){
							if( count($values['pcb_data']['projects']) > 0 ){
								$inprojects = '<p>
										<figcaption class="blockquote-footer">
											Применятся в проектах:
											<cite title="Source Title">КИПТ "Азимут 4 - Моноблок"</cite>  
										</figcaption>
									</p>';

								// Тут надо вписывать, в каких проектах
							}
						}
						$dropdown = '<div class="dropdown">
									<button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenuButton'.$values['pcb_id'].'" data-bs-toggle="dropdown" aria-expanded="false">Действие</button>
										<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$values['pcb_id'].'">
											<li><a class="dropdown-item" href="/?page=uCrewProjects/pcbsItemEdit&id='.$values['pcb_id'].'">Редактировать</a></li>
											<li><a class="dropdown-item" href="/?page=uCrewProjects/pcbsItemRemove&id='.$values['pcb_id'].'">Архивировать</a></li>
										</ul>
									</div>';

						array_push($rows, 
							array(
								$count => array('class' => 'align-middle text-center'),
								'<img src="'.$pcb_image.'" class="img-thumbnail imagecat zoom" alt="'.$values['pcb_codename'].'">' => array('class' => 'align-middle text-center'),
								'<a href="/?page=uCrewProjects/pcbsItem&id='.$values['pcb_id'].'" class="link-dark" style="text-decoration:none">'.$values['pcb_codename'].'</a>' => array('class' => 'align-middle text-center'),
								'<p>'.$values['pcb_name'].'</p><p>
								<figcaption class="blockquote-footer">
								Описание:
								<cite title="Source Title">'.$values['pcb_description'].'</cite>  
								</figcaption><figcaption class="blockquote-footer">
								Характеристики:
								<cite title="Source Title">Цвет шелкографии: ' . $silkscreen . ', цвет маски: ' . $mask . ', покрытие: ' . $surface.'</cite>  
								</figcaption></p>'.$inprojects => array('class' => 'align-middle'),
								$status => array('class' => 'align-middle text-center '.$class),
								$material => array('class' => 'align-middle text-center'),
								$dropdown => array('class' => 'align-middle text-center')
							)
						);
					}
				}
				
				$table = $this->uc_CompilatorData->generateTable($cols, $rows);
				
				print($table);

				echo $pager . '</div>';
			}
	}
	
?>