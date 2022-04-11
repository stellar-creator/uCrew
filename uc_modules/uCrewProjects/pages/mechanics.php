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
	$list = $uc_Projects->getMechanicsList($page, $count);
	// Get pager
	$pager = $this->uc_CompilatorData->generatePager(
		$page,
		$count,
		$uc_Projects->ucs_Database->getRecordsCount('ucp_mechanics'),
		'/?page=uCrewProjects/mechanics'
	);
?>
<div class="row">
	<div class="d-grid gap-1 d-md-flex justify-content-md-end" style="padding-bottom: 10px">
	 	<a href="/?page=uCrewProjects/mechanicsAdd" class="btn btn-primary me-md-1" >
	 		<i class="fa fa-plus-square" style="padding-right: 5px"></i> Добавить изделие
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
		'Материал',
		'Управление' => array('class' => 'text-center'),
	);

	$count = ($page * $count) - $count;
  	$statuses = $uc_Projects->getStatuses();
  	$rows = array();

  	if($list != 0){
		foreach ($list as $item => $values) {

			$mechanic_paths = $uc_Projects->ucs_DirectoriesPath['mechanics'];

			$mechanic_paths['web'] = $mechanic_paths['web'] . $values['mechanic_data']['fullname'] . '/';
			$mechanic_image = $mechanic_paths['web'] . $uc_Projects->ucs_DirectoriesNames['images'] . '/' . 'Изображение ' . $values['mechanic_data']['fullname'] . '.jpeg' ;
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
					'<img src="'.$mechanic_image.'" class="img-thumbnail imagecat" alt="'.$values['mechanic_codename'].'">',
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

	echo $pager;
?>
</div>
