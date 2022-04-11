<?php
  // Require API
  require_once('uc_modules/uCrewProjects/api/projects_system.php');
  // Init class
  $uc_Projects = new uCrewProjects();
  // Get data
  $data = $uc_Projects->getMechanicItem($_GET['id']);
  $user_name =  $uc_Projects->ucs_CommonDatabase->getUser($data['mechanic_author_id'])['user_name'];
  $date = date_format(date_create($data['mechanic_create_timestamp']),"d.m.Y в H:i");
  $directory_data = $uc_Projects->getProjectDirectoryData();
  $statuses = $uc_Projects->getStatuses();


  $mechanic_paths = $uc_Projects->ucs_DirectoriesPath['mechanics'];
  $mechanic_paths['web'] = $mechanic_paths['web'] . $data['mechanic_data']['fullname'] . '/';
  $mechanic_paths['smb'] = $mechanic_paths['smb'] . $data['mechanic_data']['fullname'] . '\\';
  $mechanic_paths['local'] = $mechanic_paths['local'] . $data['mechanic_data']['fullname'] . '/';

  $mechanic_image = $mechanic_paths['web'] . $uc_Projects->ucs_DirectoriesNames['images'] . '/' . 'Изображение ' . $data['mechanic_data']['fullname'] . '.jpeg' ;
  $mechanic_image = $this->uc_CompilatorData->checkImage($mechanic_image);

  $mechanic_files = $uc_Projects->directoryToArray($mechanic_paths['local']);

  //print_r($mechanic_files);
?>

<!--<script type='text/javascript' src='uc_resources/applications/x3dom/x3dom-full.js'> </script> 
<link rel='stylesheet' type='text/css' href='uc_resources/applications/x3dom/x3dom.css'></link> -->

<div class="container-fluid">
  <div class="row">
    <h4>Общая информация</h4>
    <hr>
       <div class="col-sm-6">
        <p>Шифр изделия: <?php echo $data['mechanic_codename']; ?></p>
        <p>Наиминование: <?php echo $data['mechanic_name']; ?></p>
        <p>Описание: <?php echo $data['mechanic_description']; ?></p>
        <p>Автор: <?php echo $user_name; ?></p>
        <p>Дата добавления: <?php echo $date; ?></p>
        <p>Материал: <?php echo $data['mechanic_data']['material']; ?></p>
        <p>Статус: <?php echo $statuses[$data['mechanic_status']][0]; ?></p>
      </div>
      <div class="col-sm-6 justify-content-end d-flex">
        <img src="<?php echo $mechanic_image; ?>" class="img-fluid img-thumbnail" style="width: 500px">
      </div>
    </div>

    <div class="row">
      <div class="col">
      <h4>Файлы изделия</h4>
      <hr>
      <p class="text-break">
          Расположение на диске: <a href="file:\\<?php echo $mechanic_paths['smb']; ?>\" class="link-dark"><?php echo $mechanic_paths['smb']; ?></a>
      </p>
      <p class="text-break">
          Ссылка для браузеров: <a href="<?php echo $mechanic_paths['web']; ?>" target="_blank" rel="noopener noreferrer" class="link-dark"><?php echo $mechanic_paths['web']; ?></a>
      </p>
      <!--<p class="text-break">
          Скачать весь проект архивом: <a href="<?php echo $mechanic_paths['web']; ?>" target="_blank" rel="noopener noreferrer" class="link-dark"><?php echo $data['mechanic_data']['fullname']; ?>.zip</a>
      </p>-->
      <p>
        Поделиться: 
        <a href="tg://msg?text=<?php echo urlencode($mechanic_paths['web']); ?>" class="link-dark">Telegram</a>, 
        <a href='mailto:?subject=<?php echo $_SESSION['user_email']; ?>&body=Посмотреть изделие <?php
         echo $data['mechanic_data']['fullname'] . ' '.urlencode( $mechanic_paths['web'] ); 
       ?>' class="link-dark">Электронная почта</a>
      </p>

      <hr>
    <?php

      $cols = array(
        'Директория' => array('width' => '15%'),
        'Файл' => array('width' => '30%'),
        'Размер',
        'Дата добавления',
        'Управление' => array('class' => 'text-center')
      );

      $rows = array();

      function _pushRow($dir, $file, $size, $date, &$rows, $fullpath){
        
        $dropdown = '<div class="dropdown">
            <button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">Действие</button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="#">Заменить</a></li>
                <li><a class="dropdown-item" href="/?page=uCrewProjects/mechanicsItem&id='.$_GET['id'].'&path='.$fullpath.'&a=remove">Удалить</a></li>
              </ul>
            </div>';

        array_push($rows, 
          array(
            '<i class="fa fa-folder" aria-hidden="true"></i> ' . $dir => array('width' => '15%'),
            '<i class="fa fa-file" aria-hidden="true"></i> ' .  $file => array('width' => '30%'),
            $size . ' (Байт)',
            date ("H:i:s d/m/Y", filemtime($fullpath)),
            $dropdown => array('class' => 'text-center')
          )
        );
      }

      foreach ($mechanic_files as $dir => $value) {
        if(is_array($value)){
          $dirname = $dir;
          foreach ($value as $index => $file) {
              $subdirname = $index;
              if(!is_array($file)){
                $fullpath = $mechanic_paths['local'] . $dirname . '/' . $file;
                _pushRow($dirname, '<a href="' . $mechanic_paths['web'] . $dirname . '/' . $file . '" download>' . $file . '</a>', filesize($fullpath), $index + 1, $rows, $fullpath);
              }else{
                foreach ($file as $subindex => $subfile) {
                   $fullpath = $mechanic_paths['local'] . $dirname . '/' . $subdirname . '/' . $subfile;
                  _pushRow($dirname . '<br> &#8594; <i class="fa fa-folder" aria-hidden="true"></i> ' . $subdirname, '<a href="' . $mechanic_paths['web'] . $dirname . '/' . $subdirname . '/' . $subfile . '" download>' . $subfile . '</a>', filesize($fullpath), $subindex + 1, $rows, $fullpath);
                }
              }
          }
        }else{
          //_pushRow('-', $value, '0', '1', $rows);
        }
      }

      echo $this->uc_CompilatorData->generateTable($cols, $rows);
    ?>
    </div>
  </div>
  </div>

<script type="text/javascript">
  $( document ).ready(function() {
      document.getElementById('scaleTransformation').setAttribute('scale', '25 25 25');
  }
</script>