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
    <script defer src="../js/edit.dart.js"></script>
</head>

<body>
  <div class="uk-container">
    <?php include 'views/navbar.php';?>
    <h1>Редактировать пост</h1>
    <div id="msg" class="uk-alert-warning uk-width-1-2" uk-alert></div>
    <form id="post-elements"> 
      <!-- method="post" action="/api/edit"> -->
      <div class="uk-margin">
        <input name="title" class="uk-input" type="text" placeholder="Title" aria-label="Title" minlength="3">
      </div>
      <div class="uk-margin">
        <input name="description" class="uk-input" type="text" placeholder="Description" aria-label="Description" minlength="3">
      </div>
      <div class="uk-margin">
        <input name="keywords" class="uk-input" type="text" placeholder="Keywords" aria-label="Keywords" minlength="3">
      </div>
      <div class="uk-margin">
        <textarea name="content" class="uk-textarea" rows="5" placeholder="Content" aria-label="Content" minlength="6"></textarea>
      </div>
      <div class="uk-margin">
        <label><input name="publish" class="uk-checkbox" type="checkbox"> Publish</label>
      </div>
      <input name="id" type="hidden" value="<?php echo $id;?>">
      <div class="uk-margin">  
        <label>
          <button id="save-btn" class="uk-button uk-button-default">Сохранить</button>
        </label>
      </div>
    </form>
    <hr>
    <div class="uk-margin">
      <form id="delete-form">
        <input name="id" type="hidden" value="<?php echo $id;?>">  
        <button id="delete-btn" class="uk-button uk-button-default">Удалить пост</button>
    </div>
    
    <div id="cntnt"></div>
    <div id="uri" style="display:none;"><?php echo "posts/$id";?></div>
  </div>  
</body>
</html>