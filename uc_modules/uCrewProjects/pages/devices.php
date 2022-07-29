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
	$list = $uc_Projects->getCommonList('device', $page, $count);
	// Get pager
	$pager = $this->uc_CompilatorData->generatePager(
		$page,
		$count,
		$uc_Projects->ucs_Database->getRecordsCount('ucp_devices'),
		'/?page=uCrewProjects/device'
	);
?>

<div class="row">
	<div class="d-grid gap-1 d-md-flex justify-content-md-end" style="padding-bottom: 10px">
	 	<a href="/?page=uCrewProjects/devicesAdd" class="btn btn-primary me-md-1" >
	 		<i class="fa fa-plus-square" style="padding-right: 5px"></i> Добавить устройство
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
		'Управление' => array('class' => 'text-center')
	);

	$count = ($page * $count) - $count;
  	$statuses = $uc_Projects->getStatuses();
  	$rows = array();

  	if($list != 0){
		foreach ($list as $item => $values) {

			$device_paths = $uc_Projects->ucs_DirectoriesPath['devices'];

			$device_paths['web'] = $device_paths['web'] . $values['device_data']['fullname'] . '/';
			
			$device_image = $device_paths['web'] . 'Ревизия '.$values['device_data']['revision'].'/' . $uc_Projects->ucs_DirectoriesNames['images'] . '/Изображение 3D модели ' . $values['device_codename'] . ' - ' . $values['device_name'] . '.jpeg' ;
		

			$count++;
			$status = $statuses[$values['device_status']][0];
			$class = $statuses[$values['device_status']][1];

			$inprojects = "";

			if( isset($values['device_data']['projects']) ){
				if( count($values['device_data']['projects']) > 0 ){
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
						<button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenuButton'.$values['device_id'].'" data-bs-toggle="dropdown" aria-expanded="false">Действие</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$values['device_id'].'">
								<li><a class="dropdown-item" href="/?page=uCrewProjects/devicesItemEdit&id='.$values['device_id'].'">Редактировать</a></li>
								<li><a class="dropdown-item" href="/?page=uCrewProjects/devicesItemRemove&id='.$values['device_id'].'">Архивировать</a></li>
							</ul>
						</div>';

			array_push($rows, 
				array(
					$count => array('class' => 'align-middle text-center'),
					'<img src="'.$device_image.'" class="img-thumbnail imagecat zoom" alt="'.$values['device_codename'].'">' => array('class' => 'align-middle text-center'),
					'<a href="/?page=uCrewProjects/devicesItem&id='.$values['device_id'].'" class="link-dark" style="text-decoration:none">'.$values['device_codename'].'</a>' => array('class' => 'align-middle text-center'),
					'<p>'.$values['device_name'].'</p><p>
					<figcaption class="blockquote-footer">
					Описание:
					<cite title="Source Title">'.$values['device_description'].'</cite>  
					</figcaption><figcaption class="blockquote-footer">
					Характеристики:
					<cite title="Source Title"></cite>  
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
?>
</div>
