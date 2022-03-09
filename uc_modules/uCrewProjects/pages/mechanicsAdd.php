<?php
	// Require API
	require_once('uc_modules/uCrewProjects/api/projects_system.php');
	// Init class
	$uc_Projects = new uCrewProjects();
	// Get last codename
	$codename = $uc_Projects->getMechanicsLastCodeName();
	// Get directory data
	$directory_data = $uc_Projects->getProjectDirectoryData();

	print_r($_POST);
	print_r($_FILES);
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

<div class="row">
	<form action="/?page=uCrewProjects/mechanicsAdd" method="post" id="addMechanicForm" enctype="multipart/form-data">
		<h4>Общая информация</h4>
		<hr>
		<div class="mb-3">
		  <label for="mechanics_name" class="form-label">Наиминование изделия <i>(*обратите внимание, что следующие символы запрещены: \/():*?"|+.%!@&lt;&gt;)</i></label>
		  <input class="form-control" type="text" id="mechanics_name" name="mechanics_name" required>
		  <p>
		  	<figcaption class="blockquote-footer">
		  		Директория на диске: 
		  		<cite id="directory">
		  			<?php 
		  				echo '"' . $directory_data['mask'] . "Оборудование\\Механические изделия\\" . $codename . '"'; 
		  			?>
		  		</cite>  
		  	</figcaption>
		  </p>
		</div>
		<div class="mb-3">
		  <label for="mechanics_description" class="form-label">Краткое описание</label>
		  <input class="form-control" type="text" id="mechanics_description" name="mechanics_description" required>
 			<p>
		  	<figcaption class="blockquote-footer">
		  		Осталось символов: 
		  		<cite id="symbols">250</cite>  
		  	</figcaption>
		  </p>
		</div>
		<div class="mb-3">
		  <label for="mechanics_material" class="form-label">Материал изделия</label>
		  <select class="selectpicker show-tick form-control" id="mechanics_material" name="mechanics_material" data-live-search="true" data-size="5" required>
		  	<option value="unknown" selected>Другое</option>
		  	<option value="alum">Алюминий</option>
		  </select>
		</div>
		<div class="mb-3">
		  <label for="mechanics_status" class="form-label">Статус</label>
		  <select class="form-control" id="mechanics_status" name="mechanics_status" required>
		  	<option value="1" selected>Разработка</option>
		  	<option value="2">Производство</option>
		  	<option value="3">Архивный</option>
		  </select>
		</div>
		<div class="mb-3">
		  <label for="mechanics_codename" class="form-label">Шифр изделия <i>(*присваивается <a href="#" onclick="changeCodeNameState()" id="codeNameState" class="link-dark">автоматически</a>)</i></label>
		  <input class="form-control" type="text" id="mechanics_codename" name="mechanics_codename" readonly value="<?php echo $codename; ?>" required>
		</div>
		<div class="mb-3">
		  <label for="select_image" class="form-label">Изображение изделия - JPEG (*.jpeg, *.jpg)</label>
		  <input class="form-control" type="file" id="select_image" name="select_image" accept="image/jpeg" onchange="changeImagePreview()" required>
			<p>
		  		<figcaption class="blockquote-footer">
		  			Данный файл <cite>является обязательным</cite>  
		  		</figcaption>
		  	</p>
		</div>

		<div class="mb-3" id="imagePreview">
		  <label for="select_image_preview" class="form-label"></label>
		  <img id="select_image_preview" class="img-thumbnail imagecat" src="uc_resources/images/uCrewStorage/categories/unknown.png" class="img-fluid" />
		</div>

		<h4>Файлы изделия</h4>
		<hr>

		<div class="mb-3">
		  <label for="select_3d_source" class="form-label">Исходный файл 3D модели - Компас 3D (*.m3d, *.a3d)</label>
		  <input class="form-control" type="file" id="select_3d_source" name="3d_source" accept=".m3d,.a3d" required>
			<p>
		  		<figcaption class="blockquote-footer">
		  			Данный файл <cite>является обязательным</cite>  
		  		</figcaption>
		  	</p>
		</div>
		<div class="mb-3">
		  <label for="select_step" class="form-label">Готовый файл 3D модели - STEP AP203, AP214, AP242 (*.step, *.stp)</label>
		  <input class="form-control" type="file" id="select_step" name="select_step" accept=".step,.stp" required>
			<p>
		  		<figcaption class="blockquote-footer">
		  			Данный файл <cite>является обязательным</cite>  
		  		</figcaption>
		  	</p>
		</div>


		<div class="mb-3">
		  <label for="do_stl" class="form-label">Дополнительно</label>

			<div class="form-check" id="do_stl">
			  <input class="form-check-input" type="checkbox" id="addDraw">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить файлы чертежа изделия
			  </label>
			</div>


			<div class="form-check" id="do_stl">
			  <input class="form-check-input" type="checkbox" id="addOther">
			  <label class="form-check-label" for="flexCheckDefault">
			    Прикрепить дополнительные файлы <i>(аннотации, примечания, прочее)</i>
			  </label>
			</div>

			<div class="form-check" id="do_stl">
			  <input class="form-check-input" type="checkbox" id="generate_stl" name="generate_stl">
			  <label class="form-check-label" for="flexCheckDefault">
			    Преобразовать готовую 3D модель (*.step, *.stp) в STL (*.stl) файл для 3D печати
			  </label>
			</div>

		</div>

		<div class="mb-3" id="drawSourceSelect">
		  <label for="select_draw" class="form-label">Исходный файл чертежа - Компас 3D (*.cdw)</label>
		  <input class="form-control" type="file" id="select_draw" name="draw_source" accept=".cdw">
		</div>
		<div class="mb-3" id="drawFileSelect">
		  <label for="select_pdf" class="form-label">Готовый файл чертежа - Portable Document Format (*.pdf)</label>
		  <input class="form-control" type="file" id="select_pdf" name="select_pdf" accept=".pdf">
		</div>

		<div class="mb-3" id="otherSelect">
		  <label for="select_annotation" class="form-label">Дополнительные файлы - Любой формат <i>(максимальное кол-во файлов: <?php print(ini_get('max_file_uploads')); ?>, каждый размером не более <?php print(ini_get('upload_max_filesize')); ?>)</i></label>
		  <input class="form-control" type="file" id="select_annotation" name="select_annotation" multiple>
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
					  		Оборудование
					  		<ul>
					  			<li>
					  				Механика
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

	function changeCodeNameState(){
		if(readonly == true){
			readonly = false;
			$("#codeNameState").html("пользователем");
		}else{
			readonly = true;
			$("#codeNameState").html("автоматически");
		}

		$("#mechanics_codename").attr("readonly", readonly);  
	}

	function changeImagePreview() {
		$("#imagePreview").show("fast");

  		var file = $("#select_image").get(0).files[0];

        if(file){
            var reader = new FileReader();

            reader.onload = function(){
                $("#select_image_preview").attr("src", reader.result);
            }

            reader.readAsDataURL(file);
        }		
	}

	function changeModalPresubmit() {
		var success = 'fa fa-check-circle';
		var warning = 'fa fa-exclamation-circle';
		var error = 'fa fa-times-circle';

		var filename = $('#mechanics_codename').val();

		if($('#mechanics_name').val().length > 0){
			filename =  filename + ' - ' + $('#mechanics_name').val();
		}

		// Check mechanics name
		var mechanics_state = error;
		var mechanics_value = $('#mechanics_name').val();
		if( mechanics_value.length > 0 ){
			mechanics_state = success;
		}
		var html = '<i class="' + mechanics_state + '" aria-hidden="true"></i> Наиминование изделия: ' + mechanics_value + '</li>';
		$('#lmechanic_name').html(html)

		// Check mechanics codename
		var mechanics_state = error;
		var mechanics_value = $('#mechanics_codename').val();
		if( mechanics_value.length > 0 ){
			mechanics_state = success;
		}
		var html = '<i class="' + mechanics_state + '" aria-hidden="true"></i> Шифр изделия: ' + mechanics_value + '</li>';
		$('#lmechanic_codename').html(html)


		// Check mechanics description
		var mechanics_state = error;
		var mechanics_value = $('#mechanics_description').val();
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
		var mechanics_value = $('#mechanics_material option:selected').text();
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
		var mechanics_value = $('#mechanics_status option:selected').text();
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

	}

	$( document ).ready(function() {
		// On load functions
		$("#imagePreview").hide();
		$("#drawSourceSelect").hide();
		$("#drawFileSelect").hide();
		$("#otherSelect").hide();
		$("#lfdrawsource").hide();
		$("#lfdrawdir").hide();
		$("#lfdrawdir").hide();

		// Description change
	    $('#mechanics_description').on('input', function(){ 
	    	var count = 250 - $('#mechanics_description').val().length;
	    	$('#symbols').html(count);
	    });
	    
	    // Replace bad symbols in name
	    $('body').on('input', '#mechanics_name', function(){
			this.value = this.value.replace(/[^0-9A-Za-zА-Яа-я\.\, ]/g, '');
		});

	    // Set mechanic name
	    $('#mechanics_name').on('input', function(){ 
	    	var text = $('#mechanics_name').val().replace(/[^0-9A-Za-zА-Яа-я\.\, ]/g, '');
	    	
	    	$('#lmechanic_name').html(
	    		'<i class="fa fa-check-circle" aria-hidden="true"></i> Наиминование изделия: <i>' + text + '</i>'
	    	);

	    	if($('#mechanics_name').val().length > 0){
	    		text = ' - ' + text;
	    	}

	    	$('#directory').html('"<?php echo $directory_data['mask'] ?>Оборудование\\Механические изделия\\' + $('#mechanics_codename').val() + text  + '"');
	    });

	    // On image change
	     $('#select_image').change(function(e){
            var fileName = e.target.files[0].name;
            imageFile = fileName;
        });

	    // On source 3d change
	     $('#select_3d_source').change(function(e){
            var fileName = e.target.files[0].name;
            source3dFile = fileName;
        });

	    // On source 3d change
	     $('#select_step').change(function(e){
            var fileName = e.target.files[0].name;
            step3dFile = fileName;
        });

	    // On add draw changed
	    $('#addDraw').change(function(e){
			if ($(this).is(':checked')) {
				$("#drawSourceSelect").show("fast");
				$("#drawFileSelect").show("fast");
  			}else{
				$("#drawSourceSelect").hide("fast");
				$("#drawFileSelect").hide("fast");
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

	});
</script>