<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title')</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('img/logo-kemenag.png') }}" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('vendor/_cms/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/_cms/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <!-- Sweet Alert 2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Select2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

  <!-- Data Tables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">


  <!-- Calendar -->
  <link rel="stylesheet" href="{{ asset('vendor/_cms/css/jsCalendar.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/_cms/css/jsCalendar.micro.min.css') }}">
  <script src="{{ asset('vendor/_cms/js/jsCalendar.min.js') }}"></script>

  <!-- Template Main CSS File -->
  <link href="{{ asset('vendor/_cms/css/style.css') }}" rel="stylesheet">

  @stack('styles')


  <!-- =======================================================
    * Template Name: NiceAdmin
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Updated: Apr 20 2024 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body>

  @include('cms.partials.header')

  @include('cms.partials.sidebar')

  <main id="main" class="main">

    @yield('cms')

  </main>

  @include('cms.partials.footer')

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="{{ asset('vendor/_cms/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/_cms/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- Data Tables -->
  <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.js"></script>

  <!-- TinyMCE -->
  <script src="{{ asset('vendor/_cms/vendor/tinymce/tinymce.min.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('vendor/_cms/js/main.js') }}"></script>

  @stack('scripts')

  <!-- Custom Script -->
  <script>
    $('#tags').select2({
      theme: 'bootstrap-5',
      placeholder: 'Pilih Tag'
    });
    $('#categoryID').select2({
      theme: 'bootstrap-5'
    });
    $(document).on('click', '.hover-title', function() {
      var kodeLayanan = $(this).data('kode');

      // Fetch new data and update modal
      $.ajax({
        url: '/detail-permohonan',
        method: 'GET',
        data: {
          kode_layanan: kodeLayanan
        },
        success: function(response) {
          $('#modalKodeLayanan').text(response.kode_layanan);
          $('#modalListId').text(response.list.judul); // Assuming response includes the relationship
          $('#modalServiceId').text(response.service.judul); // Assuming response includes the relationship
          $('#modalNama').text(response.nama);
          $('#modalEmail').text(response.email);
          $('#modalPesan').text(response.pesan_pengirim);
          $('#modalBalasan').val(response.pesan_balasan);
          $('#modalDiprosesOleh').val(response.diproses_oleh);
          if (response.file_persyaratan) {
            var fileLink = '<a href="/storage/' + response.file_persyaratan +
              '" class="hover-title" target="_blank"><i class="bi bi-eye"></i> Lihat File Persyaratan</a>';
            $('#modalFilePersyaratan').html(fileLink);
          } else {
            $('#modalFilePersyaratan').text('-');
          }
          $('#modalStatus').val(response.status);

          if (response.status == 'selesai') {
            // Menjadikan input sebagai text biasa atau disabled
            $('#modalBalasan').prop('disabled', true);
            $('#modalDiprosesOleh').prop('disabled', true);
            $('#modalStatus').prop('disabled', true);
            $('#buttonText').parent().css('display', 'none');
          }
        }
      });
    });

    $('#saveChanges').on('click', function() {
      var kodeLayanan = $('#modalKodeLayanan').text();
      var diprosesOleh = $('#modalDiprosesOleh').val();
      var pesanBalasan = $('#modalBalasan').val();
      var status = $('#modalStatus').val();

      // Disable buttons and show loading spinner
      $('#saveChanges').attr('disabled', true);
      $('#closeButton').attr('disabled', true);
      $('#buttonText').addClass('d-none');
      $('#loadingSpinner').removeClass('d-none');

      $.ajax({
        url: '/update-permohonan',
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          kode_layanan: kodeLayanan,
          diproses_oleh: diprosesOleh,
          pesan_balasan: pesanBalasan,
          status: status
        },
        success: function(response) {
          // Kirim email setelah status diperbarui
          $.ajax({
            url: '/kirim-status-email',
            method: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              kode_layanan: kodeLayanan
            },
            success: function() {
              // Delay for better UX to wait for email sending completion
              setTimeout(function() {
                Swal.fire({
                  title: 'Berhasil!',
                  text: 'Permohonan layanan ' + kodeLayanan + ' telah diperbarui.',
                  icon: 'success'
                }).then(() => {
                  $('#staticBackdrop').modal('hide');
                  window.location.reload();
                });
              }, 2000); // Delay for 2 seconds
            },
            error: function(response) {
              Swal.fire('Gagal!', 'Gagal mengirim email.', 'error');
            },
            complete: function() {
              // Re-enable the buttons and hide the loading spinner
              setTimeout(function() {
                $('#saveChanges').attr('disabled', false);
                $('#closeButton').attr('disabled', false);
                $('#buttonText').removeClass('d-none');
                $('#loadingSpinner').addClass('d-none');
              }, 2000); // Synchronize the loading spinner with the Swal alert
            }
          });
        },
        error: function(response) {
          Swal.fire('Gagal!', 'Gagal memperbarui data', 'error');
          // Re-enable the buttons and hide the loading spinner in case of error
          setTimeout(function() {
            $('#saveChanges').attr('disabled', false);
            $('#closeButton').attr('disabled', false);
            $('#buttonText').removeClass('d-none');
            $('#loadingSpinner').addClass('d-none');
          }, 2000); // Synchronize the loading spinner with the Swal alert
        }
      });
    });
  </script>

  <script>
    new DataTable('#datatables', {
      ordering: false,
      language: {
        search: "Cari:",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
        infoFiltered: " (memfilter dari _MAX_ total data)",
        emptyTable: "Tidak ada data",
        zeroRecords: "Tidak ada data yang cocok",
        lengthMenu: "Menampilkan _MENU_ data",
      }
    });
  </script>

</body>

</html>
