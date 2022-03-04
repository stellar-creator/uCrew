<?php
	// Require API
	require_once('uc_modules/uCrewProjects/api/projects_system.php');
	// Init class
	$uc_Projects = new uCrewProjects();
	// Get last codename
	$codename = $uc_Projects->getMechanicsLastCodeName();
?>

<div class="row">
	<form action="/?page=uCrewProjects/mechanicsAdd" method="post">
		<h4>Общая информация</h4>
		<hr>
		<div class="mb-3">
		  <label for="mechanics_name" class="form-label">Наиминование изделия <i>(*обратите внимание, что следующие символы запрещены: \/():*?"|+.%!@&lt;&gt;)</i></label>
		  <input class="form-control" type="text" id="mechanics_name" name="mechanics_name" required>
		  <p>
		  	<figcaption class="blockquote-footer">
		  		Директория на диске: 
		  		<cite id="directory">"Оборудование\Механические изделия\<?php echo $codename; ?>"</cite>  
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
		  	<option value="unknown">Другое</option>
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
		  <label for="mechanics_codename" class="form-label">Шифр изделия (присваивается автоматически)</label>
		  <input class="form-control" type="text" id="mechanics_codename" name="mechanics_codename" readonly value="<?php echo $codename; ?>">
		</div>
		<div class="mb-3">
		  <label for="select_image" class="form-label">Изображение изделия - JPEG (*.jpeg, *.jpg)</label>
		  <input class="form-control" type="file" id="select_image" name="select_image" accept="image/jpeg" required>
		</div>

		<h4>Файлы изделия</h4>
		<hr>

		<div class="mb-3">
		  <label for="select_3d_source" class="form-label">Исходный файл 3D модели - Компас 3D (*.m3d, *.a3d)</label>
		  <input class="form-control" type="file" id="select_3d_source" name="3d_source" accept=".m3d,.a3d" required>
		</div>
		<div class="mb-3">
		  <label for="select_draw" class="form-label">Исходный файл чертежа - Компас 3D (*.cdw)</label>
		  <input class="form-control" type="file" id="select_draw" name="draw_source" accept=".cdw">
		</div>
		<div class="mb-3">
		  <label for="select_spec" class="form-label">Готовый файл спецификации - Portable Document Format (*.pdf)</label>
		  <input class="form-control" type="file" id="select_spec" name="select_spec" accept=".pdf">
		</div>
		<div class="mb-3">
		  <label for="select_pdf" class="form-label">Готовый файл чертежа - Portable Document Format (*.pdf)</label>
		  <input class="form-control" type="file" id="select_pdf" name="select_pdf" accept=".pdf">
		</div>
		<div class="mb-3">
		  <label for="select_annotation" class="form-label">Дополнительные файлы - Любой формт (*.*)</label>
		  <input class="form-control" type="file" id="select_annotation" name="select_annotation" multiple>
		</div>
		<div class="d-grid gap-1 d-md-flex justify-content-md-end" style="padding-bottom: 10px">
		 	<input type="submit" class="btn btn-success me-md-1" value="Добавить изделие">
		</div>
	</form>
</div>

<script type="text/javascript">
	$( document ).ready(function() {
	    $('#mechanics_name').on('input', function(){ 
	    	$('#directory').html('"Оборудование\\Механические изделия\\<?php echo $codename; ?> - ' + $('#mechanics_name').val() + '"');
	    });

	    $('#mechanics_description').on('input', function(){ 
	    	var count = 250 - $('#mechanics_description').val().length;
	    	$('#symbols').html(count);
	    })
	});
</script>