<html>
  <head>
    <link href="/css/metaforums.css" rel="stylesheet">
    <script src="/js/vue.js"></script>
    <script src="/js/jquery.min.js"></script>
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
        <?php if($auth->isModerator()) { ?>
        <a href="/moderation" class="header-link">User Management</a>
        <?php } ?>
        <?php if($auth->isLoggedIn()) { ?>
        <a href="/logout" class="header-link">Logout</a>
        <?php } else {?>
        <a href="/login" class="header-link">Login</a>
        <a href="/signup" class="header-link">Signup</a>
        <?php } ?>
      </div>
    </header>
    <main>
