<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produto::create([
            'nome' => 'Cadeira Gamer',
            'descricao' => 'Cadeira gamer',
            'preco' => '1500',
            'imagem' => 'images/cadeira_gamer_fox_racer.png'
        ]);
        Produto::create([
            'nome' => 'Sofa',
            'descricao' => 'Sofa 3 lugares',
            'preco' => '2000',
            'imagem' => 'images/Sofa_retratil_3lugares.png'
        ]);
        Produto::create([
            'nome' => 'Geladeira',
            'descricao' => 'Geladeira HQ',
            'preco' => '3500',
            'imagem' => 'images/Geladeira_HQ_Frost.png'
        ]);
        Produto::create([
            'nome' => 'Televisao',
            'descricao' => 'Televisao 40 polegadas',
            'preco' => '2000',
            'imagem' => ''
        ]);
    }
}
