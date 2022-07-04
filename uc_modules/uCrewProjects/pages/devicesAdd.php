<?php
	// Require API
	require_once('uc_modules/uCrewProjects/api/projects_system.php');
	// Init class
	$uc_Projects = new uCrewProjects();
	// Message variable
	$message = "";
	// Check if isset data
	if(isset($_POST['device_name'])){
		// Add data
		$uc_Projects->addDevice($_POST, $_FILES);
		$message .= '
			<div class="alert alert-success alert-dismissible fade show" role="alert">
			  Печатная плата <strong>'.$_POST['device_fullname'].'</strong> успешно добавлена.
			  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		';
	}
	// Get last codename
	$codename = $uc_Projects->getLastCodeName('devices_codename', 'TBP');
	// Get directory data
	$directory_data = $uc_Projects->getProjectDirectoryData();
	
	// Get devices
	$devices = $uc_Projects->getCommonJsonData('device');
	// Get devices
	$devices_json = $uc_Projects->getCommonJsonData('device', $raw = true);
	// Generate list
	$options_devices = '<option value=\"0\">Выберите печатную плату из базы данных...</option>\n';
	foreach ($devices as $device => $data) {
		$options_devices .= "<option value=\"$device\">$device - ".$data['name']."</option>\n";
	}

	echo $message;
?>

<div class="row">
	<form action="/?page=uCrewProjects/devicesAdd" method="post" id="addDeviceForm" enctype="multipart/form-data">
		<h4>Общая информация</h4>
		<hr>
		<input type="hidden" name="device_fullname" id="device_fullname" value="">
		<input type="hidden" name="device_isnew" id="device_isnew" value="1">

		<div class="mb-3">
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addRevision">
			  <label class="form-check-label" for="addRevision">
			    Добавить ревизию к устройству
			  </label>
			</div>
		</div>
		<div id="append_device">
			<div class="mb-3">
			  <label for="device_parrent" class="form-label">Выберите печатную плату</label>
			  <select class="selectpicker show-tick form-control" id="device_parrent" name="device_parrent" data-live-search="true" data-size="15" required>
			  	<?php
			  		echo $options_devices;
			  	?>
			  </select>
			</div>
		</div>

		<div class="mb-3">
		  <label for="device_name" class="form-label">Наиминование печатной платы <i>(*обратите внимание, что следующие символы запрещены: \/():*?"|+.%!@&lt;&gt;)</i></label>
		  <input class="form-control" type="text" id="device_name" name="device_name" required>
		  <p>
		  	<figcaption class="blockquote-footer">
		  		Директория на диске: 
		  		<cite id="directory">
		  			<?php 
		  				echo '"' . $directory_data['mask'] . $uc_Projects->ucs_DirectoriesNames['develop_documentation'] . "\\" . $uc_Projects->ucs_DirectoriesNames['devices'] . "\\" . $codename . '"'; 
		  			?>
		  		</cite>  
		  	</figcaption>
		  </p>
		</div>

		<div class="mb-3">
		  <label for="device_description" class="form-label">Краткое описание</label>
		  <input class="form-control" type="text" id="device_description" name="device_description" required>
 			<p>
		  	<figcaption class="blockquote-footer">
		  		Осталось символов: 
		  		<cite id="symbols">250</cite>  
		  	</figcaption>
		  </p>
		</div>


		<div id="new_device">
			<div class="mb-3">
			  <label for="device_codename" class="form-label">Шифр печатной платы <i>(*присваивается <a href="#" onclick="changeCodeNameState()" id="codeNameState" class="link-dark">автоматически</a>)</i></label>
			  <input class="form-control" type="text" id="device_codename" name="device_codename" readonly value="<?php echo $codename; ?>" required>
			  <input type="hidden" id="device_codename_state" name="device_codename_state" value="auto">
			</div>
		</div>
		<div class="mb-3">
			<label for="device_revision" class="form-label">Ревизия печатной платы</label>
			<input class="form-control" type="text" id="device_revision" name="device_revision" readonly value="1" required>
		</div>
		
				<div class="mb-3">
		  <label for="device_status" class="form-label">Статус</label>
		  <select class="form-control" id="device_status" name="device_status" required>
<?php
  	$statuses = $uc_Projects->getStatuses();

  	foreach ($statuses as $id => $data) {
  		$sel = "";
  		if($id == 1){
  			$sel = "selected";
  		}
  		echo '<option value="'.$id.'" class="'.$data[1].'" '.$sel.'>'.$data[0].'</option>';
  	}
?>
		  </select>
		</div>

		<h4>Характеристики печатной платы</h4>
		<hr>

		<div class="mb-3">
		  <label for="device_material" class="form-label">Материал печатной платы</label>
		  <select class="selectpicker show-tick form-control" id="device_material" name="device_material" data-live-search="true" data-size="15" required>
		  	<?php
		  		echo $options;
		  	?>
		  </select>
		</div>

		<div class="mb-3">
		  <label for="device_silk" class="form-label">Цвет шелкографии</label>
		  <select class="selectpicker show-tick form-control" id="device_silk" name="device_silk" data-live-search="true" data-size="15" required>
		  	<?php
		  		echo $options_colors;
		  	?>
		  </select>
		</div>

		<div class="mb-3">
		  <label for="device_mask" class="form-label">Цвет паяльной маски</label>
		  <select class="selectpicker show-tick form-control" id="device_mask" name="device_mask" data-live-search="true" data-size="15" required>
		  	<?php
		  		echo $options_colors;
		  	?>
		  </select>
		</div>

		<div class="mb-3">
		  <label for="device_surface" class="form-label">Покрытие контактных площадок</label>
		  <select class="selectpicker show-tick form-control" id="device_surface" name="device_surface" data-live-search="true" data-size="15" required>
		  	<?php
		  		echo $options_surfaces;
		  	?>
		  </select>
		</div>

		<h4>Файлы изделия</h4>
		<hr>

		<div class="mb-3">
		  <label for="device_archive" class="form-label">Исходный проект печатной платы KiCad 6 - Архив (*.zip)</label>
		  <input class="form-control" type="file" id="device_archive" name="device_archive" accept=".zip" required>
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
		  <label for="device_photos" class="form-label">Фотографии <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="device_photos" name="device_photos[]"  accept=".jpeg,.jpg" multiple="multiple">
		</div>
		<div class="mb-3" id="marksSelect">
		  <label for="device_marks" class="form-label">Маркировка, наклейки - любой формат <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="device_marks" name="device_marks[]" multiple="multiple">
		</div>

		<div class="mb-3" id="annotationsSelect">
		  <label for="device_annotations" class="form-label">Аннотации - Text (*.txt), Microsoft Word (*.docx), Portable Document Format (*.pdf) <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="device_annotations" name="device_annotations[]" multiple="multiple"  accept=".txt,.docx,.pdf">
		</div>

		<div class="d-grid gap-1 d-md-flex justify-content-md-end" style="padding-bottom: 10px">
			<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDeviceModal" onclick="changeModalPresubmit()">
  				Добавить изделие
			</button>
		</div>
		<div class="modal fade" id="addDeviceModal" tabindex="-1" aria-labelledby="addDeviceModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="addDeviceModalLabel">Проверьте верность данных</h5>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
				<ul class="list-unstyled">
				  <li id="ldevice_codename"><i class="fa fa-check-circle" aria-hidden="true"></i> Шифр изделия: *</li>
				  <li id="ldevice_name"><i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование изделия: *</li>
				  <li id="ldevice_description"><i class="fa fa-check-circle" aria-hidden="true"></i> Краткое описание: *</li>
				  <li id="ldevice_status"><i class="fa fa-check-circle" aria-hidden="true"></i> Статус: *</li>
				  <li id="ldevice_directory">
				  	<i class="fa fa-exclamation-circle" aria-hidden="true"></i> Расположение файлов:
<?php
					/*$fullpath = array(
						$uc_Projects->ucs_DirectoriesNames['develop_documentation'] => array(
							$uc_Projects->ucs_DirectoriesNames['devices'] => array( 'Шифр' =>
								$uc_Projects->ucs_DirectoriesTemplates['devices']
							)
						)
					);

					print_r($this->uc_CompilatorData->arrayToList($fullpath));*/
?>
				<ul>
					<li>
					   Конструкторская документация
					   <ul>
					      <li id="devices_dir">
					         Печатные платы
					         <ul>
					            <li id="codename">
					               <div id="f_device_fullname">Шифр</div>
					               <ul>
					               	  <li>
					               	  	3D модель
					               	  </li>
					               	  <li>
					               	  	Исходники
					               	  </li>
					               	  <li>
					               	  	Спецификация
					               	  </li>
					               	  <li>
					               	  	Файлы для производства
					               	  	<ul>
					               	  		<li>Gerber</li>
					               	  		<li>Файлы позиций</li>
					               	  		<li>Сборочный чертёж</li>
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
		        <input type="submit" name="addDevice" class="btn btn-success me-md-1" value="Добавить изделие">
		      </div>
		    </div>
		  </div>
		</div>

	</form>
</div>

<script type="text/javascript">

	var readonly = true;

	var archive = '';
	var photos = [];
	var annotations = [];
	var marks = [];

	var devices = <?php echo $devices_json; ?>;

	// Change codename state
	function changeCodeNameState(){
		if(readonly == true){
			readonly = false;
			$("#codeNameState").html("пользователем");
			$("#device_codename_state").val("manual");

		}else{
			readonly = true;
			$("#codeNameState").html("автоматически");
			$("#device_codename_state").val("auto");
		}

		$("#device_codename").attr("readonly", readonly);  
	}

	$( document ).ready(function() {
		// On load functions
		$("#imagePreview").hide();
		$("#annotationsSelect").hide();
		$("#photoSelect").hide();
		$("#marksSelect").hide();
		$("#append_device").hide();

		$("#f_photos").hide();
		$("#f_marks").hide();
		$("#f_annotations").hide();	

		// Description change
	    $('#device_description').on('input', function(){ 
	    	var count = 250 - $('#device_description').val().length;
	    	$('#symbols').html(count);
	    });
	    
	    // Replace bad symbols in name
	    $('body').on('input', '#device_name', function(){
			this.value = this.value.replace(/[^0-9A-Za-zА-Яа-яЁё\-\. ]/g, '');
		});

	    // Set device name
	    $('#device_name').on('input', function(){ 
	    	var text = $('#device_name').val().replace(/[^0-9A-Za-zА-Яа-яЁё\-\. ]/g, '');
	    	
	    	$('#ldevice_name').html(
	    		'<i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование изделия: <i>' + text + '</i>'
	    	);

	    	if($('#device_name').val().length > 0){
	    		text = ' - ' + text;
	    	}

	    	$('#directory').html('"\\<?php echo $directory_data['mask'] ?>\\Конструкторская документация\\Печатные платы\\' + $('#device_codename').val() + text  + '"');
	    });

		// On source draw change
	     $('#device_archive').change(function(e){
            var fileName = e.target.files[0].name;
            archive = fileName;
        });

		// On source photos
	    $('#device_photos').change(function(e){
	    	photos = [];
            for (var i = 0; i < e.target.files.length; i++){
				photos.push(e.target.files[i].name);
			}
        });

        // On source photos
	    $('#device_annotations').change(function(e){
	    	annotations = [];
            for (var i = 0; i < e.target.files.length; i++){
				annotations.push(e.target.files[i].name);
			}
        });

        // On source photos
	    $('#device_marks').change(function(e){
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

	    // On add revision
	    $('#addRevision').change(function(e){
			if ($(this).is(':checked')) {
				$("#append_device").show("fast");
				$("#new_device").hide("fast");
				$("#device_name").attr("readonly", true);
				$("#device_description").attr("readonly", true);
				$("#device_revision").val("-");
				$('#device_isnew').val('0');	
  			}else{
				$("#append_device").hide("fast");
				$("#new_device").show("fast");
				$("#device_name").attr("readonly", false);
				$("#device_description").attr("readonly", false);
				$("#device_revision").val("1");
				$('#device_isnew').val('1');	
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

	    // On device revision changed
	    $('#device_parrent').change(function(e){
			$('#device_name').val(devices['devices'][this.value]['name']);
			$('#device_description').val(devices['devices'][this.value]['description']);
			var lastKey = Object.keys(devices['devices'][this.value]['revisions']).sort().reverse()[0];
			$("#device_revision").val( (parseInt(lastKey) + 1) );
			$('#device_codename').val(this.value);
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

		var filename = $('#device_codename').val();

		if($('#device_name').val().length > 0){
			filename =  filename + ' - ' + $('#device_name').val();
		}

		// Check devices name
		var devices_state = error;
		var devices_value = $('#device_name').val();
		if( devices_value.length > 0 ){
			devices_state = success;
		}
		var html = '<i class="' + devices_state + '" aria-hidden="true"></i> Наиминование изделия: ' + devices_value + '</li>';
		$('#ldevice_name').html(html)

		// Check devices codename
		var devices_state = error;
		var devices_value = $('#device_codename').val();
		if( devices_value.length > 0 ){
			devices_state = success;
		}
		var html = '<i class="' + devices_state + '" aria-hidden="true"></i> Шифр изделия: ' + devices_value + '</li>';
		$('#ldevice_codename').html(html)


		// Check devices description
		var devices_state = error;
		var devices_value = $('#device_description').val();
		if( devices_value.length > 0 ){
			devices_state = success;
		}
		var html = '<i class="' + devices_state + '" aria-hidden="true"></i> Краткое описание: ' + devices_value + '</li>';

		$('#ldevice_description').html(html)

		// Check devices status
		var devices_state = error;
		var devices_value = $('#device_status option:selected').text();
		if( devices_value.length > 0 ){
			devices_state = success;
		}
		var html = '<i class="' + devices_state + '" aria-hidden="true"></i> Статус: ' + devices_value + '</li>';
		$('#ldevice_status').html(html);

		var html = '<i class="fa fa-file" aria-hidden="true"></i> Описание ' + filename + '.txt';
		$('#f_description').html(html);

		changeTreeFile('#f_image', archive, filename + '.jpeg', 'Изображение ');

		changeTreeFiles('#f_photos', photos, 'Фотографии');
		changeTreeFiles('#f_annotations', annotations, 'Аннотации');
		changeTreeFiles('#f_marks', marks, 'Маркировка и наклейки');

		$('#f_device_fullname').html(filename);
		$('#device_fullname').val(filename);	
	}
</script>