<?php $this->layout('layouts/dashboard', ['title' => 'Home']);?>

<!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3><b id="TotalStudent"></b> <i class="fas fa-user-graduate"></i></h3>

              <p>นักเรียน</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3><b id="TotalParent"></b> <i class="fas fa-user-shield"></i></h3>

              <p>ผู้ปกครอง</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><b id="TotalTeacher"></b> <i class="fas fa-chalkboard-teacher"></i></h3>

              <p>คูณครู</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3><b id="TotalUser"></b> <i class="fas fa-user-cog"></i></h3>

              <p>ผู้ใช้งาน</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
    </div>

    <div class="container-fluid">
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

    call_ajax("post", "/api/v1/user/CountUserAll").done(function(data) {
      // console.log(data[0].TotalStudent);
      // console.log(data[0].TotalParent);
      // console.log(data[0].TotalTeacher);
      // console.log(data[0].TotalUser);
      $('#TotalStudent').text(data[0].TotalStudent);
      $('#TotalParent').text(data[0].TotalParent);
      $('#TotalTeacher').text(data[0].TotalTeacher);
      $('#TotalUser').text(data[0].TotalUser);
    });

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
