<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Kategori Media Terbanyak</title>
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
  <h2 style="text-align: center">Laporan Kategori Media Terbanyak</h2>
  <span>Total: <strong>{{ $categories->count() }} Kategori</strong></span>
  <table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th>No.</th>
        <th>Kategori Media</th>
        <th>Jumlah Dilihat</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($categories as $category)
        <tr>
          <td style="text-align: center;">{{ $loop->iteration }}</td>
          <td style="text-align: center;">{{ $category->nama_kategori }}</td>
          <td style="text-align: center;">{{ $category->jumlah_dibaca . ' Kali' }}</td>
          <td style="text-align: center;">{{ $category->medias->count() . ' Media' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
