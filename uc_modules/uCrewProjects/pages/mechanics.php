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
	$pager = $uc_Projects->getMechanicsPager($page, $count);
?>

<div class="row">
	<table class="table table-hover">
		<thead>
		  <tr>
		    <th scope="col" width="5%" class="text-center">№</th>
		    <th scope="col" width="150px" class="text-center">Изображение</th>
		    <th scope="col" width="10%" class="text-center">Шифр</th>
		    <th scope="col" width="40%">Краткая информация</th>
		    <th scope="col" class="text-center">Статус</th>
		    <th scope="col" >Описание</th>
		    <th scope="col" class="text-center">Управление</th>
		  </tr>
		</thead>
		<tbody>
<?php

	$count = ($page * $count) - $count;

	foreach ($list as $item => $values) {
		$count++;

		$image = $values['mechanic_image'];

		if($image == ""){
			$image = 'uc_resources/images/uCrewStorage/categories/unknown.png';
		}

		$status = $values['mechanic_status'];
		$class = "";

		switch($status){
			case '1':
				$status = "Применяется";
				$class = "text-primary";
				break;

			case '2':
				$status = "Разработка";
				$class = "text-danger";
				break;

			case '3':
				$status = "Архивный";
				$class = "text-secondary";
				break;
		}

		echo '
			<tr>
				<td class="align-middle text-center">'.$count.'</td>
				<td><img src="'.$image.'" class="img-thumbnail imagecat" alt="'.$image.'"></td>
				<td class="align-middle text-center">'.$values['mechanic_codename'].'</td>
				<td class="align-middle">
					<p>'.$values['mechanic_name'].'</p>
					<p>
						<figcaption class="blockquote-footer">
							Применятся в проектах:
							<cite title="Source Title">КИПТ "Азимут 4 - Моноблок"</cite>  
						</figcaption>
					</p>
					<p>
						<figcaption class="blockquote-footer">
							Состоить из нескольких частей:
							<cite title="Source Title">TBM0012, TBM0013, TBM0014, TBM00..</cite>  
						</figcaption>
					</p>
				</td>
				<td class="align-middle text-center '.$class.'">'.$status.'</td>
				<td class="align-middle">'.$values['mechanic_description'].'</td>
				<td class="align-middle text-center">

				<div class="dropdown">
					<button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenuButton'.$values['mechanic_id'].'" data-bs-toggle="dropdown" aria-expanded="false">Действие</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$values['mechanic_id'].'">
							<li><a class="dropdown-item" href="#">Издменить</a></li>
							<li><a class="dropdown-item" href="#">Удалить</a></li>
						</ul>
					</div>
				</td>
			</tr>
		';
	}
?>
		</tbody>
	</table>
<?php
	echo $pager;
?>
</div>