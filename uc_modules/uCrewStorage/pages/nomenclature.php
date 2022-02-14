<?php
	// Require API
	require_once('uc_modules/uCrewStorage/api/storage_system.php');
	// Init class
	$uc_Storage = new uCrewStorage();
	// Table variable
	$table = "";
	// Index ariable
	$index = 0;
	// Check if isset category
	$current_category = 0;
	if(isset($_GET['c'])){
		$current_category = $_GET['c'];
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
		$table .= '		    <tr>
		
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
		    </tr>';
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
    	<div class="row g-3">
		  <div class="col float-end">
		    <button type="button" class="btn btn-secondary">Добавить позицию</button>
		    <a href="/?page=uCrewStorage/add_сategory"class="btn btn-secondary">Добавить категорию</a>
		  </div>
		</div>
    </div>
</div>

<?php 
	if($index != 0 or $current_category == 0){
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
		// Items
		$items = $uc_Storage->getCategoryItems($_GET['c']);

		if($items != 0){

		foreach ($items as $index => $data) {

					$suppliers = "";
					foreach ($data['item_suppliers'] as $supindex => $supdata) {
						$suppliers .= $supdata['supplier_name'] . '<br> ';
					}
					$table .= '		    <tr>
					
					      <th scope="row" rowspan="2" class="align-middle">'.($index + 1).'</th>
					      <td>'.$data['item_data']['image'].'</td>
					      <td><a href="/?page=uCrewStorage/nomenclature&i='.$data['item_id'].'" class="link-dark">'.$data['item_name'].'</a></td>
					      <td>'.$data['item_data']['description'].'</td>
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
						      <th scope="col">Описание</th>
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
				</div>';		
	}
?>