<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../uikit.min.css">
    <!-- <script defer src="../js/auth.dart.js"></script> -->
</head>
<body>
  <div class="uk-container">
    <form method="post" action="/api/auth">
      <div class="uk-margin-top"></div>
      <div class="uk-margin">
        <input name="login" class="uk-input uk-width-1-2" type="text" placeholder="Логин" aria-label="login">
      </div>
      <div class="uk-margin">
        <input name="password" class="uk-input uk-width-1-2" type="text" placeholder="Пароль" aria-label="password">
      </div>
      <div class="uk-margin">
        <label>
          <button class="uk-button uk-button-default">Войти</button>
        </label>
      </div>
    </form>
  </div>
</body>
</html>