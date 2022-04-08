<?php
	// Require API
	require_once('uc_modules/uCrewProjects/api/projects_system.php');
	// Init class
	$uc_Projects = new uCrewProjects();
	// Get last codename
	$codename = $uc_Projects->getLastCodeName('mechanics_codename', 'TBM');
	// Get directory data
	$directory_data = $uc_Projects->getProjectDirectoryData();
	// Get materials
	$materials = $uc_Projects->getMechanicMaterials();
	// Generate list
	$options = '';
	foreach ($materials as $material) {
		$options .= "<option value=\"$material\">$material</option>\n";
	}

	// Message variable

	$message = "";

	// Check if isset data
	if(isset($_POST['mechanic_name'])){
		// Add data
		$uc_Projects->addMechanic($_POST, $_FILES);
		$message .= '
			<div class="alert alert-success alert-dismissible fade show" role="alert">
			  Изделие <strong>'.$_POST['mechanic_fullname'].'</strong> успешно добавлено.
			  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		';
	}

	echo $message;
?>

<div class="row">
	<form action="/?page=uCrewProjects/mechanicsAdd" method="post" id="addMechanicForm" enctype="multipart/form-data">
		<h4>Общая информация</h4>
		<hr>
		<input type="hidden" name="mechanic_fullname" id="mechanic_fullname" value="">
		<div class="mb-3">
		  <label for="mechanic_name" class="form-label">Наиминование изделия <i>(*обратите внимание, что следующие символы запрещены: \/():*?"|+.%!@&lt;&gt;)</i></label>
		  <input class="form-control" type="text" id="mechanic_name" name="mechanic_name" required>
		  <p>
		  	<figcaption class="blockquote-footer">
		  		Директория на диске: 
		  		<cite id="directory">
		  			<?php 
		  				echo '"' . $directory_data['mask'] . $uc_Projects->ucs_DirectoriesNames['develop_documentation'] . "\\" . $uc_Projects->ucs_DirectoriesNames['mechanics'] . "\\" . $codename . '"'; 
		  			?>
		  		</cite>  
		  	</figcaption>
		  </p>
		</div>
		<div class="mb-3">
		  <label for="mechanic_description" class="form-label">Краткое описание</label>
		  <input class="form-control" type="text" id="mechanic_description" name="mechanic_description" required>
 			<p>
		  	<figcaption class="blockquote-footer">
		  		Осталось символов: 
		  		<cite id="symbols">250</cite>  
		  	</figcaption>
		  </p>
		</div>
		<div class="mb-3">
		  <label for="mechanic_material" class="form-label">Материал изделия</label>
		  <select class="selectpicker show-tick form-control" id="mechanic_material" name="mechanic_material" data-live-search="true" data-size="5" required>
		  	<option value="unknown" selected>Другое</option>
		  	<?php
		  		echo $options;
		  	?>
		  </select>
		</div>
		<div class="mb-3">
		  <label for="mechanic_status" class="form-label">Статус</label>
		  <select class="form-control" id="mechanic_status" name="mechanic_status" required>
<?php
  	$statuses = $uc_Projects->getStatuses();

  	foreach ($statuses as $id => $data) {
  		$sel = "";
  		if($id == 2){
  			$sel = "selected";
  		}
  		echo '<option value="'.$id.'" class="'.$data[1].'" '.$sel.'>'.$data[0].'</option>';
  	}
?>
		  </select>
		</div>
		<div class="mb-3">
		  <label for="mechanic_codename" class="form-label">Шифр изделия <i>(*присваивается <a href="#" onclick="changeCodeNameState()" id="codeNameState" class="link-dark">автоматически</a>)</i></label>
		  <input class="form-control" type="text" id="mechanic_codename" name="mechanic_codename" readonly value="<?php echo $codename; ?>" required>
		</div>
		<div class="mb-3">
		  <label for="mechanic_image" class="form-label">Изображение изделия - JPEG (*.jpeg, *.jpg)</label>
		  <input class="form-control" type="file" id="mechanic_image" name="mechanic_image" accept="image/jpeg" onchange="changeImagePreview()" required>
			<p>
		  		<figcaption class="blockquote-footer">
		  			Данный файл <cite>является обязательным</cite>  
		  		</figcaption>
		  	</p>
		</div>

		<div class="mb-3" id="imagePreview">
		  <label for="mechanic_image_preview" class="form-label"></label>
		  <img id="mechanic_image_preview" class="img-thumbnail imagecat" src="uc_resources/images/uCrewStorage/categories/unknown.png" class="img-fluid" />
		</div>

		<h4>Файлы изделия</h4>
		<hr>

		<div class="mb-3">
		  <label for="mechanic_3dsource" class="form-label">Исходный файл 3D модели - Компас 3D (*.m3d, *.a3d)</label>
		  <input class="form-control" type="file" id="mechanic_3dsource" name="mechanic_3dsource" accept=".m3d,.a3d" required>
			<p>
		  		<figcaption class="blockquote-footer">
		  			Данный файл <cite>является обязательным</cite>  
		  		</figcaption>
		  	</p>
		</div>
		<div class="mb-3">
		  <label for="mechanic_3dstep" class="form-label">Готовый файл 3D модели - STEP AP203, AP214, AP242 (*.step, *.stp)</label>
		  <input class="form-control" type="file" id="mechanic_3dstep" name="mechanic_3dstep" accept=".step,.stp" required>
			<p>
		  		<figcaption class="blockquote-footer">
		  			Данный файл <cite>является обязательным</cite>  
		  		</figcaption>
		  	</p>
		</div>


		<div class="mb-3">
		  <label for="checkboxes" class="form-label">Дополнительно</label>
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addDraw">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить файлы чертежа изделия
			  </label>
			</div>

			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addLaser" name="addLaser">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить файл для лазера
			  </label>
			</div>
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addPhotos">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить фотографии
			  </label>
			</div>
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addMark">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить шильдик, маркеровку
			  </label>
			</div>
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addOther">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить дополнительные файлы
			  </label>
			</div>
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="generateStl" name="generateStl">
			  <label class="form-check-label" for="flexCheckDefault">
			    Преобразовать готовую 3D модель (*.step, *.stp) в STL (*.stl) файл для 3D печати
			  </label>
			</div>
		</div>


		<div class="mb-3" id="drawSourceSelect">
		  <label for="mechanic_drawsource" class="form-label">Исходный файл чертежа - Компас 3D (*.cdw)</label>
		  <input class="form-control" type="file" id="mechanic_drawsource" name="mechanic_drawsource" accept=".cdw">
		</div>
		<div class="mb-3" id="drawFileSelect">
		  <label for="mechanic_drawpdf" class="form-label">Готовый файл чертежа - Portable Document Format (*.pdf)</label>
		  <input class="form-control" type="file" id="mechanic_drawpdf" name="mechanic_drawpdf" accept=".pdf">
		</div>
		<div class="mb-3" id="drawLaserSelect">
		  <label for="mechanic_drawlaser" class="form-label">Файл для лазера - векторный (*.dxf)</label>
		  <input class="form-control" type="file" id="mechanic_drawlaser" name="mechanic_drawlaser" accept=".dxf">
		</div>
		<div class="mb-3" id="otherSelect">
		  <label for="mechanic_otherfiles" class="form-label">Дополнительные файлы - Любой формат <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="mechanic_otherfiles" name="mechanic_otherfiles" multiple>
		</div>

		<div class="d-grid gap-1 d-md-flex justify-content-md-end" style="padding-bottom: 10px">
			<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMechanicModal" onclick="changeModalPresubmit()">
  				Добавить изделие
			</button>
		</div>

		<div class="modal fade" id="addMechanicModal" tabindex="-1" aria-labelledby="addMechanicModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="addMechanicModalLabel">Проверьте верность данных</h5>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">

				<ul class="list-unstyled">
				  <li id="lmechanic_codename"><i class="fa fa-check-circle" aria-hidden="true"></i> Шифр изделия:</li>
				  <li id="lmechanic_name"><i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование изделия:</li>
				  <li id="lmechanic_description"><i class="fa fa-check-circle" aria-hidden="true"></i> Краткое описание:</li>
				  <li id="lmechanic_material"><i class="fa fa-check-circle" aria-hidden="true"></i> Материал: Алюминий</li>
				  <li id="lmechanic_status"><i class="fa fa-check-circle" aria-hidden="true"></i> Статус: Разработка</li>
				  <li id="lmechanic_image"><i class="fa fa-check-circle" aria-hidden="true"></i> Изображение: присутсвует</li>
				  <li id="lmechanic_directory"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Расположение файлов:
					  <ul>
					  	<li>
					  		<?php echo $uc_Projects->ucs_DirectoriesNames['develop_documentation']; ?>
					  		<ul>
					  			<li>
					  				<?php echo $uc_Projects->ucs_DirectoriesNames['mechanics']; ?>
					  				<ul>
							  			<li> <div id="lmechanic_fullname">Название</div>
							  				<ul>
							  					<li>
							  						Документация
							  						<ul>
							  							<li id="lfdescription"> 
							  								<i class="fa fa-file" aria-hidden="true"></i> Спецефикации и аннотации
							  							</li>
							  						</ul>
							  					</li>
							  					<li id="lfdrawdir">Чертёж
													<ul>
							  							<li id="lfdrawpdf"> 
							  								<i class="fa fa-file" aria-hidden="true"></i> Чертёж pdf
							  							</li>
							  						</ul>
							  					</li>
							  					<li>3D модель
							  						<ul>
							  							<li id="lf3dstep"> 
							  								<i class="fa fa-file" aria-hidden="true"></i> 3D модель
							  							</li>
							  						</ul>
							  					</li>
							  					<li id="lfdrawvectordir">Векторные файлы
							  						<ul>
							  							<li id="lfdrawvector"> 
							  								<i class="fa fa-file" aria-hidden="true"></i> 3D модель
							  							</li>
							  						</ul>
							  					</li>
							  					<li>Изображение
													<ul>
							  							<li id="lfimage"> 
							  								<i class="fa fa-file" aria-hidden="true"></i> Изображение
							  							</li>
							  						</ul>
							  					</li>
							  					<li>Исходные файлы
													<ul>
							  							<li id="lf3dsource"> 
							  								<i class="fa fa-file" aria-hidden="true"></i> 3D модель исходние
							  							</li>
							  							<li id="lfdrawsource"> 
							  								<i class="fa fa-file" aria-hidden="true"></i> Чертёж исходник
							  							</li>
							  						</ul>
							  					</li>
							  				</ul>
							  			</li>
					  				</ul>
					  			</li>
					  		</ul>
					  	</li>
					  </ul>
				  </li>
				  <li id="warn"  class="text-center">
				  	<br>
				  	<i>* Обратите внимание! У вас недостаточно информации!</i>
				  </li>

				</ul>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
		        <input type="submit" name="addMechanic" class="btn btn-success me-md-1" value="Добавить изделие">
		      </div>
		    </div>
		  </div>
		</div>

	</form>
</div>

<script type="text/javascript">

	var readonly = true;
	var imageFile = '';
	var source3dFile = '';
	var step3dFile = '';
	var sourcedrawFile = '';
	var pdfdrawFile = '';
	var vectordrawFile = '';

	function changeCodeNameState(){
		if(readonly == true){
			readonly = false;
			$("#codeNameState").html("пользователем");
		}else{
			readonly = true;
			$("#codeNameState").html("автоматически");
		}

		$("#mechanic_codename").attr("readonly", readonly);  
	}

	function changeImagePreview() {
		$("#imagePreview").show("fast");

  		var file = $("#mechanic_image").get(0).files[0];

        if(file){
            var reader = new FileReader();

            reader.onload = function(){
                $("#mechanic_image_preview").attr("src", reader.result);
            }

            reader.readAsDataURL(file);
        }		
	}

	function changeModalPresubmit() {
		var success = 'fa fa-check-circle';
		var warning = 'fa fa-exclamation-circle';
		var error = 'fa fa-times-circle';

		var filename = $('#mechanic_codename').val();

		if($('#mechanic_name').val().length > 0){
			filename =  filename + ' - ' + $('#mechanic_name').val();
		}

		// Check mechanics name
		var mechanics_state = error;
		var mechanics_value = $('#mechanic_name').val();
		if( mechanics_value.length > 0 ){
			mechanics_state = success;
		}
		var html = '<i class="' + mechanics_state + '" aria-hidden="true"></i> Наиминование изделия: ' + mechanics_value + '</li>';
		$('#lmechanic_name').html(html)

		// Check mechanics codename
		var mechanics_state = error;
		var mechanics_value = $('#mechanic_codename').val();
		if( mechanics_value.length > 0 ){
			mechanics_state = success;
		}
		var html = '<i class="' + mechanics_state + '" aria-hidden="true"></i> Шифр изделия: ' + mechanics_value + '</li>';
		$('#lmechanic_codename').html(html)


		// Check mechanics description
		var mechanics_state = error;
		var mechanics_value = $('#mechanic_description').val();
		if( mechanics_value.length > 0 ){
			mechanics_state = success;
		}
		var html = '<i class="' + mechanics_state + '" aria-hidden="true"></i> Краткое описание: ' + mechanics_value + '</li>';
		$('#lmechanic_description').html(html)

		// Check mechanics image
		var mechanics_state = error;
		var mechanics_value = imageFile;
		var html = '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Файл не выбран.';

		if( mechanics_value.length > 0 ){
			mechanics_state = success;
			var html = '<i class="fa fa-file" aria-hidden="true"></i> Изображение ' + filename + '.jpeg';
		}

		$('#lfimage').html(html);

		var html = '<i class="' + mechanics_state + '" aria-hidden="true"></i> Изображение: ' + mechanics_value + '</li>';
		$('#lmechanic_image').html(html);

		// Check mechanics material
		var mechanics_state = error;
		var mechanics_value = $('#mechanic_material option:selected').text();
		if( mechanics_value.length > 0 ){
			mechanics_state = success;
		}
		if( mechanics_value == 'Другое'){
			mechanics_state = warning;
		}
		var html = '<i class="' + mechanics_state + '" aria-hidden="true"></i> Материал: ' + mechanics_value + '</li>';
		$('#lmechanic_material').html(html);

		// Check mechanics status
		var mechanics_state = error;
		var mechanics_value = $('#mechanic_status option:selected').text();
		if( mechanics_value.length > 0 ){
			mechanics_state = success;
		}
		var html = '<i class="' + mechanics_state + '" aria-hidden="true"></i> Статус: ' + mechanics_value + '</li>';
		$('#lmechanic_status').html(html);

		var html = '<i class="fa fa-file" aria-hidden="true"></i> Описание ' + filename + '.txt';
		$('#lfdescription').html(html);


		if( source3dFile.length > 0 ){
			var html = '<i class="fa fa-file" aria-hidden="true"></i> ' + filename + '.m3d';
		}else{
			var html = '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Файл не выбран.';
		}

		$('#lf3dsource').html(html);

		if( step3dFile.length > 0 ){
			var html = '<i class="fa fa-file" aria-hidden="true"></i> ' + filename + '.step';
		}else{
			var html = '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Файл не выбран.';
		}

		$('#lf3dstep').html(html);

		if( sourcedrawFile.length > 0 ){
			var html = '<i class="fa fa-file" aria-hidden="true"></i> ' + filename + '.cdw';
		}else{
			var html = '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Файл не выбран.';
		}

		$('#lfdrawsource').html(html);

		if( pdfdrawFile.length > 0 ){
			var html = '<i class="fa fa-file" aria-hidden="true"></i> ' + filename + '.pdf';
		}else{
			var html = '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Файл не выбран.';
		}

		$('#lfdrawpdf').html(html);	

		if( vectordrawFile.length > 0 ){
			var html = '<i class="fa fa-file" aria-hidden="true"></i> ' + filename + '.dxf';
		}else{
			var html = '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Файл не выбран.';
		}

		$('#lfdrawvector').html(html);	


		$('#lmechanic_fullname').html(filename);
		$('#mechanic_fullname').val(filename);	
	}

	$( document ).ready(function() {
		// On load functions
		$("#imagePreview").hide();
		$("#drawSourceSelect").hide();
		$("#drawFileSelect").hide();
		$("#drawLaserSelect").hide();
		$("#otherSelect").hide();
		$("#lfdrawsource").hide();
		$("#lfdrawdir").hide();
		$("#lfdrawpdf").hide();
		$("#lfdrawvectordir").hide();


		// Description change
	    $('#mechanic_description').on('input', function(){ 
	    	var count = 250 - $('#mechanic_description').val().length;
	    	$('#symbols').html(count);
	    });
	    
	    // Replace bad symbols in name
	    $('body').on('input', '#mechanic_name', function(){
			this.value = this.value.replace(/[^0-9A-Za-zА-Яа-я\.\, ]/g, '');
		});

	    // Set mechanic name
	    $('#mechanic_name').on('input', function(){ 
	    	var text = $('#mechanic_name').val().replace(/[^0-9A-Za-zА-Яа-я\.\, ]/g, '');
	    	
	    	$('#lmechanic_name').html(
	    		'<i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование изделия: <i>' + text + '</i>'
	    	);

	    	if($('#mechanic_name').val().length > 0){
	    		text = ' - ' + text;
	    	}

	    	$('#directory').html('"<?php echo $directory_data['mask'] ?>Оборудование\\Механические изделия\\' + $('#mechanic_codename').val() + text  + '"');
	    });

	    // On image change
	     $('#mechanic_image').change(function(e){
            var fileName = e.target.files[0].name;
            imageFile = fileName;
        });

	    // On source 3d change
	     $('#mechanic_3dsource').change(function(e){
            var fileName = e.target.files[0].name;
            source3dFile = fileName;
        });

	    // On source 3d change
	     $('#mechanic_3dstep').change(function(e){
            var fileName = e.target.files[0].name;
            step3dFile = fileName;
        });

		// On source draw change
	     $('#mechanic_drawsource').change(function(e){
            var fileName = e.target.files[0].name;
            sourcedrawFile = fileName;
        });

        // On source pdf change
	     $('#mechanic_drawpdf').change(function(e){
            var fileName = e.target.files[0].name;
            pdfdrawFile = fileName;
        });

        // On source vector change
	     $('#mechanic_drawlaser').change(function(e){
            var fileName = e.target.files[0].name;
            vectordrawFile = fileName;
        });

	    // On add draw changed
	    $('#addDraw').change(function(e){
			if ($(this).is(':checked')) {
				$("#drawSourceSelect").show("fast");
				$("#drawFileSelect").show("fast");

				$("#lfdrawsource").show("fast");
				$("#lfdrawdir").show("fast");
				$("#lfdrawpdf").show("fast");
  			}else{
				$("#drawSourceSelect").hide("fast");
				$("#drawFileSelect").hide("fast");

				$("#lfdrawsource").hide("fast");
				$("#lfdrawdir").hide("fast");
				$("#lfdrawpdf").hide("fast");
  			}
        });

	    // On other change
	    $('#addOther').change(function(e){
			if ($(this).is(':checked')) {
				$("#otherSelect").show("fast");
  			}else{
				$("#otherSelect").hide("fast");
  			}
        });

       	// On laser change
	    $('#addLaser').change(function(e){
			if ($(this).is(':checked')) {
				$("#drawLaserSelect").show("fast");
				$("#lfdrawvectordir").show("fast");
  			}else{
				$("#drawLaserSelect").hide("fast");
				$("#lfdrawvectordir").hide("fast");
  			}
        });

	});
</script>