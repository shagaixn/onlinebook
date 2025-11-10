<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Уран зохиол',
            'Түүх',
            'Шинжлэх ухаан',
            'Технологи',
            'Бизнес',
            'Сэтгэл судлал',20
            'Эрүүл мэнд',
            'Хүүхдийн ном',
            'Хэл шинжлэл',
            'Гадаад хэл',
            'Урлаг',
            'Спорт',
            'Аялал жуулчлал',
            'Хоол хүнс',
            'Гэр бүл',
            'Санхүү',
            'Хууль',
            'Философи',
            'Шашин',
            'Өөрийгөө хөгжүүлэх'
        ];

        foreach ($categories as $category) {
            DB::table('book_categories')->updateOrInsert(
                ['name' => $category],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
