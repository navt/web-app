<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Default title</title>
    <meta name="description" content="Default description">
    <meta name="keywords" content="Default keyword">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.15.24/dist/css/uikit.min.css" />
    <script defer src="../js/all.dart.js"></script>
</head>

<body>
  <div class="uk-container">
    <?php include 'views/navbar.php';?>
    <h1></h1>
    <div id="cntnt"></div>
    <ul id="posts-list"></ul> 
    <div id="uri" style="display:none;"><?php
      $out = isset($from) ? "posts/$from-$to" : "posts/0";
      echo $out; 
      ?></div>
  </div>  
</body>
</html>
