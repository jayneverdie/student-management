<?php $this->layout('layouts/dashboard', ['title' => 'Parent']);?>
<style type="text/css">
  td {
    padding: 10px;
  }  
  .editable-popup{z-index: 9999 !important};
</style>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">ข้อมูลผู้ปกครอง </h3>
      <span style="font-size: 2em;">
        <i class="fas fa-user-shield"></i>
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
      <table id="grid_parent" class="table table-condensed table-striped" style="width:100%">
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
            <th>parent_name</th>
            <th>parent_lastname</th>
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
                <div class="row">
                    <div class="form-group col-md-4">
                      <img src="/assets/images/avatar.png" id="img_card" alt="" width="150">
                    </div>
                    <br><br><br><br>
                    <div class="col-md-4">
                        <label for="card_id">เลขบัตรประจำตัวประชาชน</label>
                        <div class="input-group">
                        <input type="text" class="form-control" name="card_id" id="card_id" required>
                            <span class="input-group-btn">
                            <button class="btn btn-info" id="select_card" type="button">
                            <i class="fa fa-id-card"></i> Scan
                            </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="name_prefix">คำนำหน้าชื่อ</label>
                  <select name="name_prefix" id="name_prefix" class="form-control" required>
                    <option value="">--เลือก--</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="parent_name">ชื่อ</label>
                  <input type="text" name="parent_name" id="parent_name" class="form-control" autocomplete="off" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="parent_lastname">นามสกุล</label>
                  <input type="text" name="parent_lastname" id="parent_lastname" class="form-control" autocomplete="off" required>
                </div>
            </div>
            <div class="form-row">
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
            <div class="form-row">
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
            <div class="form-row">
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
            <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="files_upload">เอกสารไฟล์</label>
                  <button type="button" class="btn btn-info" name="files_upload" id="files_upload"><i class="fa fa-file"></i> attachfile <i class="fa fa-plus-circle"></i> </button>
                </div>
                <div class="form-group col-md-8">
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

<!-- modal line -->
<div class="modal" id="modal_line" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" aria-label="Close">
          <span class="glyphicon glyphicon-remove"></span>
        </button>
        <h3 class="modal-title"><p id="line_card_id"></p></h3>
      </div>
      <div class="modal-body">
        <!-- Content -->
        <form id="form_create_relation">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_line_detail" data-toggle="tab" aria-expanded="false">ข้อมูลส่วนตัว</a></li>
              <li><a href="#tab_line_student" data-toggle="tab" aria-expanded="false">ความสัมพันธ์</a></li>
            </ul> 
            <div class="tab-content">
              <!-- tab line detail-->
              <div class="tab-pane active" id="tab_line_detail">
                <table class="table table-striped">
                  <tr>
                    <td colspan="6">
                      <img src="/assets/images/avatar.png" id="detail_card_img" alt="" width="150">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6">
                      <div id="detail_card_id"></div>
                    </td>
                  </tr>
                  <tr>
                    <td style="width: 140px;">
                      <b>เพศ</b>
                    </td>
                    <td>
                      <div id="detail_sex"></div>
                    </td>
                    <td>
                      <b>วันเกิด</b>
                    </td>
                    <td>
                      <div id="detail_birthday"></div>
                    </td>
                    <td>
                      <b>เบอร์โทรศัพท์</b>
                    </td>
                    <td>
                      <div id="detail_phone"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>ระดับการศึกษา</b>
                    </td>
                    <td>
                      <div id="detail_education"></div>
                    </td>
                    <td>
                      <b>อาชีพ</b>
                    </td>
                    <td>
                      <div id="detail_career"></div>
                    </td>
                    <td>
                      <b>อีเมลล์</b>
                    </td>
                    <td>
                      <div id="detail_email"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>ที่อยู่ตามทะเบียนบ้าน</b>
                    </td>
                    <td colspan="5">
                      <div id="detail_address_first"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>ที่อยู่ที่ติดต่อได้</b>
                    </td>
                    <td colspan="5">
                      <div id="detail_address_second"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>ที่อยู่ที่ทำงาน</b>
                    </td>
                    <td colspan="5">
                      <div id="detail_address_third"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>เอกสารไฟล์</b>
                    </td>
                    <td colspan="5">
                      <p id ="file_document"></p>
                    </td>
                  </tr>
                </table>
              </div>
              <!-- tab line student-->
              <div class="tab-pane" id="tab_line_student">
                <table id="grid_map" class="table table-condensed table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th>ลำดับ</th>
                      <th>ความสัมพันธ์</th>
                      <th>คำนำหน้า</th>
                      <th>ชื่อ</th>
                      <th>นาสกุล</th>
                      <th>ห้องเรียน</th>
                      <th>หมายเหตุ</th>
                      <th>ลบ</th>
                    </tr>
                  </thead>
                </table>
                <hr>

                <div class="modal-header">
                  <h3 class="modal-title"><u>เพิ่มความสัมพันธ์</u></h3>
                </div>
                <form id="form_create_map"> 
                  <div class="form-group col-md-12">
                      <div class="row">
                          <div class="form-group col-md-4">
                            <label for="map_relation">ความสัมพันธ์</label>
                            <select name="map_relation" id="map_relation" class="form-control">
                              <option value="">--เลือก--</option>
                            </select>
                          </div>
                          <div class="col-md-4">
                              <label for="map_student">นักเรียน</label>
                              <div class="input-group">
                              <input type="text" class="form-control" name="map_student" id="map_student"  readonly>
                                <span class="input-group-btn">
                                <button class="btn btn-info" id="select_student" type="button">
                                <i class="fa fa-search"></i> 
                                </button>
                                </span>
                              </div>
                          </div>
                          <div class="col-md-4">
                              <label for="map_student_card_id">รหัสประจำตัวนักเรียน</label>
                              <div class="input-group">
                              <input type="text" class="form-control" name="map_student_card_id" id="map_student_card_id"  readonly>
                              <input type="hidden" name="map_parent_id" id="map_parent_id">
                              <input type="hidden" name="map_student_id" id="map_student_id">
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="form-group col-md-12">
                    <div class="row">
                      <div class="form-group col-md-4">
                        <label for="map_remark">หมายเหตุ</label>
                        <textarea rows="3" name="map_remark" id="map_remark" class="form-control" autocomplete="off" ></textarea>
                      </div>
                    </div>
                  </div>
                  <button type="button" id="submit_map" class="btn btn-primary">เพิ่ม</button>
                </form>
                
              </div>
            </div>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="modal_select_student" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" aria-label="Close">
          <span class="glyphicon glyphicon-remove"></span>
        </button>

        <h3 class="modal-title">Select Student</h3>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="grid_student" class="table table-condensed table-striped" style="width:100%">
            <thead>
              <tr>
              <th>คำนำหน้า</th>
              <th>ชื่อ</th>
              <th>นาสกุล</th>
              <th>ชื่อเล่น</th>
              <th>รหัสประจำตัวนักเรียน</th>
              <th>ห้องเรียน</th>
            </tr>
            <tr>
              <th>name_prefix</th>
              <th>student_name</th>
              <th>student_lastname</th>
              <th>nickname</th>
              <th>student_id</th>
              <th>classroom</th>
            </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function ($) {

    $('#select_card').on('click', function(){
        
        $('#select_card').html('<i class="fa fa-id-card"> </i> reading...');
        $('#select_card').attr('disabled', true);
        call_ajax('get', '/api/v1/parent/readcard', {
        }).done(function(data) {
            if (data.result === false) {
                alert(data.message);
            } else {
                var path_img = "/files/images/parent/"+data.cid+"/"; 
                document.getElementById("img_card").src = path_img+data.cid+".jpg";
                // cardid
                $('#card_id').val(data.cid);
                // nameprefix
                if (data.prename=="นาย") {
                    $('#name_prefix').val(1);
                }else if(data.prename=="นางสาว"){
                    $('#name_prefix').val(2);
                }else if(data.prename=="นาง"){
                    $('#name_prefix').val(3);
                }
                // fname
                $('#parent_name').val(data.fname);
                // lname
                $('#parent_lastname').val(data.lname);
                // sex
                if (data.gender==1) {
                    $('#sex_id').val('Male');
                }else{
                    $('#sex_id').val('Female');
                }
                // birthday
                var ymd = data.dob;
                var y = ymd.substring(0, 4);
                var m = ymd.substring(4, 6);
                var d = ymd.substring(6, 8);
                $('#birthday').val(d+"-"+m+"-"+(y-543));
                // address
                var str_address = data.address;
                var address = str_address.replace(/#/g, " ");
                $('#address_first').val(address);
            }
            console.log(data);
            $('#select_card').html('<i class="fa fa-id-card"> Scan</i>');
            $('#select_card').attr('disabled', false);
        });
    });

    loadGrid({
      el: '#grid_parent',
      processing: true,
      serverSide: true,
      deferRender: true,
      searching: true,
      order: [],
      orderCellsTop: true,
      destroy: true,
      modeSelect: "single",
      ajax: {
        url: '/api/v1/parent/all',
        method: 'post'
      },
      // fnDrawCallback: grid_parent_callback,
      columns: [
        { data: 'id'},
        { data: 'name_prefix'},
        { data: 'parent_name'},
        { data: 'parent_lastname'},
        { data: 'sex_description'},
        { data: 'card_id'},
        { data: 'phone'},
        { data: 'birthday'},
        { data: 'email'},
        // { data: 'address_first'},
        // { data: 'address_second'},
        // { data: 'address_third'},
        // { data: 'education'},
        // { data: 'career'},
        { data: 'status_name'}
      ]
    });

    $('#create').on('click', function () {
        $('#modal_create').modal({backdrop: 'static'});
        $('#form_create').trigger('reset');
        $('#select_card').html('<i class="fa fa-id-card"> Scan</i>');
        $('#select_card').attr('disabled', false);

        $("#birthday").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true
        });

        var path_img = "/assets/images/" 
        document.getElementById("img_card").src = path_img+"avatar.png";

        call_ajax("post", "/api/v1/parent/load/namefix").done(function(data) {
          $('#name_prefix').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#name_prefix").append(
              "<option value='" + v.id + "'>" + v.name_prefix + "</option>"
            );
          });
        });

        call_ajax("post", "/api/v1/parent/load/sex").done(function(data) {
          $('#sex_id').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#sex_id").append(
              "<option value='" + v.sex_id + "'>" + v.sex_description + "</option>"
            );
          });
        });

        call_ajax("post", "/api/v1/parent/load/education").done(function(data) {
          $('#education').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#education").append(
              "<option value='" + v.id + "'>" + v.education + "</option>"
            );
          });
        });

        call_ajax("post", "/api/v1/parent/load/career").done(function(data) {
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
           $('#myfile').append("<button id='"+add+"' onclick='removeEle("+add+','+add1+','+br+")' type='button' class='btn btn-info'><i class='fa fa-trash'></i></button><input type='file' class='btn btn-info' name='files_upload[]' id='"+add1+"'><br id='"+br+"'>");
            num++;
        });
    });

    $('#line').on('click', function() {

      var rowdata = rowSelected('#grid_parent');
      var selected = '';
      $('#file_document').html('');
      if ( rowdata.length !== 0) {

        $('#modal_line').modal({backdrop: 'static'}); 
        $('#line_card_id').text(rowdata[0].name_prefix+rowdata[0].parent_name+" "+rowdata[0].parent_lastname);
        // tab detail
        $('#detail_card_id').html("<b>เลขบัตรประจำตัวประชาชน &nbsp;&nbsp;&nbsp;</b>"+rowdata[0].card_id);
        $('#detail_birthday').text(rowdata[0].birthday);
        $('#detail_sex').text(rowdata[0].sex_description);
        $('#detail_phone').text(rowdata[0].phone);
        $('#detail_education').text(rowdata[0].education);
        $('#detail_career').text(rowdata[0].career);
        $('#detail_email').text(rowdata[0].email);
        $('#detail_address_first').text(rowdata[0].address_first);
        $('#detail_address_second').text(rowdata[0].address_second);
        $('#detail_address_third').text(rowdata[0].address_third);
        $('#map_parent_id').val(rowdata[0].id);

        var path_img = "/files/images/parent/"+rowdata[0].card_id+"/"; 
        document.getElementById("detail_card_img").src =path_img+rowdata[0].card_id+".jpg";

        call_ajax('get', '/api/v1/parent/loadfile?card_id=' + rowdata[0].card_id+"&filetype=parent&ran="+Math.random()*99999)
        .done(function(data) {
          var i = 1;
          $.each(data, function( k, v ) {
              var filename = v.file_name;
              $('#file_document').append("<button type='button' class='btn btn-info btn-xs' onclick=deleteFile\('"+filename+"')><i class='far fa-trash-alt'></i></button> <a target='_blank' href='/files/document/parent/"+rowdata[0].card_id+"/" + v.file_name + "'>" + v.file_name + "</a><br>");
              i++;
          });
        });

        call_ajax("post", "/api/v1/parent/load/relation").done(function(data) {
          $('#map_relation').html("<option value=''>- เลือก -</option>");
          $.each(data, function(i, v) {
            $("#map_relation").append(
              "<option value='" + v.id + "'>" + v.relation_description + "</option>"
            );
          });
        });

        var grid_map_callback = function() {

          call_ajax('get', '/api/v1/master/getall/relation')
          .done(function(data) {
            // cap status
            $('#grid_map .--relation-name').editable({
              type: 'select',
              name: 'relation',
              url: '/api/v1/parent/relation/update',
              title: 'Relation',
              source: pack_dd(data, 'id', 'relation_description'),
              success: function(response, newValue) {
                if (response.result === false) {
                  alert(response.message);
                  window.location.reload();
                }
              }
            });
          });

          $('#grid_map .--delete-map').on('click', function(){

            var id = $(this).data('pk');
            $.ajax({
                url: '/api/v1/parent/map/delete',
                type : 'post',
                cache : false,
                dataType : 'json',
                data : {
                  id : id
                }
            })
            .done(function(data) {
              if ( data.result === true ) {
                reloadGrid('#grid_map');
              } else {
                alert(data.message);
              }
            });
            return false;
            
          });

        };

        loadGrid({
          el: '#grid_map',
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
            url: '/api/v1/parent/map?parent_id='+rowdata[0].id,
            method: 'post'
          },
          fnDrawCallback: grid_map_callback,
          columns: [
            { data: 'id'},
            { data: 'relation_description'},
            { data: 'name_prefix'},
            { data: 'student_name'},
            { data: 'student_lastname'},
            { data: 'classroom'},
            { data: 'remark'}
          ],
          columnDefs: [
            {
              render: function(data, type, row) {
                return '<a href="javascript:void(0)" class="--relation-name" data-pk="'+row.relation+'">'+data+'</a>';
              }, targets: 1
            },
            {
              render: function(data, type, row) {
                return '<a href="javascript:void(0)" class="--delete-map" data-pk="'+row.id+'">'+'<button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>'+'</a>';
              }, targets: 7
            }
          ]
        });

        $('#select_student').on('click', function () {
          $('#modal_select_student').modal({backdrop: 'static'});

          loadGrid({
            el: '#grid_student',
            processing: true,
            serverSide: true,
            deferRender: true,
            searching: true,
            order: [],
            orderCellsTop: true,
            modeSelect: "single",
            destroy: true,
            ajax: {
              url: '/api/v1/student/all',
              method: 'post'
            },
            columns: [
              { data: 'name_prefix'},
              { data: 'student_name'},
              { data: 'student_lastname'},
              { data: 'student_nickname'},
              { data: 'student_id'},
              { data: 'classroom'}
            ]
          });

          $('#grid_student').on('dblclick', function () {
            var rowdata = rowSelected('#grid_student');
            $('input[name=map_student_id]').val(rowdata[0].id);
            $('input[name=map_student]').val(rowdata[0].name_prefix+" "+rowdata[0].student_name+" "+rowdata[0].student_lastname);
            $('input[name=map_student_card_id').val(rowdata[0].student_id);
            $('#modal_select_student').modal('hide');
          });

        });

      } else {
        alert('Please select row!');
      }
    });
  
    $('#submit_map').on('click', function() {
      $.ajax({
          url: '/api/v1/parent/create/map',
          type : 'post',
          cache : false,
          dataType : 'json',
          data : {
            map_relation : $('#map_relation').val(),
            map_student_id : $('#map_student_id').val(),
            map_parent_id : $('#map_parent_id').val(),
            map_remark : $('#map_remark').val()
          }
      })
      .done(function(data) {
        if ( data.result === true ) {
          reloadGrid('#grid_map');
          $('input[name=map_relation]').val('');
          $('input[name=map_student]').val('');
          $('input[name=map_student_card_id').val('');
          $('input[name=map_remark]').val('');
        } else {
          alert(data.message);
        }
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
            url: '/api/v1/parent/create',
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
              reloadGrid('#grid_parent');
            } else {
              alert(data.message);
            }

        });
            
        return false;
    }

</script>
<?php $this->end() ?>