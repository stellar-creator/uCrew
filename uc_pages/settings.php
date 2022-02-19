<?php
    if(isset($_POST["main_page_content"])){
        $this->ucDatabase->setMainPageContent($_POST["main_page_content"]);
    }

    $main_page_content = $this->ucDatabase->getMainPageContent();

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
                                <div class="form-group">
                                    <form action="/?page=uCrew/settings" method="post">
                                        <label class="control-label" for="main_page_content">Редактирование главной страницы:</label>
                                        <textarea id="main_page_content" class="form-control d-none" rows="10" name="main_page_content">
    <?php
                                        echo $main_page_content;
    ?>
                                        </textarea>
                                        <input type="submit" value="Сохранить" class="btn btn-success">
                                    </form>
                                </div>
                              </div>

                              <div class="tab-pane fade" id="system" role="tabpanel" aria-labelledby="system-tab">

                              </div>

                              <div class="tab-pane fade" id="modules" role="tabpanel" aria-labelledby="modules-tab">

                              </div>

                              <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">


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