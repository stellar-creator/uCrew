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
		$table .= '		    <tr>
		
		      <th scope="row">'.$index.'</th>
		      <td>'.$data['category_image'].'</td>
		      <td><a href="/?page=uCrewStorage/nomenclature&c='.$data['category_id'].'" class="link-dark">'.$data['category_name'].'</a></td>
		      <td>'.$data['category_description'].'</td>
		      <td>'.$subcategories.'</td>

		      <td>
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
		    <button type="button" class="btn btn-secondary">Добавить номенклатуру</button>
		    <button type="button" class="btn btn-secondary">Добавить категорию</button>
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
				      <th scope="col">№</th>
				      <th scope="col">Изображение</th>
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
		
	}
?>