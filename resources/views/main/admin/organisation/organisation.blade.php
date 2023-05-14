@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />

@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Master Data</a></li>
        <li class="breadcrumb-item active" aria-current="page">Organisation</li>
        </ol>
  </nav>

  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="float-right mb-3">
                <button type="button" onclick="addData()" class="btn btn-outline-success btn-icon-text mr-2 d-md-block">
                    <i class="btn-icon-prepend" data-feather="plus"></i>
                    Add Data
                </button>
            </div>
          <div class="table-responsive">
            <table id="dataTables" class="table table-bordered">
              <thead>
                <tr>
                    <th>No</th>
                    <th>Wilayah</th>
                    <th>Type</th>
                    <th>Parent</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="dataModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="dataModalTitle"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="/dashboard/master-data/role/add" id="formData" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="form-group mb-2">
                    <label for="organisation_type_id" class="mb-0 required">Type</label>
                    <select name="organisation_type_id" required id="organisation_type_id" class="select2 w-100 @error('organisation_type_id') is-invalid @enderror">
                        <option value="" selected>&nbsp;</option>
                        @foreach ($type as $item)
                            <option @if(old('organisation_type_id') == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    @error('organisation_type_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="wilayah_id" class="mb-0 required">Wilayah</label>
                    <select name="wilayah_id" id="wilayah_id" required class="select2 w-100 @error('wilayah_id') is-invalid @enderror">
                        <option value="" selected>&nbsp;</option>
                        @foreach ($wilayahs as $item)
                            <option @if(old('wilayah_id') == $item->id) selected @endif value="{{$item->id}}">{{$item->wilayah_name}}</option>
                        @endforeach
                    </select>
                    @error('wilayah_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                @if(\Auth::user()->role()->first()->name == 'superadmin')
                <div class="form-group mb-2">
                    <label for="parent_id" class="mb-0">Parent</label>
                    <select name="parent_id" id="parent_id" class="select2 w-100 @error('parent_id') is-invalid @enderror">
                        <option value="" selected>&nbsp;</option>
                        @foreach ($parent as $item)
                            <option @if(old('parent_id') == $item->id) selected @endif value="{{$item->id}}">{{$item->wilayah->wilayah_name}}</option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                @endif
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="submit()">Save</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>

@endpush

@push('custom-scripts')
<script>
        var link = '/dashboard/master-data/organisation';
        var aTable;
    $(document).ready( function () {
        $(function() {
            'use strict';
            $(function() {
                aTable = $('#dataTables').DataTable({
                    processing: true,
                    serverSide: true,
                    lengthChange:false,
                    searching:true,
                    searchDelay: 3000,
                    ajax: {
                        url:link+'/get-data',
                    },
                    columnDefs: [
                        { className: "text-center",targets:'_all'}
                    ],
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex',autoWidth: true,orderable:false,searchable:false},
                        { data: 'wilayahName', name: 'wilayahName',autoWidth: true },
                        { data: 'type.name', name: 'type.name',autoWidth: true },

                        { data: 'parentName', name: 'parentName',autoWidth: true },
                        {data:'action',name:'action',orderable:false,searchable:false},
                    ],
                    "aLengthMenu": [
                        [10, 30, 50, -1],
                        [10, 30, 50, "All"]
                    ],
                    "iDisplayLength": 10,
                    "language": {
                        search: ""
                    },
                    "initComplete": function()
                    {
                        $(".dataTables_filter input")
                        .unbind() // Unbind previous default bindings
                        .bind("input keyup", function(e) { // Bind our desired behavior
                            // If the length is 3 or more characters, or the user pressed ENTER, search
                            if(e.keyCode == 13) {
                                // Call the API search function
                                aTable.search(this.value).draw();
                            }
                            // Ensure we clear the search if they backspace far enough
                            if(this.value == "") {
                                aTable.search("").draw();
                            }
                            return;
                        });
                    }
                });
            });
            if ($(".select2").length) {
                $(".select2").select2({
                    width:'100%'
                });
            }
            if ($("#typeSelect").length) {
                $("#typeSelect").select2({
                    width:'100%',
                    delay:1800,
                    selectOnClose: true,
                    ajax:{
                        url:'/dashboard/config/organisation-type/get-data',
                        dataType: 'json',
                        quietMillis: 100,
                        data:function(param){
                            var query ={
                                name:param.term,
                            }
                            return query;
                        },
                        processResults:function(res){
                            return {
                                results: $.map(res.data,function(obj){
                                    return {id:obj.id,text:obj.name}
                                })
                            };
                        }
                    },
                });
            }
        });
        @if(count($errors) > 0)
            @if(old('id'))
                $('#dataModalTitle').text("Edit Data");
                $('#password').attr('required',false);
            @else
                $('#dataModalTitle').text("Add Data");
                $('#password').attr('required',true);
            @endif
            $('#dataModal').modal('show');
        @endif
    });
    function clearData(){
        $('#dataModalTitle').text("Add Data");
        $('#name').val('');
        $('#formData').attr('action',this.link+'/add');
        $('#emailField').attr('value','');
        $('#password').val('');
        $('#password').attr('required',true);
        $('#status').val('active').trigger('change');
        $('#role').val('').trigger('change');
        $('#id').val('');
        $('#wilayah').val('').trigger('change');
        $('#picture').val('');
    }
    function ShowDetail(data){
        console.log(data);
        $('#formData').attr('action',this.link+'/update');
        $('#dataModalTitle').text("Edit Data");
        $('#name').val(data.name);
        $('#id').val(data.id);
        $('#emailField').attr('value',data.email);
        $('#password').attr('required',false);
        $('#status').val(data.status);
        $('#role').val(data.role_id).trigger('change');
        $('#wilayah').val(data.wilayah_id).trigger('change');
        $('#dataModal').modal('show');

    }
    // Ajax CRUD
    function addData(){
        clearData();
        $('#dataModal').modal('show');
    }
    function submit(){
        var form =document.getElementById('formData');
        var a = form.checkValidity();
        if(a){
            form.submit();
        }else{
            form.reportValidity();
        }
    }
    function Delete(id){
        Swal.fire({
            title: "Kamu yakin ?",
            icon: 'warning',
            text: "Data Tidak akan bisa dikembalikan jika sudah dihapus!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Iya, Lanjut !",
            cancelButtonText: "Tidak, Kembali !",
        }).then((Deleted) => {
            if(Deleted.value == true){
                $.ajax({
                    url : this.link+'/delete',
                    type : 'POST',
                    data : {'id':id},
                    cache: false,
                    success:function(data) {
                        if(data.status == 200){
                            Swal.fire({
                                title: 'Success',
                                text: 'Data Berhasil Dihapus',
                                icon: 'success',
                                confirmButtonText: 'Close'
                            });
                            aTable.ajax.reload();
                        }else{
                            Swal.fire({
                                title: 'Error!',
                                text: 'Delete Failed Please Contact Administrator',
                                icon: 'error',
                                confirmButtonText: 'Close'
                            });
                        }
                    },
                    error:function(){
                        toastr.error('Ada Kesalahan Sistem, silakan hubungi pengembang sistem');
                    }
                });
            }
        });
    }
    function Edit(id) {
        $.ajax({
            url : this.link+'/show',
            type : 'POST',
            data : {'id':id},
            cache: false,
            success:function(datas) {
                clearData();
                if(datas.status == 200){
                    console.log(datas);
                   ShowDetail(datas.data);
                }else{
                    toastr.error('Ada Kesalahan Sistem, silakan hubungi pengembang sistem');
                }
            },
            error:function(){
                toastr.error('Ada Kesalahan Sistem, silakan hubungi pengembang sistem');
            }
        });
    }
</script>
@endpush
