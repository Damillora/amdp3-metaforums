<?php
$view->include('layouts/head');
?>
<div id="forumbrowser">
  <div class="flex flex-col w-1/6">
    <div :id="'group-'+group.id":class="'forumbrowser-group'+(current_group == group.id ? ' selected' : '')" v-for="(group, key) in groups" @click="current_group = group.id;">
            {{ group.group_name }}
    </div>
  </div>
  <div class="flex flex-col w-1/6">
    <div :id="'category-'+category.id":class="'forumbrowser-category'+(current_category == category.id ? ' selected' : '')" v-for="(category, key) in categories" @click="current_category = category.id;">
            {{ category.category_name }}
    </div>
  </div>
  <div class="forumbrowser-thread-table w-4/6">
    <div v-if="current_category > 0" id="thread-create" class="forumbrowser-thread" @click="new_thread(current_category)">
      <div class="forumbrowser-thread-col"></div>
      <div class="forumbrowser-thread-col">Create New Thread</div>
      <div class="forumbrowser-thread-col"></div>
      <div class="forumbrowser-thread-col"></div>
      <div class="forumbrowser-thread-col"></div>
    </div>
    <div :id="'thread-'+thread.id":class="'forumbrowser-thread'+(current_thread == thread.id ? ' selected' : '')" v-for="(thread, key) in threads" @click="current_thread = thread.id;">
      <div class="forumbrowser-thread-col">
        <p v-if="thread.is_hot">[HOT]</p>
      </div>
      <div class="forumbrowser-thread-col">
        {{ thread.title }}
      </div>
      <div class="forumbrowser-thread-col">
        by {{ thread.author_model.username }}
      </div>
      <div class="forumbrowser-thread-col">
        View: {{ thread.view_count }} Post count: {{ thread.post_count }}
      </div>
      <div class="forumbrowser-thread-col">
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
        $.ajax("<?php echo $root; ?>/api/get_threads?id="+id)
          .done(function(data) { 
            this.threads = data;
          }.bind(this));
      },
      change_group(id) {
        $.ajax("<?php echo $root; ?>/api/get_categories?id="+id)
          .done(function(data) {
            this.categories = data;
          }.bind(this));
      },
      change_thread(id) {
        $.ajax("<?php echo $root; ?>/thread?id="+id)
          .done(function(data) {
            $("#threadreader").html(data);
            $("#editor").html("");
          }.bind(this));
      },
      new_thread(id) {
        $.ajax("<?php echo $root; ?>/thread/editor?category="+id)
          .done(function(data) {
            $("#threadreader").html("");
            $("#editor").html(data);
          }.bind(this));
      },
    },
  updated: function() {
    if(this.current_thread > 0) {
      this.change_thread(this.current_thread);
    }
    if(this.current_category > 0) {
      this.change_category(this.current_category);
    }
    if(this.current_group > 0) {
      this.change_group(this.current_group);
    }
  },
});
</script>
<?php
$view->include('layouts/foot');
?>
