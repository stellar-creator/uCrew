<?php
	// Require API
	require_once('uc_modules/uCrewStorage/api/storage_system.php');
	// Init class
	$uc_Storage = new uCrewStorage();
	// Table variable
	$table = "";
	// Index variable
	$index = 0;
	// Items variable
	$items = 0;
	// Check if isset category
	$current_category = 0;
	if(isset($_GET['c'])){
		$current_category = $_GET['c'];
		// Items
		$items = $uc_Storage->getCategoryItems($_GET['c']);
	}
	// Generate table
	foreach ($uc_Storage->collectCategories($current_category) as $id => $data) {
		$index = $index + 1;
		$subcategories = 0;
		if(isset($data['subcategories'])){
			$subcategories = count($data['subcategories']);
		}
		if($data['category_image'] == ''){
			$data['category_image'] = 'uc_resources/images/uCrewStorage/categories/unknown.png';
		}
		$table .= '	
								<tr>
							      <th scope="row"  class="align-middle">'.$index.'</th>
							      <td><img src="'.$data['category_image'].'" class="img-thumbnail imagecat" alt="'.$data['category_name'].'"></td>
							      <td><a href="/?page=uCrewStorage/nomenclature&c='.$data['category_id'].'" class="link-dark">'.$data['category_name'].'</a></td>
							      <td>'.$data['category_description'].'</td>
							      <td>'.$subcategories.'</td>

							      <td  class="align-middle">
									<div class="dropdown">
									  <button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenuButton'.$index.'" data-bs-toggle="dropdown" aria-expanded="false">
									    Действие
									  </button>
									  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$index.'">
									    <li><a class="dropdown-item" href="#">Издменить</a></li>
									    <li><a class="dropdown-item" href="#">Удалить</a></li>
									  </ul>
									</div>
									</td>
							    </tr>' . "\n";
	}

?>
						<div class="row">
							  <div id="card mb-4">
						    <div class="card-header">
						        <i class="fas fa-table me-1"></i>
						        Управлние
						    </div>
						</div>
						    <div class="card-body">
						    	<div class="row g-4">
								  <div class="col float-end">
								    <button type="button" class="btn btn-secondary">Добавить позицию</button>
								    <a href="/?page=uCrewStorage/add_сategory" class="btn btn-secondary">Добавить категорию</a>
<?php
	if($items != 0 OR $index == 0){
		echo '<a href="/?page=uCrewStorage/templates&c=' . $current_category. '" class="btn btn-secondary">Редактировать шаблон</a> ';
		echo '  <button type="button" class="btn btn-success" id="write" name="write" data-bs-toggle="modal" data-bs-target="#filterModal">Фильтры</button>';
	}
?>
		    

								  </div>
								</div>
						    </div>
						</div>

<?php 
	if($index != 0 or $current_category == 0){
		echo '						<div class="row">
						  <div id="card mb-4">
					    <div class="card-header">
					        <i class="fas fa-table me-1"></i>
					        Все категории
					    </div>
					    <div class="card-body">
							<table class="table table-hover">
							  <thead>
							    <tr>
							      <th scope="col" width="50px">№</th>
							      <th scope="col" width="150px">Изображение</th>
							      <th scope="col">Наиминование</th>
							      <th scope="col">Описание</th>
							      <th scope="col">Подкатегорий</th>
							      <th scope="col">Управление</th>
							    </tr>
							  </thead>
							  <tbody>
							  ' . $table . '
							  </tbody>
							</table>
						</div>
					</div>';
	}else{


		$table_header = "";

		if($items != 0){

			$template = $uc_Storage->getCategoryTemplate($_GET['c']);


			foreach ($template['template_data'] as $name => $data) {
				if(!isset($data['hide'])){
					$table_header .= '<th scope="col">' . $name . '</th>' . "\n";
				}
			}

			foreach ($items as $index => $data) {
						// Suppliers list
						$suppliers = "";
						// Table data by template
						$table_data = "";
						// Add header col by template
						foreach ($data['item_suppliers'] as $supindex => $supdata) {
							$suppliers .= $supdata['supplier_name'] . '<br> ';
						}
						// Add table 
						foreach ($template['template_data'] as $name => $tdata) {
				
							if(!isset($tdata['hide'])){
								$table_data .= '<td>' . $data['item_data']['template_data'][$name] . '</td>' . "\n";
							}
						}

						if($data['item_data']['image'] == ''){
							$data['item_data']['image'] = 'uc_resources/images/uCrewStorage/categories/unknown.png';
						}

						$table .= '		    <tr>
						
						      <th scope="row" rowspan="2" class="align-middle">'.($index + 1).'</th>
						      <td><img src="'.$data['item_data']['image'].'" class="img-thumbnail imagecat" alt="'.$data['item_name'].'"></td>
						      <td><a href="/?page=uCrewStorage/item&id='.$data['item_id'].'" class="link-dark">'.$data['item_name'].'</a></td>
						      '. $table_data .'
						      <td>'.$data['item_count'].'</td>
						      <td>Нет</td>

						      <td class="align-middle">
				<div class="dropdown">
				  <button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenuButton'.$index.'" data-bs-toggle="dropdown" aria-expanded="false">
				    Действие
				  </button>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$index.'">
				    <li><a class="dropdown-item" href="#">Издменить</a></li>
				    <li><a class="dropdown-item" href="#">Удалить</a></li>
				  </ul>
				</div>
				</td>';
				}
			}

			echo '<div class="row">
				  <div id="card mb-4">
				    <div class="card-header">
				        <i class="fas fa-table me-1"></i>
				        Все категории
				    </div>
				    <div class="card-body">
							<table class="table table-hover">
							  <thead>
							    <tr>
							      <th scope="col" width="50px">№</th>
							      <th scope="col" width="150px">Изображение</th>
							      <th scope="col">Наиминование</th>
							      ' . $table_header . '
							      <th scope="col" width="100px">Кол-во</th>
							      <th scope="col" width="200px">Состовная позиция</th>
							      <th scope="col">Управление</th>
							    </tr>
							  </thead>
							  <tbody>
							  ' . $table . '
							  </tbody>
							</table>
						</div>
					</div>

  <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Выберите фильтры</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/" method="get">
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary" value="Применить фильтры">
          </form>
        </div>
      </div>
    </div>
  </div>


					';	

	}
?>