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
                    <label for="provinceSelect" class="mb-0 required">Provinsi</label>
                    <select id="provinceSelect" name="provinsi" required class="select2 w-100 @error('provinsi') is-invalid @enderror">
                        <option value="" selected>&nbsp;</option>

                    </select>
                    @error('provinsi')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="citySelect" class="mb-0 required">Kabupaten</label>
                    <select id="citySelect" name="kabupaten" required class="select2 w-100 @error('kabupaten') is-invalid @enderror">
                        <option value="" selected>&nbsp;</option>

                    </select>
                    @error('kabupaten')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="districtSelect" class="mb-0 required">Kecamatan</label>
                    <select name="kecamatan" id="districtSelect" required class="select2 w-100 @error('kecamatan') is-invalid @enderror">
                        <option value="" selected>&nbsp;</option>

                    </select>
                    @error('kecamatan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="villageSelect" class="mb-0 required">Kelurahan</label>
                    <select name="kelurahan" id="villageSelect" required class="select2 w-100 @error('kelurahan') is-invalid @enderror">
                        <option value="" selected>&nbsp;</option>

                    </select>
                    @error('kelurahan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2">
                    <label for="kode_pos" class="mb-0 required">Postal Code</label>
                    <input type="text" value="{{old('kode_pos')}}" required class="form-control @error('kode_pos') is-invalid @enderror" name="kode_pos" id="kode_pos">
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
                            <input type="text" value="{{old('rw')}}" required class="form-control  @error('rw') is-invalid @enderror" id="rw" name="rw">
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
                            <input type="text" value="{{old('rt')}}" class="form-control  @error('rt') is-invalid @enderror" id="rt" name="rt">
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
        var provinceId;
        var cityId;
        var districtId;
        var $provinceSelect = $("#provinceSelect");
        var $citySelect = $("#citySelect");
        var $districtSelect = $("#districtSelect");
        var $villageSelect = $("#villageSelect");
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
            if ($(".select2").length) {
                $(".select2").select2({
                    width:'100%'
                });
            }
            if ($provinceSelect.length) {
                $provinceSelect.select2({
                    width:'100%',
                    delay:1800,
                    minimumInputLength:3,
                    selectOnClose: true,
                    ajax:{
                        url:'/dashboard/master-wilayah/get-province',
                        dataType: 'json',
                        quietMillis: 100,
                        data:function(param){
                            var query ={
                                name:param.term,
                            }
                            return query;
                        },
                        processResults:function(data){
                            return {
                                results: $.map(data,function(obj){
                                    return {id:obj.province_name,text:obj.province_name,dataId:obj.id}
                                })
                            };
                        }
                    },
                }).on("select2:select",function(e){
                    provinceId = e.params.data.dataId;
                    if(provinceId !== ''){
                        $citySelect.select2('enable');
                    }else{
                        $citySelect.select2('disable');
                    }
                });
            }
            if ($citySelect.length) {
                $("#citySelect").select2({
                    width:'100%',
                    delay:1800,
                    minimumInputLength:3,
                    ajax:{
                        url:'/dashboard/master-wilayah/get-city',
                        dataType: 'json',
                        quietMillis: 100,
                        data:function(param){
                            var query ={
                                province_id:provinceId,
                                name:param.term,
                            }
                            return query;
                        },
                        processResults:function(data){
                            return {
                                results: $.map(data,function(obj){
                                    return {id:obj.city_name,text:obj.city_name,dataId:obj.id}
                                })
                            };
                        }
                    },
                }).on("select2:select",function(e){
                    cityId = e.params.data.dataId;
                    if(cityId !== ''){
                        $districtSelect.select2('enable');
                    }else{
                        $districtSelect.select2('disable');
                    }
                });
            }
            if ($districtSelect.length) {
                $districtSelect.select2({
                    width:'100%',
                    delay:1800,
                    minimumInputLength:3,
                    ajax:{
                        url:'/dashboard/master-wilayah/get-district',
                        dataType: 'json',
                        quietMillis: 100,
                        data:function(param){
                            var query ={
                                city_id:cityId,
                                name:param.term,
                            }
                            return query;
                        },
                        processResults:function(data){
                            return {
                                results: $.map(data,function(obj){
                                    return {id:obj.district_name,text:obj.district_name,dataId:obj.id}
                                })
                            };
                        }
                    },
                }).on("select2:select",function(e){
                    districtId = e.params.data.dataId;
                    if(districtId !== ''){
                        $villageSelect.select2('enable');
                    }else{
                        $villageSelect.select2('disable');
                    }
                });;
            }
            if ($villageSelect.length) {
                $villageSelect.select2({
                    width:'100%',
                    delay:1800,
                    minimumInputLength:3,
                    ajax:{
                        url:'/dashboard/master-wilayah/get-village',
                        dataType: 'json',
                        quietMillis: 100,
                        data:function(param){
                            var query ={
                                district_id:districtId,
                                name:param.term,
                            }
                            return query;
                        },
                        processResults:function(data){
                            return {
                                results: $.map(data,function(obj){
                                    return {id:obj.village_name,text:obj.village_name}
                                })
                            };
                        }
                    },
                });
            }
            @if(!count($errors) > 0)
            // Disable Select2
                if($citySelect.data('select2')){
                    $citySelect.select2("enable",false);
                }
                if($districtSelect.data('select2')){
                    $districtSelect.select2("enable",false);
                }
                if($villageSelect.data('select2')){
                    $villageSelect.select2("enable",false);
                }
            // End Disable
            @endif
        });
        @if(count($errors) > 0)
            var oldProvinsi = "{{old('provinsi')}}";
            var oldKabupaten = "{{old('kabupaten')}}";
            var oldKecamatan = "{{old('kecamatan')}}";
            var oldKelurahan = "{{old('kelurahan')}}";
            console.log(oldProvinsi,
            oldKabupaten,
            oldKecamatan,
            oldKelurahan,
            );
            @if(old('id'))
                $('#dataModalTitle').text("Edit Data");
                $('#password').attr('required',false);
            @else
                $('#dataModalTitle').text("Add Data");
                $('#password').attr('required',true);
            @endif

            $provinceSelect
            .empty()
            .append($("<option/>")
                .val(oldProvinsi)
                .text(oldProvinsi)
            ).val(oldProvinsi)
            .trigger("change");

            $citySelect
            .empty()
            .append($("<option/>")
                .val(oldKabupaten)
                .text(oldKabupaten)
            ).val(oldKabupaten)
            .trigger("change");

            $districtSelect
            .empty()
            .append($("<option/>")
                .val(oldKecamatan)
                .text(oldKecamatan)
            ).val(oldKecamatan)
            .trigger("change");

            $villageSelect
            .empty()
            .append($("<option/>")
                .val(oldKelurahan)
                .text(oldKelurahan)
            ).val(oldKelurahan)
            .trigger("change");

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
