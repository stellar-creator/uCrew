<?php
	// Require API
	require_once('uc_modules/uCrewProjects/api/projects_system.php');
	// Init class
	$uc_Projects = new uCrewProjects();
	// Message variable
	$message = "";
	// Check if isset data
	if(isset($_POST['pcb_name'])){
		// Add data
		$uc_Projects->addPcb($_POST, $_FILES);
		$message .= '
			<div class="alert alert-success alert-dismissible fade show" role="alert">
			  Печатная плата <strong>'.$_POST['pcb_fullname'].'</strong> успешно добавлена.
			  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		';
	}
	// Get last codename
	$codename = $uc_Projects->getLastCodeName('pcbs_codename', 'TBP');
	// Get directory data
	$directory_data = $uc_Projects->getProjectDirectoryData();

	// Get materials
	$materials = $uc_Projects->getPcbMaterials();
	// Generate list
	$options = '';
	foreach ($materials as $material) {
		$options .= "<option value=\"$material\">$material</option>\n";
	}

	// Get materials
	$colors = $uc_Projects->getPcbColors();
	// Generate list
	$options_colors = '';
	foreach ($colors as $color) {
		$options_colors .= "<option value=\"$color\">$color</option>\n";
	}

	// Get materials
	$surfaces = $uc_Projects->getPcbSurfaces();
	// Generate list
	$options_surfaces = '';
	foreach ($surfaces as $surface) {
		$options_surfaces .= "<option value=\"$surface\">$surface</option>\n";
	}

	
	// Get pcbs
	$pcbs = $uc_Projects->getPcbsData();
	// Get pcbs
	$pcbs_json = $uc_Projects->getPcbsData($raw = true);
	// Generate list
	$options_pcbs = '<option value=\"0\">Выберите печатную плату из базы данных...</option>\n';
	foreach ($pcbs as $pcb => $data) {
		$options_pcbs .= "<option value=\"$pcb\">$pcb - ".$data['name']."</option>\n";
	}

	echo $message;
?>

<div class="row">
	<form action="/?page=uCrewProjects/pcbsAdd" method="post" id="addpcbForm" enctype="multipart/form-data">
		<h4>Общая информация</h4>
		<hr>
		<input type="hidden" name="pcb_fullname" id="pcb_fullname" value="">

		<div class="mb-3">
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addRevision">
			  <label class="form-check-label" for="addRevision">
			    Добавить ревизию к плате
			  </label>
			</div>
		</div>
		<div id="append_pcb">
			<div class="mb-3">
			  <label for="pcb_parrent" class="form-label">Выберите печатную плату</label>
			  <select class="selectpicker show-tick form-control" id="pcb_parrent" name="pcb_parrent" data-live-search="true" data-size="15" required>
			  	<?php
			  		echo $options_pcbs;
			  	?>
			  </select>
			</div>
		</div>

		<div class="mb-3">
		  <label for="pcb_name" class="form-label">Наиминование печатной платы <i>(*обратите внимание, что следующие символы запрещены: \/():*?"|+.%!@&lt;&gt;)</i></label>
		  <input class="form-control" type="text" id="pcb_name" name="pcb_name" required>
		  <p>
		  	<figcaption class="blockquote-footer">
		  		Директория на диске: 
		  		<cite id="directory">
		  			<?php 
		  				echo '"' . $directory_data['mask'] . $uc_Projects->ucs_DirectoriesNames['develop_documentation'] . "\\" . $uc_Projects->ucs_DirectoriesNames['pcbs'] . "\\" . $codename . '"'; 
		  			?>
		  		</cite>  
		  	</figcaption>
		  </p>
		</div>

		<div class="mb-3">
		  <label for="pcb_description" class="form-label">Краткое описание</label>
		  <input class="form-control" type="text" id="pcb_description" name="pcb_description" required>
 			<p>
		  	<figcaption class="blockquote-footer">
		  		Осталось символов: 
		  		<cite id="symbols">250</cite>  
		  	</figcaption>
		  </p>
		</div>


		<div id="new_pcb">
			<div class="mb-3">
			  <label for="pcb_codename" class="form-label">Шифр печатной платы <i>(*присваивается <a href="#" onclick="changeCodeNameState()" id="codeNameState" class="link-dark">автоматически</a>)</i></label>
			  <input class="form-control" type="text" id="pcb_codename" name="pcb_codename" readonly value="<?php echo $codename; ?>" required>
			  <input type="hidden" id="pcb_codename_state" name="pcb_codename_state" value="auto">
			</div>
		</div>
		<div class="mb-3">
			<label for="pcb_revision" class="form-label">Ревизия печатной платы</label>
			<input class="form-control" type="text" id="pcb_revision" name="pcb_revision" readonly value="1" required>
		</div>
		
				<div class="mb-3">
		  <label for="pcb_status" class="form-label">Статус</label>
		  <select class="form-control" id="pcb_status" name="pcb_status" required>
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

		<h4>Характеристики печатной платы</h4>
		<hr>

		<div class="mb-3">
		  <label for="pcb_material" class="form-label">Материал печатной платы</label>
		  <select class="selectpicker show-tick form-control" id="pcb_material" name="pcb_material" data-live-search="true" data-size="15" required>
		  	<?php
		  		echo $options;
		  	?>
		  </select>
		</div>

		<div class="mb-3">
		  <label for="pcb_silk" class="form-label">Цвет шелкографии</label>
		  <select class="selectpicker show-tick form-control" id="pcb_silk" name="pcb_silk" data-live-search="true" data-size="15" required>
		  	<?php
		  		echo $options_colors;
		  	?>
		  </select>
		</div>

		<div class="mb-3">
		  <label for="pcb_mask" class="form-label">Цвет паяльной маски</label>
		  <select class="selectpicker show-tick form-control" id="pcb_mask" name="pcb_mask" data-live-search="true" data-size="15" required>
		  	<?php
		  		echo $options_colors;
		  	?>
		  </select>
		</div>

		<div class="mb-3">
		  <label for="pcb_surface" class="form-label">Покрытие контактных площадок</label>
		  <select class="selectpicker show-tick form-control" id="pcb_surface" name="pcb_surface" data-live-search="true" data-size="15" required>
		  	<?php
		  		echo $options_surfaces;
		  	?>
		  </select>
		</div>

		<h4>Файлы изделия</h4>
		<hr>

		<div class="mb-3">
		  <label for="pcb_archive" class="form-label">Исходный проект печатной платы KiCad 6 - Архив (*.zip)</label>
		  <input class="form-control" type="file" id="pcb_archive" name="pcb_archive" accept=".zip" required>
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
		  <label for="pcb_photos" class="form-label">Фотографии <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="pcb_photos" name="pcb_photos[]"  accept=".jpeg,.jpg" multiple="multiple">
		</div>
		<div class="mb-3" id="marksSelect">
		  <label for="pcb_marks" class="form-label">Маркировка, наклейки - любой формат <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="pcb_marks" name="pcb_marks[]" multiple="multiple">
		</div>

		<div class="mb-3" id="annotationsSelect">
		  <label for="pcb_annotations" class="form-label">Аннотации - Text (*.txt), Microsoft Word (*.docx), Portable Document Format (*.pdf) <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="pcb_annotations" name="pcb_annotations[]" multiple="multiple"  accept=".txt,.docx,.pdf">
		</div>

		<div class="d-grid gap-1 d-md-flex justify-content-md-end" style="padding-bottom: 10px">
			<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addpcbModal" onclick="changeModalPresubmit()">
  				Добавить изделие
			</button>
		</div>
		<div class="modal fade" id="addpcbModal" tabindex="-1" aria-labelledby="addpcbModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="addpcbModalLabel">Проверьте верность данных</h5>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
				<ul class="list-unstyled">
				  <li id="lpcb_codename"><i class="fa fa-check-circle" aria-hidden="true"></i> Шифр изделия: *</li>
				  <li id="lpcb_name"><i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование изделия: *</li>
				  <li id="lpcb_description"><i class="fa fa-check-circle" aria-hidden="true"></i> Краткое описание: *</li>
				  <li id="lpcb_status"><i class="fa fa-check-circle" aria-hidden="true"></i> Статус: *</li>
				  <li id="lpcb_directory">
				  	<i class="fa fa-exclamation-circle" aria-hidden="true"></i> Расположение файлов:
<?php
					/*$fullpath = array(
						$uc_Projects->ucs_DirectoriesNames['develop_documentation'] => array(
							$uc_Projects->ucs_DirectoriesNames['pcbs'] => array( 'Шифр' =>
								$uc_Projects->ucs_DirectoriesTemplates['pcbs']
							)
						)
					);

					print_r($this->uc_CompilatorData->arrayToList($fullpath));*/
?>
				<ul>
					<li>
					   Конструкторская документация
					   <ul>
					      <li id="pcbs_dir">
					         Печатные платы
					         <ul>
					            <li id="codename">
					               <div id="f_pcb_fullname">Шифр</div>
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
		        <input type="submit" name="addpcb" class="btn btn-success me-md-1" value="Добавить изделие">
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

	var pcbs = <?php echo $pcbs_json; ?>;

	// Change codename state
	function changeCodeNameState(){
		if(readonly == true){
			readonly = false;
			$("#codeNameState").html("пользователем");
			$("#pcb_codename_state").val("manual");

		}else{
			readonly = true;
			$("#codeNameState").html("автоматически");
			$("#pcb_codename_state").val("auto");
		}

		$("#pcb_codename").attr("readonly", readonly);  
	}

	$( document ).ready(function() {
		// On load functions
		$("#imagePreview").hide();
		$("#annotationsSelect").hide();
		$("#photoSelect").hide();
		$("#marksSelect").hide();
		$("#append_pcb").hide();

		$("#f_photos").hide();
		$("#f_marks").hide();
		$("#f_annotations").hide();	

		// Description change
	    $('#pcb_description').on('input', function(){ 
	    	var count = 250 - $('#pcb_description').val().length;
	    	$('#symbols').html(count);
	    });
	    
	    // Replace bad symbols in name
	    $('body').on('input', '#pcb_name', function(){
			this.value = this.value.replace(/[^0-9A-Za-zА-Яа-яЁё\-\. ]/g, '');
		});

	    // Set pcb name
	    $('#pcb_name').on('input', function(){ 
	    	var text = $('#pcb_name').val().replace(/[^0-9A-Za-zА-Яа-яЁё\-\. ]/g, '');
	    	
	    	$('#lpcb_name').html(
	    		'<i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование изделия: <i>' + text + '</i>'
	    	);

	    	if($('#pcb_name').val().length > 0){
	    		text = ' - ' + text;
	    	}

	    	$('#directory').html('"\\<?php echo $directory_data['mask'] ?>\\Конструкторская документация\\Печатные платы\\' + $('#pcb_codename').val() + text  + '"');
	    });

		// On source draw change
	     $('#pcb_archive').change(function(e){
            var fileName = e.target.files[0].name;
            archive = fileName;
        });

		// On source photos
	    $('#pcb_photos').change(function(e){
	    	photos = [];
            for (var i = 0; i < e.target.files.length; i++){
				photos.push(e.target.files[i].name);
			}
        });

        // On source photos
	    $('#pcb_annotations').change(function(e){
	    	annotations = [];
            for (var i = 0; i < e.target.files.length; i++){
				annotations.push(e.target.files[i].name);
			}
        });

        // On source photos
	    $('#pcb_marks').change(function(e){
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
				$("#append_pcb").show("fast");
				$("#new_pcb").hide("fast");
				$("#pcb_name").attr("readonly", true);
				$("#pcb_description").attr("readonly", true);
				$("#pcb_revision").val("-");
  			}else{
				$("#append_pcb").hide("fast");
				$("#new_pcb").show("fast");
				$("#pcb_name").attr("readonly", false);
				$("#pcb_description").attr("readonly", false);
				$("#pcb_revision").val("1");
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
	    $('#pcb_parrent').change(function(e){
			$('#pcb_name').val(pcbs['pcbs'][this.value]['name']);
			$('#pcb_description').val(pcbs['pcbs'][this.value]['description']);
			var lastKey = Object.keys(pcbs['pcbs'][this.value]['revisions']).sort().reverse()[0];
			$("#pcb_revision").val(lastKey);
			$('pcb_codename').val(this.value);
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

		var filename = $('#pcb_codename').val();

		if($('#pcb_name').val().length > 0){
			filename =  filename + ' - ' + $('#pcb_name').val();
		}

		// Check pcbs name
		var pcbs_state = error;
		var pcbs_value = $('#pcb_name').val();
		if( pcbs_value.length > 0 ){
			pcbs_state = success;
		}
		var html = '<i class="' + pcbs_state + '" aria-hidden="true"></i> Наиминование изделия: ' + pcbs_value + '</li>';
		$('#lpcb_name').html(html)

		// Check pcbs codename
		var pcbs_state = error;
		var pcbs_value = $('#pcb_codename').val();
		if( pcbs_value.length > 0 ){
			pcbs_state = success;
		}
		var html = '<i class="' + pcbs_state + '" aria-hidden="true"></i> Шифр изделия: ' + pcbs_value + '</li>';
		$('#lpcb_codename').html(html)


		// Check pcbs description
		var pcbs_state = error;
		var pcbs_value = $('#pcb_description').val();
		if( pcbs_value.length > 0 ){
			pcbs_state = success;
		}
		var html = '<i class="' + pcbs_state + '" aria-hidden="true"></i> Краткое описание: ' + pcbs_value + '</li>';

		$('#lpcb_description').html(html)

		// Check pcbs status
		var pcbs_state = error;
		var pcbs_value = $('#pcb_status option:selected').text();
		if( pcbs_value.length > 0 ){
			pcbs_state = success;
		}
		var html = '<i class="' + pcbs_state + '" aria-hidden="true"></i> Статус: ' + pcbs_value + '</li>';
		$('#lpcb_status').html(html);

		var html = '<i class="fa fa-file" aria-hidden="true"></i> Описание ' + filename + '.txt';
		$('#f_description').html(html);

		changeTreeFile('#f_image', archive, filename + '.jpeg', 'Изображение ');

		changeTreeFiles('#f_photos', photos, 'Фотографии');
		changeTreeFiles('#f_annotations', annotations, 'Аннотации');
		changeTreeFiles('#f_marks', marks, 'Маркировка и наклейки');

		$('#f_pcb_fullname').html(filename);
		$('#pcb_fullname').val(filename);	
	}
</script>