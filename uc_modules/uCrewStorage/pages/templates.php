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
?>
		<div class="row">

				<form class="col-xl-8" action="/?page=uCrewStorage/templates&c=<?php echo $_GET['c'] ?>" method="post">
					<input type="hidden" name="apply" value="<?php echo $_GET['c'] ?>">
	     			<?php
	     				echo $data;
	     			?>
						

						<div class="row mb-3 float-end">
							<div class="col float-end">
								<button type="submit" class="btn btn-success">Сохранить</button>
								<button type="button" class="btn btn-primary">Добавить параматер</button>
							</div>
						</div>
				</form>
		</div>

		<script type="text/javascript">
			function removeElement(element) {
				$('#' + element).fadeOut(333, function(){ $('#' + element).remove(); });
				$('#' + element + 'hr').fadeOut(333, function(){ $('#' + element  + 'hr').remove(); });
			}
		</script>