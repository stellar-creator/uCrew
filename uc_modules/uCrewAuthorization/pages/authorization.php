<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Авторизация | uCrew</title>
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
         }
         .btn {        
            font-size: 15px;
            font-weight: bold;
         }
      </style>
   </head>
   <body>
      <div class="login-form row h-100">
         <form action="/?handler=authorization" method="post" class="col-sm-12 my-auto">
            <h2 class="text-center">Авторизация</h2>
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
               <label class="float-left form-check-label"><input type="checkbox" name="keep"> Запонить меня</label>
               <a href="/?page=PasswordRecover" class="float-right">Забыли пароль?</a>
            </div>
            <div class="clearfix" style="padding-top: 10px">
                <p class="text-right"><a href="/?page=CreateAccount">Создать аккаунт</a></p>
            </div>
         </form>
      </div>
   </body>
</html>