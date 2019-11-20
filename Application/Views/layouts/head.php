<!DOCTYPE html>
<html>
  <head>
    <script src="<?php echo $root ?>/js/vue.js"></script>
    <script src="<?php echo $root ?>/js/jquery.min.js"></script>
    <title>Metaforums</title>
    <link href="<?php echo $root ?>/css/metaforums.css" rel="stylesheet">
  </head>
  <body>
    <header>
      <div class="header-left">
        <a href="<?php echo $root ?>/" class="header-link">Metaforums</a>
      </div>
      <div class="header-middle">
      </div>
      <div class="header-left">
        <?php if($auth->isLoggedIn()) { ?>
          <?php if($auth->user()->is_moderator) { ?>
            <a href="<?php echo $root ?>/moderation" class="header-link">User Management</a>
          <?php } ?>
        <a href="<?php echo $root ?>/logout" class="header-link">Logout</a>
        <?php } else {?>
        <a href="<?php echo $root ?>/login" class="header-link">Login</a>
        <a href="<?php echo $root ?>/signup" class="header-link">Signup</a>
        <?php } ?>
      </div>
    </header>
    <main>
