

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<script>
  $(function() {
    $('.custom-dropdown').on('click', function() {

        $('.dropdown-menu').show();
        // $(this).next('.dropdown-menu').find('li').toggle();
    });
  });
</script>
<!-- Vendor JS Files -->

<script src="{{ URL::asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Template Main JS File -->
<script src="{{ URL::asset('assets/js/main.js') }}"></script>

</body>

</html>
