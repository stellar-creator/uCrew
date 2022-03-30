<?php
    if(isset($_POST["main_page_content"])){
        $this->ucDatabase->setMainPageContent($_POST["main_page_content"]);
    }

    $main_page_content = $this->ucDatabase->getMainPageContent();

    $smtp = $this->ucDatabase->getSettingsData('smtp');
    $smtp['setting_text'] = json_decode($smtp['setting_text'], true);
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

                              </div>

                              <div class="tab-pane fade" id="modules" role="tabpanel" aria-labelledby="modules-tab">

                              </div>

                              <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                                

                                <form class="col-xl-8 form-group" action="/" method="get">
                                    <input hidden="" name="page" value="uCrew/user">
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

                              </div>

                            </div>                             



                            </div>
                        </div>

<script type="text/javascript">
    $( document ).ready(function() {
        $('#main_page_content').wysiwyg();
    });
</script>
