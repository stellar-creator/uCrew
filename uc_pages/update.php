<?php
   $remote_version = $this->ucSystemPipe->checkUpdates();
?>

<div class="row">
  <p>Обновление системы с версии <?php echo $this->version . ' на ' . $remote_version['version']; ?> </p>
  <?php
    echo "<p>Процесс обновления запущен успешно.</p><pre>";
    echo $this->ucSystemPipe->updateSystem();
    echo "</pre><p>Процесс обновления зевершён.</p>";
  ?>                          
</div>
