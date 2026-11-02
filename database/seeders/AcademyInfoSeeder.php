<?php

namespace Database\Seeders;

use App\Models\AcademyInfo;
use Illuminate\Database\Seeder;

class AcademyInfoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'name' => 'Portal Academia',
            'slogan' => 'Aprende con los mejores profesionales',
            'address' => 'Calle 123, Ciudad, País',
            'phone' => '+58 412 1234567',
            'email' => 'info@portalacademia.com',
            'instagram' => '@portalacademia',
            'facebook' => '@portalacademia',
            'whatsapp' => '584121234567',
            'logo' => null, // O una ruta si tienes una imagen
        ];

        foreach ($data as $key => $value) {
            AcademyInfo::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        AcademyInfo::clearCache();
    }
}
