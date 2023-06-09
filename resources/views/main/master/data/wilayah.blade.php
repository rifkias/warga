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
        <li class="breadcrumb-item active" aria-current="page">Wilayah</li>
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
                    <th>Rt</th>
                    <th>Rw</th>
                    <th>Village</th>
                    <th>District</th>
                    <th>City</th>
                    <th>Province</th>
                    <th>Postal Code</th>
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
                    <label for="provinsi" class="mb-0 required">Provinsi</label>
                    <input type="text" value="{{old('provinsi')}}" required class="form-control @error('provinsi') is-invalid @enderror" name="provinsi" id="provinsi">

                    @error('provinsi')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="kabupaten" class="mb-0 required">Kabupaten</label>
                    <input type="text" value="{{old('kabupaten')}}" required class="form-control @error('kabupaten') is-invalid @enderror" name="kabupaten" id="kabupaten">
                    @error('kabupaten')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="kecamatan" class="mb-0 required">Kecamatan</label>
                    <input type="text" value="{{old('kecamatan')}}" required class="form-control @error('kecamatan') is-invalid @enderror" name="kecamatan" id="kecamatan">
                    @error('kecamatan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="kelurahan" class="mb-0 required">Kelurahan</label>
                    <input type="text" value="{{old('kelurahan')}}" required class="form-control @error('kelurahan') is-invalid @enderror" name="kelurahan" id="kelurahan">
                    @error('kelurahan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="kode_pos" class="mb-0 required">Postal Code</label>
                    <input type="number" value="{{old('kode_pos')}}" required class="form-control @error('kode_pos') is-invalid @enderror" name="kode_pos" id="kode_pos">
                    @error('kode_pos')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label required" for="rw">RW</label>
                            <input type="number" value="{{old('rw')}}" required class="form-control  @error('rw') is-invalid @enderror" id="rw" name="rw">
                            @error('rw')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label" for="rt">RT</label>
                            <input type="number" value="{{old('rt')}}" class="form-control  @error('rt') is-invalid @enderror" id="rt" name="rt">
                            @error('rt')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                    </div>
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
  <script src="{{ asset('assets/js/file-upload.js') }}"></script>

@endpush

@push('custom-scripts')
<script>
        var link = '/dashboard/config/wilayah';
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
                        { data: 'rt', name: 'rt',autoWidth: true },
                        { data: 'rw', name: 'rw',autoWidth: true },
                        { data: 'kelurahan', name: 'kelurahan',autoWidth: true },
                        { data: 'kecamatan', name: 'kecamatan',autoWidth: true },
                        { data: 'kabupaten', name: 'kabupaten',autoWidth: true },
                        { data: 'provinsi', name: 'provinsi',autoWidth: true },
                        { data: 'kode_pos', name: 'kode_pos',autoWidth: true },
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
        });
        @if(count($errors) > 0)
            @if(old('id'))
                $('#dataModalTitle').text("Edit Data");
                $('#formData').attr('action',this.link+'/update');
            @else
                $('#dataModalTitle').text("Add Data");
                $('#formData').attr('action',this.link+'/add');
            @endif
            $('#dataModal').modal('show');
        @endif
    });
    function clearData(){
        $('#dataModalTitle').text("Add Data");
        $('#formData').attr('action',this.link+'/add');
        $('#provinsi').val('');
        $('#kabupaten').val('');
        $('#kecamatan').val('');
        $('#kelurahan').val('');
        $('#kode_pos').val('');
        $('#rw').val('');
        $('#rt').val('');
        $('#id').val('');
    }
    function ShowDetail(data){
        $('#formData').attr('action',this.link+'/update');
        $('#dataModalTitle').text("Edit Data");
        $('#provinsi').val(data.provinsi);
        $('#kecamatan').val(data.kecamatan);
        $('#kabupaten').val(data.kabupaten);
        $('#kelurahan').val(data.kelurahan);
        $('#kode_pos').val(data.kode_pos);
        $('#rw').val(data.rw);
        $('#rt').val(data.rt);
        $('#id').val(data.id);
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
