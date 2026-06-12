<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ArAsset;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users (Admin, Customers)
        User::updateOrCreate(
            ['email' => 'admin@modestmirror.com'],
            [
                'name' => 'Admin Owner',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $customer1 = User::updateOrCreate(
            ['email' => 'customer@modestmirror.com'],
            [
                'name' => 'Sara Ahmed',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        $customer2 = User::updateOrCreate(
            ['email' => 'fatima@modestmirror.com'],
            [
                'name' => 'Fatima Khan',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        $customer3 = User::updateOrCreate(
            ['email' => 'zainab@modestmirror.com'],
            [
                'name' => 'Zainab Bibi',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // 2. Create Categories
        $categoriesData = [
            ['name' => 'Casual Elegance', 'description' => 'Light, breathable hijabs designed for everyday sophistication and effortless wear.'],
            ['name' => 'Formal Chic', 'description' => 'Shimmering silks and soft chiffons perfect for workwear, galas, and evening events.'],
            ['name' => 'Bridal Luxury', 'description' => 'Ornately detailed satin and lace hijabs with premium craftsmanship for your special day.'],
            ['name' => 'Everyday Basics', 'description' => 'Comfortable, stretchy jerseys and cottons in standard neutral tones.'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['name']] = Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description']
                ]
            );
        }

        // 3. Products Data (8 items matching product_1.png to product_8.png)
        $productsData = [
            [
                'category' => 'Casual Elegance',
                'name' => 'Casual Sand Cotton Hijab',
                'description' => 'Crafted from the finest organic cotton, this sand beige hijab offers comfortable all-day wear with a luxury texture. Drapes beautifully and resists slipping.',
                'price' => 1200,
                'fabric' => 'Premium Cotton',
                'color' => 'Sand Beige',
                'image' => '/images/products/product_1.png',
                'overlay' => '/images/overlays/overlay_2.png',
                'scale' => 1.05,
                'offset_y' => -0.05
            ],
            [
                'category' => 'Formal Chic',
                'name' => 'Lavender Silk Chiffon Hijab',
                'description' => 'An elegant, lightweight lavender silk chiffon hijab with a subtle shimmer. Designed to elevate your formal gowns and evening ensembles.',
                'price' => 1600,
                'fabric' => 'Silk Chiffon',
                'color' => 'Soft Lavender',
                'image' => '/images/products/product_3_1781260344.jpg',
                'overlay' => '/images/overlays/overlay_4.png',
                'scale' => 1.05,
                'offset_y' => -0.05
            ],
            [
                'category' => 'Bridal Luxury',
                'name' => 'Ivory Royal Satin Hijab',
                'description' => 'A heavy, rich ivory royal satin hijab tailored for weddings and bridal events. Features a premium reflective shine that catches lights perfectly.',
                'price' => 2500,
                'fabric' => 'Royal Satin',
                'color' => 'Ivory White',
                'image' => '/images/products/product_5_1781260436.jpg',
                'overlay' => '/images/overlays/overlay_5.png',
                'scale' => 1.08,
                'offset_y' => -0.04
            ],
            [
                'category' => 'Everyday Basics',
                'name' => 'Charcoal Soft Jersey Hijab',
                'description' => 'A highly stretchable and breathable medium-weight charcoal jersey hijab. Stays in place without pins. The perfect base for active daily wear.',
                'price' => 950,
                'fabric' => 'Soft Jersey',
                'color' => 'Charcoal Coffee',
                'image' => '/images/products/product_7_1781260526.jpg',
                'overlay' => '/images/overlays/overlay_7.png',
                'scale' => 1.03,
                'offset_y' => -0.06
            ],
            [
                'category' => 'Casual Elegance',
                'name' => 'Mint Breeze Georgette Hijab',
                'description' => 'A light and airy mint green georgette hijab. Adds a refreshing pop of color to any casual style, providing premium breathability.',
                'price' => 1100,
                'fabric' => 'Georgette',
                'color' => 'Mint Green',
                'image' => '/images/products/product_2_1781260272.jpg',
                'overlay' => '/images/overlays/overlay_1.png',
                'scale' => 1.05,
                'offset_y' => -0.05
            ],
            [
                'category' => 'Formal Chic',
                'name' => 'Midnight Rose Silk Hijab',
                'description' => 'A premium Turkish silk hijab featuring a deep, elegant midnight rose floral accent. Drapes in structure and exudes sheer luxury.',
                'price' => 1950,
                'fabric' => 'Premium Silk',
                'color' => 'Midnight Rose',
                'image' => '/images/products/product_4_1781260436.jpg',
                'overlay' => '/images/overlays/overlay_3.png',
                'scale' => 1.06,
                'offset_y' => -0.05
            ],
            [
                'category' => 'Bridal Luxury',
                'name' => 'Pearl Grace Lace Hijab',
                'description' => 'Tailored for elegant modest brides, this cream-colored satin hijab features intricate lace borders around the crown and drape line.',
                'price' => 2400,
                'fabric' => 'Satin Lace',
                'color' => 'Pearl Cream',
                'image' => '/images/products/product_6_1781260490.jpg',
                'overlay' => '/images/overlays/overlay_6.png',
                'scale' => 1.08,
                'offset_y' => -0.04
            ],
            [
                'category' => 'Everyday Basics',
                'name' => 'Warm Mocha Jersey Hijab',
                'description' => 'The ultimate everyday hijab. Made from premium, super-soft modal jersey in a rich, warm mocha tone. Highly stretchable, comfortable, and elegant.',
                'price' => 850,
                'fabric' => 'Soft Jersey',
                'color' => 'Warm Mocha',
                'image' => '/images/products/product_8.png',
                'overlay' => '/images/overlays/overlay_8.png',
                'scale' => 1.03,
                'offset_y' => -0.06
            ],
        ];

        foreach ($productsData as $pData) {
            $cat = $categories[$pData['category']];
            $slug = Str::slug($pData['name']);

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $cat->id,
                    'name' => $pData['name'],
                    'description' => $pData['description'],
                    'price' => $pData['price'],
                    'fabric_type' => $pData['fabric'],
                    'color' => $pData['color'],
                    'ar_overlay_path' => $pData['overlay'],
                ]
            );

            // Create Product Primary Image record
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'is_primary' => true],
                ['image_path' => $pData['image']]
            );

            // Create AR Asset record linked to product
            ArAsset::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'name' => $pData['name'] . ' AR Overlay',
                    'overlay_image_path' => $pData['overlay'],
                    'scale_factor' => $pData['scale'],
                    'offset_y' => $pData['offset_y'],
                ]
            );
        }

        // 4. Create Mock Reviews & Star Ratings
        $products = Product::all();
        $reviewsData = [
            [
                'rating' => 5,
                'comment' => 'This hijab drapes absolutely beautifully! The fabric feels premium and it matches perfectly with warm tones.'
            ],
            [
                'rating' => 4,
                'comment' => 'Very elegant look. The texture is soft and high quality, though I need an under-cap to keep it fully in place.'
            ],
            [
                'rating' => 5,
                'comment' => 'Stunning! Tried it on using the virtual AR mirror first and it looks exactly as it does on camera.'
            ]
        ];

        $users = [$customer1, $customer2, $customer3];

        foreach ($products as $product) {
            // Give each product 1 to 2 random reviews from our customer pool
            $numberOfReviews = rand(1, 2);
            $chosenUsers = (array) array_rand($users, $numberOfReviews);
            
            foreach ($chosenUsers as $userIndex) {
                $user = $users[$userIndex];
                $rev = $reviewsData[array_rand($reviewsData)];
                
                Review::updateOrCreate(
                    ['user_id' => $user->id, 'product_id' => $product->id],
                    [
                        'rating' => $rev['rating'],
                        'comment' => $rev['comment']
                    ]
                );
            }
        }
    }
}
