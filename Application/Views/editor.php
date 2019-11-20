<?php if (!$auth->isLoggedIn()) { ?>
<p class="">You need to be logged in to post. <a href="/login">Login</a></p>
<?php } else if(!isset($report) && $auth->user()->isBanned($thread_post->category_id ?? $category->id)) {?>
<p class="">You have been banned from this category.</p>
<?php } else if(!isset($report) && $auth->user()->isSilenced($thread_post->category_id ?? $category->id)) {?>
<p class="">You have been silenced from this category. You cannot post new replies or threads at this moment.</p>
<?php } else if(!isset($report) && isset($thread) && $auth->user()->isPardoned($thread->id)) {?>
<p class="">You have been pardoned from this thread. You cannot post new replies at this moment.</p>
<?php } else if(isset($edit) && (time() - strtotime($edit->created_at)) > 300) {?>
<p class="">You cannot edit this post.</p>
<?php } else if(isset($delete) && (time() - strtotime($delete->created_at)) > 300) {?>
<p class="">You cannot delete this post.</p>
<?php } else if(isset($category) && $auth->user()->is_confirmed == 0 ) { ?>
<p class="">Please confirm your email address to create threads</p>
<?php } else if(isset($thread) && $thread->isLocked() ) { ?>
This thread has been locked.
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
                  <div class="forum-post-user-detail items-center">
                    <img src="/<?php echo $auth->user()->avatar_path != "" ? $auth->user()->avatar_path : "noava.jpg" ?>">
                    <p><?php echo $auth->user()->username ?></p>
                  </div>
                </a>
                <div class="forum-post-user-detail items-center">
                  <p><?php echo $auth->user()->status ?></p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <p><?php echo $auth->user()->role_string ?></p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <p><?php echo $auth->user()->post_count ?> posts</p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <p><?php echo $auth->user()->elapsed_login ?></p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <?php if($auth->user()->isBanned($category->id ?? 0)) { ?>
                  <p>Banned</p>
                  <?php } else if($auth->user()->isSilenced($category->id ?? 0)) { ?>
                  <p>Silenced</p>
                  <?php } else {?>
                  <p>Active</p>
                  <?php } ?>
                </div>
              </div>
              <div class="forum-post-text">
                <form method="POST" action="/thread/process" id="editor-poster">
                <input type="hidden" name="category" value="<?php echo $category->id ?? "" ?>">
                <input type="hidden" name="thread" value="<?php echo $thread->id ?? "" ?>">
                <input type="hidden" name="reply" value="<?php echo $reply->id ?? "" ?>">
                <input type="hidden" name="edit" value="<?php echo $edit->id ?? ""?>">
                <input type="hidden" name="delete" value="<?php echo $delete->id ?? ""?>">
                <input type="hidden" name="report" value="<?php echo $report->id ?? ""?>">
                <?php if(isset($category)) { ?>
                <input type="text" name="title" placeholder="title">
                <?php } ?>
                <?php if(!isset($delete)) { ?>
                <textarea id="<?php echo isset($report) ? "report-" : "" ?>editor-text" class="w-full h-full" name="content"><?php echo isset($edit) ? $edit->post : "" ;  ?></textarea>
                <?php } ?>
                </form>
              </div>
            </div>
            <div class="forum-post-footer">
              <div class="forum-post-footer-left">
                 <a class="forum-post-footer-action" @click="cancel()">Cancel</a>
              </div>
              <div class="forum-post-footer-mid">
              </div>
              <div class="forum-post-footer-right">
                 <a class="forum-post-footer-action" @click="post()">Confirm</a>
              </div>
            </div>
        </div>
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
<script src="/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script>tinymce.init({
  selector:'#editor-text',
  menubar: false,
  height: "100%",
  branding: false,
  plugins: "lists",
  toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fontsizeselect",
});
</script>

<?php } ?>
