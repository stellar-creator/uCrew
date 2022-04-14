<?php
	// Require API
	require_once('uc_modules/uCrewProjects/api/projects_system.php');
	// Init class
	$uc_Projects = new uCrewProjects();
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
		  <select class="selectpicker show-tick form-control" id="mechanic_material" name="mechanic_material" data-live-search="true" data-size="15" required>
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
		  <input type="hidden" id="mechanic_codename_state" name="mechanic_codename_state" value="auto">
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
			  <input class="form-check-input" type="checkbox" id="addStl">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить 3D модель для печати (*.stl)
			  </label>
			</div>
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addDraw">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить файлы чертежа изделия (*.pdf, *.cdw)
			  </label>
			</div>

			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addLaser" name="addLaser">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить векторный файл для лазера (*.dxf)
			  </label>
			</div>
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addPhotos">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить фотографии (*.jpeg, *.png)
			  </label>
			</div>
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addMarks">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить шильдик, маркировку (*.*)
			  </label>
			</div>
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addAnnotations">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить аннотации (*.txt, *.docx, *.pdf)
			  </label>
			</div>
		</div>

		<div class="mb-3" id="3dStlSourceSelect">
		  <label for="mechanic_3dstl" class="form-label">Готовый файл 3D модели - STL (*.stl)</label>
		  <input class="form-control" type="file" id="mechanic_3dstl" name="mechanic_3dstl" accept=".stl">
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
		<div class="mb-3" id="photoSelect">
		  <label for="mechanic_photos" class="form-label">Фотографии <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="mechanic_photos" name="mechanic_photos[]"  accept=".jpeg,.jpg" multiple="multiple">
		</div>
		<div class="mb-3" id="marksSelect">
		  <label for="mechanic_marks" class="form-label">Маркировка, наклейки - любой формат <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="mechanic_marks" name="mechanic_marks[]" multiple="multiple">
		</div>

		<div class="mb-3" id="annotationsSelect">
		  <label for="mechanic_annotations" class="form-label">Аннотации - Text (*.txt), Microsoft Word (*.docx), Portable Document Format (*.pdf) <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="mechanic_annotations" name="mechanic_annotations[]" multiple="multiple"  accept=".txt,.docx,.pdf">
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
				  <li id="lmechanic_codename"><i class="fa fa-check-circle" aria-hidden="true"></i> Шифр изделия: *</li>
				  <li id="lmechanic_name"><i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование изделия: *</li>
				  <li id="lmechanic_description"><i class="fa fa-check-circle" aria-hidden="true"></i> Краткое описание: *</li>
				  <li id="lmechanic_material"><i class="fa fa-check-circle" aria-hidden="true"></i> Материал: *</li>
				  <li id="lmechanic_status"><i class="fa fa-check-circle" aria-hidden="true"></i> Статус: *</li>
				  <li id="lmechanic_image"><i class="fa fa-check-circle" aria-hidden="true"></i> Изображение: *</li>
				  <li id="lmechanic_directory">
				  	<i class="fa fa-exclamation-circle" aria-hidden="true"></i> Расположение файлов:
<?php
					/*$fullpath = array(
						$uc_Projects->ucs_DirectoriesNames['develop_documentation'] => array(
							$uc_Projects->ucs_DirectoriesNames['mechanics'] => array( 'Шифр' =>
								$uc_Projects->ucs_DirectoriesTemplates['mechanics']
							)
						)
					);

					print_r($this->uc_CompilatorData->arrayToList($fullpath));*/
?>
				<ul>
					<li>
					   Конструкторская документация
					   <ul>
					      <li id="mechanics_dir">
					         Механические изделия
					         <ul>
					            <li id="codename">
					               <div id="f_mechanic_fullname">Шифр</div>
					               <ul>
					                  <li id="f3D_modeli">
					                  3D модели
						                  <ul>
						                  	<li id="f_3dsource">
						                  		<i class="fa fa-file" aria-hidden="true"></i> Исходный файл
						                  	</li>
						                  	<li id="f_3dstep">
						                  		<i class="fa fa-file" aria-hidden="true"></i> 3D модель step
						                  	</li>
						                  	<li id="f_3dstl">
						                  		<i class="fa fa-file" aria-hidden="true"></i> 3D модель stl
						                  	</li>
						                  	<li id="f_3dx3d">
						                  		<i class="fa fa-file" aria-hidden="true"></i> 3D модель x3d
						                  	</li>
						                  </ul>
					              	  </li>
					                  <li id="f_drawings">
					                  Чертежи
 										<ul>
						                  	<li id="f_drawsource">
						                  		<i class="fa fa-file" aria-hidden="true"></i> Исходный файл
						                  	</li>
						                  	<li id="f_drawpdf">
						                  		<i class="fa fa-file" aria-hidden="true"></i> PDF
						                  	</li>
						                  </ul>
					              	  </li>
					                  <li id="f_vectors">
					                  Векторные файлы
 										<ul>
						                  	<li id="f_vector">
						                  		<i class="fa fa-file" aria-hidden="true"></i> DXF
						                  	</li>
						                </ul>
					              	  </li>
					                  <li id="f_images">
					                     Изображения
					                     <ul>
					                        <li id="f_photos">Фотографии</li>
					                        <li id="f_marks">Маркировка и наклейки</li>
					                        <li id="f_image">
						                  		<i class="fa fa-file" aria-hidden="true"></i>
						                  	</li>

					                     </ul>
					                  </li>
					                  <li id="f_annotations">
					                  	Аннотации
					                  </li>
					                  <li id="f_description">
					                  	<i class="fa fa-file" aria-hidden="true"></i> Описание 
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
				  	<i>* Внимательно проверьте информацию!</i>
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
	var stl3dFile = '';
	var sourcedrawFile = '';
	var pdfdrawFile = '';
	var vectordrawFile = '';
	var photos = [];
	var annotations = [];
	var marks = [];


	// Change codename state
	function changeCodeNameState(){
		if(readonly == true){
			readonly = false;
			$("#codeNameState").html("пользователем");
			$("#mechanic_codename_state").val("manual");

		}else{
			readonly = true;
			$("#codeNameState").html("автоматически");
			$("#mechanic_codename_state").val("auto");
		}

		$("#mechanic_codename").attr("readonly", readonly);  
	}

	// Set image preview
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

	$( document ).ready(function() {
		// On load functions
		$("#imagePreview").hide();
		$("#drawSourceSelect").hide();
		$("#drawFileSelect").hide();
		$("#drawLaserSelect").hide();
		$("#annotationsSelect").hide();
		$("#photoSelect").hide();
		$("#marksSelect").hide();
		$("#3dStlSourceSelect").hide();

		$("#f_drawings").hide();
		$("#f_vectors").hide();
		$("#f_photos").hide();
		$("#f_marks").hide();
		$("#f_annotations").hide();		
		$("#f_3dstl").hide();

		// Description change
	    $('#mechanic_description').on('input', function(){ 
	    	var count = 250 - $('#mechanic_description').val().length;
	    	$('#symbols').html(count);
	    });
	    
	    // Replace bad symbols in name
	    $('body').on('input', '#mechanic_name', function(){
			this.value = this.value.replace(/[^0-9A-Za-zА-Яа-яЁё\-\. ]/g, '');
		});

	    // Set mechanic name
	    $('#mechanic_name').on('input', function(){ 
	    	var text = $('#mechanic_name').val().replace(/[^0-9A-Za-zА-Яа-яЁё\-\. ]/g, '');
	    	
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

	    // On source 3d change
	     $('#mechanic_3dstl').change(function(e){
            var fileName = e.target.files[0].name;
            stl3dFile = fileName;
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

		// On source photos
	    $('#mechanic_photos').change(function(e){
	    	photos = [];
            for (var i = 0; i < e.target.files.length; i++){
				photos.push(e.target.files[i].name);
			}
        });

        // On source photos
	    $('#mechanic_annotations').change(function(e){
	    	annotations = [];
            for (var i = 0; i < e.target.files.length; i++){
				annotations.push(e.target.files[i].name);
			}
        });

        // On source photos
	    $('#mechanic_marks').change(function(e){
	    	marks = [];
            for (var i = 0; i < e.target.files.length; i++){
				marks.push(e.target.files[i].name);
			}
        });

	    // On add draw changed
	    $('#addDraw').change(function(e){
			if ($(this).is(':checked')) {
				$("#drawSourceSelect").show("fast");
				$("#drawFileSelect").show("fast");
				$("#f_drawings").show("fast");
  			}else{
				$("#drawSourceSelect").hide("fast");
				$("#drawFileSelect").hide("fast");
				$("#f_drawings").hide("fast");
  			}
        });

	    // On other change
	    $('#addAnnotations').change(function(e){
			if ($(this).is(':checked')) {
				$("#annotationsSelect").show("fast");
				$("#f_annotations").show("fast");
  			}else{
				$("#annotationsSelect").hide("fast");
				$("#f_annotations").hide("fast");
  			}
        });

       	// On laser change
	    $('#addLaser').change(function(e){
			if ($(this).is(':checked')) {
				$("#drawLaserSelect").show("fast");
				$("#f_vectors").show("fast");
  			}else{
				$("#drawLaserSelect").hide("fast");
				$("#f_vectors").hide("fast");
  			}
        });

	    // On add draw changed
	    $('#addPhotos').change(function(e){
			if ($(this).is(':checked')) {
				$("#photoSelect").show("fast");
				$("#f_photos").show("fast");
  			}else{
				$("#photoSelect").hide("fast");
				$("#f_photos").hide("fast");
  			}
        });

	    // On add draw changed
	    $('#addMarks').change(function(e){
			if ($(this).is(':checked')) {
				$("#marksSelect").show("fast");
				$("#f_marks").show("fast");
  			}else{
				$("#marksSelect").hide("fast");
				$("#f_marks").hide("fast");
  			}
        });


	    // On add draw changed
	    $('#addStl').change(function(e){
			if ($(this).is(':checked')) {
				$("#3dStlSourceSelect").show("fast");
				$("#f_3dstl").show("fast");
  			}else{
				$("#3dStlSourceSelect").hide("fast");
				$("#f_3dstl").hide("fast");
  			}
        });
        

	});

	var noFile = '<i class="fa fa-times-circle" aria-hidden="true"></i> Файл не выбран';

	function changeTreeFile(id, fileVarialbe, filename, type = '') {
		if( fileVarialbe.length > 0 ){
			var html = '<i class="fa fa-file" aria-hidden="true"></i> ' + type + filename;
		}else{
			var html = noFile + ' (' + filename + ')';
		}

		$(id).html(html);
	}

	function changeTreeFiles(id, arr, title) {
		if( arr.length > 0 ){
			var result = title + '<ul>';
			for (var i = 0; i < arr.length; i++){
				result += '<li><i class="fa fa-file" aria-hidden="true"></i> ' + arr[i] + '</li>';
			}
			result += '</ul>';
			var html = result;
		}else{
			var html = noFile;
		}
		$(id).html(html);
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
		var html = noFile + ' (Изображение)';

		if( mechanics_value.length > 0 ){
			mechanics_state = success;
			var html = '<i class="fa fa-file" aria-hidden="true"></i> Изображение ' + filename + '.jpeg';
		}

		$('#f_image').html(html);

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
		$('#f_description').html(html);

		changeTreeFile('#f_3dsource', source3dFile, filename + '.m3d', 'Исходник ');
		changeTreeFile('#f_3dstep', step3dFile, filename + '.step', '3D модель ');
		changeTreeFile('#f_3dstl', stl3dFile, filename + '.stl', '3D модель для печати ');
		changeTreeFile('#f_drawsource', sourcedrawFile, filename + '.cdw', 'Исходник ');
		changeTreeFile('#f_drawpdf', pdfdrawFile, filename + '.pdf', 'Чертёж ');
		changeTreeFile('#f_vector', vectordrawFile, filename + '.dxf', 'Векторный файл ');
		changeTreeFile('#f_3dx3d', step3dFile, filename + '.x3d', 'Веб 3D модель ');

		changeTreeFiles('#f_photos', photos, 'Фотографии');
		changeTreeFiles('#f_annotations', annotations, 'Аннотации');
		changeTreeFiles('#f_marks', marks, 'Маркировка и наклейки');

		$('#f_mechanic_fullname').html(filename);
		$('#mechanic_fullname').val(filename);	
	}
</script>