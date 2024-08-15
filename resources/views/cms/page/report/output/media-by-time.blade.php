<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Media Berdasarkan Waktu</title>
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
  <h2 style="text-align: center">Laporan Media Berdasarkan Waktu</h2>
  <span>Total: <strong>{{ $medias->count() }} Media</strong></span>
  <table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th>No.</th>
        <th>Gambar</th>
        <th>Judul</th>
        <th>Kategori</th>
        <th>Penting</th>
        <th>Dibaca</th>
        <th>Dibuat</th>
        <th>Diupload Oleh</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($medias as $media)
        <tr>
          <td style="text-align: center;">{{ $loop->iteration }}</td>
          <td align="center"><img src="{{ public_path('storage/' . $media->gambar) }}" alt="" width="80px"
              height="60px">
            @foreach ($media->tags as $tag)
              <div style="font-size: 12px; text-align: center;">
                #{{ $tag->nama_tag }}</div>
            @endforeach
          </td>
          <td>{{ $media->judul }}</td>
          <td style="text-align: center;">{{ $media->category->nama_kategori }}</td>
          <td style="text-align: center;">{{ $media->penting == 1 ? 'Ya' : 'Tidak' }}</td>
          <td style="text-align: center;">{{ $media->jumlah_dibaca }} Kali</td>
          <td style="text-align: center;">{{ $media->created_at->translatedFormat('d F Y H:i') . ' WITA' }}</td>
          <td style="text-align: center;">{{ $media->user->username }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
