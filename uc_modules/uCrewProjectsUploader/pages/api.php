<?php
  if(isset($_GET['login']) and isset($_GET['password'])){
    $ucSession = new uCrewSession();
    if($ucSession->checkAuthorization() == 0){
      $ucSession->authorizeUser($_GET['login'], $_GET['password']);
    }else{
      echo '{"error": "false"}';
    }
  }else{
    echo '{"error": "true"}';
  }
?>