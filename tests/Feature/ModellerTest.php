<?php

namespace Tests\Feature;

use App\Models\Draw;
use App\Models\DrawnFisher;
use App\Models\Guest;
use App\Models\Pool;
use App\Models\Room;
use App\Models\Stay;
use App\Models\ValdInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModellerTest extends TestCase
{
    use RefreshDatabase;

    public function test_opphold_med_rom_gjest_og_folge(): void
    {
        $hans = Guest::create(['name' => 'Hans']);
        $kari = Guest::create(['name' => 'Kari']);
        $rom = Room::create(['name' => 'Hytte nord']);
        $stay = Stay::create([
            'label' => 'Hans m.fl.', 'arrival' => '2026-07-04', 'departure' => '2026-07-11',
            'guest_id' => $hans->id,
        ]);
        $stay->rooms()->attach($rom);
        $stay->companions()->attach($kari);

        $stay = Stay::with(['guest', 'rooms', 'companions'])->first();
        $this->assertSame('Hans', $stay->guest->name);
        $this->assertSame(['Hytte nord'], $stay->rooms->pluck('name')->all());
        $this->assertSame(['Kari'], $stay->companions->pluck('name')->all());
        $this->assertSame(1, $kari->companionStays()->count());
        $this->assertSame('2026-07-04', $stay->arrival->toDateString());
        $this->assertSame('confirmed', $stay->status);
        $this->assertSame(2, $stay->fishers);
    }

    public function test_pool_trekning_og_primaernokler(): void
    {
        $vald = ValdInfo::create(['elveguiden_id' => 7, 'slug' => 'storholen', 'name' => 'Storhølen', 'place' => 'Gaula']);
        $pool = Pool::create(['vald_id' => 7, 'name' => 'Gapahuk']);
        $this->assertSame('Storhølen', $pool->vald->name);
        $this->assertSame(1, $vald->pools()->count());

        $draw = Draw::create(['week_start' => '2026-07-04']);
        DrawnFisher::create(['draw_id' => $draw->id, 'number' => 1, 'name' => 'Hans']);
        $this->assertSame(1, $draw->fishers()->count());
    }
}
