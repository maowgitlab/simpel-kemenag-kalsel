<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Status Permohonan Anda Telah Diperbarui</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      color: #333;
      line-height: 1.6;
    }

    .container {
      width: 100%;
      max-width: 600px;
      margin: 20px auto;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
      background-color: #4CAF50;
      color: #fff;
      padding: 10px;
      text-align: center;
      border-radius: 5px 5px 0 0;
    }

    .content {
      padding: 20px;
    }

    .content p {
      margin: 0 0 15px;
    }

    .content ul {
      list-style: none;
      padding: 0;
    }

    .content ul li {
      padding: 5px 0;
    }

    .content ul li a {
      color: #4CAF50;
      text-decoration: none;
    }

    .content ul li a:hover {
      text-decoration: underline;
    }

    .footer {
      text-align: center;
      padding: 10px;
      font-size: 0.9em;
      color: #777;
      border-top: 1px solid #ddd;
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h2>Status Permohonan Anda Telah Diperbarui</h2>
    </div>
    <div class="content">
      <p>Halo, {{ $applicant->nama }}</p>
      <p>Status permohonan Anda dengan kode layanan <strong>{{ $applicant->kode_layanan }}</strong> telah diperbarui
        menjadi <strong>{{ $applicant->status }}</strong>.</p>
      <p>Detail permohonan:</p>
      <ul>
        <li><strong>Kode Layanan:</strong> {{ $applicant->kode_layanan }}</li>
        <li><strong>Kategori Layanan:</strong> {{ $applicant->list->judul }}</li>
        <li><strong>Layanan:</strong> {{ $applicant->service->judul }}</li>
        <li><strong>Nama Pemohon:</strong> {{ $applicant->nama }}</li>
        <li><strong>Email Aktif:</strong> {{ $applicant->email }}</li>
        <li><strong>Pesan Pemohon:</strong> {{ $applicant->pesan_pengirim }}</li>
        <li><strong>Pesan Balasan:</strong> {{ $applicant->pesan_balasan }}</li>
        <li><strong>Diproses Oleh:</strong> {{ $applicant->diproses_oleh }}</li>
        <li><strong>File Persyaratan:</strong> <a
            href="{{ $applicant->file_persyaratan == null ? '#' : asset('storage/' . $applicant->file_persyaratan) }}">{{ $applicant->file_persyaratan == null ? '-' : 'Lihat' }}</a>
        </li>
        <li><strong>Status:</strong> {{ $applicant->status }}</li>
      </ul>
      <p>
        Terima kasih telah menggunakan layanan kami.
        @if ($applicant->status == 'selesai')
          Dan jangan lupa kasih rating bintang 5 ya di <a href="{{ route('service.show') }}">Periksa Status Layanan</a>
          :)
        @endif
      </p>
    </div>
    <div class="footer">
      &copy; 2024 Kantor Kemenag Kalsel. All rights reserved.
    </div>
  </div>
</body>

</html>
