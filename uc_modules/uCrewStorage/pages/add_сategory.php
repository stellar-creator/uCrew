<?php	
	// Require API
	require_once('uc_modules/uCrewStorage/api/storage_system.php');
	// Init class
	$uc_Storage = new uCrewStorage();
	$message = "";
 	if(isset($_POST['name']) && isset($_POST['description'])){
 		$message = $uc_Storage->addCategory($_POST['name'], $_POST['description'], $_POST['subcategory'], "", $_FILES);
 	}

 	$categories = $uc_Storage->collectCategories();

?>
                <div class="row">

                    <form class="col-xl-8" action="/?page=uCrewStorage/add_сategory" method="post" enctype="multipart/form-data">
<?php
	echo $message;
?>
                          <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Наиминование</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" name="name" id="name">
                            </div>
                          </div>
                          <div class="row mb-3">
                            <label for="description" class="col-sm-2 col-form-label">Описание</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" name="description" id="description">
                            </div>
                          </div>
                          <div class="row mb-3">
                            <label for="subcategory" class="col-sm-2 col-form-label">Подкатегория</label>
                            <div class="col-sm-10">
                              <select class="form-select form-control" aria-label="Default select example" name="subcategory">
								  <option value="0" selected>Без подкатегории</option>
<?php
	$uc_Storage->getCategoriesSelect($categories);
?>
								</select>
                            </div>
                          </div>
<!--                          <div class="row mb-3">
                            <label for="template" class="col-sm-2 col-form-label">Шаблон</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="template" name="template" readonly>
                            </div>
                          </div>
-->
                          <div class="row mb-3">
                            <label for="image" class="col-sm-2 col-form-label">Изображение</label>
                            <div class="col-sm-10">
                              <input class="form-control" type="file" id="image" name="image">
                            </div>
                          </div>

                          
                          <div class="row mb-3 float-end">
                            <button type="submit" class="btn btn-primary">Добавить категрию</button>
                          </div>
                        </form>
                    </div>