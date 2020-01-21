<?php $this->layout('layouts/dashboard', ['title' => 'Student']);?>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">ข้อมูลนักเรียน </h3>
      <span style="font-size: 2em;">
        <i class="fas fa-child"></i>
      </span>
    </div>
    <div class="box-body">
      <!-- button -->
      <div class="btn-control">
        <button class="btn btn-primary" id="create"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create</button>
        <!-- <button class="btn btn-danger" id="delete"><i class="fa fa-close" aria-hidden="true"></i> Delete</button> -->
        <button class="btn btn-success" id="line"><i class="fa fa-address-book"></i> Detail</button>
      </div>
      <!-- grid -->
      <table id="grid_student" class="table table-condensed table-striped" style="width:100%">
        <thead>
          <tr>
            <th>ลำดับ</th>
            <th>คำนำหน้า</th>
            <th>ชื่อ</th>
            <th>นาสกุล</th>
            <th>ชื่อเล่น</th>
            <th>รหัสประจำตัวนักเรียน</th>
            <th>ห้องเรียน</th>
            <th>เบอร์โทร</th>
            <th>วันเกิด</th>
            <th>วันที่เข้าเรียน</th>
            <th>สถานะ</th>
          </tr>
          <tr>
            <th>id</th>
            <th>name_prefix</th>
            <th>student_name</th>
            <th>student_lastname</th>
            <th>nickname</th>
            <th>student_id</th>
            <th>classroom</th>
            <th>phone</th>
            <th>birthday</th>
            <th>attendance_date</th>
            <th>status_name</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</section>

<!-- modal create -->
<div class="modal" id="modal_create" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" aria-label="Close">
          <span class="glyphicon glyphicon-remove"></span>
        </button>
        <h4 class="modal-title">Create</h4>
      </div>
      <div class="modal-body">
        <!-- Content -->
        <form id="form_create" onsubmit="return submit_create()"> 

            <div class="form-group col-md-12">
                <div class="form-row">
                    <div class="form-group col-md-4">
                      <img src="/assets/images/avatar.png" id="img_card" alt="" width="150">
                    </div>
                    <br><br><br><br>
                    <div class="form-group col-md-4">
                        <label for="card_id">รหัสประจำตัวนักเรียน</label>
                        <input type="text" class="form-control" name="card_id" id="card_id" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="nickname">ชื่อเล่น</label>
                        <input type="text" class="form-control" name="nickname" id="nickname" required>
                    </div>
                </div>
            </div>
            <div class="form-row col-md-12">
                <div class="form-group col-md-4">
                  <label for="name_prefix">คำนำหน้าชื่อ</label>
                  <select name="name_prefix" id="name_prefix" class="form-control" required>
                    <option value="">--เลือก--</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="student_name">ชื่อ</label>
                  <input type="text" name="student_name" id="student_name" class="form-control" autocomplete="off" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="student_lastname">นามสกุล</label>
                  <input type="text" name="student_lastname" id="student_lastname" class="form-control" autocomplete="off" required>
                </div>
            </div>
            <div class="form-row col-md-12">
                <div class="form-group col-md-4">
                  <label for="sex_id">เพศ</label>
                  <select name="sex_id" id="sex_id" class="form-control" required>
                    <option value="">--เลือก--</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="birthday">วันเกิด</label>
                  <input type="text" name="birthday" id="birthday" class="form-control" autocomplete="off" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="phone">เบอร์โทรศัพท์</label>
                  <input type="text" name="phone" id="phone" class="form-control" autocomplete="off" required>
                </div>
            </div>
            <div class="form-row col-md-12">
                <div class="form-group col-md-4">
                  <label for="attendance">วันที่เข้าเรียน</label>
                  <input type="text" name="attendance" id="attendance" class="form-control" autocomplete="off" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="classroom">ห้องเรียน</label>
                  <select name="classroom" id="classroom" class="form-control" required>
                    <option value="">--เลือก--</option>
                  </select>
                </div>
            </div>
            <div class="form-row col-md-12">
                <div class="form-group col-md-4">
                  <label for="address_first">ที่อยู่ตามทะเบียนบ้าน</label>
                  <textarea rows="3" name="address_first" id="address_first" class="form-control" autocomplete="off" required></textarea>
                </div>
                <div class="form-group col-md-4">
                  <label for="address_second">ที่อยู่ที่ติดต่อได้ 
                    <a>(<input type="checkbox" id="chk_address_second"> เหมือนที่อยู่ตามทะเบียนบ้าน)</a>
                  </label> 
                  <textarea rows="3" name="address_second" id="address_second" class="form-control" autocomplete="off"></textarea>
                </div>
                <div class="form-group col-md-4">
                  <label for="remark">หมายเหตุ</label>
                  <textarea rows="3" name="remark" id="remark" class="form-control" autocomplete="off"></textarea>
                </div>
            </div>

            <div class="form-row col-md-12">
              <div class="form-group col-md-12">
                <label></label>
              </div>
            </div>

            <div class="form-row col-md-12">
                <div class="form-group col-md-4">
                  <label for="cardid_father">เลขบัตรประชาชนบิดา</label>
                  <input type="text" name="cardid_father" id="cardid_father" class="form-control" autocomplete="off" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="cardid_mother">เลขบัตรประชาชนมารดา</label>
                  <input type="text" name="cardid_mother" id="cardid_mother" class="form-control" autocomplete="off" required>
                </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-12">
                <label></label>
              </div>
            </div>

            <div class="form-row col-md-12">
                <div class="form-group col-md-4">
                  <label for="image_student">รูปประจำตัวนักเรียน</label>
                  <input type="file" name="image_student" class="btn btn-info">
                </div>
                <div class="form-group col-md-3" class="text-right">
                  <div class="text-right">
                  <label for="files_upload">เอกสารไฟล์</label><br>
                  <button type="button" class="btn btn-info" name="files_upload" id="files_upload"><i class="fa fa-file"></i> attachfile <i class="fa fa-plus-circle"></i> </button>
                  </div>
                </div>
                <div class="form-group col-md-5">
                  <div class="well"><span id="myfile"></span></div>
                </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-12">
                <div class="modal-footer"></div>
              </div>
            </div>

          <button class="btn btn-primary" type="submit"><i class="fa fa-check" aria-hidden="true"></i> บันทึก</button>

        </form>
      </div>
    </div>
  </div>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function ($) {

    loadGrid({
      el: '#grid_student',
      processing: true,
      serverSide: true,
      deferRender: true,
      searching: true,
      order: [],
      orderCellsTop: true,
      modeSelect: "single",
      ajax: {
        url: '/api/v1/student/all',
        method: 'post'
      },
      // fnDrawCallback: grid_student_callback,
      columns: [
        { data: 'id'},
        { data: 'name_prefix'},
        { data: 'student_name'},
        { data: 'student_lastname'},
        { data: 'student_nickname'},
        { data: 'student_id'},
        { data: 'classroom'},
        { data: 'phone'},
        { data: 'birthday'},
        { data: 'attendance_date'},
        { data: 'status_name'}
      ]
    });

    $('#create').on('click', function () {
        $('#modal_create').modal({backdrop: 'static'});
        $('#form_create').trigger('reset');

        $("#birthday").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true
        });
        $("#attendance").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true
        });

        var path_img = "/assets/images/" 
        document.getElementById("img_card").src = path_img+"avatar.png";

        call_ajax("post", "/api/v1/student/load/namefix").done(function(data) {
          $('#name_prefix').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#name_prefix").append(
              "<option value='" + v.id + "'>" + v.name_prefix + "</option>"
            );
          });
        });

        call_ajax("post", "/api/v1/student/load/sex").done(function(data) {
          $('#sex_id').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#sex_id").append(
              "<option value='" + v.sex_id + "'>" + v.sex_description + "</option>"
            );
          });
        });

        call_ajax("post", "/api/v1/student/load/education").done(function(data) {
          $('#education').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#education").append(
              "<option value='" + v.id + "'>" + v.education + "</option>"
            );
          });
        });

        call_ajax("post", "/api/v1/student/load/classroom").done(function(data) {
          $('#classroom').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#classroom").append(
              "<option value='" + v.id + "'>" + v.classroom + "</option>"
            );
          });
        });

        $('#chk_address_second').on('change', function() {
            if ($(this).is(':checked')) {
              $('#address_second').val($('#address_first').val());
            } else {
              $('#address_second').val('');
            }
        });

        $('#chk_address_third').on('change', function() {
            if ($(this).is(':checked')) {
              $('#address_third').val($('#address_second').val());
            } else {
              $('#address_third').val('');
            }
        });

        var num =1;
        $('#files_upload').bind('click',function(){
           var add  = "add"+num;
           var add1 = "add1"+num;
           var br   = "br"+num;
           $('#myfile').append("<button id='"+add+"' onclick='removeEle("+add+','+add1+','+br+")' type='button' class='btn btn-info'><i class='fa fa-trash'></i></button><input type='file' class='btn btn-info btn-sm' name='files_upload[]' id='"+add1+"'><br id='"+br+"'>");
            num++;
        });
    });

  });
    

    function removeEle(divid,_divid,divbr){
        $(divid).remove(); 
        $(_divid).remove(); 
        $(divbr).remove(); 
    }

    function submit_create() {
        var form_data = new FormData($("#form_create")[0]);

        $.ajax({
            url: '/api/v1/student/create',
            type : 'post',
            cache : false,
            dataType : 'json',
            contentType: false,
            processData: false,
            data : form_data
        })
        .done(function(data) {
            
            if ( data.result === true ) {
              $('#modal_create').modal('hide');
              $('#form_create').trigger('reset');
              reloadGrid('#grid_student');
            } else {
              alert(data.message);
            }

        });
            
        return false;
    }

</script>
<?php $this->end() ?>