<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Illuminate\Support\Facades\Process;

class TrafficController extends Controller


{

    protected static array $trafficPlaces = [
        ['cameraId' => '2701', 'name' => 'Woodlands Checkpoint'],
        ['cameraId' => '4713', 'name' => 'Tuas Checkpoint'],
    ];

    public function index()
    {

        $response = Http::withHeaders([
                'AccountKey' => config('lta.api_key'),
                'accept' => 'application/json'
            ]

        )->get('http://datamall2.mytransport.sg/ltaodataservice/Traffic-Imagesv2');


        $data = $response->json()['value'];
        $trafficImages = array_map(function ($trafficPlace) {

            foreach (self::$trafficPlaces as $selectedTp)
                if ($trafficPlace['CameraID'] === $selectedTp['cameraId']) {
                    $trafficPlace['name'] = $selectedTp['name'];
                    return $trafficPlace;
                }
        }, $data);

        $finalImages = [];
        foreach (array_filter($trafficImages) as $tp) {
            $encodedImage = base64_encode(Http::get($tp['ImageLink'])->body());
            $finalImages[] = ['name' => $tp['name'], 'ImageLink' => $encodedImage];

            $res = Http::withUrlParameters([

                "model" => "llava",
                "prompt" => "how many cars are there?",
                "images" => [$encodedImage]
            ])->dd('http://localhost:11434/api/generate/');
        }

        return Inertia::render('Traffic/Index', [
            'trafficImages' => $finalImages,
            'status' => session('status'),
        ]);


    }
}
