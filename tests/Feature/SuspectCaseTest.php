<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SuspectCaseTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * A basic test example.
     * @test
     */
    public function al_crear_caso_sin_usuario_ingresado_debe_solicitar_ingreso()
    {
        $this->expectException(AuthenticationException::class);
        $this->post(route('lab.suspect_cases.store_admission'),
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
    }

    /**
     * @test
     */
    public function un_usuario_sin_permiso_de_suspectcase_admission_debe_no_autorizar()
    {
        $user = User::create(['name' => 'admin', 'password' => bcrypt('test'), 'email' => 'jmandiol@gmail.com']);
        $this->expectException(AuthorizationException::class);
        $this->actingAs($user)->post(route('lab.suspect_cases.store_admission'),
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
    }

    /**
     * @test
     */
    public function  un_usuario_con_permiso_de_suspectcase_admission_debe_registrar()
    {
        $user = User::create(['name' => 'admin', 'password' => bcrypt('test'), 'email' => 'jmandiol@gmail.com']);
        $permiso = Permission::firstOrCreate(['name' => 'SuspectCase: admission'],['description' => 'Permiso para crear casos de sospecha']);
        $user->permissions->add($permiso);
        $user->save();

        $this->actingAs($user)->post(route('lab.suspect_cases.store_admission'),
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
                'sample_at' => '2021-03-01 14:00:00',
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

        $this->assertDatabaseHas('patients', ['run' => 12838526,'dv'=>6]);
        $this->assertDatabaseHas( 'suspect_cases', ['sample_type' => 'TÓRULAS NASOFARÍNGEAS', 'sample_at' => '2021-03-01 14:00:00']);
    }
}
