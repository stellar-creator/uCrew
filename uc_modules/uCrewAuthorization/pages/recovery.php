<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Восстановление пароля / uCrew</title>
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
         <form action="/?handler=recovery" method="post" class="col-sm-12 my-auto">
            <h4 class="text-center">Восстановление пароля</h4>
            <div class="form-group">
               <input type="text" class="form-control" name="user" placeholder="Ф.И.О. / E-mail / Телефон" required="required">
            </div>
            <div class="form-group">
               <button type="submit" class="btn btn-primary btn-block">Восстановить</button>
            </div>
            <div class="clearfix">
               <a href="/?page=uCrewAuthorization/registration" class="float-start">Регистрация</a>
               <a href="/" class="float-end">Авторизация</a>
            </div>
         </form>
      </div>
   </body>
</html>
