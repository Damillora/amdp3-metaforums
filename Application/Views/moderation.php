<?php
$view->include('layouts/head');
?>
<h1 class="text-2xl">Recent Abuse Reports</h1>
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
  <div class="forumbrowser-right-table">
    <div :id="'report-'+report.id":class="'forumbrowser-right'+(current_report == report.id ? ' selected' : '')" v-for="(report, key) in reports" @click="change_report(report)">
       <div class="forumbrowser-right-col flex-grow">{{ report.post.title }}</div>
       <div class="forumbrowser-right-col">Reported by {{ report.reporter.username }}</div>
       <div class="forumbrowser-right-col">Post by {{ report.reported.username }}</div>
       <div class="forumbrowser-right-col">{{ report.elapsed }}</div>
    </div>
  </div>
</div>
<div id="reportreader">
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
      current_report: 0,
      categories: [],
      reports: [],
    },
    methods: {
      change_category(id) {
        this.current_category = id;
        this.current_report = 0;
        this.reports = [];
        window.history.pushState('category-'+id,'','<?php echo $root; ?>/moderation?category='+id+window.location.hash);
        $.ajax("<?php echo $root; ?>/api/get_reports?id="+id)
          .done(function(data) { 
            this.reports = data;
          }.bind(this));
      },
      change_group(id) {
        this.current_group = id;
        this.categories = [];
        this.current_category = 0;
        this.current_report = 0;
        this.reports = [];
        window.history.pushState('group-'+id,'','<?php echo $root; ?>/moderation?group='+id+window.location.hash);
        $.ajax("<?php echo $root; ?>/api/get_categories?id="+id)
          .done(function(data) {
            this.categories = data;
          }.bind(this));
      },
      change_report(report) {
        this.current_report = report.id;
        window.location.href = "<?php echo $root; ?>/?thread="+report.post.thread_id+"#forum-post-"+report.post.id;
      }
    },
    mounted: function() {
      <?php
      if(isset($group)) {
        echo "this.change_group(".$group->id.")\n";
      }
      echo "this.change_category(".($category->id ?? 0).")\n";
      ?>
    }
});

</script>
<?php
$view->include('layouts/foot');
?>
