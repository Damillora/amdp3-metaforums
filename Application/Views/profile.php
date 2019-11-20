<?php
$view->include('layouts/head');
?>
<div id="profile">
<div class="forum-post" id="forum-post-<?php echo $post->id ?>">
            <div class="forum-post-title">
              <p class="text-lg flex-grow"><?php echo $user->username ?>'s profile</p>
              <?php if ($auth->isLoggedIn() && $auth->user()->id == $user->id) { ?>
              <p class="text-lg px-2"><a href="/me">Edit</a></p>
              <?php } ?>
            </div>
            <div class="forum-post-content">
              <div class="forum-post-user">
                <a href="/profile?id=<?php echo $user->id ?>"> 
                  <div class="forum-post-user-detail items-center">
                    <img src="/<?php echo $user->avatar_path != "" ? $user->avatar_path : "noava.jpg" ?>">
                    <p><?php echo $user->username ?></p>
                  </div>
                </a>
                <div class="forum-post-user-detail items-center">
                  <p><?php echo $user->status ?></p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <p><?php echo $user->role_string ?></p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <p><?php echo $user->post_count ?> posts</p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <p><?php echo $user->elapsed_login ?></p>
                </div>
                <div class="forum-post-user-detail items-start">
                  <p>Active</p>
                </div>
              </div>
              <div class="forum-post-profile">
                <h1 class="text-4xl">About me</h1>
                <p class="forum-post-profile-about"><?php echo $user->about ?></p>
                <div class="forum-post-profile-detail">
                  <div class="w-1/3">
                    <h2 class="text-xl">Additional information</h2>
                    <table class="w-full">
                      <tr>
                        <td>Username</td>
                        <td><?php echo $user->username ?></td>
                      </tr>
                      <tr>
                        <td>Email</td>
                        <td><?php echo $user->email_visible ? $user->email : "hidden" ?></td>
                      </tr>
                      <tr>
                        <td>Most Active In</td>
                        <td><?php echo $user->most_active()->category_name ?> (<?php echo $user->most_active()->group()->group_name ?>)</td>
                      </tr>
                      <tr>
                        <td>Number of Hearts</td>
                        <td><?php echo $user->hearts ?></td>
                      </tr>
                    </table>
                  </div>
                  <div class="w-2/3">
                    <p>Recent posts</p>
                    <table class="w-full">
                    <?php foreach($user->recent_posts(5) as $post) { ?> 
                      <tr class="h-12">
                        <td><a href="/?thread=<?php echo $post->thread_id ?>#forum-post-<?php echo $post->id ?>"><?php echo $post->title ?></a></td>
                        <td>by <?php echo $post->thread()->author_model->username ?></td>
                        <td><?php echo $post->elapsed_created ?></td>
                      </tr>
                    <?php } ?>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
</div>

<?php
$view->include('layouts/foot');
?>
