<?php $this->layout('layouts/dashboard', ['title' => 'Receive']);?>

<style type="text/css">
  .ui-datepicker{z-index: 9999 !important};
</style>

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
          <button class="btn btn-success" id="btnSend"><i class="fas fa-angle-double-down"></i> ส่ง</button>
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
          </tr>
          <tr>
            <th>student_id</th>
            <th>student_name</th>
            <th>student_lastname</th>
            <th>nickname</th>
            <th>classroom</th>
            <th>send_date</th>
          </tr>
        </thead>
      </table>
      
    </div>
  </div>
</section>

<!-- modal send -->
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
        <form id="form_send" onsubmit="return submit_send()"> 
          <div class="form-group col-md-12">
              <div class="row">
                <div class="form-group col-md-3">
                    <label for="send_time">วันที่ส่ง</label>
                    <div class="input-group">
                    <input type="text" class="form-control" name="send_time" id="send_time" required autocomplete="off">
                        <span class="input-group-btn">
                        <button class="btn btn-info" id="select_send_time" type="button">
                        <i class="far fa-calendar-alt"></i>
                        </button>
                        </span>
                    </div>
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
              </div>
          </div>
          <div class="form-row col-md-12">
            <div class="modal-footer"></div>
          </div>
          <!-- <div class="form-row col-md-12">
            <div class="form-group col-md-12">
              <input type="checkbox" name="uncard" id="uncard" style="width: 1.3em; height: 1.3em;">
              <label>กรณีไม่มีบัตร</label>
            </div>
          </div> -->
          <div class="form-row col-md-12">
            <div class="col-md-4">
              <label for="card_id">เลขบัตรประจำตัวประชาชน</label>
              <div class="input-group">
              <input type="text" class="form-control" name="card_id" id="card_id" maxlength="13" required>
                  <span class="input-group-btn">
                  <button class="btn btn-info" id="select_card" type="button">
                  <i class="fa fa-id-card"></i> Scan
                  </button>
                  </span>
              </div>
              <br>
            </div>
          </div>

          <div class="form-row col-md-12">
            <div class="modal-footer"></div>
          </div>

          <div class="form-row col-md-12">
            <div class="row">
              <div class="text-right col-md-4">
                <label>สถานะความสัมพันธ์</label>
                <h3><p id="Prelation"></p></h3>
              </div>
              <div class="text-right col-md-4">
                <img src="/assets/images/avatar.png" id="Pimg_card" alt="" width="150">
              </div>
              <div class="text-left col-md-4">
                <span style="font-size: 2em;">
                  <i class="fas fa-exchange-alt"></i>
                </span>
                <img src="/assets/images/avatar.png" id="Simg_card" alt="" width="150">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="Pname_prefix">คำนำหน้าชื่อ</label>
                <input type="text" name="Pname_prefix" id="Pname_prefix" class="form-control"
                readonly>
              </div>
              <div class="form-group col-md-4">
                <label for="Pparent_name">ชื่อ</label>
                <input type="text" name="Pparent_name" id="Pparent_name" class="form-control"
                readonly>
              </div>
              <div class="form-group col-md-4">
                <label for="Pparent_lastname">นามสกุล</label>
                <input type="text" name="Pparent_lastname" id="Pparent_lastname" class="form-control"
                readonly>
              </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="Psex_id">เพศ</label>
                   <input type="text" name="Psex_id" id="Psex_id" class="form-control"
                readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="Pbirthday">วันเกิด</label>
                  <input type="text" name="Pbirthday" id="Pbirthday" class="form-control" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="Pphone">เบอร์โทรศัพท์</label>
                  <input type="text" name="Pphone" id="Pphone" class="form-control" readonly>
                </div>
            </div>
          </div>

          <div class="form-row col-md-12">
            <div class="modal-footer"></div>
          </div>

          <div class="form-row col-md-12">
            <div class="form-group col-md-4">
              <label>ข้อมูลนักเรียน</label>
            </div>
          </div>
          <div class="form-row col-md-12">
            <div class="form-group col-md-4">
              <label for="Ssudent_nickname">ชื่อเล่น</label>
              <select name="Ssudent_nickname" id="Ssudent_nickname" class="form-control" required>
              </select>
            </div>
          </div>
          <div class="form-row col-md-12">
              <div class="form-group col-md-4">
                <label for="Sname_prefix">คำนำหน้าชื่อ</label>
                <input type="text" name="Sname_prefix" id="Sname_prefix" class="form-control" readonly>
              </div>
              <div class="form-group col-md-4">
                <label for="Sstudent_name">ชื่อ</label>
                <input type="text" name="Sstudent_name" id="Sstudent_name" class="form-control" readonly>
              </div>
              <div class="form-group col-md-4">
                <label for="Sstudent_lastname">นามสกุล</label>
                <input type="text" name="Sstudent_lastname" id="Sstudent_lastname" class="form-control" readonly>
              </div>
          </div>
          <div class="form-row col-md-12">
              <div class="form-group col-md-4">
                <label for="Ssex_id">เพศ</label>
                <input type="text" name="Ssex_id" id="Ssex_id" class="form-control" readonly>
              </div>
              <div class="form-group col-md-4">
                <label for="Sbirthday">วันเกิด</label>
                <input type="text" name="Sbirthday" id="Sbirthday" class="form-control" readonly>
              </div>
              <div class="form-group col-md-4">
                <label for="Sstudent_id">รหัสนักเรียน</label>
                <input type="text" name="Sstudent_id" id="Sstudent_id" class="form-control" readonly>
              </div>
          </div>

          <button type="submit" class="btn btn-primary">ยืนยัน</button>
        </form>

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
        ]
      });

    });

    $('#btnSend').on('click', function(){
      $('#form_send').trigger('reset');
      
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

      $('#select_send_time').on('click',function(){
        $('#send_time').datepicker('show');
      });

      $('#line_send_student_id').html("<button class='btn btn-success btn-sm'><i class='fas fa-angle-double-down'></i></button> <font color='green'>บันทึกการมาส่ง</font>");
      $('#modal_send').modal({backdrop: 'static'}); 

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

    $('#select_card').on('click', function(){
        // $('#select_card').html('<i class="fa fa-id-card"> </i> reading...');
        // $('#select_card').attr('disabled', true);
        if ($('#card_id').val()!=='') {
          var card_id = $('#card_id').val();
          call_ajax('post', '/api/v1/receive/read?card_id='+card_id, {
          }).done(function(data) {
            if (data.result===false) {
              alert(data.message);
            }else{
              $('#Prelation').text(data[0].relation_description);
              $('#Pname_prefix').val(data[0].Pname_prefix);
              $('#Pparent_name').val(data[0].parent_name);
              $('#Pparent_lastname').val(data[0].parent_lastname);
              $('#Psex_id').val(data[0].Psex_id);
              $('#Pphone').val(data[0].phone);
              $('#Pbirthday').val(data[0].Pbirthday);
              
              $('#Sstudent_name').val(data[0].student_name); 
              $('#Sstudent_lastname').val(data[0].student_lastname); 
              $('#Sstudent_id').val(data[0].Sstudent_id); 
              $('#Sbirthday').val(data[0].Sbirthday);       
              $('#Ssex_id').val(data[0].Ssex_id);     
              $('#Sname_prefix').val(data[0].Sname_prefix);   
              $('#Ssudent_nickname').val(data[0].card_id);
              
              call_ajax("post", "/api/v1/receive/load/student/by/parent?card_id="+data[0].card_id+"&ran="+Math.random()*99999).done(function(data) {
                $.each(data, function(i, v) {
                    $("#Ssudent_nickname").append(
                      "<option value='" + v.student_id + "'>" + v.student_nickname + "</option>"
                    );
                });
              });

              var path_img = "/files/images/parent/"+data[0].card_id+"/"; 
              document.getElementById("Pimg_card").src = path_img+data[0].card_id+".jpg";

              var path_imgS = "/files/images/student/"+data[0].Sstudent_id+"/"; 
              document.getElementById("Simg_card").src = path_imgS+data[0].Sstudent_id+".jpg";
            }
          });
        }else{
          
          call_ajax('get', '/api/v1/parent/readcard', {
          }).done(function(data) {
              if (data.result === false) {
                  alert(data.message);
              } else {
                  // var path_img = "/files/images/parent/"+data.cid+"/"; 
                  // document.getElementById("img_card").src = path_img+data.cid+".jpg";
                  // // cardid
                  // $('#card_id').val(data.cid);
                  // // nameprefix
                  // if (data.prename=="นาย") {
                  //     $('#name_prefix').val(1);
                  // }else if(data.prename=="นางสาว"){
                  //     $('#name_prefix').val(2);
                  // }else if(data.prename=="นาง"){
                  //     $('#name_prefix').val(3);
                  // }
                  // // fname
                  // $('#parent_name').val(data.fname);
                  // // lname
                  // $('#parent_lastname').val(data.lname);
                  // // sex
                  // if (data.gender==1) {
                  //     $('#sex_id').val('Male');
                  // }else{
                  //     $('#sex_id').val('Female');
                  // }
                  // // birthday
                  // var ymd = data.dob;
                  // var y = ymd.substring(0, 4);
                  // var m = ymd.substring(4, 6);
                  // var d = ymd.substring(6, 8);
                  // $('#birthday').val(d+"-"+m+"-"+(y-543));
                  // // address
                  // var str_address = data.address;
                  // var address = str_address.replace(/#/g, " ");
                  // $('#address_first').val(address);
              }
              
              // console.log(data);

              // $('#select_card').html('<i class="fa fa-id-card"> Scan</i>');
              // $('#select_card').attr('disabled', false);
          }).fail(function(data) {
            // console.log(data);
            setTimeout(function(){ 
              alert("Please check scanner!");
            }, 3000);
          });
          
        }

    });

    $('#Ssudent_nickname').on('change',function(){
      alert("change");
    });

    $('#submit_send').on('click',function() {
      console.log($('#send_student_id').val()+"_"+$('#send_parent').val());
      // $.ajax({
      //     url: '/api/v1/receive/send',
      //     type : 'post',
      //     cache : false,
      //     dataType : 'json',
      //     data : {
      //       send_student_id : $('#send_student_id').val(),
      //       send_parent_id : $('#send_parent_id').val(),
      //       send_time : $('#send_time').val(),
      //       send_time_hour : $('#send_time_hour').val(),
      //       send_time_minute : $('#send_time_minute').val()
      //     }
      // })
      // .done(function(data) {
      //   if ( data.result === true ) {
      //     reloadGrid('#grid_receive');
      //     $('#modal_send').modal('hide');
      //   } else {
      //     alert(data.message);
      //   }
      // });
    });

  });
</script>
<?php $this->end() ?>