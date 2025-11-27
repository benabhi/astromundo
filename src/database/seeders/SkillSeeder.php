<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // PILOTING
            [
                'code' => 'PILOTING_BASIC',
                'name' => 'Pilotaje Básico',
                'description' => 'Habilidad fundamental para operar naves espaciales básicas.',
                'category' => 'piloting',
                'multiplier' => 1,
            ],
            [
                'code' => 'PILOTING_ADVANCED',
                'name' => 'Pilotaje Avanzado',
                'description' => 'Técnicas avanzadas de vuelo para naves más complejas.',
                'category' => 'piloting',
                'multiplier' => 2,
            ],
            [
                'code' => 'PILOTING_COMBAT',
                'name' => 'Pilotaje de Combate',
                'description' => 'Maniobras evasivas y ofensivas para situaciones de combate.',
                'category' => 'piloting',
                'multiplier' => 3,
            ],
            [
                'code' => 'PILOTING_CARGO',
                'name' => 'Pilotaje de Carga',
                'description' => 'Especialización en el manejo de naves pesadas de transporte.',
                'category' => 'piloting',
                'multiplier' => 2,
            ],

            // INDUSTRY
            [
                'code' => 'MINING_BASIC',
                'name' => 'Minería Básica',
                'description' => 'Operación de láseres de minería y extracción de minerales comunes.',
                'category' => 'industry',
                'multiplier' => 1,
            ],
            [
                'code' => 'MINING_ADVANCED',
                'name' => 'Minería Avanzada',
                'description' => 'Técnicas eficientes para extraer minerales raros y preciosos.',
                'category' => 'industry',
                'multiplier' => 3,
            ],
            [
                'code' => 'REFINING',
                'name' => 'Refinado',
                'description' => 'Procesamiento de mineral crudo para obtener materiales utilizables.',
                'category' => 'industry',
                'multiplier' => 2,
            ],
            [
                'code' => 'MANUFACTURING',
                'name' => 'Fabricación',
                'description' => 'Ensamblaje de componentes y módulos a partir de materiales.',
                'category' => 'industry',
                'multiplier' => 4,
            ],

            // TRADE
            [
                'code' => 'TRADE_BASIC',
                'name' => 'Comercio Básico',
                'description' => 'Fundamentos de compra y venta en mercados estelares.',
                'category' => 'trade',
                'multiplier' => 1,
            ],
            [
                'code' => 'NEGOTIATION',
                'name' => 'Negociación',
                'description' => 'Habilidad para obtener mejores precios en transacciones.',
                'category' => 'trade',
                'multiplier' => 2,
            ],
            [
                'code' => 'MARKET_ANALYSIS',
                'name' => 'Análisis de Mercado',
                'description' => 'Interpretación de tendencias económicas y predicción de precios.',
                'category' => 'trade',
                'multiplier' => 3,
            ],

            // SCIENCE
            [
                'code' => 'SCIENCE_BASIC',
                'name' => 'Ciencia Básica',
                'description' => 'Conocimientos fundamentales para operar equipos científicos.',
                'category' => 'science',
                'multiplier' => 1,
            ],
            [
                'code' => 'SCANNING',
                'name' => 'Escaneo Espacial',
                'description' => 'Uso de sensores para detectar anomalías y recursos.',
                'category' => 'science',
                'multiplier' => 2,
            ],
            [
                'code' => 'HACKING',
                'name' => 'Hacking',
                'description' => 'Infiltración en sistemas informáticos y seguridad electrónica.',
                'category' => 'science',
                'multiplier' => 4,
            ],

            // COMBAT
            [
                'code' => 'GUNNERY_SMALL',
                'name' => 'Artillería Pequeña',
                'description' => 'Uso efectivo de armas de pequeño calibre.',
                'category' => 'combat',
                'multiplier' => 1,
            ],
            [
                'code' => 'GUNNERY_MEDIUM',
                'name' => 'Artillería Mediana',
                'description' => 'Operación de torretas y armas de calibre medio.',
                'category' => 'combat',
                'multiplier' => 2,
            ],
            [
                'code' => 'DEFENSIVE_SYSTEMS',
                'name' => 'Sistemas Defensivos',
                'description' => 'Gestión de escudos y blindaje para maximizar la supervivencia.',
                'category' => 'combat',
                'multiplier' => 2,
            ],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['code' => $skill['code']],
                $skill
            );
        }
    }
}
