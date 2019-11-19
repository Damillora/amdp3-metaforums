<?php if (!$auth->isLoggedIn()) { ?>
<p class="">You need to be logged in to post. <a href="/login">Login</a></p>
<?php } else if($auth->user()->isBanned($category)) {?>
<p class="">You have been banned from this category.</p>
<?php } else if($auth->user()->isSilenced($category)) {?>
<p class="">You have been silenced from this category. You cannot post new replies or threads at this moment.</p>
<?php } else if($auth->user()->isPardoned($thread)) {?>
<p class="">You have been pardoned from this thread. You cannot post new replies at this moment.</p>
<?php } else if(isset($edit_post) && (time() - strtotime($edit_post->created_at)) > 300) {?>
<p class="">You cannot edit this post.</p>
<?php } else { ?>
<div id="editor-comp">
        <div class="forum-post" id="forum-editor">
            <div class="forum-post-title">
              <p class="text-lg flex-grow">
              <?php echo $title ?>
              </p>
            </div>
            <div class="forum-post-content">
              <div class="forum-post-user">
                <a href="/profile?id=<?php echo $auth->user()->id ?>"> 
                  <div class="flex flex-col justify-center items-center">
                    <img src="/noava.jpg">
                    <p><?php echo $auth->user()->username ?></p>
                  </div>
                </a>
                <div class="flex flex-col justify-center items-center">
                  <p><?php echo $auth->user()->logged_in ? 'Online' : 'Offline' ?></p>
                </div>
                <div class="flex flex-col justify-center items-start">
                  <p><?php echo $auth->user()->role_string ?></p>
                </div>
                <div class="flex flex-col justify-center items-start">
                  <p><?php echo $auth->user()->post_count ?> posts</p>
                </div>
                <div class="flex flex-col justify-center items-start">
                  <p><?php echo $auth->user()->last_login ?></p>
                </div>
                <div class="flex flex-col justify-center items-start">
                  <?php if($auth->user()->isBanned($category)) { ?>
                  <p>Banned</p>
                  <?php } else if($auth->user()->isSilenced($category)) { ?>
                  <p>Silenced</p>
                  <?php } else {?>
                  <p>Active</p>
                  <?php } ?>
                </div>
              </div>
              <div class="forum-post-text w-full h-full">
                <form method="POST" action="/thread/process">
                <input type="hidden" name="category" value="<?php echo $category ?>">
                <input type="hidden" name="thread" value="<?php echo $thread ?>">
                <input type="hidden" name="reply" value="<?php echo $reply ?>">
                <input type="hidden" name="edit" value="<?php echo $edit ?>">
                <textarea id="editor-text" class="w-full h-full" name="content"></textarea>
                </form>
              </div>
            </div>
            <div class="forum-post-footer">
              <div class="forum-post-favorite">
                 <a class="cursor-pointer" @click="cancel()">Cancel</a>
              </div>
              <div class="forum-post-actions">
                 <a class="cursor-pointer" @click="post()">Post</a>
              </div>
            </div>
        </div>
</div>
<script>
var editorapp = new Vue({
    el: "#editor-comp",
    methods: {
      cancel() {
        confirm("Are you sure?");
        if(confirm) {
          $("#editor").html("");
        }
      },
      post() {
        
      }
    }
});
</script>
<?php } ?>
