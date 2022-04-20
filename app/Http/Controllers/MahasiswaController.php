<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Mahasiswa_MataKuliah;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use PDF;

class MahasiswaController extends Controller
{
    public function nilai($id)
    {
        $daftar = Mahasiswa_MataKuliah::with("matakuliah")->where("mahasiswa_id", $id)->get();
        $daftar->mahasiswa = Mahasiswa::with('kelas')->where('id_mahasiswa', $id)->first();
        return view('mahasiswa.detailNilai', compact('daftar'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fungsi eloquent menampilkan data menggunakan pagination
        $mahasiswa = Mahasiswa::with('kelas')->get(); 
        // Mengambil semua isi tabel
        $paginate = Mahasiswa::orderBy('id_mahasiswa','asc')->paginate(5); 
        return view('mahasiswa.index', ['mahasiswa' => $mahasiswa,'paginate'=>$paginate]);
    }

    public function search(Request $request)
    {
        $keyword = $request->search;
        $mahasiswa = Mahasiswa::where('nim', 'like', "%" . $keyword . "%")
                                ->orwhere('nama','like',"%". $keyword . "%")
                                ->orwhere('tanggal_lahir','like',"%". $keyword . "%")
                                ->orwhere('jenis_kelamin','like',"%". $keyword . "%")
                                ->orwhere('alamat','like',"%". $keyword . "%")
                                ->orwhere('email','like',"%". $keyword . "%")
                                ->orwhere('kelas','like',"%". $keyword . "%")
                                ->orwhere('jurusan','like',"%". $keyword . "%")->paginate(5);
        return view('mahasiswa.index', compact('mahasiswa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('mahasiswa.create',['kelas'=> $kelas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Tanggal_Lahir' => 'required',
            'Jenis_Kelamin' => 'required',
            'Alamat' => 'required',
            'Email' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'Foto' => 'required',
        ]);

        if($request->file('Foto')){
            $image_name = $request->file('Foto')->store('images', 'public');
        }

        $mahasiswa = new Mahasiswa;
        $mahasiswa->nim = $request->get('Nim');
        $mahasiswa->nama = $request->get('Nama');
        $mahasiswa->tanggal_lahir = $request->get('Tanggal_Lahir');
        $mahasiswa->jenis_kelamin = $request->get('Jenis_Kelamin');
        $mahasiswa->alamat = $request->get('Alamat');
        $mahasiswa->email = $request->get('Email');
        $mahasiswa->jurusan = $request->get('Jurusan');
        // $mahasiswa->save();
        $mahasiswa->foto = $image_name;

        $kelas = new Kelas;
        $kelas->id = $request->get('Kelas');

        //fungsi eloquent untuk menambah data
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
        ->with('success', 'Mahasiswa Berhasil Ditambahkan');
   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim',$nim)->first();
        return view('mahasiswa.detail', ['Mahasiswa'=>$Mahasiswa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();
        $kelas = Kelas::all();
        return view('mahasiswa.edit', compact('Mahasiswa','kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nim)
    {
        //melakukan validasi data
             //melakukan validasi database
             $request->validate([
                'Nim' => 'required',
                'Nama' => 'required',
                'Tanggal_Lahir' => 'required',
                'Jenis_Kelamin' => 'required',
                'Alamat' => 'required',
                'Email' => 'required',
                'Kelas' => 'required',
                'Jurusan' => 'required',
            ]);
    
            $mahasiswa = Mahasiswa::with('kelas')->where('nim',$nim)->first();
            $mahasiswa->nim = $request->get('Nim');
            $mahasiswa->nama = $request->get('Nama');
            $mahasiswa->tanggal_lahir = $request->get('Tanggal_Lahir');
            $mahasiswa->jenis_kelamin = $request->get('Jenis_Kelamin');
            $mahasiswa->alamat = $request->get('Alamat');
            $mahasiswa->email = $request->get('Email');
            $mahasiswa->jurusan = $request->get('Jurusan');
            
            if($mahasiswa->foto && file_exists(storage_path('app/public/'. $mahasiswa->foto))) {
                Storage::delete('public/' . $mahasiswa->foto);
            }

            $image_name = $request->file('Foto')->store('images','public');
            $mahasiswa->foto = $image_name;

            $mahasiswa->save();
    
            $kelas = new Kelas;
            $kelas ->id = $request->get('Kelas');
    
       //fungsi eloquent untuk mengupdate data inputan kita
       $mahasiswa->kelas()->associate($kelas);
       $mahasiswa->save();
    
        //jika data berhasil diupdate, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
        ->with('success', 'Mahasiswa Berhasil Diupdate');
    } 

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($nim)
    {
        //fungsi eloquent untuk menghapus data
        Mahasiswa::where('nim', $nim)->delete();
        return redirect()->route('mahasiswa.index')
        -> with('success', 'Mahasiswa Berhasil Dihapus');
    }

    public function cetak_pdf($nim)
    {
        $mhs = Mahasiswa::where('nim',$nim)->first();
        $nilai = Mahasiswa_MataKuliah::where('mahasiswa_id', $mhs->id_mahasiswa)
                                        ->with('matakuliah')
                                        ->with('mahasiswa')
                                        ->get();
        $nilai->mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();
        $pdf = PDF::loadview('mahasiswa.khs_pdf', ['nilai' =>$nilai]);
        return $pdf->stream();
    }

    }