<!-- jQuery -->
<script src="{{asset('backend/plugins/jquery/jquery.min.js')}}" type="text/javascript"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('backend/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
<!-- ChartJS -->
<script src="{{asset('backend/plugins/chart.js/Chart.min.js')}}"type="text/javascript" ></script>
<!-- Sparkline -->
<script src="{{asset('backend/plugins/sparklines/sparkline.js')}}" type="text/javascript"></script>
<!-- JQVMap -->
<script src="{{asset('backend/plugins/jqvmap/jquery.vmap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/jqvmap/maps/jquery.vmap.usa.js')}}" type="text/javascript"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('backend/plugins/jquery-knob/jquery.knob.min.js')}}" type="text/javascript"></script>
<!-- daterangepicker -->
<script src="{{asset('backend/plugins/moment/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('backend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}" type="text/javascript"></script>
<!-- Summernote -->
<script src="{{asset('backend/plugins/summernote/summernote-bs4.min.js')}}" type="text/javascript"></script>
<!-- overlayScrollbars -->
<script src="{{asset('backend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="{{asset('backend/dist/js/adminlte.js')}}" type="text/javascript"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backend/dist/js/demo.js')}}" type="text/javascript"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('backend/dist/js/pages/dashboard.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/dist/js/sweetalert.min.js')}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
<script src="{{asset('backend/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('backend/dist/js/lightbox.min.js')}}" type="text/javascript"></script>
@stack('script')
</body>
</html>
