<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4F81BD;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Data Kontak</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Kontak</th>
                <th>Nama Kontak</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Nama Perusahaan</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($kontak as $data)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data->jenis_kontak }}</td>
                <td>{{ $data->nama_kontak }}</td>
                <td>{{ $data->email }}</td>
                <td>{{ $data->no_hp }}</td>
                <td>{{ $data->nm_perusahaan }}</td>
                <td>{{ $data->alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
