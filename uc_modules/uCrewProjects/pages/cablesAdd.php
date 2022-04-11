<?php
	// Require API
	require_once('uc_modules/uCrewProjects/api/projects_system.php');
	// Init class
	$uc_Projects = new uCrewProjects();
	// Get last codename
	$codename = $uc_Projects->getLastCodeName('cables_codename', 'TBC');
	// Get directory data
	$directory_data = $uc_Projects->getProjectDirectoryData();

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
?>

<style type="text/css">
	.fa-check-circle {
		color: #198754;
	}
	
	.fa-exclamation-circle {
		color: #ffc107;
	}
	
	.fa-times-circle {

		color: #dc3545;
	}
</style>

<?php
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
		  				echo '"' . $directory_data['mask'] . "Оборудование\\Кабели и провода\\" . $codename . '"'; 
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
		</div>
		<div class="mb-3">
		  <label for="cable_image" class="form-label">Изображение кабеля - JPEG (*.jpeg, *.jpg)</label>
		  <input class="form-control" type="file" id="cable_image" name="cable_image" accept="image/jpeg" onchange="changeImagePreview()" required>
			<p>
		  		<figcaption class="blockquote-footer">
		  			Данный файл <cite>является обязательным</cite>  
		  		</figcaption>
		  	</p>
		</div>

		<div class="mb-3" id="imagePreview">
		  <label for="cable_image_preview" class="form-label"></label>
		  <img id="cable_image_preview" class="img-thumbnail imagecat" src="uc_resources/images/uCrewStorage/categories/unknown.png" class="img-fluid" />
		</div>

		<h4>Файлы кабеля</h4>
		<hr>

		<div class="mb-3" id="drawSourceSelect">
		  <label for="cable_drawsource" class="form-label">Исходный файл чертежа - Компас 3D (*.cdw)</label>
		  <input class="form-control" type="file" id="cable_drawsource" name="cable_drawsource" accept=".cdw" required>
		</div>
		<div class="mb-3" id="drawFileSelect">
		  <label for="cable_drawpdf" class="form-label">Готовый файл чертежа - Portable Document Format (*.pdf)</label>
		  <input class="form-control" type="file" id="cable_drawpdf" name="cable_drawpdf" accept=".pdf" required>
		</div>

		<div class="mb-3">
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addOther">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить дополнительные файлы
			  </label>
			</div>
			<div class="form-check" id="checkboxes">
			  <input class="form-check-input" type="checkbox" id="addOther" checked>
			  <label class="form-check-label" for="flexCheckDefault">
			    Сгенерировать файл для маркировачного принтера
			  </label>
			</div>
		</div>

		<div class="mb-3" id="otherSelect">
		  <label for="cable_otherfiles" class="form-label">Дополнительные файлы - Любой формат <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="cable_otherfiles" name="cable_otherfiles" multiple>
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
				  <li id="lcable_codename"><i class="fa fa-check-circle" aria-hidden="true"></i> Шифр кабеля:</li>
				  <li id="lcable_name"><i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование кабеля:</li>
				  <li id="lcable_description"><i class="fa fa-check-circle" aria-hidden="true"></i> Краткое описание:</li>
				  <li id="lcable_status"><i class="fa fa-check-circle" aria-hidden="true"></i> Статус: Разработка</li>
				  <li id="lcable_image"><i class="fa fa-check-circle" aria-hidden="true"></i> Изображение: присутсвует</li>
				  <li id="lcable_directory"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Расположение файлов:
					  <ul>
					  	<li>
					  		Оборудование
					  		<ul>
					  			<li>
					  				Кабели и провода
					  				<ul>
							  			<li> <div id="lcable_fullname">Название</div>
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
							  					<li>Изображение
													<ul>
							  							<li id="lfimage"> 
							  								<i class="fa fa-file" aria-hidden="true"></i> Изображение
							  							</li>
							  						</ul>
							  					</li>
							  					<li>Исходные файлы
													<ul>
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
				  	<i>Внимание! Проверьте всю информацию перед публикацией!</i>
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
		$("#cable_codename").attr("readonly", readonly);  
	}

	function changeImagePreview() {
		$("#imagePreview").show("fast");

  		var file = $("#cable_image").get(0).files[0];

        if(file){
            var reader = new FileReader();

            reader.onload = function(){
                $("#cable_image_preview").attr("src", reader.result);
            }

            reader.readAsDataURL(file);
        }		
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
		var cable_state = error;
		var cable_value = $('#cable_name').val();
		if( cable_value.length > 0 ){
			cable_state = success;
		}

		var html = '<i class="' + cable_state + '" aria-hidden="true"></i> Наиминование кабеля: ' + cable_value + '</li>';
		$('#lcable_name').html(html)

		// Check cables codename
		var cable_state = error;
		var cable_value = $('#cable_codename').val();
		if( cable_value.length > 0 ){
			cable_state = success;
		}
		var html = '<i class="' + cable_state + '" aria-hidden="true"></i> Шифр кабеля: ' + cable_value + '</li>';
		$('#lcable_codename').html(html)


		// Check cables description
		var cable_state = error;
		var cable_value = $('#cable_description').val();
		if( cable_value.length > 0 ){
			cable_state = success;
		}
		var html = '<i class="' + cable_state + '" aria-hidden="true"></i> Краткое описание: ' + cable_value + '</li>';
		$('#lcable_description').html(html)

		// Check cables image
		var cable_state = error;
		var cable_value = imageFile;
		var html = '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Файл не выбран.';

		if( cable_value.length > 0 ){
			cable_state = success;
			var html = '<i class="fa fa-file" aria-hidden="true"></i> Изображение ' + filename + '.jpeg';
		}

		$('#lfimage').html(html);

		var html = '<i class="' + cable_state + '" aria-hidden="true"></i> Изображение: ' + cable_value + '</li>';
		$('#lcable_image').html(html);

		// Check cables material
		var cable_state = error;
		var cable_value = $('#cable_material option:selected').text();
		if( cable_value.length > 0 ){
			cable_state = success;
		}
		if( cable_value == 'Другое'){
			cable_state = warning;
		}
		var html = '<i class="' + cable_state + '" aria-hidden="true"></i> Материал: ' + cable_value + '</li>';
		$('#lcable_material').html(html);

		// Check cables status
		var cable_state = error;
		var cable_value = $('#cable_status option:selected').text();
		if( cable_value.length > 0 ){
			cable_state = success;
		}
		var html = '<i class="' + cable_state + '" aria-hidden="true"></i> Статус: ' + cable_value + '</li>';
		$('#lcable_status').html(html);

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


		$('#lcable_fullname').html(filename);
		$('#cable_fullname').val(filename);	
	}

	$( document ).ready(function() {
		// On load functions
		$("#imagePreview").hide();
		$("#drawLaserSelect").hide();
		$("#otherSelect").hide();
		$("#lfdrawvectordir").hide();


		// Description change
	    $('#cable_description').on('input', function(){ 
	    	var count = 250 - $('#cable_description').val().length;
	    	$('#symbols').html(count);
	    });
	    
	    // Replace bad symbols in name
	    $('body').on('input', '#cable_name', function(){
			this.value = this.value.replace(/[^0-9A-Za-zА-Яа-я\.\, -]/g, '');
		});

	    // Set cable name
	    $('#cable_name').on('input', function(){ 
	    	var text = $('#cable_name').val().replace(/[^0-9A-Za-zА-Яа-я\.\, -]/g, '');
	    	
	    	$('#lcable_name').html(
	    		'<i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование кабеля: <i>' + text + '</i>'
	    	);

	    	if($('#cable_name').val().length > 0){
	    		text = ' - ' + text;
	    	}

	    	$('#directory').html('"<?php echo $directory_data['mask'] ?>Оборудование\\Кабели и провода\\' + $('#cable_codename').val() + text  + '"');
	    });

	    // On image change
	     $('#cable_image').change(function(e){
            var fileName = e.target.files[0].name;
            imageFile = fileName;
        });

	    // On source 3d change
	     $('#cable_3dsource').change(function(e){
            var fileName = e.target.files[0].name;
            source3dFile = fileName;
        });

	    // On source 3d change
	     $('#cable_3dstep').change(function(e){
            var fileName = e.target.files[0].name;
            step3dFile = fileName;
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

        // On source vector change
	     $('#cable_drawlaser').change(function(e){
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