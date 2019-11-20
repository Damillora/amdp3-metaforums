<?php
$view->include('layouts/head');
?>
<h1 class="text-4xl py-4">Welcome to metaforums!</h1>
<div id="forumbrowser">
  <div class="forumbrowser-left">
    <div :id="'group-'+group.id":class="'forumbrowser-item'+(current_group == group.id ? ' selected' : '')" v-for="(group, key) in groups" @click="change_group(group.id)">
            {{ group.group_name }}
    </div>
  </div>
  <div class="forumbrowser-left">
    <div :id="'category-'+category.id":class="'forumbrowser-item'+(current_category == category.id ? ' selected' : '')" v-for="(category, key) in categories" @click="change_category(category.id)">
            {{ category.category_name }}
    </div>
  </div>
  <div class="forumbrowser-right-table mx-4">
    <div v-if="current_category > 0" id="thread-create" class="forumbrowser-right" @click="new_thread(current_category)">
      <div class="forumbrowser-right-col"></div>
      <div class="forumbrowser-right-col flex-grow">Create New Thread</div>
      <div class="forumbrowser-right-col"></div>
      <div class="forumbrowser-right-col"></div>
      <div class="forumbrowser-right-col"></div>
    </div>
    <div :id="'thread-'+thread.id":class="'forumbrowser-right'+(current_thread == thread.id ? ' selected' : '')" v-for="(thread, key) in threads" @click="change_thread(thread.id)">
      <div class="forumbrowser-right-col">
        <p v-if="thread.is_hot">[HOT]</p>
      </div>
      <div class="forumbrowser-right-col flex-grow">
        {{ thread.title }}
      </div>
      <div class="forumbrowser-right-col">
        by {{ thread.author_model.username }}
      </div>
      <div class="forumbrowser-right-col">
        View: {{ thread.view_count }} Post count: {{ thread.post_count }}
      </div>
      <div class="forumbrowser-right-col">
        {{ thread.last_reply }}
      </div>
    </div>
  </div>
</div>
<div id="threadreader">
</div>
<div id="editor">
</div>
<script>
var selectapp = new Vue({
    el: "#forumbrowser",
    data: {
      groups: <?php echo json_encode($groups); ?>,
      current_group: 0,
      current_category: 0,
      current_thread: 0,
      current_group: <?php echo isset($group) ? $group->id : 0; ?>,
      current_category: <?php echo isset($category) ? $category->id : 0;?>,
      current_thread: <?php echo isset($thread) ? $thread->id : 0; ?>,
      categories: [],
      threads: [],
    },
    methods: {
      change_category(id) {
        this.current_category = id;
        this.threads = [];
        this.current_thread = 0;
        $("#threadreader").html("");
        $("#editor").html("");
        window.history.pushState('category-'+id,'','<?php echo $root; ?>/?category='+id+window.location.hash);
        $.ajax("<?php echo $root; ?>/api/get_threads?id="+id)
          .done(function(data) { 
            this.threads = data;
          }.bind(this));
      },
      change_group(id) {
        this.current_group = id; 
        this.categories = [];
        this.current_category = 0;
        this.threads = [];
        this.current_thread = 0;
        $("#threadreader").html("");
        $("#editor").html("");
        window.history.pushState('group-'+id,'','<?php echo $root; ?>/?group='+id+window.location.hash);
        $.ajax("<?php echo $root; ?>/api/get_categories?id="+id)
          .done(function(data) {
            this.categories = data;
          }.bind(this));
      },
      change_thread(id) {
        if(this.current_thread != 0 && this.current_thread != id) {
          window.location.hash = "#";
        } else if(this.current_thread == id) return;
        this.current_thread = id;
        $("#editor").html("");
        window.history.pushState('thread-'+id,'','<?php echo $root; ?>/?thread='+id+window.location.hash);
        $.ajax("<?php echo $root; ?>/thread?id="+id)
          .done(function(data) {
            $("#threadreader").html(data);
            window.location.hash = window.location.hash;
          }.bind(this));
      },
      new_thread(id) {
        this.current_thread = 0;
        $.ajax("<?php echo $root; ?>/thread/editor?category="+id)
          .done(function(data) {
            $("#threadreader").html("");
            $("#editor").html(data);
          }.bind(this));
      },
    },
    mounted: function() {
      <?php
      if(isset($group)) {
        echo "this.change_group(".$group->id.")\n";
      }
      if(isset($category)) {
        echo "this.change_category(".$category->id.")\n";
      }
      if(isset($thread)) {
        echo "this.change_thread(".$thread->id.")\n";
      }
      ?>
    }
});

</script>
<?php
$view->include('layouts/foot');
?>
