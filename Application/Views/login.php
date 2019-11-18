<?php
include 'layouts/head.php';
?>
<div id="login">
  <form action="/login" method="POST" id="login">
    <h1 class="form-title">Login</h1>
    <div class="input-group">
      <input type="text" name="username" id="username" placeholder="username" v-model="username"></input>
    </div>
    <div class="input-group">
      <input type="password" name="password" id="password" placeholder="password" v-model="password"></input>
    </div>
    <div id="errors" v-if="errors != ''">
      <p>{{ errors }}</p>
    </div>
    <div class="input-group">
      <button type="submit" id="submit" @click="validate">Sign in</button>
    </div>
  </form>
</div>
<script>
var app = new Vue({
  el: "#login",
  data: {
    username: "",
    password: "",
    errors: "<?php echo $_SESSION['errors'] ?? "" ?>",
  },
  methods: {
    validate: function(e) {
      // https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
      var emailre = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      var emailvalid = emailre.test(String(this.email).toLowerCase()) ;

      if(this.username == "") {
          this.errors = "Username must not be empty";
          e.preventDefault();
          return false;
      } else if(this.password == "") {
          this.errors = "Password must not be empty";
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
