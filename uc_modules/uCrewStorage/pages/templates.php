<?php
	// Require API
	require_once('uc_modules/uCrewStorage/api/storage_system.php');
	// Init class
	$uc_Storage = new uCrewStorage();

	if(isset($_GET['c'])){
		$template = $uc_Storage->getCategoryTemplate($_GET['c']);
		if($template != 0){
			$data = $uc_Storage->templateEditorBuilder($template);
		}
	}

	//print_r($_POST);
?>
		<script type="text/javascript">
			lastField = 1;
		</script>
		<div class="row">

				<form class="col-xl-8" action="/?page=uCrewStorage/templates&c=<?php echo $_GET['c'] ?>" method="post">
					<div  id="fields">
						<input type="hidden" name="apply" value="<?php echo $_GET['c'] ?>">
		     			<?php
		     				if(isset($data["complete"])){
		     					echo $data["complete"];
		     				}
		     			?>
							
						</div>
						<div class="row mb-3 float-end">
							<div class="col float-end">
								<button type="submit" class="btn btn-success">Сохранить</button>
								<button type="button" class="btn btn-primary" onclick="addField()">Добавить параматер</button>
							</div>
						</div>
				</form>
		</div>

		<script type="text/javascript" id="scriptdata">
			wasField = false;
			globalField = 0;

			function removeElement(element) {
				if(wasField == false){
					globalField = lastField;
					wasField = true;
				}
				$('#' + element).fadeOut(333, function(){ $('#' + element).remove(); });
				$('#' + element + 'hr').fadeOut(333, function(){ $('#' + element  + 'hr').remove(); });
				//globalField = globalField - 1;
			}
			$( document ).ready(function() {
<?php
		     				if(isset($data["js"])){
		     					echo $data["js"];
		     				}
?>
			});

			function addField(){

				if(wasField == false){
					globalField = lastField;
					wasField = true;
				}

				$('#fields').append('<!-- START -->						<div class="input-group mb-3" id="group' + globalField + '">								<label for="field' + globalField + '[name]" class="col-sm-2 col-form-label">Параметр ' + globalField + ':</label>							<div class="col-sm-10" style="padding-bottom: 10px">								<div class="input-group" id="field' + globalField + '-div">									<span class="input-group-text">Название / Тип</span>									<input type="text" aria-label="field' + globalField + '[name]" name="field' + globalField + '[name]" id="field' + globalField + '[name]" class="form-control" placeholder="Введите название параметра...">									<select class="form-control" name="field' + globalField + '[type]" id="field' + globalField + '[type]" >										<option value="unknown" selected>Выберите тип...</option>										<option value="text">Текст</option>										<option value="select_list" >Произвольный список</option>										<option value="select_category">Позиции из категории</option>									</select>								</div>							</div>							<div class="col-sm-12 float-end" style="padding-top: 10px">								<div class="col float-end">									<button type="button" class="btn btn-danger" onClick="removeElement(\'group' + globalField + '\')">Удалить</button>								</div>							</div>						</div>						<hr id="group' + globalField + 'hr">	     				<!-- END -->');

				globalField = globalField + 1;

				categories = categories.replace(/'/g, "\"");

				//scr = '$(\'select[name="field'+ globalField +'[type]"]\').on(\'change\', function() {			        value = $(\'select[name="field'+ globalField +'[type]"]\').val();			        if(value == \'text\'){			        	$(\'#field'+ globalField +'-value-lable\').fadeOut(100, function(){ $(\'#field'+ globalField +'-value-lable\').empty(); });			        	$(\'#field'+ globalField +'-values\').fadeOut(100, function(){ $(\'#field'+ globalField +'-values\').empty(); });			        }			        if(value == \'select_list\'){			        	$(\'#field'+ globalField +'-value-lable\').fadeOut(100, function(){ $(\'#field'+ globalField +'-value-lable\').empty(); });			        	$(\'#field'+ globalField +'-values\').fadeOut(100, function(){ $(\'#field'+ globalField +'-values\').empty(); });			        	$(\'#field'+ globalField +'-value-lable\').fadeIn(100, function(){ $(\'#field'+ globalField +'-value-lable\').append(\'Значения:\'); });			        	$(\'#field'+ globalField +'-values\').fadeIn(100, function(){ $(\'#field'+ globalField +'-values\').append(\'	<input type="text" aria-label="field'+ globalField +'-value" name="field'+ globalField +'[values]" id="field'+ globalField +'[value]" class="form-control" placeholder="Введите значения">\'); });			        }			        if(value == \'select_category\'){			        	$(\'#field'+ globalField +'-value-lable\').fadeOut(100, function(){ $(\'#field'+ globalField +'-value-lable\').empty(); });			        	$(\'#field'+ globalField +'-values\').fadeOut(100, function(){ $(\'#field'+ globalField +'-values\').empty(); });			        	$(\'#field'+ globalField +'-value-lable\').fadeIn(100, function(){ $(\'#field'+ globalField +'-value-lable\').append(\'Категория:\'); });			        	$(\'#field'+ globalField +'-values\').fadeIn(100, function(){ $(\'#field'+ globalField +'-values\').append(\' <select class="form-control" name="field'+ globalField +'[value]" id="field'+ globalField +'[value]" ><option value="0" selected>Выберите категорию...</option>' + categories + '</select>	 \'); });			        }			    });	     		';

				//$('#scriptdata').append(scr);

				scr = '$(\'select[name="field'+ globalField +'[type]"]\').on(\'change\', function() { alert(123);  });'
				var s = document.createElement("script");
				s.type = "text/javascript";
				$("#scriptdata").append("\n" + scr + "\n");


			}


		</script>