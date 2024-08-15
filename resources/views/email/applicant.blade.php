<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Permohonan Pelayanan Baru</title>
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

    .content ul li strong {
      color: #4CAF50;
    }

    .content a {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 15px;
      background-color: #4CAF50;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
    }

    .content a:hover {
      background-color: #45a049;
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
      <h2>Permohonan Pelayanan Baru</h2>
    </div>
    <div class="content">
      <p>Permohonan Anda berhasil dibuat dengan detail sebagai berikut:</p>
      <ul>
        <li><strong>Kode Layanan:</strong> {{ $applicant->kode_layanan }}</li>
        <li><strong>Nama Pemohon:</strong> {{ $applicant->nama }}</li>
        <li><strong>Email aktif:</strong> {{ $applicant->email }}</li>
        <li><strong>Pesan Pemohon:</strong> {{ $applicant->pesan_pengirim }}</li>
        <li><strong>Status:</strong> {{ $applicant->status }}</li>
      </ul>
      <p>Silahkan periksa status permohonan secara berkala.</p>
      <a href="{{ route('service.show') }}">Cek Status Permohonan</a>
    </div>
    <div class="footer">
      &copy; 2024 Kantor Kemenag Kalsel. All rights reserved.
    </div>
  </div>
</body>

</html>
