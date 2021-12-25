<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Camera;

class CameraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $test_camera_1 = Camera::create([
            'name' => 'MainGate1',
            'traffic_direction' => 'inbound',
            'latitude' => '3.547966172323439',
            'longitude' => '103.43754957238477',
        ]);
        $token_1 = $test_camera_1->createToken('camera-access-token', ['camera'])->plainTextToken;
        $test_camera_1->plain_text_token = $token_1;
        $test_camera_1->save();

        $test_camera_2 = Camera::create([
            'name' => 'MainGate2',
            'traffic_direction' => 'outbound',
            'latitude' => '3.547966172323439',
            'longitude' => '103.43754957238477',
        ]);
        $token_2 = $test_camera_2->createToken('camera-access-token', ['camera'])->plainTextToken;
        $test_camera_2->plain_text_token = $token_2;
        $test_camera_2->save();

        $test_cameras_bulk = Camera::factory()->count(20)->create();
        foreach ($test_cameras_bulk as $camera) {
            $token = $camera->createToken('camera-access-token', ['camera'])->plainTextToken;
            $camera->plain_text_token = $token;
            $camera->save();
        }

    }
}
