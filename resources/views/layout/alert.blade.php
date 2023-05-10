<!-- Toastr -->
<link type="text/css" href="{{asset('assets/plugins/toastr/toastr.min.css')}}" rel="stylesheet">
<script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>

@if ($errors->any())
    @foreach($errors->all() as $error)
        <script>
            console.log("Has Error");
            toastr.warning('{{$error}}', 'Warning!!');
        </script>
    @endforeach
@endif

@if ($message = Session::get('success'))
    <script>
        console.log('Success Alert');
        toastr.success('{{$message}}', 'Success!!');
    </script>
@endif

@if($message = Session::get('info'))
    <script>
        console.log('Info Alert');
        toastr.info('{{$message}}', 'Info!!');
    </script>
@endif

@if($message = Session::get('warning'))
    <script>
        console.log('warning alert');
        toastr.warning('{{$message}}', 'warning!!');
    </script>
@endif
