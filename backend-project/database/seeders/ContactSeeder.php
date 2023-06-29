<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contacts = [
            [
                'name' => "Carmen Solis",
                'url_avatar' => 'https://www.dzoom.org.es/wp-content/uploads/2020/02/portada-foto-perfil-redes-sociales-consejos.jpg',
                'phone' => 3121001111,
                'info' => "¡Hola! Estoy usando WhatsApp"
            ],
            [
                'name' => "Carlos Cervantes",
                'url_avatar' => 'https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg?cs=srgb&dl=pexels-justin-shaifer-1222271.jpg&fm=jpg',
                'phone' => 3121002222,
                'info' => "¡Hola! Estoy usando WhatsApp"
            ],
            [
                'name' => "Edgar Ramos",
                'url_avatar' => 'https://i0.wp.com/thehappening.com/wp-content/uploads/2017/07/foto-perfil-5.jpg?resize=1024%2C694&ssl=1',
                'phone' => 3121003333,
                'info' => "¡Hola! Estoy usando WhatsApp"
            ],
            [
                'name' => "Diana Mendez",
                'url_avatar' => 'https://www.lavanguardia.com/files/article_gallery_microformat/uploads/2018/07/25/5fa43a4a54014.jpeg',
                'phone' => 3121004444,
                'info' => "¡Hola! Estoy usando WhatsApp"
            ],

        ];

        DB::table('contacts')->insert($contacts);
    }
}
