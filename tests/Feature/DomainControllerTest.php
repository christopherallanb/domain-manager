<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Domain;
use App\Models\User;

class DomainControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_domains()
    {
        Domain::factory()->create(['name' => 'example.com']);
        $this->actingAs(User::factory()->create());
        $resp = $this->get(route('domains.index'));
        $resp->assertStatus(200);
        $resp->assertSee('example.com');
    }

    public function test_store_creates_domain()
    {
        $this->actingAs(User::factory()->create());
        $data = [
            'name' => 'newdomain.test',
            'registrar' => 'RegistrarX',
            'expiration_date' => now()->addYear()->format('Y-m-d'),
            'annual_cost' => 12.34,
        ];
        $resp = $this->post(route('domains.store'), $data);
        $resp->assertRedirect(route('domains.index'));
        $this->assertDatabaseHas('domains', ['name' => 'newdomain.test']);
    }

    public function test_update_domain()
    {
        $this->actingAs(User::factory()->create());
        $d = Domain::factory()->create();
        $resp = $this->put(route('domains.update', $d), [
            'name' => $d->name,
            'registrar' => 'NewReg',
            'expiration_date' => now()->addMonth()->format('Y-m-d'),
        ]);
        $resp->assertRedirect(route('domains.index'));
        $this->assertDatabaseHas('domains', ['id' => $d->id, 'registrar' => 'NewReg']);
    }

    public function test_destroy_domain()
    {
        $this->actingAs(User::factory()->create());
        $d = Domain::factory()->create();
        $resp = $this->delete(route('domains.destroy', $d));
        $resp->assertRedirect(route('domains.index'));
        $this->assertDatabaseMissing('domains', ['id' => $d->id]);
    }

    public function test_renew_domain()
    {
        $this->actingAs(User::factory()->create());
        $d = Domain::factory()->create(['expiration_date' => now()->subYear()]);
        $resp = $this->post(route('domains.renew', $d), ['new_date' => now()->addYear()->format('Y-m-d')]);
        $resp->assertRedirect();
        $this->assertDatabaseMissing('domains', ['id' => $d->id, 'expiration_date' => $d->expiration_date]);
    }
}
