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
                  <div class="flex flex-col justify-center items-center">
                    <img src="/noava.jpg">
                    <p><?php echo $post->user()->username ?></p>
                  </div>
                </a>
                <div class="flex flex-col justify-center items-center">
                  <p><?php echo $post->user()->logged_in ? 'Online' : 'Offline' ?></p>
                </div>
                <div class="flex flex-col justify-center items-start">
                  <p><?php echo $post->user()->role_string ?></p>
                </div>
                <div class="flex flex-col justify-center items-start">
                  <p><?php echo $post->user()->post_count ?> posts</p>
                </div>
                <div class="flex flex-col justify-center items-start">
                  <p><?php echo $post->user()->last_login ?></p>
                </div>
                <div class="flex flex-col justify-center items-start">
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
              <div class="forum-post-favorite">
                  0 favorites
              </div>
              <div class="forum-post-actions">
                 <?php if(!$thread->lock_moderator && $auth->isLoggedIn()) { ?>
                 <?php if($post->user_id == $auth->user()->id) { ?>
                 <a class="cursor-pointer" @click="reply(<?php echo $post->id ?>)">Reply</a>
                 <a class="cursor-pointer" @click="edit(<?php echo $post->id ?>)">Edit</a>
                 <a class="cursor-pointer" @click="delete_post(<?php echo $post->id ?>)">Delete</a>
                 <?php } else { ?>
                 <a class="cursor-pointer" @click="favorite(<?php echo $post->id ?>)">Favorite</a>
                 <a class="cursor-pointer" @click="reply(<?php echo $post->id ?>)">Reply</a>
                 <a class="cursor-pointer" @click="report(<?php echo $post->id ?>)">Report Abuse</a>
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
  },  
});
</script>
