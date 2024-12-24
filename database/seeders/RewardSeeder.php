<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;
use App\Models\Partner;
use App\Models\Reward;
use Illuminate\Database\Seeder;
use Faker\Factory;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rewardsPerType = 10;
        $businessTypes = ['restaurants', 'cinema', 'beauty', 'fitness'];

        foreach ($businessTypes as $businessType) {
            // Count demo images
            $directory = storage_path('demo-images/' . $businessType . '/rewards/');
            $files = File::files($directory);
            $imageCount = 0;

            foreach ($files as $file) {
                if (File::extension($file) == 'jpg') {
                    $imageCount++;
                }
            }

            // Locales
            $directory = database_path('data/demo/'.$businessType.'/rewards/');
            $files = File::files($directory);
            
            $locales = array_map(function ($file) {
                return pathinfo($file, PATHINFO_FILENAME);
            }, $files);

            // Rewards
            foreach ($locales as $locale) {
                $jsonFilePath = database_path('data/demo/'.$businessType.'/rewards/'.$locale.'.json');
                $jsonString = file_get_contents($jsonFilePath);
                $rewards[$locale] = json_decode($jsonString, true);
            }

            // Get the partners from the database
            $partners = Partner::all();

            foreach ($partners as $partner) {
                $usedKeys = [];
                $usedImages = [];

                for ($i = 0; $i < $rewardsPerType; $i++) {
                    $created_at = fake()->dateTimeBetween('-32 week', '-6 week');
                    $active_from = fake()->dateTimeBetween('-32 week', '-1 day');
                    $expiration_date = fake()->dateTimeBetween('+2 week', '+64 week');

                    // Points
                    $values = [10, 10, 25, 25, 100, 100, 100, 100, 100, 150, 200, 250, 300, 400, 500, 500, 750, 1000, 1500, 2000, 2500, 3000, 4000, 5000, 7500, 10000];
                    $points = $values[array_rand($values)];

                    // Generate a unique random key for each partner
                    do {
                        $randomKey = array_rand($rewards[$locales[0]]);
                    } while (isset($usedKeys[$partner->id]) && in_array($randomKey, $usedKeys[$partner->id]));

                    // Add the random key to the array of used keys for this partner
                    $usedKeys[$partner->id][] = $randomKey;

                    foreach ($locales as $locale) {
                        //$faker = Factory::create($locale);
                        //$title[$locale] = $faker->sentence(3);
                        //$description[$locale] = $faker->realText(128);
                        $title[$locale] = $rewards[$locale][$randomKey]['title'];
                        $description[$locale] = $rewards[$locale][$randomKey]['description'];
                    }

                    $reward = Reward::create([
                        'name' => $rewards['en_US'][$randomKey]['title'], // fake()->sentence(rand(1,2)),
                        'title' => $title,
                        'description' => $description,
                        'max_number_to_redeem' => 0,
                        'points' => $points,
                        'active_from' => $active_from,
                        'expiration_date' => $expiration_date,
                        'is_active' => true,
                        'is_undeletable' => env('APP_IS_UNEDITABLE', true),
                        'is_uneditable' => env('APP_IS_UNEDITABLE', true),
                        'number_of_times_redeemed' => 0,
                        'views' => 0,
                        'created_at' => $created_at,
                        'created_by' => $partner->id,
                    ]);

                    for ($imageNumber = 1; $imageNumber <= 3; $imageNumber++) {
                        if ($imageNumber == 1) {
                            do {
                                $image = storage_path('demo-images/' . $businessType . '/rewards/' . rand(1, $imageCount) . '.jpg');
                            } while (in_array($image, $usedImages));
                            $usedImages[] = $image;
                        } else {
                            $image = storage_path('demo-images/' . $businessType . '/rewards/' . rand(1, $imageCount) . '.jpg');
                        }
                    
                        $reward
                            ->addMedia($image)
                            ->preservingOriginal()
                            ->sanitizingFileName(function ($fileName) {
                                return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                            })
                            ->toMediaCollection('image' . $imageNumber, 'files');
                    }                    
                }
            }
        }
    }
}