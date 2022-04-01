<?php
    $ucModules = new uCrewModules();

    $modules = $ucModules->getAllModules();

    if(isset($_POST["main_page_content"])){
        $this->ucDatabase->setMainPageContent($_POST["main_page_content"]);
    }
    // Get main page content
    $main_page_content = $this->ucDatabase->getMainPageContent();
    // Get SMPT settings
    $smtp = $this->ucDatabase->getSettingsData('smtp');
    $smtp['setting_text'] = json_decode($smtp['setting_text'], true);
    // Get users list
    $users = $this->ucDatabase->getUsers();
    $posts = $this->ucDatabase->getPosts();
    $locations = $this->ucDatabase->getLocations();
    $remote_update_url = $this->ucDatabase->getSettingsData('version_remote')['setting_text'];

    $remote_version = $this->ucSystemPipe->checkUpdates();

    //checkUpdates

    $cols = array(
      '№' => array('class' => 'text-center', 'width' => '5%'),
      'Изображение' => array('class' => 'text-center', 'width' => '150px'),
      'Имя' => array('class' => 'text-center', 'width' => '150px'),
      'E-mail' => array('class' => 'text-center', 'width' => '10%'),
      'Телефон' => array('class' => 'text-center', 'width' => '10%'),
      'Расположение' => array('class' => 'text-center'),
      'Должность' => array('class' => 'align-middle text-center'),
      'Сатус' => array('class' => 'align-middle text-center'),
      'Группы' => array('class' => 'text-center'),
    );

    $colsModules = array(
      '№' => array('class' => 'text-center', 'width' => '5%'),
      'Наиминование' => array('class' => 'text-center'),
      'Состояние' => array('class' => 'text-center'),
      'Управление' => array('class' => 'text-center', 'width' => '150px')
    );

    $rows = array();
    $rowsModules = array();

    foreach ($users as $user_index => $user_data) {
      $post = "";
      $location = "";

      $image = $this->uc_CompilatorData->checkImage($user_data['user_image']);
      
      foreach($posts as $data){
        if($data['post_id'] == $user_data['user_post']){
            $post = $data['post_name'];
        }
      }  

      foreach($locations as $data){
        if($data['location_id'] == $user_data['user_location']){
            $location = $data['location_name'];
        }
      }

      $status = $user_data['user_status'];

      switch($status){
        case 0:
          $status = "Заблокирован";
          break;
        case 1:
          $status = "Активен";
          break;
        case 2:
          $status = "Требует активации";
          break;
        case 3:
          $status = "Почта не подтверждена";
          break;
      }

      array_push($rows, 
        array(
          $user_index + 1 => array('class' => 'align-middle text-center'),
          '<img src="'.$image.'" class="img-thumbnail imagecat" alt="'.$image.'">' => array('class' => 'align-middle text-center'),
          $user_data['user_name'] => array('class' => 'align-middle text-center'),
          $user_data['user_email'] => array('class' => 'align-middle text-center'),
          $user_data['user_phone'] => array('class' => 'align-middle text-center'),
          $location => array('class' => 'align-middle text-center'),
          $post => array('class' => 'align-middle text-center'),
          $status => array('class' => 'align-middle text-center'),
          $user_data['user_groups'] => array('class' => 'align-middle text-center')
        )
      );
    }
    
    foreach ($modules as $key => $value) {
      if(is_array($value)){
        array_push($rowsModules, 
          array(
            $key + 1 => array('class' => 'text-center', 'width' => '5%'),
            $value[0] => array('class' => 'text-center'),
            "Активен" => array('class' => 'text-center'),
            "-" => array('class' => 'text-center', 'width' => '150px')
          )
        );
      }else{
         array_push($rowsModules, 
            array(
            $key + 1 => array('class' => 'text-center', 'width' => '5%'),
            $value => array('class' => 'text-center'),
            "Неактивен" => array('class' => 'text-center'),
            "-" => array('class' => 'text-center', 'width' => '150px')
          )
        );
      }
    }

    $usersTable = $this->uc_CompilatorData->generateTable($cols, $rows);
    $modulesTable = $this->uc_CompilatorData->generateTable($colsModules, $rowsModules);
  
?>
                        <div class="row">
                            <div class="col-xl-12 col-md-6">

                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                              <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="main-page-tab" data-bs-toggle="pill" data-bs-target="#main-page" type="button" role="tab" aria-controls="main-page" aria-selected="true">Главная страница</button>
                              </li>
                              <li class="nav-item" role="presentation">
                                <button class="nav-link" id="system-tab" data-bs-toggle="pill" data-bs-target="#system" type="button" role="tab" aria-controls="system" aria-selected="false">Система</button>
                              </li>

                              <li class="nav-item" role="presentation">
                                <button class="nav-link" id="users-tab" data-bs-toggle="pill" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="false">Пользователи</button>
                              </li>

                              <li class="nav-item" role="presentation">
                                <button class="nav-link" id="modules-tab" data-bs-toggle="pill" data-bs-target="#modules" type="button" role="tab" aria-controls="modules" aria-selected="false">Модули</button>
                              </li>
                              <li class="nav-item" role="presentation">
                                <button class="nav-link" id="notifications-tab" data-bs-toggle="pill" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false">Оповещения</button>
                              </li>
                              <li class="nav-item" role="presentation">
                                <button class="nav-link" id="updates-tab" data-bs-toggle="pill" data-bs-target="#updates" type="button" role="tab" aria-controls="updates" aria-selected="false">Обновления</button>
                              </li>
                            </ul>

                            <div class="tab-content" id="pills-tabContent">

                              <div class="tab-pane fade show active" id="main-page" role="tabpanel" aria-labelledby="main-page-tab">
                                <div class="form-group col-xl-8">
                                    <form action="/?page=uCrew/settings" method="post">
                                        <label class="control-label" for="main_page_content">Редактирование главной страницы:</label>
                                        <hr>
                                        <textarea id="main_page_content" class="form-control d-none" rows="10" name="main_page_content">
<?php
                                        echo $main_page_content;
?>
                                        </textarea>

                                      <div class="float-end">
                                        <input type="submit" value="Сохранить" class="btn btn-success">
                                      </div>
                                    </form>
                                </div>
                              </div>

                              <div class="tab-pane fade" id="system" role="tabpanel" aria-labelledby="system-tab">
                               
                               <div class="row">
                                <form class="col-xl-8 form-group" action="/" method="get">
                                    <input hidden="" name="page" value="uCrew/settings">
                                    <input hidden="" name="handler" value="change_notification_email">
                                    <label class="control-label">Общие настройки:</label>
                                      <hr>
                                      <div class="row mb-3">
                                        <label for="name" class="col-sm-2 col-form-label">Наиминование системы</label>
                                        <div class="col-sm-10">
                                          <input type="text" class="form-control" name="system_name" id="system_name" value="<?php echo $this->system['organization']; ?>">
                                        </div>
                                      </div>

                                      <div class="row mb-3">
                                        <label for="password" class="col-sm-2 col-form-label">Расположение</label>
                                        <div class="col-sm-10">
                                          <input type="text" class="form-control" name="directory" id="directory" value="<?php echo $this->system['main_directory']; ?>">
                                        </div>
                                      </div>
                                      
                                        <div class="float-end">
                                          <button type="submit" class="btn btn-success">Изменить данные</button>
                                        </div>
                                     
                                    </form>
                                </div>
                                <div class="row">
                                <form class="col-xl-8 form-group" action="/" method="get">
                                    <input hidden="" name="page" value="uCrew/settings">
                                    <input hidden="" name="handler" value="change_notification_email">
                                    <label class="control-label">Настройки сервера:</label>
                                      <hr>
                                      <div class="row mb-3">
                                        <label for="name" class="col-sm-2 col-form-label">Доменное имя</label>
                                        <div class="col-sm-10">
                                          <input type="text" class="form-control" name="domain" id="domain" value="<?php echo $this->system['main_domain']; ?>">
                                        </div>
                                      </div>
                                      <div class="float-end">
                                        <button type="submit" class="btn btn-success">Изменить данные</button>
                                      </div>
                                    </form>
                                </div>
                              </div>

                              <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
<?php
                                    echo $usersTable;
?>
                              </div>

                              <div class="tab-pane fade" id="modules" role="tabpanel" aria-labelledby="modules-tab">
<?php
                                    echo $modulesTable;
?>
                              </div>

                              <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                                

                                <form class="col-xl-8 form-group" action="/" method="get">
                                    <input hidden="" name="page" value="uCrew/settings">
                                    <input hidden="" name="handler" value="change_notification_email">
                                    <label class="control-label">Настройки оповещений на E-mail:</label>
                                      <hr>
                                      <div class="row mb-3">
                                        <label for="name" class="col-sm-2 col-form-label">Сервер SMTP</label>
                                        <div class="col-sm-10">
                                          <input type="text" class="form-control" name="server" id="server" value="<?php echo $smtp['setting_text']['server']; ?>">
                                        </div>
                                      </div>
                                      <div class="row mb-3">
                                        <label for="password" class="col-sm-2 col-form-label">Порт (SSL)</label>
                                        <div class="col-sm-10">
                                          <input type="text" class="form-control" name="port" id="port" value="<?php echo $smtp['setting_text']['port']; ?>">
                                        </div>
                                      </div>
                                      <div class="row mb-3">
                                        <label for="email" class="col-sm-2 col-form-label">Пользователь</label>
                                        <div class="col-sm-10">
                                          <input type="email" class="form-control" id="email" value="<?php echo $smtp['setting_text']['user']; ?>">
                                        </div>
                                      </div>
                                      <div class="row mb-3">
                                        <label for="text" class="col-sm-2 col-form-label">Пароль</label>
                                        <div class="col-sm-10">
                                          <input type="password" class="form-control" id="password" name="password" value="<?php echo $smtp['setting_text']['password']; ?>">
                                        </div>
                                      </div>

                                      <div class="float-end">
                                        <button type="submit" class="btn btn-success">Изменить данные</button>
                                      </div>
                                    </form>
      
                              </div>

                              <div class="tab-pane fade" id="updates" role="tabpanel" aria-labelledby="updates-tab">
                                  
                                  <div class="col-xl-8 form-group">

                                  <div class="row mb-3">

                                    <div class="col-sm-12">
                                    <label class="control-label">Проверка обновлений:</label>
                                    <hr>
<?php
                                    if($remote_version['state'] == true){
                                      echo "<p>Доступно обновление (новая версия <b>".$remote_version['version']."</b>)</p>\n";
                                      echo '
                                      <div class="float-end">
                                      <a href="/?page=uCrew/update" class="btn btn-primary">Обновить систему</a>
                                      </div>
                                      ';

                                    }else{
                                       echo "<p>У вас актуальная версия</p>\n";
                                    }
?>
                                  
                                    </div>
                                  </div>

                                    <input hidden="" name="page" value="uCrew/settings">
                                    <input hidden="" name="handler" value="change_update_server">
                                    <label class="control-label">Настройки сервера обновлений:</label>
                                      <hr>

                                      <div class="row mb-3">
                                        <label for="name" class="col-sm-2 col-form-label">Сервер (git raw)</label>
                                        <div class="col-sm-10">
                                          <input type="text" class="form-control" name="server" id="server" value="<?php echo $remote_update_url; ?>">
                                        </div>
                                      </div> 
                                      <div class="row mb-3">
                                        <label for="name" class="col-sm-2 col-form-label">Репозиторий</label>
                                        <div class="col-sm-10">
                                          <input type="text" class="form-control" name="repo" id="repo" value="<?php echo $this->system['update_server']; ?>">
                                        </div>
                                      </div>
                      
                                      <div class="float-end">
                                        <button type="submit" class="btn btn-success">Изменить сервер</button>
                                      </div>

                                    </div>
                              </div>

                            </div>                             



                            </div>
                        </div>

<script type="text/javascript">
    $( document ).ready(function() {
        $('#main_page_content').wysiwyg();
    });
</script>
