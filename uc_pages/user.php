
<?php
    $post = "";
    $location = "";
    $posts = $this->ucDatabase->getPosts();
    foreach($posts as $data){
        if($data['post_id'] == $_SESSION['user_post']){
            $post = $data['post_name'];
        }
    }    

    $locations = $this->ucDatabase->getLocations();
    foreach($locations as $data){
        if($data['location_id'] == $_SESSION['user_location']){
            $location = $data['location_name'];
        }
    }
?>
                <div class="row">
                    <form class="col-xl-8" action="/" method="get">
                        <input hidden="" name="page" value="uCrew/user">
                        <input hidden="" name="handler" value="change_user_data">
                          <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Пользователь</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" name="name" id="name" value="<?php echo $_SESSION["user_name"] ?>" disabled readonly>
                            </div>
                          </div>
                          <div class="row mb-3">
                            <label for="password" class="col-sm-2 col-form-label">Пароль</label>
                            <div class="col-sm-10">
                              <input type="password" class="form-control" name="password" id="password" value="<?php echo $_SESSION["user_password"] ?>">
                            </div>
                          </div>
                          <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">E-mail</label>
                            <div class="col-sm-10">
                              <input type="email" class="form-control" id="email" value="<?php echo $_SESSION["user_email"] ?>">
                            </div>
                          </div>
                          <div class="row mb-3">
                            <label for="text" class="col-sm-2 col-form-label">Телефон</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $_SESSION["user_phone"] ?>">
                            </div>
                          </div>

                          <div class="row mb-3">
                            <label for="post" class="col-sm-2 col-form-label">Должность</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="post" value="<?php echo $post; ?>" disabled readonly>
                            </div>
                          </div>

                           <div class="row mb-3">
                            <label for="location" class="col-sm-2 col-form-label">Расположение</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="location" value="<?php echo $location; ?>" disabled readonly>
                            </div>
                          </div>

                           <div class="row mb-3">
                            <p>* Обратите внимание: только администратор может изменять данные на сером фоне</p>
                           </div>
                          <div class="row mb-3 float-end">
                            <button type="submit" class="btn btn-primary">Изменить данные</button>
                          </div>
                        </form>
                    </div>