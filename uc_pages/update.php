<?php
   $remote_version = $this->ucSystemPipe->checkUpdates();
?>

<div class="row">
  <p>Обновление системы с версии <?php echo $this->version . ' на ' . $remote_version['version']; ?> </p>
  <?php
    $this->ucSystemPipe->updateSystem();
  ?>                          
</div>
