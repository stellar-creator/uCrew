<?php
  $message = "";
  $table = "";
  $blocktable = 0;

  if(isset($_GET['a'])){
    switch ($_GET['a']) {
      case 'create':
          if($this->ucDatabase->createChat($_SESSION['user_id'], $_GET['user']) != 0){
            $message = "Чат успешно создан";
          }else{
            $message = "Такой чат уже существует";
          }
        break;

      case 'chat':
         $blocktable = 1;
        break;

      default:

        break;
    }
  }

  $users = $this->ucDatabase->getUsers();
  $chats = $this->ucDatabase->getChatList($_SESSION['user_id']);
  foreach ($chats as $index => $chat) {
    $table .=  '<tr>
            <th scope="row">'.($index + 1).'</th>
            <td><a href="/?page=uCrew/messages&a=chat&user='.$chat['user_to']['user_id'].'">'.$chat['user_to']['user_name'].'</a></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
    ';
  }

if($blocktable == 0){
   echo      '<div class="row">
              <div id="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Управлние
            </div>
        </div>
            <div class="card-body">
                <div class="row g-3">
                  <div class="col float-end">
                    <button type="button" class="btn btn-secondary" id="write" name="write" data-bs-toggle="modal" data-bs-target="#createChat">Создать чат</button>
                  </div>
                </div>
            </div>
        </div>

        <div class="row">
          <div id="card mb-4">
              <div class="card-header">
                  <i class="fas fa-table me-1"></i>
                  Все чаты
              </div>
              <div class="card-body">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th scope="col" width="50px">№</th>
                        <th scope="col" width="250px">Пользователь</th>
                        <th scope="col">Последнее сообщение</th>
                        <th scope="col">Дата</th>
                        <th scope="col">Управление</th>
                      </tr>
                    </thead>
                    <tbody>
                    ' . $table . '
                    </tbody>
                  </table>
              </div>
          </div>';

   echo      '

  <!-- Modal -->
  <div class="modal fade" id="createChat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Выберите пользователя</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/" method="get">
            <input type="hidden" name="page" value="uCrew/messages">
            <input type="hidden" name="a" value="create">
            <select name="user" id="user" class="selectpicker show-tick form-control" aria-label=".form-select-sm example" data-live-search="true" data-size="5">
  ';
    foreach ($users as $index => $data) {
      echo ' <option value="'.$data['user_id'].'" selected>'.$data['user_name'].'</option>' . "\n";
    }
  echo '
            </select>
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary" value="Создать чат">

          </form>
        </div>
      </div>
    </div>
  </div>';
}else{
  echo      '<div class="row">
              <div id="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Управлние
            </div>
        </div>
            <div class="card-body">
                <div class="row g-3">
                  <div class="col float-end">
                    <button type="button" class="btn btn-secondary" id="write" name="write" data-bs-toggle="modal" data-bs-target="#deleteChat">Удалить чат</button>
                  </div>
                </div>
            </div>
        </div>
';
echo '

';
   echo      '
  <!-- Modal -->
  <div class="modal fade" id="deleteChat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Удаление чата</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/" method="get">
            <input type="hidden" name="page" value="uCrew/messages">
            <input type="hidden" name="a" value="delete">
            <p>Вы действительно хотите удалить чат?</p>
            <p>Если вы удалите чат, то вся переписка будет удалена</p>
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-danger" value="Да" >
          <input type="button" class="btn btn-primary" value="Нет" data-bs-dismiss="modal">

          </form>
        </div>
      </div>
    </div>
  </div>


  ';
}
?>