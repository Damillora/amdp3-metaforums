<html>
  <head>
    <link href="/css/metaforums.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <title>Metaforums</title>
  </head>
  <body>
    <header>
      <div class="header-left">
        <a href="/" class="header-link">Metaforums</a>
      </div>
      <div class="header-middle">
      </div>
      <div class="header-left">
        <?php if($auth->isLoggedIn()) { ?>
        <a href="/logout" class="header-link">Logout</a>
        <?php } else {?>
        <a href="/login" class="header-link">Login</a>
        <a href="/signup" class="header-link">Signup</a>
        <?php } ?>
      </div>
    </header>
    <main>
