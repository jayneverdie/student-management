<?php $this->layout('layouts/dashboard', ['title' => 'Receive']);?>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">รับ-ส่ง </h3> 
      <span style="font-size: 2em;">
        <i class="fas fa-exchange-alt"></i>
      </span>
    </div>
    <div class="box-body">
        
        <div class="col-md-3">
          <div class="input-group">
            <input type="text" class="form-control" name="dateview" id="dateview">
            <span class="input-group-btn">
            <button class="btn btn-info" id="search_receive" type="button">
            <i class="fa fa-search"></i> Search
            </button>
            </span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
          <button class="btn btn-success" id="send"><i class="fas fa-angle-double-down"></i> ส่ง</button>
          <button class="btn btn-warning" id="send"><i class="fas fa-angle-double-up"></i> รับ</button>
          </div>
        </div>
        <br><br><br>
        <hr>

      <table id="grid_receive" class="table table-condensed table-striped" style="width:100%">
        <thead>
          <tr>
            <th>รหัสประจำตัวนักเรียน</th>
            <th>ชื่อ</th>
            <th>นาสกุล</th>
            <th>ชื่อเล่น</th>
            <th>ห้องเรียน</th>
            <th>เวลาส่ง</th>
            <th>ส่ง</th>
          </tr>
          <!-- <tr>
            <th>student_id</th>
            <th>student_name</th>
            <th>student_lastname</th>
            <th>nickname</th>
            <th>classroom</th>
            <th>send_date</th>
            <th></th>
          </tr> -->
        </thead>
      </table>
      
    </div>
  </div>
</section>

<!-- modal student -->
<div class="modal" id="modal_send" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" aria-label="Close">
          <span class="glyphicon glyphicon-remove"></span>
        </button>
        <h3 class="modal-title"><p id="line_send_student_id"></p></h3>
      </div>
      <div class="modal-body">
        <!-- Content -->
        <div class="form-group col-md-12">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="send_parent">ผู้ปกครอง</label>
                <select name="send_parent" id="send_parent" class="form-control">
                  <!-- <option value="">--เลือก--</option> -->
                </select>
              </div>
              <div class="form-group col-md-4">
                <label for="send_time">วันที่ส่ง</label>
                <input type="text" class="form-control" name="send_time" id="send_time" required>
              </div>
              <div class="form-group col-md-2">
                <label for="send_time_hour">เวลา(ชั่วโมง)</label>
                <select name="send_time_hour" id="send_time_hour" class="form-control" required>
                  <option value="">--เลือก--</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="send_time_minute">เวลา(นาที)</label>
                <select name="send_time_minute" id="send_time_minute" class="form-control" required>
                  <option value="">--เลือก--</option>
                </select>
              </div>
              <input type="hidden" name="send_student_id" id="send_student_id">
              <input type="hidden" name="send_parent_id" id="send_parent_id">
            </div>
        </div>
        <button type="button" id="submit_send" class="btn btn-primary">ยืนยัน</button>
      </div>
    </div>
  </div>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function($) {
    
    $("#dateview").datepicker({
        dateFormat: 'dd-mm-yy',
        autoclose: true,
        todayHighlight: true
    });
    var today = new Date();

    $('#dateview').datepicker('setDate', today);
    var dateview = $('#dateview').val();

    var grid_student_callback = function() {
      $('#grid_receive .--send').on('click', function(){

        var student_id = $(this).data('pk');
        var student_name = $(this).data('name');
        
        $('#line_send_student_id').html("<button class='btn btn-success btn-sm'><i class='fas fa-angle-double-down'></i></button> <font color='green'>"+student_name+"</font>");
        $('#modal_send').modal({backdrop: 'static'}); 

        $('#send_student_id').val(student_id);
        call_ajax("post", "/api/v1/receive/load/parent?student_id="+student_id+"&ran="+Math.random()*99999).done(function(data) {
          $.each(data, function(i, v) {
            // if (v.length !== 0) {
              $("#send_parent").append(
                "<option value='" + v.parent_id + "'>" + v.parent_name+" "+v.parent_lastname+ " ("+v.relation_description+")" +"</option>"
              );
              // console.log('v');
            // }else{
              // console.log('x');
            // }
            // $('#send_parent_id').val(v.parent_id);
          });
        });

        $("#send_time").datepicker({
          dateFormat: 'dd-mm-yy',
          autoclose: true,
          todayHighlight: true
        });
        $('#send_time').datepicker('setDate', today);

        var hournow = today.getHours().toString();
        if (hournow.length===1) {
          hournow = "0"+hournow; 
        }

        var minutenow = today.getMinutes().toString();
        if (minutenow.length===1) {
          minutenow = "0"+minutenow; 
        }

        call_ajax("post", "/api/v1/receive/load/hours").done(function(data) {
          $('#send_time_hour').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#send_time_hour").append(
              "<option value='" + v.hour + "'>" + v.hour + "</option>"
            );
            $('#send_time_hour').val(hournow);
          });
        });

        call_ajax("post", "/api/v1/receive/load/minutes").done(function(data) {
          $('#send_time_minute').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#send_time_minute").append(
              "<option value='" + v.minute + "'>" + v.minute + "</option>"
            );
            $('#send_time_minute').val(minutenow);
          });
        });

      });
    };

    loadGrid({
      el: '#grid_receive',
      processing: true,
      serverSide: true,
      deferRender: true,
      searching: true,
      order: [],
      orderCellsTop: true,
      modeSelect: "single",
      lengthChange: false,
      destroy: true,
      ajax: {
        url: '/api/v1/receive/all?dateview='+dateview,
        method: 'post'
      },
      fnDrawCallback: grid_student_callback,
      columns: [
        { data: 'student_id'},
        { data: 'student_name'},
        { data: 'student_lastname'},
        { data: 'student_nickname'},
        { data: 'classroom'},
        { data: 'send_date'}
      ],
      columnDefs: [
        {
          render: function(data, type, row) {
            return '<a href="javascript:void(0)" class="--send" data-pk="'+row.id+'" data-name="'+row.name_prefix+row.student_name+" "+row.student_lastname+" ("+row.student_nickname+")"+'">'+'<button class="btn btn-success btn-sm"><i class="fas fa-angle-double-down"></i></button>'+'</a>';
          }, targets: 6
        },
      ]
    });

    $('#search_receive').on('click',function() {
      
      var dateview = $('#dateview').val();

      loadGrid({
        el: '#grid_receive',
        processing: true,
        serverSide: true,
        deferRender: true,
        searching: true,
        order: [],
        orderCellsTop: true,
        modeSelect: "single",
        lengthChange: false,
        destroy: true,
        ajax: {
          url: '/api/v1/receive/all?dateview='+dateview,
          method: 'post'
        },
        fnDrawCallback: grid_student_callback,
        columns: [
          { data: 'student_id'},
          { data: 'student_name'},
          { data: 'student_lastname'},
          { data: 'student_nickname'},
          { data: 'classroom'},
          { data: 'send_date'}
        ],
        columnDefs: [
          {
            render: function(data, type, row) {
              return '<a href="javascript:void(0)" class="--send" data-pk="'+row.student_id+'">'+'<button class="btn btn-success btn-sm"><i class="fas fa-angle-double-down"></i></button>'+'</a>';
            }, targets: 6
          },
        ]
      });

    });

    $('#submit_send').on('click',function() {
      console.log($('#send_student_id').val());
      console.log($('#send_parent_id').val());
      $.ajax({
          url: '/api/v1/receive/send',
          type : 'post',
          cache : false,
          dataType : 'json',
          data : {
            send_student_id : $('#send_student_id').val(),
            send_parent_id : $('#send_parent_id').val(),
            send_time : $('#send_time').val(),
            send_time_hour : $('#send_time_hour').val(),
            send_time_minute : $('#send_time_minute').val()
          }
      })
      .done(function(data) {
        if ( data.result === true ) {
          reloadGrid('#grid_receive');
          $('#modal_send').modal('hide');
        } else {
          alert(data.message);
        }
      });
    });

  });
</script>
<?php $this->end() ?>