<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Авторизация / uCrew</title>
      <link rel="stylesheet" href="uc_resources/distribution/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="uc_resources/distribution/fontawesome/css/fontawesome.min.css">
      <script src="uc_resources/distribution/jquery/jquery.min.js"></script>
      <script src="uc_resources/distribution/popper/popper.js"></script>
      <script src="uc_resources/distribution/bootstrap/js/bootstrap.min.js"></script>
      <style>
         .login-form {
            width: 340px;
            margin: 0px auto;
            font-size: 15px;
         }
         .login-form form {
            margin-bottom: 15px;
            background: #f7f7f7;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding-top: 30px;
            padding-bottom: 10px;
            padding-left: 30px;
            padding-right: 30px;
         }
         .login-form h2 {
            margin: 0 0 15px;
         }
         .form-control, .btn {
            min-height: 38px;
            border-radius: 2px;
            margin-top: 10px;
         }
         .btn {        
            font-size: 15px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
         }
      </style>
   </head>
   <body>
      <div class="login-form row h-100">
         <form action="/?handler=authorization" method="post" class="col-sm-12 my-auto">
            <h2 class="text-center">Авторизация</h2>
<?php
   // Show wrong message
   if( isset($_SESSION['activation']) ){
      if($_SESSION["activation"] == "wrongpass" or $_SESSION["activation"] == "nouser"){
         echo '            <div class="clearfix" style="padding-top: 5px">
                <p class="text-center" style="color:red">Неверный пользователь или пароль</p>
            </div>' . "\n";
      }
      if($_SESSION["activation"] == "register"){
         echo '            <div class="clearfix" style="padding-top: 5px">
                <p class="text-center" style="color:green">Регистрация успешна!<br>Ждите одобрения администратора</p>
            </div>' . "\n";
      }
      if($_SESSION["activation"] == "unregister"){
         echo '            <div class="clearfix" style="padding-top: 5px">
                <p class="text-center" style="color:red">Невозможно зарегистрировать пользователя</p>
            </div>' . "\n";
      }
      if($_SESSION["activation"] == "acceptEmail"){
         echo '            <div class="clearfix" style="padding-top: 5px">
                <p class="text-center" style="color:green">Подтверждение успешно!<br>Ждите одобрения администратора</p>
            </div>' . "\n";
      }
      if($_SESSION["activation"] == "unactive"){
         echo '            <div class="clearfix" style="padding-top: 5px">
                <p class="text-center" style="color:red">Учётная запись не подтверждена</p>
            </div>' . "\n";
      }
      
   }

?>
            <div class="form-group">
               <input type="text" class="form-control" name="user" placeholder="Пользователь / E-mail" required="required">
            </div>
            <div class="form-group">
               <input type="password" class="form-control" name="password" placeholder="Пароль" required="required">
            </div>
            <div class="form-group">
               <button type="submit" class="btn btn-primary btn-block">Войти</button>
            </div>
            <div class="clearfix">
               <label class="float-start form-check-label"><input type="checkbox" name="keep"> Запонить меня</label>
               <a href="/?page=PasswordRecover" class="float-end">Забыли пароль?</a>
            </div>
            <div class="clearfix" style="padding-top: 10px">
                <p class="float-end"><a href="/?page=uCrewAuthorization/registration">Создать аккаунт</a></p>
            </div>
         </form>
      </div>
   </body>
</html>

<?php
   // Clear activation
   $_SESSION['activation'] = "no";
?>