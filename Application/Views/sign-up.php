<?php
include 'layouts/head.php';
?>
<div id="signup">
  <form action="/signup" method="POST" id="signup">
    <h1 class="form-title">Sign up</h1>
    <div class="input-group">
      <input type="text" name="email" id="email" placeholder="email" v-model="email"></input>
    </div>
    <div class="input-group">
      <input type="text" name="username" id="username" placeholder="username" v-model="username"></input>
    </div>
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
    email: "",
    username: "",
    password: "",
    confirmpassword: "",
    errors: "<?php echo $_SESSION['errors'] ?? "" ?>",
  },
  methods: {
    validate: function(e) {
      // https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
      var emailre = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      var emailvalid = emailre.test(String(this.email).toLowerCase()) ;

      if(this.email == "") {
          this.errors = "Email must not be empty";
          e.preventDefault();
          return false;
      } else if(!emailvalid) {
          this.errors = "Email is invalid";
          e.preventDefault();
          return false;
      } else if(this.username == "") {
          this.errors = "Username must not be empty";
          e.preventDefault();
          return false;
      } else if(this.username.length < 6 || this.username.length > 20) {
          this.errors = "Username must be between 6 and 20 characters";
          e.preventDefault();
          return false;
      } else if(this.password.length < 8) {
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
include 'layouts/foot.php';
?>
