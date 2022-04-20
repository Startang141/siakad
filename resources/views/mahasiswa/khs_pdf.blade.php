@extends('mahasiswa.layout')

@section('content')
        <center>
            <h3 class="text-center mb-3">JURUSAN TEKNOLOGI INFORMASI - POLITEKNIK NEGERI MALANG</h3>
            <h3 class="text-center mb-2">KARTU HASIL STUDI (KHS)</h2>
        </center>
        <div class="container">
            <p><b>Nama : </b> {{ $nilai->mahasiswa->nama }}</p>
            <p><b>NIM : </b> {{ $nilai->mahasiswa->nim }}</p>
            <p><b>Kelas : </b> {{ $nilai->mahasiswa->kelas->nama_kelas }}</p>
        <table border="1" style="width:100%; margin:0 auto;">
        <tr>
            <th>Mata kuliah</th>
            <th>SKS</th>
            <th>Semester</th>
            <th>Nilai</th>
        </tr>
            @foreach ($nilai as $nl)
                <tr>
                    <td>{{ $nl->matakuliah->nama_matkul }}</td>&emsp
                    <td>{{ $nl->matakuliah->sks}}</td>&emsp
                    <td>{{ $nl->matakuliah->semester }}</td>&emsp
                    <td>{{ $nl->nilai }}</td>&emsp
                </tr>
            @endforeach
        </table>
@endsection