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
	// Get pcbs list
	$list = $uc_Projects->getPcbsList($page, $count);
	$pager = $uc_Projects->getPager($page, $count, 'ucp_pcbs', '/?page=uCrewProjects/pcbs');
?>
<div class="row">
	<div class="d-grid gap-1 d-md-flex justify-content-md-end" style="padding-bottom: 10px">
	 	<a href="/?page=uCrewProjects/pcbAdd" class="btn btn-primary me-md-1" >
	 		<i class="fa fa-plus-square" style="padding-right: 5px"></i> Добавить печатную плату
		</a>
	</div>
</div>

<div class="row">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
			  <tr>
			    <th scope="col" width="5%" class="text-center">№</th>
			    <th scope="col" width="150px" class="text-center">Изображение</th>
			    <th scope="col" width="10%" class="text-center">Шифр</th>
			    <th scope="col" width="40%">Краткая информация</th>
			    <th scope="col" class="text-center">Статус</th>
			    <th scope="col" class="text-center">Управление</th>
			  </tr>
			</thead>
			<tbody>
<?php
	$count = ($page * $count) - $count;
  	$statuses = $uc_Projects->getStatuses();
  	if($list != 0){
		foreach ($list as $item => $values) {
			$count++;

			$image = $values['cable_image'];

			if($image == ""){
				$image = 'uc_resources/images/uCrewStorage/categories/unknown.png';
			}

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

			echo '
				<tr>
					<td class="align-middle text-center">'.$count.'</td>
					<td><img src="'.$image.'" class="img-thumbnail imagecat" alt="'.$image.'"></td>
					<td class="align-middle text-center"><a href="/?page=uCrewProjects/pcbsItem&id='.$values['cable_id'].'" class="link-dark" style="text-decoration:none">'.$values['cable_codename'].'</a></td>
					<td class="align-middle">
						<p>'.$values['cable_name'].'</p>
						<p>
							<figcaption class="blockquote-footer">
								Описание:
								<cite title="Source Title">'.$values['cable_description'].'</cite>  
							</figcaption>
						</p>
						'.$inprojects.'
					</td>
					<td class="align-middle text-center '.$class.'">'.$status.'</td>
					<td class="align-middle text-center">

					<div class="dropdown">
						<button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenuButton'.$values['cable_id'].'" data-bs-toggle="dropdown" aria-expanded="false">Действие</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$values['cable_id'].'">
								<li><a class="dropdown-item" href="/?page=uCrewProjects/pcbsItemEdit&id='.$values['cable_id'].'">Редактировать</a></li>
								<li><a class="dropdown-item" href="/?page=uCrewProjects/pcbsItemRemove&id='.$values['cable_id'].'">Архивировать</a></li>
							</ul>
						</div>
					</td>
				</tr>
			';
		}
	}
?>
			</tbody>
		</table>
	</div>
<?php
	echo $pager;
?>
</div>