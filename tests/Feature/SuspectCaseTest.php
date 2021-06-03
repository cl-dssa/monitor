<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuspectCaseTest extends TestCase
{
    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function al_crear_caso_sin_usuario_logeado_debe_solicitar_ingreso()
    {
      $response = $this->post('/lab/suspect_cases/admission',
            [
                'id_laboratory' => '1',
                'run' => '12838526',
                'dv' => '6',
                'other_identification' => '',
                'gender' => 'male',
                'birthday' => '1975-04-05',
                'age' => '45',
                'name' => 'Javier Andrés',
                'fathers_family' => 'Mandiola',
                'mothers_family' => 'Ovalle',
                'street_type' => 'Pasaje',
                'address' => 'Emilio Vaisse Houle',
                'number' => '01484',
                'suburb' => '',
                'nationality' => 'Chile',
                'region_id' => '2',
                'commune_id' => '12',
                'sample_type' => 'TÓRULAS NASOFARÍNGEAS',
                'establishment_id' => '3799',
                'origin' => '',
                'functionary' => '1',
                'symptoms' => '0',
                'symptoms_at' => '',
                'gestation' => '',
                'gestation_week' => '',
                'close_contact' => '0',
                'case_type' => 'Atención médica'
            ]);
      $response->assertRedirect(route('login'));
    }
}
