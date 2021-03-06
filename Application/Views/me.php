<?php
$view->include('layouts/head');
?>
<h1 class="text-4xl">Account Management</h1>
<div id="me">
  <form method="POST" action="/me/update" enctype="multipart/form-data">
    <div class="input-group">
      <label for="DisplayName">Display Name</label>
      <input id="DisplayName" type="text" name="username" value="<?php echo $user->username ?>" <?php echo $user->hasChangedNameRecently() ? "disabled" : "" ?>>
    </div>
    <div class="input-group">
      <label for="About">About</label>
      <textarea id="About" name="about" v-model="about" id="about"><?php echo $user->about ?></textarea>
    </div>
    <div class="input-group">
      <input type="checkbox" name="email_visible" v-model="email_visible" value="<?php echo $user->email_visible ? "on" : "" ?>">
      <label for="">Display Email on Profile</label>
    </div>
    <div class="input-group">
      <p>Current avatar:</p>
      <img class="w-64 object-contain" src="/<?php echo $auth->user()->avatar_path != "" ? $auth->user()->avatar_path : "noava.jpg" ?>">
    </div>
    <div class="input-group">
      <label for="Avatar">Avatar</label>
      <input type="file" name="avatar" id="Avatar">
    </div>
    <div class="input-group">
      <button type="submit">Save</button>
    </div>
   
  </form>
  <form method="POST" action="/me/update-account" enctype="multipart/form-data">
    <div class="input-group">
      <label for="NewPassword">Change Password</label>
      <input id="NewPassword" type="password" name="newpassword" value="" placeholder="new password">
      <input id="ConfirmPassword" type="password" name="confirmpassword" value="" placeholder="confirm new password">
    </div>
    <div class="input-group">
      <label for="Email">Email</label>
      <input id="Email" type="text" name="email" value="<?php echo $user->email ?>" placeholder="email">
    </div>
    <div class="input-group">
      <label for="Delete">Delete Account</label>
      <input id="Delete" type="text" name="delete" value="" placeholder="input your username to confirm">
    </div>
    <div class="input-group">
      <label for="Password">Current Password</label>
      <input id="Password" type="password" name="password" value="" placeholder="current password">
    </div>
    <div class="input-group">
      <button type="submit">Save</button>
    </div>
  </form>
</div>

<script src="/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

<script>tinymce.init({
  selector:'#about',
  menubar: false,
});
</script>
<?php
$view->include('layouts/foot');
?>
