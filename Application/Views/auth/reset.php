<?php
$view->include('layouts/head');
?>
<div id="signup">
  <form action="/login/reset" method="POST" id="signup">
    <h1 class="form-title">Time to reset your password</h1>
    <input type="hidden" name="confirm_key" value="<?php echo $key; ?>">
    <div class="input-group">
      <input type="password" name="password" id="password" placeholder="password" v-model="password"></input>
    </div>
    <div class="input-group">
      <input type="password" name="confirmpassword" id="confirmpassword" placeholder="confirm password" v-model="confirmpassword"></input>
    </div>
    <div id="errors" v-if="errors != ''">
      <p>{{ errors }}</p>
    </div>
    <div class="input-group">
      <button type="submit" id="submit" @click="validate">Sign Up</button>
    </div>
  </form>
</div>
<script>
var app = new Vue({
  el: "#signup",
  data: {
    password: "",
    confirmpassword: "",
    errors: "<?php echo $_SESSION['errors'] ?? "" ?>",
  },
  methods: {
    validate: function(e) {
      if(this.password.length < 8) {
          this.errors = "Password must be at least 8 characters";
          e.preventDefault();
          return false;
      } else if(this.password !== this.confirmpassword) {
          this.errors = "Password and confirm password must be the same";
          e.preventDefault();
          return false;
      }
      this.errors = "";
      return true;
    }
  }
});
</script>
<?php
$view->include('layouts/foot');
?>
