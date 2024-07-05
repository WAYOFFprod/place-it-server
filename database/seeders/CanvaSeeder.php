<?php

namespace Database\Seeders;

use App\Models\Canva;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CanvaSeeder extends Seeder
{

    private $accessTypes = [
        'open',
        'request_only',
        'closed'
    ];

    private $types = [
        'pixelwar',
        'artistic',
        'free'
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();


        foreach ($users as $key => $user) {
            $this->shiftArrays();
            $this->createCanvaForUser($user);
            $this->createPublicCanvas($user);
        }

        foreach ($users as $key => $user) {
            $this->participateInHalfCanvas($user);
        }
    }

    private function createCanvaForUser($user) {
        $canvas = Canva::factory()
            ->count(3)
            ->sequence(
                ['name' => $user->name.'\'s First Canva'],
                ['name' => $user->name.'\'s Second Canva'],
                ['name' => $user->name.'\'s Thrid Canva'],
            )
            ->sequence(
                ['category' => $this->types[0]],
                ['category' => $this->types[1]],
                ['category' => $this->types[2]],
            )
            ->sequence(
                ['access' => $this->accessTypes[0]],
                ['access' => $this->accessTypes[1]],
                ['access' => $this->accessTypes[2]],
            )
            ->sequence(
                ['visibility' => 'public'],
                ['visibility' => 'friends_only'],
                ['visibility' => 'private'],
            )
            ->create();

        foreach ($canvas as $key => $canva) {
            $user->participates()->attach($canva->id,['status' => 'accepted']);
            $imageCreated = ImageService::createImage($canva->id, $canva->width, $canva->height);
        }
        $user->canvas()->saveMany($canvas);
    }

    private function createPublicCanvas($user) {
        $canvas = Canva::factory()
            ->count(3)
            ->sequence(
                ['name' => $user->name.'\'s  public Canva'],
            )
            ->sequence(
                ['category' => $this->types[0]],
                ['category' => $this->types[1]],
                ['category' => $this->types[2]],
            )
            ->sequence(
                ['access' => 'open'],
                ['access' => 'request_only'],
            )
            ->sequence(
                ['visibility' => 'public'],
            )
            ->create();

        foreach ($canvas as $key => $canva) {
            $user->participates()->attach($canva->id,['status' => 'accepted']);
            $imageCreated = ImageService::createImage($canva->id, $canva->width, $canva->height);
        }
        $user->canvas()->saveMany($canvas);
    }

    private function participateInHalfCanvas(User $user) {
        $canvas = Canva::community()->get();
        for ($i=0; $i < round($canvas->count() / 2); $i++) {
            $canva = $canvas->random();
            $user->participates()->attach($canva->id, ['status' => 'accepted']);
        }
    }

    private function shiftArrays() {
        $type = array_shift($this->types);
        $this->types[] = $type;

        $accessType = array_shift($this->accessTypes);
        $this->accessTypes[] = $accessType;
    }
}
