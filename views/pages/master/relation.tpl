<?php $this->layout('layouts/dashboard', ['title' => 'Master-Relation']);?>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">สถานะความสัมพันธ์</h3>
    </div>
    <div class="box-body">
      <!-- button -->
      <div class="btn-control">
        <button class="btn btn-primary" id="create"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
        <button class="btn btn-danger" id="delete"><i class="fa fa-close" aria-hidden="true"></i> Delete</button>
      </div>
      <!-- grid -->
      <table id="grid_relation" class="table table-condensed table-striped" style="width:100%">
        <thead>
          <tr>
            <th>Id</th>
            <th>สถานะความสัมพันธ์</th>
            <th>ผู้สร้าง</th>
            <th>วัน/เวลาสร้าง</th>
            <th>ผู้แก้ไข</th>
            <th>วัน/เวลาแก้ไข</th>
          </tr>
          <tr>
            <th>id</th>
            <th>relation_description</th>
            <th>create_by</th>
            <th>create_date</th>
            <th>update_by</th>
            <th>update_date</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</section>

<!-- modal create -->
<div class="modal" id="modal_create" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" aria-label="Close">
          <span class="glyphicon glyphicon-remove"></span>
        </button>
        <h4 class="modal-title">Create</h4>
      </div>
      <div class="modal-body">
        <!-- Content -->
        <form id="form_create">
          <div class="form-group">
            <label for="name_relation">สถานะความสัมพันธ์</label>
            <input type="text" name="name_relation" id="name_relation" class="form-control" autocomplete="off" autofocus required>
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

    var grid_relation_callback = function() {
      // cap name
      $('#grid_relation .--relation-name').editable({
        type: 'text',
        name: 'name',
        url: '/api/v1/master/relation/update',
        title: 'Name',
        success: function(response, newValue) {
          if (response.result === false) {
            alert(response.message);
            window.location.reload();
          }
          reloadGrid('#grid_relation', grid_relation_callback);
        }
      });
    };

    loadGrid({
      el: '#grid_relation',
      processing: true,
      serverSide: true,
      deferRender: true,
      searching: true,
      order: [],
      orderCellsTop: true,
      modeSelect: "single",
      ajax: {
        url: '/api/v1/master/relation/all',
        method: 'post'
      },
      fnDrawCallback: grid_relation_callback,
      columns: [
        { data: "id"},
        { data: "relation_description"},
        { data: 'create_by'},
        { data: "create_date"},
        { data: 'update_by'},
        { data: "update_date"}
      ],
      columnDefs: [
        {
          render: function(data, type, row) {
            return '<a href="javascript:void(0)" class="--relation-name" data-pk="'+row.id+'">'+data+'</a>';
          }, targets: 1
        }
      ]
    });

    $('#create').on('click', function () {
      $('#modal_create').modal({backdrop: 'static'});
      $('#form_create').trigger('reset');
    });

    $('#delete').on('click', function() {
      var rowdata = rowSelected('#grid_relation');
      if (rowdata.length !== 0) {
        if (confirm('Aru you sure?')) {
          call_ajax('post', '/api/v1/master/relation/delete', {
            id: rowdata[0].id
          }).done(function(data) {
            if (data.result === true) {
              reloadGrid('#grid_relation', grid_relation_callback);
            } else {
              alert(data.message);
            }
          });
        }
      } else {
        alert('Please select row!');
      }
    }); 

    $('#form_create').submit(function(e) {
      e.preventDefault();
      call_ajax('post', '/api/v1/master/relation/create', {
        name_relation: $('#name_relation').val()
      }).done(function(data) {
        if ( data.result === true ) {
          $('#modal_create').modal('hide');
          $('#form_create').trigger('reset');
          reloadGrid('#grid_relation', grid_relation_callback);
        } else {
          alert(data.message);
        }
      });
    });

  });
</script>
<?php $this->end() ?>