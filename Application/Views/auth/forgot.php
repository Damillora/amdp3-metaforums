<?php
$view->include('layouts/head');
?>
<div id="login">
  <form action="/login/forget" method="POST" id="login">
    <h1 class="form-title">Help, I forgot my password!</h1>
    <div class="input-group">
      <input type="text" name="email" id="email" placeholder="email" v-model="email"></input>
    </div>
    <div class="input-group">
      <p>{{ forget_message }}</p>
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
    email: "",
    password: "",
    forget_message: "<?php echo $session->get('forget_message') ?? "" ?>",
  },
  methods: {
    validate: function(e) {
      this.forget_message = "";
      return true;
    }
  }
});
</script>
<?php
$view->include('layouts/foot');
?>
