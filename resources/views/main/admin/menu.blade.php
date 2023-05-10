@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />

@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Menu</li>
        </ol>
  </nav>

  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="float-right mb-3">
                <button type="button" class="btn btn-outline-primary btn-icon-text mr-2 d-md-block">
                    <i class="btn-icon-prepend" data-feather="eye"></i>
                    Menu Map
                </button>
            </div>
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
                    <th>Name</th>
                    <th>Description</th>
                    <th>Menu Group</th>
                    <th>Status</th>
                    <th>Icons</th>
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
          <form method="POST" action="/dashboard/admin/menu/add" id="formData">
            @csrf
            <input type="hidden" name="id" id="id">
            <div class="form-group mb-2">
                <label for="name" class="mb-0 required">Name</label>
                <input type="text" value="{{old('name')}}" required class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Name">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-2">
              <label for="description" class="mb-0">Description</label>
              <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" placeholder="Description">{{old('description')}}</textarea>
              @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-2">
                <label for="MenuIcons" class="mb-0">Icons</label>
                <input type="text" value="{{old('icons')}}" class="form-control @error('icons') is-invalid @enderror" name="icons" id="MenuIcons" placeholder="Icon">
                @error('icons')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-2">
                <label for="sort" class="mb-0 required">Sort</label>
                <input type="number" required class="form-control @error('sort') is-invalid @enderror" value="{{old('sort')}}" name="sort" id="sort" placeholder="Sort">
                @error('sort')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-2">
                <label for="link" class="mb-0">Link</label>
                <input type="text" class="form-control @error('link') is-invalid @enderror" name="link" value="{{old('link')}}" id="link" placeholder="/example">
                @error('link')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-2">
                <label for="group" class="mb-0">Group</label>
                <input type="text" class="form-control @error('menu_group') is-invalid @enderror" name="menu_group" id="group" value="{{old('menu_group')}}" placeholder="Menu Group">
                @error('menu_group')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-2">
                <label for="status" required class="mb-0 required">Status</label>
                <select name="status" id="status" required class="form-control @error('status') is-invalid @enderror">
                    <option @if(old('status') == 'active') selected @endif value="active">Active</option>
                    <option @if(old('status') == 'deactive') selected @endif value="deactive">Deactive</option>
                </select>
                @error('status')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-2">
                <label for="parent" class="mb-0">Parent</label>
                <select name="parent" id="parent" class="select2 w-100 @error('parent') is-invalid @enderror">
                    <option value="" selected>&nbsp;</option>
                    @foreach ($parrentLists as $item)
                        <option @if(old('parent') == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
                @error('parent')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" onclick="submit()" class="btn btn-primary">Save</button>

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
        var link = '/dashboard/config/menu';
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
                        { data: 'name', name: 'name',autoWidth: true },
                        { data: 'description', name: 'description',autoWidth: true },
                        { data: 'menu_group', name: 'menu_group',autoWidth: true },
                        { data: 'status', name: 'status',autoWidth: true },
                        { data: 'icons', name: 'icons',autoWidth: true },
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
    function submit(){
        var form =document.getElementById('formData');
        var a = form.checkValidity();
        if(a){
            form.submit();
        }else{
            form.reportValidity();
        }
    }
    function clearData(){
        $('#dataModalTitle').text("Add Data");
        $('#name').val('');
        $('#formData').attr('action',this.link+'/add');
        $('#description').val('');
        $('#MenuIcons').val('');
        $('#sort').val('');
        $('#link').val('');
        $('#id').val('');
        $('#group').val('');
        $('#status').val('active');
        $('#parent').val('');
    }
    function ShowDetail(data){
        console.log(data);
        $('#formData').attr('action',this.link+'/update');
        $('#dataModalTitle').text("Edit Data");
        $('#name').val(data.name);
        $('#id').val(data.id);
        $('#description').val(data.description);
        $('#MenuIcons').val(data.icons);
        $('#sort').val(data.sort);
        $('#link').val(data.link);
        $('#group').val(data.menu_group);
        $('#status').val(data.status);
        $('#parent').val(data.parent).trigger('change');
        // $('#parent').select2('val',data.parent);
        $('#dataModal').modal('show');

    }
    // Ajax CRUD
    function addData(){
        clearData();
        $('#dataModal').modal('show');
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
</script>
@endpush
