<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Default title</title>
    <meta name="description" content="Default description">
    <meta name="keywords" content="Default keyword">
    <link rel="stylesheet" href="../uikit.min.css">
    <script defer src="../js/add.dart.js"></script>
</head>

<body>
  <div class="uk-container">
    <?php include 'views/navbar.php';?>
    <h1>Создать пост</h1>
    <div id="msg" class="uk-alert-warning uk-width-1-2" uk-alert></div>
    <form id="post-elements">
      <div class="uk-margin">
        <input name="title" class="uk-input" type="text" placeholder="Title" aria-label="Title">
      </div>
      <div class="uk-margin">
        <input name="description" class="uk-input" type="text" placeholder="Description" aria-label="Description">
      </div>
      <div class="uk-margin">
        <input name="keywords" class="uk-input" type="text" placeholder="Keywords" aria-label="Keywords">
      </div>
      <div class="uk-margin">
        <textarea name="content" class="uk-textarea" rows="5" placeholder="Content" aria-label="Content"></textarea>
      </div>
      <div class="uk-margin">
        <label><input name="publish" class="uk-checkbox" type="checkbox"> Publish</label>
      </div>
      <div class="uk-margin">
        <label>
          <button id="btn" class="uk-button uk-button-default">Создать пост</button>
        </label>
      </div>
    </form>
    <div id="cntnt"></div>
  </div>  
</body>
</html>