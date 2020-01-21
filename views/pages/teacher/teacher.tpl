<?php $this->layout('layouts/dashboard', ['title' => 'Teacher']);?>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">ข้อมูลครู </h3> 
      <span style="font-size: 2em;">
        <i class="fas fa-chalkboard-teacher"></i>
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
      <table id="grid_teacher" class="table table-condensed table-striped" style="width:100%">
        <thead>
          <tr>
            <th>ลำดับ</th>
            <th>คำนำหน้า</th>
            <th>ชื่อ</th>
            <th>นาสกุล</th>
            <th>เพศ</th>
            <th>เลขบัตรประชาชน</th>
            <th>เบอร์โทร</th>
            <th>วันเกิด</th>
            <th>e-mail</th>
            <th>สถานะ</th>
          </tr>
          <tr>
            <th>id</th>
            <th>name_prefix</th>
            <th>teacher_name</th>
            <th>teacher_lastname</th>
            <th>sex_description</th>
            <th>card_id</th>
            <th>phone</th>
            <th>birthday</th>
            <th>email</th>
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
                        <label for="card_id">เลขบัตรประชาชน</label>
                        <input type="text" class="form-control" name="card_id" id="card_id" required>
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
                  <label for="teacher_name">ชื่อ</label>
                  <input type="text" name="teacher_name" id="teacher_name" class="form-control" autocomplete="off" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="teacher_lastname">นามสกุล</label>
                  <input type="text" name="teacher_lastname" id="teacher_lastname" class="form-control" autocomplete="off" required>
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
                  <label for="education">ระดับการศึกษา</label>
                  <select name="education" id="education" class="form-control" required>
                    <option value="">--เลือก--</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="career">อาชีพ</label>
                  <select name="career" id="career" class="form-control" required>
                    <option value="">--เลือก--</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="email">e-mail</label>
                  <input type="email" name="email" id="email" class="form-control" autocomplete="off" required>
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
                  <label for="address_third">ที่อยู่ที่ทำงาน
                    <a>(<input type="checkbox" id="chk_address_third"> เหมือนที่อยู่ที่ติดต่อได้)</a>
                  </label>
                  <textarea rows="3" name="address_third" id="address_third" class="form-control" autocomplete="off"></textarea>
                </div>
            </div>

            <div class="form-row col-md-12">
              <div class="form-group col-md-12">
                <label></label>
              </div>
            </div>

            <div class="form-row col-md-12">
                <div class="form-group col-md-4">
                  <label for="image_teacher">รูปประจำตัวครู</label>
                  <input type="file" name="image_teacher" class="btn btn-info">
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
      el: '#grid_teacher',
      processing: true,
      serverSide: true,
      deferRender: true,
      searching: true,
      order: [],
      orderCellsTop: true,
      modeSelect: "single",
      ajax: {
        url: '/api/v1/teacher/all',
        method: 'post'
      },
      // fnDrawCallback: grid_teacher_callback,
      columns: [
        { data: 'id'},
        { data: 'name_prefix'},
        { data: 'teacher_name'},
        { data: 'teacher_lastname'},
        { data: 'sex_description'},
        { data: 'card_id'},
        { data: 'phone'},
        { data: 'birthday'},
        { data: 'email'},
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

        call_ajax("post", "/api/v1/teacher/load/namefix").done(function(data) {
          $('#name_prefix').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#name_prefix").append(
              "<option value='" + v.id + "'>" + v.name_prefix + "</option>"
            );
          });
        });

        call_ajax("post", "/api/v1/teacher/load/sex").done(function(data) {
          $('#sex_id').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#sex_id").append(
              "<option value='" + v.sex_id + "'>" + v.sex_description + "</option>"
            );
          });
        });

        call_ajax("post", "/api/v1/teacher/load/education").done(function(data) {
          $('#education').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#education").append(
              "<option value='" + v.id + "'>" + v.education + "</option>"
            );
          });
        });

        call_ajax("post", "/api/v1/teacher/load/career").done(function(data) {
          $('#career').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#career").append(
              "<option value='" + v.id + "'>" + v.career + "</option>"
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
            url: '/api/v1/teacher/create',
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
              reloadGrid('#grid_teacher');
            } else {
              alert(data.message);
            }

        });
            
        return false;
    }

</script>
<?php $this->end() ?>