<?php
	// Require API
	require_once('uc_modules/uCrewStorage/api/storage_system.php');
	// Init class
	$uc_Storage = new uCrewStorage();
	if(isset($_POST['addCount'])){
		echo $_POST['addCount'];
	}
	// Get item data
	$item = $uc_Storage->getItem($_GET['id']);
	// Get item template
	$template = $uc_Storage->getCategoryTemplate($item['item_category']);
	$data = "";
	foreach($item['item_data']['template_data'] as $parametr => $value){
		if($value == ""){
			$value = "Отсутствует";
		}
		
		$data .= '
			<div class="mb-3 row">
			    <label for="'.$parametr.'" class="col-sm-2 col-form-label">'.$parametr.':</label>
			    <div class="col-sm-10">
			      <p class="form-control-plaintext" id="'.$parametr.'" >'.$value.'</p>
			    </div>
		  	</div>
		';

	}

	$suppliers = "";

	foreach ($item['item_suppliers'] as $index => $values) {
		$suppliers .= $values['supplier_name'] . '<br>';
	}

	 $users = $this->ucDatabase->getUsers();

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
								    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#getItem">Выдать</button>
								    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addToItem">Пополнить</button>
								    <a href="/?page=uCrewStorage/add_сategory" class="btn btn-secondary">Изменить</a>
								    <a href="/?page=uCrewStorage/add_сategory" class="btn btn-danger">Удалить</a>

								  </div>
								</div>
						    </div>
						</div>

						<div class="row">
							  <div id="card mb-4">
						    <div class="card-header">
						        <i class="fas fa-table me-1"></i>
						        Просмотр
						    </div>
						</div>
						    <div class="card-body">
						    	
						


                        <div class="row">
                            <div class="col-xl-8 col-md-6">
                            	<h2><?php echo $item['item_name'];?></h2>
                            	<h6>Категория &rarr; Подкатегория &rarr; Подкатегория</h6>

                            	<hr>

								<div class="mb-3 row">
								    <label for="count" class="col-sm-2 col-form-label">В наличии: </label>
								    <div class="col-sm-10">
								      <p class="form-control-plaintext" id="suppliers"><?php echo $item['item_count'];?></p>
								    </div>
							  	</div>

								<div class="mb-3 row">
								    <label for="location" class="col-sm-2 col-form-label">Расположение: </label>
								    <div class="col-sm-10">
								      <p class="form-control-plaintext" id="location"><?php echo $item['item_location']['location_name']; ?></p>
								    </div>
							  	</div>

								<div class="mb-3 row">
								    <label for="suppliers" class="col-sm-2 col-form-label">Поставщики: </label>
								    <div class="col-sm-10">
								      <p class="form-control-plaintext" id="suppliers"><?php echo $suppliers; ?></p>
								    </div>
							  	</div>

                            	<hr>
<?php
                            //print_r($item);
                            //print_r($template);
							echo $data;
?>


                            </div>
                        </div>

    </div>
						</div>



  <div class="modal fade" id="addToItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Пополнить «<?php echo $item['item_name'];?>»</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/?page=uCrewStorage/item&id=<?php echo $_GET['id']; ?>" method="post">
			<div class="input-group flex-nowrap">
			  <span class="input-group-text" id="addon-wrapping">Введите количество: </span>
			  <input type="text" class="form-control" aria-describedby="addon-wrapping" name="addCount">
			</div>
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-success" value="Применить">
          </form>
        </div>
      </div>
    </div>
  </div>

    <div class="modal fade" id="getItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Выдать «<?php echo $item['item_name'];?>»</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/?page=uCrewStorage/item&id=<?php echo $_GET['id']; ?>" method="post">
			
			<div class="input-group flex-nowrap">
			  <span class="input-group-text" id="addon-wrapping">Введите количество: </span>
			  <input type="text" class="form-control" aria-describedby="addon-wrapping" name="getCount">
			</div>

			<div class="mb-3">
				<label for="user" class="form-label" style="padding-left: 5px; padding-top: 10px">Кому выдать:</label>
				 <select name="user" id="user" class="selectpicker show-tick form-control" aria-label=".form-select-sm example" data-live-search="true" data-size="5">
	<?php
			    foreach ($users as $index => $data) {
			      echo ' <option value="'.$data['user_id'].'" selected>'.$data['user_name'].'</option>' . "\n";
			    }
	?>
	            </select>
	        </div>

        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-success" value="Применить">
          </form>
        </div>
      </div>
    </div>
  </div>
