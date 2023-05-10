@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />

@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">System Config</a></li>
        <li class="breadcrumb-item active" aria-current="page">Menu Permission</li>
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
                            <th>Id</th>
                            <th>Menu</th>
                            <th>Role</th>
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
            <form method="POST" action="/dashboard/master-data/role/add" id="formData">
                @csrf
                <div class="form-group mb-2">
                    <label for="menu" class="mb-0">Menu</label>
                    <select name="menu_id" id="menu" class="select2 w-100 @error('menu_id') is-invalid @enderror">
                        <option value="" selected>&nbsp;</option>
                        @foreach ($menus as $item)
                            <option @if(old('menu_id') == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    @error('menu_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="role" class="mb-0">Role</label>
                    <select name="role_id" id="role" class="select2 w-100 @error('role_id') is-invalid @enderror">
                        <option value="" selected>&nbsp;</option>
                        @foreach ($roles as $item)
                            <option @if(old('role_id') == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


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

@endpush

@push('custom-scripts')
<script>
        var link = '/dashboard/config/menu-access';
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
                    ajax: {
                        url:link+'/get-data',
                    },
                    columnDefs: [
                        { className: "text-center",targets:'_all'}
                    ],
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex',autoWidth: true,orderable:false,searchable:false},
                        { data: 'menu.name', name: 'menu.name',autoWidth: true },
                        { data: 'role.name', name: 'role.name',autoWidth: true },
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
        });
        @if(count($errors) > 0)
            @if(old('id'))
                $('#dataModalTitle').text("Edit Data");
            @else
                $('#dataModalTitle').text("Add Data");
            @endif
            $('#dataModal').modal('show');
        @endif
    });
    function clearData(){
        $('#dataModalTitle').text("Add Data");
        $('#name').val('');
        $('#formData').attr('action',this.link+'/add');
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
                console.log(this.link+'/delete');
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
</script>
@endpush
