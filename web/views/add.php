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
    <!--<script defer src="../js/one.dart.js"></script>-->
</head>

<body>
  <div class="uk-container">
    <h1>Создать пост</h1>
    <form method="post" action="/api/add">
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
          <button class="uk-button uk-button-default">Создать пост</button>
        </label>
      </div>
    </form>
    <div id="cntnt"></div>
  </div>  
</body>
</html>