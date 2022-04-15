<?php
	// Require API
	require_once('uc_modules/uCrewProjects/api/projects_system.php');
	// Init class
	$uc_Projects = new uCrewProjects();
	// Message variable
	$message = "";
	// Check if isset data
	if(isset($_POST['cable_name'])){
		// Add data
		$uc_Projects->addCable($_POST, $_FILES);
		$message .= '
			<div class="alert alert-success alert-dismissible fade show" role="alert">
			  Кабель <strong>'.$_POST['cable_fullname'].'</strong> успешно добавлен.
			  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		';
	}
	// Get last codename
	$codename = $uc_Projects->getLastCodeName('cables_codename', 'TBC');
	// Get directory data
	$directory_data = $uc_Projects->getProjectDirectoryData();

	echo $message;
?>

<div class="row">
	<form action="/?page=uCrewProjects/cablesAdd" method="post" id="addcableForm" enctype="multipart/form-data">
		<h4>Общая информация</h4>
		<hr>
		<input type="hidden" name="cable_fullname" id="cable_fullname" value="">
		<div class="mb-3">
		  <label for="cable_name" class="form-label">Наиминование кабеля <i>(*обратите внимание, что следующие символы запрещены: \/():*?"|+.%!@&lt;&gt;)</i></label>
		  <input class="form-control" type="text" id="cable_name" name="cable_name" required>
		  <p>
		  	<figcaption class="blockquote-footer">
		  		Директория на диске: 
		  		<cite id="directory">
		  			<?php 
		  				echo '"' . $directory_data['mask'] . $uc_Projects->ucs_DirectoriesNames['develop_documentation'] . "\\" . $uc_Projects->ucs_DirectoriesNames['cables'] . "\\" . $codename . '"'; 
		  			?>
		  		</cite>  
		  	</figcaption>
		  </p>
		</div>
		<div class="mb-3">
		  <label for="cable_description" class="form-label">Краткое описание</label>
		  <input class="form-control" type="text" id="cable_description" name="cable_description" required>
 			<p>
		  	<figcaption class="blockquote-footer">
		  		Осталось символов: 
		  		<cite id="symbols">250</cite>  
		  	</figcaption>
		  </p>
		</div>

		<div class="mb-3">
		  <label for="cable_status" class="form-label">Статус</label>
		  <select class="form-control" id="cable_status" name="cable_status" required>
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
		  <label for="cable_codename" class="form-label">Шифр кабеля <i>(*присваивается <a href="#" onclick="changeCodeNameState()" id="codeNameState" class="link-dark">автоматически</a>)</i></label>
		  <input class="form-control" type="text" id="cable_codename" name="cable_codename" readonly value="<?php echo $codename; ?>" required>
		  <input type="hidden" id="cable_codename_state" name="cable_codename_state" value="auto">
		</div>

		<h4>Файлы изделия</h4>
		<hr>

		<div class="mb-3">
		  <label for="cable_drawsource" class="form-label">Исходный файл чертежа - Компас 3D (*.cdw)</label>
		  <input class="form-control" type="file" id="cable_drawsource" name="cable_drawsource" accept=".cdw" required>
			<p>
		  		<figcaption class="blockquote-footer">
		  			Данный файл <cite>является обязательным</cite>  
		  		</figcaption>
		  	</p>
		</div>
		<div class="mb-3">
		  <label for="cable_drawpdf" class="form-label">Готовый файл чертежа - Portable Document Format (*.pdf)</label>
		  <input class="form-control" type="file" id="cable_drawpdf" name="cable_drawpdf" accept=".pdf" required>
			<p>
		  		<figcaption class="blockquote-footer">
		  			Данный файл <cite>является обязательным</cite>  
		  		</figcaption>
		  	</p>
		</div>

		<div class="mb-3">
		  	<label for="checkboxes" class="form-label">Дополнительно</label>
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

		<div class="mb-3" id="photoSelect">
		  <label for="cable_photos" class="form-label">Фотографии <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="cable_photos" name="cable_photos[]"  accept=".jpeg,.jpg" multiple="multiple">
		</div>
		<div class="mb-3" id="marksSelect">
		  <label for="cable_marks" class="form-label">Маркировка, наклейки - любой формат <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="cable_marks" name="cable_marks[]" multiple="multiple">
		</div>

		<div class="mb-3" id="annotationsSelect">
		  <label for="cable_annotations" class="form-label">Аннотации - Text (*.txt), Microsoft Word (*.docx), Portable Document Format (*.pdf) <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="cable_annotations" name="cable_annotations[]" multiple="multiple"  accept=".txt,.docx,.pdf">
		</div>

		<div class="d-grid gap-1 d-md-flex justify-content-md-end" style="padding-bottom: 10px">
			<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addcableModal" onclick="changeModalPresubmit()">
  				Добавить изделие
			</button>
		</div>

		<div class="modal fade" id="addcableModal" tabindex="-1" aria-labelledby="addcableModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="addcableModalLabel">Проверьте верность данных</h5>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">

				<ul class="list-unstyled">
				  <li id="lcable_codename"><i class="fa fa-check-circle" aria-hidden="true"></i> Шифр изделия: *</li>
				  <li id="lcable_name"><i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование изделия: *</li>
				  <li id="lcable_description"><i class="fa fa-check-circle" aria-hidden="true"></i> Краткое описание: *</li>
				  <li id="lcable_status"><i class="fa fa-check-circle" aria-hidden="true"></i> Статус: *</li>
				  <li id="lcable_directory">
				  	<i class="fa fa-exclamation-circle" aria-hidden="true"></i> Расположение файлов:
<?php
					/*$fullpath = array(
						$uc_Projects->ucs_DirectoriesNames['develop_documentation'] => array(
							$uc_Projects->ucs_DirectoriesNames['cables'] => array( 'Шифр' =>
								$uc_Projects->ucs_DirectoriesTemplates['cables']
							)
						)
					);

					print_r($this->uc_CompilatorData->arrayToList($fullpath));*/
?>
				<ul>
					<li>
					   Конструкторская документация
					   <ul>
					      <li id="cables_dir">
					         Провода и кабели
					         <ul>
					            <li id="codename">
					               <div id="f_cable_fullname">Шифр</div>
					               <ul>
					                  <li id="f_drawings">
					                  Чертежи
 										<ul>
						                  	<li id="f_drawsource">
						                  		<i class="fa fa-file" aria-hidden="true"></i> Исходный файл
						                  	</li>
						                  	<li id="f_drawpdf">
						                  		<i class="fa fa-file" aria-hidden="true"></i> PDF
						                  	</li>
						                  	<li id="f_drawjpeg">
						                  		<i class="fa fa-file" aria-hidden="true"></i> JPEG
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
		        <input type="submit" name="addcable" class="btn btn-success me-md-1" value="Добавить изделие">
		      </div>
		    </div>
		  </div>
		</div>

	</form>
</div>

<script type="text/javascript">

	var readonly = true;

	var imageFile = '';
	var sourcedrawFile = '';
	var pdfdrawFile = '';
	var photos = [];
	var annotations = [];
	var marks = [];

	// Change codename state
	function changeCodeNameState(){
		if(readonly == true){
			readonly = false;
			$("#codeNameState").html("пользователем");
			$("#cable_codename_state").val("manual");

		}else{
			readonly = true;
			$("#codeNameState").html("автоматически");
			$("#cable_codename_state").val("auto");
		}

		$("#cable_codename").attr("readonly", readonly);  
	}

	$( document ).ready(function() {
		// On load functions
		$("#imagePreview").hide();
		$("#annotationsSelect").hide();
		$("#photoSelect").hide();
		$("#marksSelect").hide();

		$("#f_photos").hide();
		$("#f_marks").hide();
		$("#f_annotations").hide();	

		// Description change
	    $('#cable_description').on('input', function(){ 
	    	var count = 250 - $('#cable_description').val().length;
	    	$('#symbols').html(count);
	    });
	    
	    // Replace bad symbols in name
	    $('body').on('input', '#cable_name', function(){
			this.value = this.value.replace(/[^0-9A-Za-zА-Яа-яЁё\-\. ]/g, '');
		});

	    // Set cable name
	    $('#cable_name').on('input', function(){ 
	    	var text = $('#cable_name').val().replace(/[^0-9A-Za-zА-Яа-яЁё\-\. ]/g, '');
	    	
	    	$('#lcable_name').html(
	    		'<i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование изделия: <i>' + text + '</i>'
	    	);

	    	if($('#cable_name').val().length > 0){
	    		text = ' - ' + text;
	    	}

	    	$('#directory').html('"<?php echo $directory_data['mask'] ?>Оборудование\\Провода и кабели\\' + $('#cable_codename').val() + text  + '"');
	    });

		// On source draw change
	     $('#cable_drawsource').change(function(e){
            var fileName = e.target.files[0].name;
            sourcedrawFile = fileName;
        });

        // On source pdf change
	     $('#cable_drawpdf').change(function(e){
            var fileName = e.target.files[0].name;
            pdfdrawFile = fileName;
        });

		// On source photos
	    $('#cable_photos').change(function(e){
	    	photos = [];
            for (var i = 0; i < e.target.files.length; i++){
				photos.push(e.target.files[i].name);
			}
        });

        // On source photos
	    $('#cable_annotations').change(function(e){
	    	annotations = [];
            for (var i = 0; i < e.target.files.length; i++){
				annotations.push(e.target.files[i].name);
			}
        });

        // On source photos
	    $('#cable_marks').change(function(e){
	    	marks = [];
            for (var i = 0; i < e.target.files.length; i++){
				marks.push(e.target.files[i].name);
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

		var filename = $('#cable_codename').val();

		if($('#cable_name').val().length > 0){
			filename =  filename + ' - ' + $('#cable_name').val();
		}

		// Check cables name
		var cables_state = error;
		var cables_value = $('#cable_name').val();
		if( cables_value.length > 0 ){
			cables_state = success;
		}
		var html = '<i class="' + cables_state + '" aria-hidden="true"></i> Наиминование изделия: ' + cables_value + '</li>';
		$('#lcable_name').html(html)

		// Check cables codename
		var cables_state = error;
		var cables_value = $('#cable_codename').val();
		if( cables_value.length > 0 ){
			cables_state = success;
		}
		var html = '<i class="' + cables_state + '" aria-hidden="true"></i> Шифр изделия: ' + cables_value + '</li>';
		$('#lcable_codename').html(html)


		// Check cables description
		var cables_state = error;
		var cables_value = $('#cable_description').val();
		if( cables_value.length > 0 ){
			cables_state = success;
		}
		var html = '<i class="' + cables_state + '" aria-hidden="true"></i> Краткое описание: ' + cables_value + '</li>';

		$('#lcable_description').html(html)

		// Check cables status
		var cables_state = error;
		var cables_value = $('#cable_status option:selected').text();
		if( cables_value.length > 0 ){
			cables_state = success;
		}
		var html = '<i class="' + cables_state + '" aria-hidden="true"></i> Статус: ' + cables_value + '</li>';
		$('#lcable_status').html(html);

		var html = '<i class="fa fa-file" aria-hidden="true"></i> Описание ' + filename + '.txt';
		$('#f_description').html(html);



		changeTreeFile('#f_drawsource', sourcedrawFile, filename + '.cdw', 'Исходник ');
		changeTreeFile('#f_drawpdf', pdfdrawFile, filename + '.pdf', 'Чертёж ');
		changeTreeFile('#f_drawjpeg', pdfdrawFile, filename + '.jpeg', 'Чертёж ');
		changeTreeFile('#f_image', pdfdrawFile, filename + '.jpeg', 'Изображение ');

		changeTreeFiles('#f_photos', photos, 'Фотографии');
		changeTreeFiles('#f_annotations', annotations, 'Аннотации');
		changeTreeFiles('#f_marks', marks, 'Маркировка и наклейки');

		$('#f_cable_fullname').html(filename);
		$('#cable_fullname').val(filename);	
	}
</script>