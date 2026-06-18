<?php

namespace Tests\Feature;

use App\Models\BodyType;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Listing;
use App\Models\Transmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingCompareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_empty_compare_page(): void
    {
        $this->get(route('compare.index'))
            ->assertOk()
            ->assertSee('Lista porównania jest pusta');
    }

    public function test_user_can_add_and_remove_listing_from_compare_list(): void
    {
        $listing = $this->makeListing();

        $this->from(route('listings.index'))
            ->post(route('compare.store', $listing))
            ->assertRedirect(route('listings.index'));

        $this->get(route('compare.index'))
            ->assertOk()
            ->assertSee($listing->title);

        $this->from(route('compare.index'))
            ->delete(route('compare.destroy', $listing))
            ->assertRedirect(route('compare.index'));

        $this->get(route('compare.index'))
            ->assertOk()
            ->assertSee('Lista porównania jest pusta');
    }

    private function makeListing(): Listing
    {
        $user = User::factory()->create();
        $brand = Brand::create(['name' => 'Audi']);
        $model = CarModel::create([
            'brand_id' => $brand->id,
            'name' => 'A4',
        ]);
        $fuel = Fuel::create(['name' => 'Diesel']);
        $transmission = Transmission::create(['name' => 'Automatic']);
        $bodyType = BodyType::create(['name' => 'Sedan']);

        return Listing::create([
            'user_id' => $user->id,
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'fuel_id' => $fuel->id,
            'transmission_id' => $transmission->id,
            'body_type_id' => $bodyType->id,
            'title' => 'Audi A4 B9',
            'description' => 'Test listing',
            'price' => 55000,
            'status' => 'active',
            'city' => 'Kiev',
            'year' => 2020,
            'mileage' => 45000,
            'engine_capacity' => 2000,
            'power_hp' => 190,
            'views_count' => 15,
            'color' => 'Black',
        ]);
    }
}
