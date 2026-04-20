<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        $domains = [
            [
                'name' => 'meusite.com.br',
                'registrar' => 'Registro.br',
                'expiration_date' => now()->addDays(45)->format('Y-m-d'),
                'annual_cost' => 39.90,
                'notes' => 'Domínio principal da empresa',
                'auto_renew' => true,
            ],
            [
                'name' => 'minha-loja.com.br',
                'registrar' => 'GoDaddy',
                'expiration_date' => now()->addDays(15)->format('Y-m-d'),
                'annual_cost' => 49.90,
                'notes' => 'Loja virtual',
                'auto_renew' => false,
            ],
            [
                'name' => 'meublog.com',
                'registrar' => 'Namecheap',
                'expiration_date' => now()->addDays(7)->format('Y-m-d'),
                'annual_cost' => 29.90,
                'notes' => 'Blog pessoal',
                'auto_renew' => true,
            ],
            [
                'name' => 'consultoria.com.br',
                'registrar' => 'Registro.br',
                'expiration_date' => now()->addDays(90)->format('Y-m-d'),
                'annual_cost' => 59.90,
                'notes' => 'Site institucional',
                'auto_renew' => true,
            ],
            [
                'name' => 'projetox.com',
                'registrar' => 'Cloudflare',
                'expiration_date' => now()->subDays(5)->format('Y-m-d'),
                'annual_cost' => 19.90,
                'notes' => 'Domínio vencido - renovar urgente',
                'auto_renew' => false,
            ],
            [
                'name' => 'sistemas.com.br',
                'registrar' => 'AWS Route53',
                'expiration_date' => now()->addDays(30)->format('Y-m-d'),
                'annual_cost' => 89.90,
                'notes' => 'Sistema interno',
                'auto_renew' => true,
            ],
            [
                'name' => 'portifolio.com',
                'registrar' => 'GoDaddy',
                'expiration_date' => now()->addDays(60)->format('Y-m-d'),
                'annual_cost' => 34.90,
                'notes' => 'Portfólio profissional',
                'auto_renew' => false,
            ],
            [
                'name' => 'appstartup.com',
                'registrar' => 'Namecheap',
                'expiration_date' => now()->addDays(3)->format('Y-m-d'),
                'annual_cost' => 44.90,
                'notes' => 'Startup - vence em breve',
                'auto_renew' => true,
            ],
            [
                'name' => 'ecommerce.com.br',
                'registrar' => 'Registro.br',
                'expiration_date' => now()->addDays(120)->format('Y-m-d'),
                'annual_cost' => 99.90,
                'notes' => 'E-commerce completo',
                'auto_renew' => true,
            ],
            [
                'name' => 'teste.com',
                'registrar' => 'Cloudflare',
                'expiration_date' => now()->addDays(20)->format('Y-m-d'),
                'annual_cost' => 14.90,
                'notes' => 'Domínio de teste',
                'auto_renew' => false,
            ],
        ];

        foreach ($domains as $domain) {
            Domain::create($domain);
        }

        $this->command->info('10 domínios de exemplo criados com sucesso!');
    }
}
