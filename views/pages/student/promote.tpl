<?php $this->layout('layouts/dashboard', ['title' => 'Promote']);?>

<style type="text/css">
  input[type=checkbox] {
    width: 20px;
    height: 20px;
  }
</style>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">เลื่อนระดับชั้น </h3>
      <span style="font-size: 2em;">
        <i class="fas fa-sort-amount-up"></i>
      </span>
    </div>
    <div class="box-body">

      <!-- <form id="form_move" onsubmit="return submit_move()">  -->
      
      <div class="form-group col-md-8">
        <div class="form-group col-md-4">
          <label>สถานะเดิม</label>
        </div>
        <div class="form-group col-md-4">
          <label>สถานะใหม่</label>
        </div>
      </div>
      <div class="form-group col-md-8">
        <div class="form-group col-md-4">
          <label for="classroom_before">ห้องเรียนเดิม</label>
          <select name="classroom_before" id="classroom_before" class="form-control" required>
            <option value="">--เลือก--</option>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="classroom_after">ห้องเรียนใหม่</label>
          <select name="classroom_after" id="classroom_after" class="form-control" required>
            <option value="">--เลือก--</option>
          </select>
        </div>
      </div>

      <div class="form-row col-md-12">
        <div class="modal-footer"></div>
      </div>

      <div class="form-group col-md-8">
        <button class="btn btn-warning btn-sm" id="btn_checkall"><i class="fas fa-angle-double-right"></i> Select All</button>
        <button class="btn btn-warning btn-sm" id="btn_uncheckall"><i class="fas fa-angle-double-left"></i> UnSelect All</button>
        <button class="btn btn-success btn-sm" id="btn_viewtemp"><i class="far fa-list-alt"></i> ViewTemp</button>
      </div>

      <div class="form-group col-md-12">
        <table id="grid_student" class="table table-condensed table-striped" style="width:100%">
          <thead>
            <tr>
              <th>ลำดับ</th>
              <th>เลือก</th>
              <th>รหัสนักเรียน</th>
              <th>ชื่อ-นาสกุล</th>
              <th>ห้องเรียนเดิม</th>
              <th>ห้องเรียนใหม่</th>
            </tr>
          </thead>
        </table>
      </div>
      <div class="form-group col-md-12">
        <button id="submit_move" class="btn btn-primary">บันทึก</button>
      </div>
      <!-- </form> -->

    </div>
  </div>
</section>

<!-- modal view -->
<div class="modal" id="modal_view" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" aria-label="Close">
          <span class="glyphicon glyphicon-remove"></span>
        </button>
        <h3 class="modal-title">ยืนยันการเลื่อนระดับชั้น</h3>
      </div>
      <div class="modal-body">
        <!-- Content -->
        <form id="form_confirm" onsubmit="return submit_confirm()"> 
          <div class="form-row col-md-12">
            <table id="grid_temp" class="table table-condensed table-striped" style="width:100%">
              <thead>
                <tr>
                  <th>ห้องเรียนเดิม</th>
                  <th>ห้องเรียนเใหม่</th>
                </tr>
              </thead>
            </table>
          </div>
          <button type="submit" class="btn btn-success">ยืนยัน</button>
        </form>

      </div>
    </div>
  </div>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function($) {
    $('#btn_checkall').hide();
    $('#btn_uncheckall').hide();

    call_ajax("post", "/api/v1/student/load/classroom").done(function(data) {
      $('#classroom_before').html("<option value=''>- เลือก -</option>");
      $.each(data, function(i, v) {
        $("#classroom_before").append(
          "<option value='" + v.id + "'>" + v.classroom + "</option>"
        );
      });
    });

    call_ajax("post", "/api/v1/student/load/classroom").done(function(data) {
      $('#classroom_after').html("<option value=''>- เลือก -</option>");
      $.each(data, function(i, v) {
        $("#classroom_after").append(
          "<option value='" + v.id + "'>" + v.classroom + "</option>"
        );
      });
    });

    $('#classroom_after').on('change', function() {
      load_student();
    });
    
    $('#btn_checkall').on('click', function() {
      $("input:checkbox").attr('checked', true);
    });

    $('#btn_uncheckall').on('click', function() {
      $("input:checkbox").attr('checked', false);
    }); 

    $('#btn_viewtemp').on('click', function() {
      $('#modal_view').modal({backdrop: 'static'}); 
      loadGrid({
        el: '#grid_temp',
        processing: true,
        serverSide: true,
        deferRender: true,
        searching: true,
        order: [],
        orderCellsTop: true,
        destroy: true,
        lengthChange: false,
        modeSelect: "single",
        ajax: {
          url: '/api/v1/student/all/promote/temp',
          method: 'post'
        },
        columns: [
          { data: 'classroom'},
          { data: 'classroom_after'}
        ]
      });
    });

    $('#submit_move').on('click', function() {
      var c_af = $('#classroom_after').val();
      var c_bf = $('#classroom_before').val();

      var vals = [];
      $(':checkbox:checked').each(function(i,v){
        vals[i] = $(this).val();
      });

      if (vals.length===0) {
        alert("Please select student!");
        return  false;
      }

      // console.log(vals);
      // console.log(c_af);
      // console.log(c_bf);

      $.ajax({
          url: '/api/v1/student/move',
          type : 'post',
          cache : false,
          dataType : 'json',
          data : {
            student_id : vals,
            classroom_before : c_bf,
            classroom_after : c_af
          }
      })
      .done(function(data) {
        if ( data.result === true ) {
          alert(data.message);
          reloadGrid('#grid_student');
        } else {
          alert(data.message);
        }
      });

      return false;
    });

  });

    function load_student(){
      $('#btn_checkall').show();
      $('#btn_uncheckall').show();
      var c_af = $('#classroom_after').val();
      var c_bf = $('#classroom_before').val();
      loadGrid({
        el: '#grid_student',
        processing: true,
        serverSide: true,
        deferRender: true,
        searching: true,
        order: [],
        orderCellsTop: true,
        destroy: true,
        modeSelect: "single",
        ajax: {
          url: '/api/v1/student/all/promote?c_af='+c_af+'&c_bf='+c_bf,
          method: 'post'
        },
        columns: [
          { data: 'rowid'},
          { data: 'id'},
          { data: 'student_id'},
          { data: 'fullname'},
          { data: 'classroom'},
          { data: 'classroom_after'}
        ],
        columnDefs: [
          {
            render: function(data, type, row) {
                return '<input type="checkbox" value="'+row.id+'"></input>';
            }, targets: 1,width: "5%"
          }
        ]
      });
    }

    function submit_confirm() {
      $.ajax({
          url: '/api/v1/student/move/confirm',
          type : 'post',
          cache : false,
          dataType : 'json'
      })
      .done(function(data) {
        if ( data.result === true ) {
          alert(data.message);
          $('#modal_view').modal('hide');
          reloadGrid('#grid_temp');
        } else {
          alert(data.message);
        }
      });
      return false;
    }
</script>
<?php $this->end() ?>