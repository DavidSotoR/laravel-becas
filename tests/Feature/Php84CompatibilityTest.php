<?php

namespace Tests\Feature;

use App\Mail\NotificacionReset;
use App\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class Php84CompatibilityTest extends TestCase
{
    public function test_protected_api_requires_a_token(): void
    {
        $this->getJson('/api/auth/usuarios')->assertUnauthorized();
    }

    public function test_login_validates_required_fields(): void
    {
        $this->postJson('/api/auth/login', [])
            ->assertUnprocessable()->assertJsonValidationErrors(['login', 'password']);
    }

    public function test_cors_preflight_is_handled_by_the_framework(): void
    {
        $this->withHeaders([
            'Origin' => 'http://frontend.test',
            'Access-Control-Request-Method' => 'POST',
            'Access-Control-Request-Headers' => 'content-type,authorization',
        ])->options('/api/auth/login')->assertSuccessful()
            ->assertHeader('Access-Control-Allow-Origin', '*');
    }

    public function test_login_and_jwt_identification_work_on_php84(): void
    {
        // Isolated fixture: never connects to the project's MySQL database.
        Schema::create('perfiles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('short_name');
            $table->string('password');
            $table->unsignedBigInteger('id_perfil');
            $table->boolean('active');
            $table->timestamps();
        });
        DB::table('perfiles')->insert(['id' => 1, 'nombre' => 'Pruebas']);
        $id = DB::table('users')->insertGetId([
            'name' => 'Prueba PHP 8.4', 'email' => 'php84@example.test',
            'short_name' => 'php84', 'password' => Hash::make('test-password'),
            'id_perfil' => 1, 'active' => true,
        ]);
        $response = $this->postJson('/api/auth/login', [
            'login' => 'php84@example.test', 'password' => 'test-password',
        ])->assertOk()->assertJsonStructure(['access_token', 'expires_in', 'data']);

        $token = $response->json('access_token');
        $payload = json_decode(base64_decode(strtr(explode('.', $token)[1], '-_', '+/')), true);
        $this->assertEquals($id, $payload['sub']);
        $this->assertEquals($payload['exp'] - $payload['iat'], $response->json('expires_in'));

        auth()->forgetGuards();
        $this->withToken($token)->postJson('/api/auth/me')->assertOk()->assertJsonPath('id', $id);
        auth()->forgetGuards();
        $this->withToken($token)->postJson('/api/auth/logout')->assertOk();
        auth()->forgetGuards();
        $this->withToken($token)->postJson('/api/auth/me')->assertUnauthorized();
    }

    public function test_pdf_can_be_generated(): void
    {
        $pdf = Pdf::loadHTML('<html><body>Estudio socioeconómico</body></html>')->output();
        $this->assertStringStartsWith('%PDF-', $pdf);
    }

    public function test_excel_export_can_be_read_back(): void
    {
        $export = new class implements FromArray {
            public function array(): array
            {
                return [['Familia', 'Monto'], ['Prueba', 1500]];
            }
        };
        $bytes = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        $path = tempnam(sys_get_temp_dir(), 'becas-php84-');
        try {
            file_put_contents($path, $bytes);
            $sheet = IOFactory::load($path);
            $this->assertSame('Familia', $sheet->getActiveSheet()->getCell('A1')->getValue());
            $this->assertSame(1500, $sheet->getActiveSheet()->getCell('B2')->getValue());
            $sheet->disconnectWorksheets();
        } finally {
            unlink($path);
        }
    }

    public function test_existing_mail_template_works_with_symfony_mailer(): void
    {
        Mail::to('php84@example.test')->send(new NotificacionReset([
            'nombre' => 'Prueba', 'email' => 'php84@example.test',
            'password_temporal' => 'test-only',
        ]));
        $this->assertCount(1, Mail::mailer()->getSymfonyTransport()->messages());
    }

    public function test_user_factory_uses_the_modern_factory_api(): void
    {
        $user = User::factory()->make();
        $this->assertNotEmpty($user->email);
        $this->assertTrue(Hash::check('password', $user->password));
    }
}
