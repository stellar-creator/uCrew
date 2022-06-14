<?php
	// Buffer data
	$page = 1;
	$count = 25;
	// Check values
	if(isset($_GET['p'])){
		$page = $_GET['p'];
	}
	if(isset($_GET['c'])){
		$count = $_GET['c'];
	}
	// Require API
	require_once('uc_modules/uCrewProjects/api/projects_system.php');
	// Init class
	$uc_Projects = new uCrewProjects();
	// Get mechanics list
	$list = $uc_Projects->getPcbsList($page, $count);
	// Get pager
	$pager = $this->uc_CompilatorData->generatePager(
		$page,
		$count,
		$uc_Projects->ucs_Database->getRecordsCount('ucp_pcbs'),
		'/?page=uCrewProjects/pcbs'
	);
?>
<div class="row">
	<div class="d-grid gap-1 d-md-flex justify-content-md-end" style="padding-bottom: 10px">
	 	<a href="/?page=uCrewProjects/pcbsAdd" class="btn btn-primary me-md-1" >
	 		<i class="fa fa-plus-square" style="padding-right: 5px"></i> Добавить плату
		</a>
	</div>
</div>


<div class="row">

<?php
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
  	$statuses = $uc_Projects->getStatuses();
  	$rows = array();

  	if($list != 0){
		foreach ($list as $item => $values) {

			$pcb_paths = $uc_Projects->ucs_DirectoriesPath['pcbs'];

			$pcb_paths['web'] = $pcb_paths['web'] . $values['pcb_data']['fullname'] . '/';
			
			$pcb_image = $pcb_paths['web'] . 'Ревизия '.$values['pcb_data']['revision'].'/' . $uc_Projects->ucs_DirectoriesNames['images'] . '/Изображение ' . $values['pcb_codename'] . ' - ' . $values['pcb_name'] . '.jpeg' ;
		

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
					'<img src="'.$pcb_image.'" class="img-thumbnail imagecat" alt="'.$values['pcb_codename'].'">',
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

	echo $pager;
?>
</div>
