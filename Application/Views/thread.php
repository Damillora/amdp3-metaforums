<?php if($thread->isLocked()) { ?>
<div class="lock-message">
This thread has been locked for <?php echo $thread->lock()->duration ?>. See the last post for reason why.
<?php if($auth->user()->didIModerateThis($thread->category_id) ) { ?>
<a href="/thread/unlock?id=<?php echo $thread->id ?>">Unlock this thread</a>
<?php } ?>
</div>
<?php } ?>
<div id="forum">
    <h1 class="text-2xl">Thread in: <?php echo $thread->category()->category_name ?></h1>
    <p class="text-4xl"><?php echo $thread->title ?></p>
    <p>Posted on <?php echo $thread->created_at ?> by <?php echo $thread->author_model->username ?></p>
    <p><?php echo $thread->elapsed_created ?></p>
    <div id="forum-posts">
    <?php foreach($thread->posts() as $post) { ?>
        <div class="forum-post" id="forum-post-<?php echo $post->id ?>">
            <div class="forum-post-title">
              <p class="text-lg flex-grow"><?php echo $post->title ?></p>
              <p class="text-lg"><?php echo $post->elapsed_created; ?></p>
            </div>
            <div class="forum-post-content">
              <div class="forum-post-user">
                <a href="/profile?id=<?php echo $post->user()->id ?>"> 
                  <div class="forum-post-user-detail items-center">
                    <img src="/<?php echo $post->user()->avatar_path != "" ? $post->user()->avatar_path : "noava.jpg" ?>">
                    <p><?php echo $post->user()->username ?></p>
                  </div>
                </a>
                <div class="forum-post-user-detail items-center">
                  <p><?php echo $post->user()->status ?></p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <p><?php echo $post->user()->role_string ?></p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <p><?php echo $post->user()->post_count ?> posts</p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <p><?php echo $post->user()->elapsed_login ?></p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <?php if($post->user()->isBanned($thread->category()->id)) { ?>
                  <p>Banned</p>
                  <?php } else if($post->user()->isSilenced($thread->category()->id)) { ?>
                  <p>Silenced</p>
                  <?php } else {?>
                  <p>Active</p>
                  <?php } ?>
                </div>
              </div>
              <div class="forum-post-text">
                  <?php echo $post->post ?>
              </div>
            </div>
            <div class="forum-post-footer">
              <div class="forum-post-footer-left">
                  <?php echo $post->favorites; ?> favorites
              </div>
              <div class="forum-post-footer-mid">
              </div>
              <div class="forum-post-footer-right">
                 <?php if($auth->isLoggedIn()) { ?>
                   <?php if($post->user_id == $auth->user()->id) { ?>
                     <a class="forum-post-footer-action" @click="reply(<?php echo $post->id ?>)">Reply</a>
                     <a class="forum-post-footer-action" @click="edit(<?php echo $post->id ?>)">Edit</a>
                     <a class="forum-post-footer-action" @click="delete_post(<?php echo $post->id ?>)">Delete</a>
                   <?php } else { ?>
                     <a class="forum-post-footer-action" @click="favorite(<?php echo $post->id ?>)">Favorite</a>
                     <a class="forum-post-footer-action" @click="reply(<?php echo $post->id ?>)">Reply</a>
                     <?php if(!$auth->user()->didIModerateThis($thread->category()->id) && $auth->user()->is_confirmed ) { ?>
                      <a class="forum-post-footer-action" @click="report(<?php echo $post->id ?>)">Report Abuse</a>
                     <?php } ?>
                   <?php } ?>
                   <?php if($auth->user()->didIModerateThis($thread->category()->id) ) { ?>
                     <a class="forum-post-footer-action" @click="moderate(<?php echo $post->id ?>)">Moderate</a>
                   <?php } ?> 
                 <?php } ?>
              </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>
<script>
var threadapp = new Vue({
  el: "#forum",
  data: {
    favorite_num: [],
  },
  methods: {
    reply(post_id) {
      $.ajax("<?php echo $root ?>/thread/editor?thread=<?php echo $thread->id ?>&reply="+post_id).done(function(data) {
        $("#editor").html(data);
      });
    },
    edit(post_id) {
      $.ajax("<?php echo $root ?>/thread/editor?thread=<?php echo $thread->id ?>&edit="+post_id).done(function(data) {
        $("#editor").html(data);
      });
    },
    delete_post(post_id) {
      $.ajax("<?php echo $root ?>/thread/editor?thread=<?php echo $thread->id ?>&delete="+post_id).done(function(data) {
        $("#editor").html(data);
      });
    },
    favorite(post_id) {
      $.ajax("<?php echo $root ?>/api/favorite?id="+post_id).done(function(data) {
        console.log(data);
        location.reload();
      }.bind(this));
    },
    report(post_id) {
      $.ajax("<?php echo $root ?>/thread/editor?thread=<?php echo $thread->id ?>&report="+post_id).done(function(data) {
        $("#editor").html(data);
      });
    },
    moderate(post_id) {
      $.ajax("<?php echo $root ?>/thread/moderating_editor?id="+post_id).done(function(data) {
        $("#editor").html(data);
      });
    }
  },  
});
</script>
