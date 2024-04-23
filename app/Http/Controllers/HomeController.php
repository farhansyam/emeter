<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Exception\Auth\RevokedIdToken;
use Khill\Lavacharts\Lavacharts;

class HomeController extends Controller

{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/firesmoke.json')
            ->withDatabaseUri('https://firesmoke-detection-default-rtdb.asia-southeast1.firebasedatabase.app');

        $this->auth = $factory->createAuth();
        $this->database = $factory->createDatabase();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total = $this->database->getReference('admin')->getValue();
        $ref = $this->database->getReference('admin')->getValue();
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/firesmoke.json');
        $voltage = $ref['volage'];
        $current = $ref['current'];
        $energy = $ref['energy'];
        $energy = number_format($energy, 2);
        $power = $ref['power'];
        $Va = $ref['Va'];
        $VAR = $ref['VAR'];
        $biaya = $energy * 13520;
        return view('home', compact('voltage', 'current', 'energy', 'power', 'Va', 'VAR', 'biaya'));
    }

    function api()
    {
        // Mengambil nilai dari database untuk referensi 'admin'
        $adminRef = $this->database->getReference('admin')->getValue();

        // Menginisialisasi Factory dengan Service Account
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/firesmoke.json');

        // Mengambil nilai 'api' dan 'asap' dari referensi 'admin'
        $api = $adminRef['api'];
        $asap = $adminRef['asap'];

        // Mengembalikan nilai atau melakukan operasi tertentu sesuai kebutuhan
        return ($adminRef);
    }

    function statistik2()
    {
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/firesmoke.json');
        $firestore = $factory->createFirestore();

        $monitor = $firestore->database()->collection('admin/lab-1/update-harian')->orderby('jam', 'ASC')->limit(7)->documents(); //FireStoreClient Object
        foreach ($monitor as $k)
            $tanggal[] = $k->data()['jam'];
        foreach ($monitor as $k)
            $energy[] = $k->data()['energy'];

        $convertedDates = [];

        // Loop melalui setiap elemen dalam array tanggal
        foreach ($tanggal as $date) {
            // Mengonversi tanggal ke format "tahun-bulan-tanggal"
            $convertedDate = date('Y-m-d', strtotime($date));
            // Menambahkan tanggal yang telah dikonversi ke dalam array baru
            $convertedDates[] = $convertedDate;
        }

        foreach ($energy as $date) {
            // Mengonversi tanggal ke format "tahun-bulan-tanggal"
            $ce = number_format($date, 3);
            // Menambahkan tanggal yang telah dikonversi ke dalam array baru
            $cee[] = $ce;
        }
        
        return view('statistik', compact('convertedDates', 'cee'));
    }


    public function statistik()
    {
        $total = $this->database->getReference('log')->getValue();
        $ref = $this->database->getReference('log')->getValue();
        $asap = $ref['asap'];

        // Inisialisasi Lavacharts
        $lava = new Lavacharts;

        // Buat data tabel
        $data = $lava->DataTable();
        $data->addStringColumn('Category')
            ->addNumberColumn('Value')
            ->addRow(['API', $api])
            ->addRow(['Asap', $asap]);

        // Buat chart dengan Lavacharts
        $chart = $lava->ColumnChart('Statistik', $data, [
            'title' => 'Statistik API dan Asap',
            // Tambahan konfigurasi lainnya sesuai kebutuhan
        ]);

        return view('statistik', compact('chart'));
    }
}
