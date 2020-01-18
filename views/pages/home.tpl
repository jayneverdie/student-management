<?php $this->layout('layouts/dashboard', ['title' => 'Home']);?>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Logs Login</h3>
    </div>
    <div class="box-body">
      <!-- grid -->
      <div class="table-responsive">
        <table id="grid_user" class="table table-condensed table-striped" style="width:100%">
          <thead>
            <tr>
              <th>Username</th>
              <th>Login Date</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</section>

<?php $this->push('scripts'); ?>
<script>
  jQuery(document).ready(function ($) {
    var grid_user_callback = function() {
      // link
      // END
    };

    loadGrid({
      el: '#grid_user',
      processing: false,
      serverSide: false,
      deferRender: true,
      searching: true,
      order:[],
      modeSelect: "single",
      ajax: "/api/v1/user/AllLogin",
      fnDrawCallback: grid_user_callback,
      columns: [
        { data: "user_login"},
        { data: 'LastestLogin'}
      ]
    });
  });
</script>
<?php $this->end(); ?>
