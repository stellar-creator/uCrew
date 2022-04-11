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
  $location = '"' . $directory_data['mask'] . "Механические изделия\\" . $data['mechanic_data']['fullname'] . '\"';
  $location_ref = $directory_data['mask'] . "Механические изделия\\" . $data['mechanic_data']['fullname'];
  $web_location = 'http://94.51.83.132/uc_resources/projects/mount/Механические изделия/' . $data['mechanic_data']['fullname'] . '/'; 
  if($data['mechanic_image'] == ""){
    $data['mechanic_image'] = 'uc_resources/images/uCrewStorage/categories/unknown.png';
  }

  $statuses = $uc_Projects->getStatuses();
?>


<script type='text/javascript' src='uc_resources/applications/x3dom/x3dom-full.js'> </script> 
<link rel='stylesheet' type='text/css' href='uc_resources/applications/x3dom/x3dom.css'></link> 

<div class="container-fluid">
  <div class="row">
    <h4>Общая информация</h4>
    <hr>
     <div class="col">
      <p>Шифр изделия: <?php echo $data['mechanic_codename']; ?></p>
      <p>Наиминование: <?php echo $data['mechanic_name']; ?></p>
      <p>Описание: <?php echo $data['mechanic_description']; ?></p>
      <p>Автор: <?php echo $user_name; ?></p>
      <p>Дата добавления: <?php echo $date; ?></p>
      <p>Материал: <?php echo $data['mechanic_data']['material']; ?></p>
      <p>Статус: <?php echo $statuses[$data['mechanic_status']][0]; ?></p>
    </div>
     <div class="col d-flex justify-content-center">
      <!--<img src="<?php echo $data['mechanic_image']; ?>" class="img-thumbnail" style="width: 50%">-->

      <x3d width='400px' height='300px' id="x3dElement"> 
        <scene> 
          <Transform id="scaleTransformation" scale="25 25 25">
            <inline url="uc_resources/data/test.x3d"> </inline> 
          </Transform>
        </scene> 
      </x3d>   

     </div>
    </div>
    <div class="row">
      <div class="col">
      <h4>Файлы изделия</h4>
      <hr>
      <p class="text-break">
          Расположение на диске: <a href="file:\<?php echo $location_ref; ?>\" class="link-dark"><?php echo $location; ?></a>
      </p>
      <p class="text-break">
          Ссылка для браузеров: <a href="<?php echo $web_location; ?>" target="_blank" rel="noopener noreferrer" class="link-dark"><?php echo $web_location; ?></a>
      </p>
      <p class="text-break">
          Скачать весь проект архивом: <a href="<?php echo $web_location; ?>" target="_blank" rel="noopener noreferrer" class="link-dark"><?php echo $data['mechanic_data']['fullname']; ?>.zip</a>
      </p>
      <p>
        Поделться: 
        <a href="tg://msg?text=<?php echo urlencode($web_location); ?>" class="link-dark">Telegram</a>, 
        <a href='mailto:?subject=<?php echo $_SESSION['user_email']; ?>&body=Посмотерть изделие <?php
         echo $data['mechanic_data']['fullname'] . ' '.urlencode( $web_location ); 
       ?>' class="link-dark">Электронная почта</a>
      </p>
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col" >Тип</th>
            <th scope="col" >Файл</th>
            <th scope="col" class="align-middle text-center">Управление</th>
          </tr>
        </thead>
        <tbody>

          <tr>
            <td class="align-middle">Исходный 3D файл (Компас 3D, *.m3d)</td>
            <td class="align-middle"><?php echo $data['mechanic_data']['fullname']; ?>.m3d</td>
            <td class="align-middle text-center">
              <div class="dropdown">
              <button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">Действие</button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <li><a class="dropdown-item" href="<?php echo $web_location . 'Исходные файлы/3D модель ' . $data['mechanic_data']['fullname'] . '.m3d'; ?>" download>Скачать</a></li>
                  <li><a class="dropdown-item" href="#">Заменить</a></li>
                </ul>
            </div>
            </td>
          </tr>

          <tr>
            <td class="align-middle">Готовая 3D модель (STEP, *.step)</td>
            <td class="align-middle"><?php echo $data['mechanic_data']['fullname']; ?>.step</td>
            <td class="align-middle text-center">
            
            <div class="dropdown">
              <button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">Действие</button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                  <li><a class="dropdown-item" href="<?php echo $web_location . '3D модель/3D модель ' . $data['mechanic_data']['fullname'] . '.step'; ?>" download>Скачать</a></li>
                  <li><a class="dropdown-item" href="#">Заменить</a></li>
                </ul>
            </div>
            </td>
          </tr>


        </tbody>
      </table>
    </div>
  </div>
  </div>

<script type="text/javascript">
  $( document ).ready(function() {
      document.getElementById('scaleTransformation').setAttribute('scale', '25 25 25');
  }
</script>