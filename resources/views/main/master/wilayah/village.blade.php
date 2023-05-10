@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />

@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Master Wilayah</a></li>
        <li class="breadcrumb-item active" aria-current="page">Village</li>
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
                    <th>Province Name</th>
                    <th>City Name</th>
                    <th>District Name</th>
                    <th>Village Name</th>
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
            <input type="hidden" name="id" id="id">
            <div class="form-group mb-2">
                <label for="provinceSelect" class="mb-0 required">Province</label>
                <select id="provinceSelect" required class="select2 w-100 @error('province_id') is-invalid @enderror">
                    <option value="" selected>&nbsp;</option>

                </select>
                @error('province_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-2">
                <label for="citySelect" class="mb-0 required">City</label>
                <select id="citySelect" required class="select2 w-100 @error('city_id') is-invalid @enderror">
                    <option value="" selected>&nbsp;</option>

                </select>
                @error('city_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-2">
                <label for="districtSelect" class="mb-0 required">District</label>
                <select name="district_id" id="districtSelect" required class="select2 w-100 @error('district_id') is-invalid @enderror">
                    <option value="" selected>&nbsp;</option>

                </select>
                @error('district_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-2">
                <label for="village_name" class="mb-0 required">Village Name</label>
                <input type="text" value="{{old('village_name')}}" required class="form-control @error('village_name') is-invalid @enderror" name="village_name" id="village_name" placeholder="Name Village">
                @error('village_name')
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
        var link = '/dashboard/master-wilayah/village';
        var aTable;
        var $provinceSelect = $("#provinceSelect");
        var $citySelect = $("#citySelect");
        var $districtSelect = $("#districtSelect");
        var provinceId;
        var cityId;
    $(document).ready( function () {

        $(function() {
            'use strict';
            $(function() {
                aTable = $('#dataTables').DataTable({
                    processing: true,
                    serverSide: true,
                    lengthChange:false,
                    searching:true,
                    orderCellsTop: true,
                    fixedHeader: true,
                    ajax: {
                        url:link+'/get-data',
                    },
                    columnDefs: [
                        { className: "text-center",targets:'_all'}
                    ],
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex',autoWidth: true,orderable:false,searchable:false},
                        { data: 'district.city.province.province_name', name: 'district.city.province.province_name',autoWidth: true },
                        { data: 'district.city.city_name', name: 'district.city.city_name',autoWidth: true },
                        { data: 'district.district_name', name: 'district.district_name',autoWidth: true },
                        { data: 'village_name', name: 'village_name',autoWidth: true },
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
            // Init Select2
            if ($("#provinceSelect").length) {
                $("#provinceSelect").select2({
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
                                    return {id:obj.id,text:obj.province_name}
                                })
                            };
                        }
                    },
                }).on("select2:select",function(e){
                    provinceId = e.params.data.id;
                    if(provinceId !== ''){
                        $citySelect.select2('enable');
                    }else{
                        $citySelect.select2('disable');
                    }
                });
            }
            if ($("#citySelect").length) {
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
                                    return {id:obj.id,text:obj.city_name}
                                })
                            };
                        }
                    },
                }).on("select2:select",function(e){
                    cityId = e.params.data.id;
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
                                province_id:provinceId,
                                city_id:cityId,
                                name:param.term,
                            }
                            return query;
                        },
                        processResults:function(data){
                            return {
                                results: $.map(data,function(obj){
                                    return {id:obj.id,text:obj.district_name}
                                })
                            };
                        }
                    },
                });
            }
            if($citySelect.data('select2')){
                $citySelect.select2("enable",false);
            }
            if($districtSelect.data('select2')){
                $districtSelect.select2("enable",false);
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
                $.ajax({
                    url : link+'/delete',
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
