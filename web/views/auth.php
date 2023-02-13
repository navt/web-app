<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.15.24/dist/css/uikit.min.css" />
    <script defer src="../js/auth.dart.js"></script>
</head>
<body>
  <div class="uk-container">
    <?php include 'views/navbar.php';?>
    <form id="lform">
      <div class="uk-margin-top"></div>
      <div class="uk-margin">
        <input name="login" class="uk-input uk-width-1-2" type="text" placeholder="Login" aria-label="login">
      </div>
      <div class="uk-margin">
        <input name="password" class="uk-input uk-width-1-2" type="text" placeholder="Password" aria-label="password">
      </div>
      <div class="uk-margin">
        <label>
          <button id="btn" class="uk-button uk-button-default">Enter</button>
        </label>
      </div>
    </form>

    <div id="msg" class="uk-alert-warning uk-width-1-2" uk-alert></div>

  </div>
</body>
</html>