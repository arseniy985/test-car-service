<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Configuration;
use App\Models\Option;
use App\Models\Price;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create cars
        $toyota = Car::create(['name' => 'Toyota Camry']);
        $honda = Car::create(['name' => 'Honda Accord']);
        $bmw = Car::create(['name' => 'BMW 3 Series']);
        
        // Create options
        $climateControl = Option::create(['name' => 'Climate Control']);
        $leatherSeats = Option::create(['name' => 'Leather Seats']);
        $sunroof = Option::create(['name' => 'Sunroof']);
        $navigationSystem = Option::create(['name' => 'Navigation System']);
        $heatedSeats = Option::create(['name' => 'Heated Seats']);
        
        // Create configurations for Toyota
        $toyotaComfort = Configuration::create([
            'car_id' => $toyota->id,
            'name' => 'Comfort'
        ]);
        $toyotaComfort->options()->attach([$climateControl->id, $leatherSeats->id]);
        
        $toyotaPremium = Configuration::create([
            'car_id' => $toyota->id,
            'name' => 'Premium'
        ]);
        $toyotaPremium->options()->attach([$climateControl->id, $leatherSeats->id, $sunroof->id]);
        
        // Create configurations for Honda
        $hondaSport = Configuration::create([
            'car_id' => $honda->id,
            'name' => 'Sport'
        ]);
        $hondaSport->options()->attach([$climateControl->id, $leatherSeats->id]);
        
        $hondaTuring = Configuration::create([
            'car_id' => $honda->id,
            'name' => 'Touring'
        ]);
        $hondaTuring->options()->attach([$climateControl->id, $leatherSeats->id, $navigationSystem->id]);
        
        // Create configurations for BMW
        $bmwBase = Configuration::create([
            'car_id' => $bmw->id,
            'name' => 'Base'
        ]);
        $bmwBase->options()->attach([$climateControl->id, $leatherSeats->id]);
        
        $bmwLuxury = Configuration::create([
            'car_id' => $bmw->id,
            'name' => 'Luxury'
        ]);
        $bmwLuxury->options()->attach([$climateControl->id, $leatherSeats->id, $sunroof->id, $navigationSystem->id, $heatedSeats->id]);
        
        // Add prices for Toyota configurations
        Price::create([
            'configuration_id' => $toyotaComfort->id,
            'price' => 35000,
            'start_date' => now()->subMonth(),
            'end_date' => null
        ]);
        
        Price::create([
            'configuration_id' => $toyotaPremium->id,
            'price' => 40000,
            'start_date' => now()->subMonth(),
            'end_date' => null
        ]);
        
        // Add prices for Honda configurations
        Price::create([
            'configuration_id' => $hondaSport->id,
            'price' => 32000,
            'start_date' => now()->subMonth(),
            'end_date' => null
        ]);
        
        Price::create([
            'configuration_id' => $hondaTuring->id,
            'price' => 38000,
            'start_date' => now()->subMonth(),
            'end_date' => null
        ]);
        
        // Add prices for BMW configurations
        Price::create([
            'configuration_id' => $bmwBase->id,
            'price' => 45000,
            'start_date' => now()->subMonth(),
            'end_date' => null
        ]);
        
        Price::create([
            'configuration_id' => $bmwLuxury->id,
            'price' => 55000,
            'start_date' => now()->subMonth(),
            'end_date' => null
        ]);
        
        // Add an expired price for Toyota Comfort to test filtering
        Price::create([
            'configuration_id' => $toyotaComfort->id,
            'price' => 33000,
            'start_date' => now()->subMonths(3),
            'end_date' => now()->subMonths(2)
        ]);
    }
} 