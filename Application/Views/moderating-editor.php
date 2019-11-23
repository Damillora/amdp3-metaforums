<div id="editor-comp">
      <form method="POST" action="/thread/moderate" id="editor-poster">
        <div class="forum-post" id="forum-editor">
            <div class="forum-post-title">
              <p class="text-lg flex-grow">
              <?php echo $title ?>
              </p>
            </div>
            <div class="forum-post-content">
              <div class="forum-post-user">
                <a href="/profile?id=<?php echo $auth->user()->id ?>"> 
                  <div class="forum-post-user-detail items-center">
                    <img src="/<?php echo $auth->user()->avatar_path != "" ? $auth->user()->avatar_path : "noava.jpg" ?>">
                    <p><?php echo $auth->user()->username ?></p>
                  </div>
                </a>
              </div>
              <div class="forum-post-text">
                <input type="hidden" name="post" value="<?php echo $post->id ?? ""?>">
                <input type="hidden" name="thread" value="<?php echo $post->thread()->id ?? ""?>">
                <input type="hidden" name="category" value="<?php echo $post->thread()->category()->id ?? ""?>">
                <textarea id="moderating-editor-text" class="w-full h-full" name="content"><?php echo isset($edit) ? $edit->post : "" ;  ?></textarea>
              </div>
            </div>
            <div class="forum-post-footer">
              <div class="forum-post-footer-left w-1/6">
                 <a class="forum-post-footer-action" @click="cancel()">Cancel</a>
              </div>
              <div class="forum-post-footer-mid">
                <select name="duration">
                  <option value="+ 1 hour">1 hours</option>
                  <option value="+ 6 hour">6 hours</option>
                  <option value="+ 24 hour">24 hours</option>
                  <option value="+ 72 hour">3 days</option>
                  <option value="+ 7 day">1 week</option>
                  <option value="+ 30 day">1 month</option>
                  <option value="2099-12-31 23:59">indefinitely</option>
                </select>
                <select name="action">
                  <?php if($auth->user()->didIModerateThis($post->thread()->category()->id) && $auth->user()->id != $post->user_id) { ?>
                  <option value="pardon">Pardon</option>
                  <option value="silence">Silence</option>
                  <option value="ban">Ban</option>
                  <?php } ?>
                  <?php if(!$post->thread()->isLocked()) { ?>
                  <option value="lock">Lock</option> 
                  <?php } ?>
                </select>
              </div>
              <div class="forum-post-footer-right">
                 <a class="forum-post-footer-action" @click="post()">Confirm</a>
              </div>
            </div>
        </div>
      </form>
</div>
<script>
var editorapp = new Vue({
    el: "#editor-comp",
    methods: {
      cancel() {
        var sure = confirm("Are you sure?");
        if(sure) {
          $("#editor").html("");
        }
      },
      post() {
        $("#editor-poster").submit();
      }
    }
});
</script>
