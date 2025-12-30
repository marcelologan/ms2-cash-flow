<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Categorias de Entrada
            ['name' => 'Salário', 'type' => 'entrada'],
            ['name' => 'Freelance', 'type' => 'entrada'],
            ['name' => 'Vendas', 'type' => 'entrada'],
            ['name' => 'Investimentos', 'type' => 'entrada'],
            ['name' => 'Outros Recebimentos', 'type' => 'entrada'],
            
            // Categorias de Saída
            ['name' => 'Alimentação', 'type' => 'saida'],
            ['name' => 'Transporte', 'type' => 'saida'],
            ['name' => 'Moradia', 'type' => 'saida'],
            ['name' => 'Saúde', 'type' => 'saida'],
            ['name' => 'Educação', 'type' => 'saida'],
            ['name' => 'Lazer', 'type' => 'saida'],
            ['name' => 'Roupas', 'type' => 'saida'],
            ['name' => 'Impostos', 'type' => 'saida'],
            ['name' => 'Outros Gastos', 'type' => 'saida'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}