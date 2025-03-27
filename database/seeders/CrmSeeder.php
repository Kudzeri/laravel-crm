<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CrmSeeder extends Seeder
{
    public function run(): void
    {
        // Админ
        User::create([
            'name' => 'Admin',
            'email' => 'admin@crm.test',
            'password' => Hash::make('password'),
        ]);

        // Категории
        $category = Category::create(['name' => 'Stalai su lentyna', 'slug' => 'stalai-su-lentyna']);

        // Теги
        $tags = Tag::insert([
            ['name' => 'baldai', 'slug' => 'baldai'],
            ['name' => 'restoranas', 'slug' => 'restoranas'],
            ['name' => 'nerūdijančio plieno', 'slug' => 'nerudijancio-plieno'],
        ]);

        // Продукт
        $product = Product::create([
            'name' => '1600x700x850 mm N. pl. stalas su lentyna, be bortelio',
            'slug' => '1600x700x850-mm-n-pl-stalas-su-lentyna-be-bortelio',
            'sku' => '190',
            'type' => 'simple',
            'short_description' => 'Surenkamas stalas su lentyna',
            'description' => 'Kokybiškas nerūdijančio plieno stalas su lentyna, be bortelio',
            'on_sale' => true,
            'price' => 284.00,
            'regular_price' => 458.00,
            'sale_price' => 284.00,
            'currency_code' => 'EUR',
            'stock_quantity' => 2,
            'is_in_stock' => true,
            'permalink' => 'https://metaliniai.lt/parduotuve/stalai/stalai-su-lentyna/1600x700x850-mm-n-pl-stalas-su-lentyna-be-bortelio/',
        ]);

        $product->categories()->attach($category->id);
        $product->tags()->attach(Tag::pluck('id'));

        // Картинки
        ProductImage::create([
            'product_id' => $product->id,
            'image_url' => 'https://metaliniai.lt/wp-content/uploads/2024/10/Stalas-be-bortelio.jpg',
            'thumbnail' => 'https://metaliniai.lt/wp-content/uploads/2024/10/Stalas-be-bortelio-433x516.jpg',
        ]);

        // Атрибуты
        ProductAttribute::create([
            'product_id' => $product->id,
            'name' => 'Medžiaga',
            'value' => 'Nerūdijantis plienas AISI304',
        ]);
    }
}

