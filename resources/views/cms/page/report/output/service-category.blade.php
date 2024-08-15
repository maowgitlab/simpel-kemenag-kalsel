<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Layanan Berdasarkan Kategori</title>
</head>

<body>
  <table width="100%">
    <tr>
      <td><img src="{{ public_path('img/logo-kemenag.png') }}" width="100px" height="100px"></td>
      <td>
        <center>
          <font size="5">KEMENTERIAN AGAMA REPUBLIK INDONESIA</font> <br>
          <font size="4">KANTOR WILAYAH KEMENTERIAN AGAMA</font> <br>
          <font size="4">PROVINSI KALIMANTAN SELATAN</font> <br>
          <font size="2">Jl. D.I. Panjaitan No. 19 Banjarmasin</font> <br>
          <font size="2">Telepon (0511) 3353150 - 3353472; Faxmile (0511) 3353437</font> <br>
          <font size="2">Website: www.kalsel.kemenag.go.id</font> <br>
        </center>
      </td>
    </tr>
  </table>
  <hr style="border: 2px double black">
  <h2 style="text-align: center">Laporan Layanan Berdasarkan Kategori</h2>
  <span>Total: <strong>{{ $listServices->count() }} Permhonan</strong></span>
  <table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th>No.</th>
        <th>Kategori Layanan</th>
        <th>Total Layanan</th>
        <th>Total Pemohon</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($listServices as $listService)
      <tr>
        <td class="align-middle">{{ $loop->iteration }}</td>
        <td class="align-middle" style="text-align: center">{{ $listService->judul }}</td>
        <td class="align-middle" style="text-align: center">{{ $listService->services->count() }} Layanan Aktif</td>
        <td class="align-middle" style="text-align: center">{{ $listService->applicants->count() }} Permohonan</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
