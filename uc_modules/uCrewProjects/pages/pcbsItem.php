<?php
  // Require API
  require_once('uc_modules/uCrewProjects/api/projects_system.php');
  // Init class
  $uc_Projects = new uCrewProjects();
  // Get data
  $common_data = $uc_Projects->getCommonData('pcb', $_GET['id']);

  $common_data['statuses'] = $uc_Projects->getStatuses();

  $fullname_revision = $common_data['data']['pcb_codename'] . ' - ' . $common_data['data']['pcb_name'];

  $pcb_paths = $uc_Projects->ucs_DirectoriesPath['pcbs'];
  $pcb_paths['web'] = $pcb_paths['web'] . $common_data['data']['pcb_data']['fullname'] . '/Ревизия ' . $common_data['data']['pcb_data']['revision'] . '/';
  $pcb_paths['smb'] = $pcb_paths['smb'] . $common_data['data']['pcb_data']['fullname'] . '\\Ревизия ' . $common_data['data']['pcb_data']['revision'] . '\\';
  $pcb_paths['local'] = $pcb_paths['local'] . $common_data['data']['pcb_data']['fullname'] . '/Ревизия ' . $common_data['data']['pcb_data']['revision'] . '/';

  $pcb_image = $pcb_paths['web'] . $uc_Projects->ucs_DirectoriesNames['images'] . '/' . 'Изображение 3D модели ' . $fullname_revision . '.jpeg' ;
  $pcb_image = $this->uc_CompilatorData->checkImage($pcb_image);

  $pcb_files = $uc_Projects->directoryToArray($pcb_paths['local']);

  $pcb_x3d = $pcb_paths['local'] . $uc_Projects->ucs_DirectoriesNames['3dmodels'] . '/Веб 3D модель ' . $fullname_revision . '.x3d';

  $pcb_x3d_web = "";

  if(file_exists($pcb_x3d)){
    $pcb_x3d_web = $pcb_paths['web']. $uc_Projects->ucs_DirectoriesNames['3dmodels'] . '/Веб 3D модель ' . $fullname_revision . '.x3d';
    echo "
    <script type='text/javascript' src='uc_resources/applications/x3dom/x3dom-full.js'> </script> \n
    <link rel='stylesheet' type='text/css' href='uc_resources/applications/x3dom/x3dom.css'></link> \n";
  }
?>

<div class="container-fluid">
  <div class="row">
    <h4>Общая информация</h4>
    <hr>
       <div class="col-sm-6">
        <p>Шифр печатной платы: <?php echo $common_data['data']['pcb_codename']; ?></p>
        <p>Наиминование: <?php echo $common_data['data']['pcb_name']; ?></p>
        <p>Описание: <?php echo $common_data['data']['pcb_description']; ?></p>
        <p>Автор: <?php echo $common_data['user_name']; ?></p>
        <p>Дата добавления: <?php echo $common_data['date']; ?></p>
        <p>Материал: <?php echo $common_data['data']['pcb_data']['material']; ?></p>
        <p>Цвет шелгорафии: <?php echo $common_data['data']['pcb_data']['silkscreen']; ?></p>
        <p>Цвет паяльной маски: <?php echo $common_data['data']['pcb_data']['mask']; ?></p>
        <p>Покрытие: <?php echo $common_data['data']['pcb_data']['surface']; ?></p>
        <p>Ревизия: <?php echo $common_data['data']['pcb_data']['revision']; ?></p>
        <p>Статус: <?php echo $common_data['statuses'][$common_data['data']['pcb_status']][0]; ?></p>
      </div>
      

      <div class="col-sm-6">

      <ul class="nav nav-tabs justify-content-center d-flex" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="image-tab" data-bs-toggle="tab" data-bs-target="#image" type="button" role="tab" aria-controls="image" aria-selected="true">Изображение</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="model-tab" data-bs-toggle="tab" data-bs-target="#model" type="button" role="tab" aria-controls="model" aria-selected="false">3D модель</button>
        </li>
      </ul>

      <div class="tab-content d-flex justify-content-center" id="myTabContent">
        <div class="tab-pane fade show active" id="image" role="tabpanel" aria-labelledby="image-tab">
          <?php
           echo '<img src="'.$pcb_image.'" class="img-fluid img-thumbnail" style="width: 400px">';
          ?>
        </div>
        <div class="tab-pane fade" id="model" role="tabpanel" aria-labelledby="model-tab">
        <?php

          if(file_exists($pcb_x3d)){
            echo '<x3d width="400px" height="400px" id="x3dElement"> 
                <scene> 
                  <Transform id="scaleTransformation" scale="0.33 0.33 0.33">
                    <inline url="'.$pcb_x3d_web.'"> </inline> 
                  </Transform>
                </scene> 
              </x3d>   ';
          }
        ?> 
        </div>
      </div>

      </div>
    </div>

    <div class="row">
      <div class="col">
      <h4>Файлы изделия</h4>
      <hr>
      <p class="text-break">
          Расположение на диске: <a href="file:\\<?php echo $pcb_paths['smb']; ?>\" class="link-dark"><?php echo $pcb_paths['smb']; ?></a>
      </p>
      <p class="text-break">
          Ссылка для браузеров: <a href="<?php echo $pcb_paths['web']; ?>" target="_blank" rel="noopener noreferrer" class="link-dark"><?php echo $pcb_paths['web']; ?></a>
      </p>
      <!--<p class="text-break">
          Скачать весь проект архивом: <a href="<?php echo $pcb_paths['web']; ?>" target="_blank" rel="noopener noreferrer" class="link-dark"><?php echo $common_data['data']['pcb_data']['fullname']; ?>.zip</a>
      </p>-->
      <p>
        Поделиться: 
        <a href="tg://msg?text=<?php echo urlencode($pcb_paths['web']); ?>" class="link-dark">Telegram</a>, 
        <a href='mailto:?subject=<?php echo $_SESSION['user_email']; ?>&body=Посмотреть изделие <?php
         echo $common_data['data']['pcb_data']['fullname'] . ' '.urlencode( $pcb_paths['web'] ); 
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
                <li><a class="dropdown-item" href="/?page=uCrewProjects/pcbsItem&id='.$_GET['id'].'&path='.$fullpath.'&a=remove">Удалить</a></li>
              </ul>
            </div>';

        array_push($rows, 
          array(
            '<i class="fa fa-folder" aria-hidden="true"></i> ' . $dir => array('width' => '15%'),
            '<i class="fa fa-file" aria-hidden="true"></i> ' .  $file => array('width' => '30%'),
            $size,
            date ("H:i:s d/m/Y", filemtime($fullpath)),
            $dropdown => array('class' => 'text-center')
          )
        );
      }

      foreach ($pcb_files as $dir => $value) {
        if(is_array($value)){
          $dirname = $dir;
          foreach ($value as $index => $file) {
              $subdirname = $index;
              if(!is_array($file)){
                $fullpath = $pcb_paths['local'] . $dirname . '/' . $file;
                _pushRow($dirname, '<a href="' . $pcb_paths['web'] . $dirname . '/' . $file . '" download>' . $file . '</a>', $uc_Projects->formatBytes(filesize($fullpath)), $index + 1, $rows, $fullpath);
              }else{
                foreach ($file as $subindex => $subfile) {
                  if(!is_array($subfile)){
                   $fullpath = $pcb_paths['local'] . $dirname . '/' . $subdirname . '/' . $subfile;
                  _pushRow($dirname . '<br> &#8594; <i class="fa fa-folder" aria-hidden="true"></i> ' . $subdirname, '<a href="' . $pcb_paths['web'] . $dirname . '/' . $subdirname . '/' . $subfile . '" download>' . $subfile . '</a>', $uc_Projects->formatBytes(filesize($fullpath)), $subindex + 1, $rows, $fullpath);
                  }
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
