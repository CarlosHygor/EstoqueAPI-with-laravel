<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produtos = [
            ['codigo' => 'PROD-001', 'descricao' => 'Notebook Dell Inspiron 15 (16GB RAM, 512GB SSD)', 'saldo' => 15],
            ['codigo' => 'PROD-002', 'descricao' => 'Mouse Sem Fio Logitech MX Master 3S', 'saldo' => 40],
            ['codigo' => 'PROD-003', 'descricao' => 'Teclado Mecânico Keychron K2 RGB', 'saldo' => 25],
            ['codigo' => 'PROD-004', 'descricao' => 'Monitor LG UltraWide 29 IPS Full HD', 'saldo' => 12],
            ['codigo' => 'PROD-005', 'descricao' => 'Cadeira de Escritório Ergonômica Comfy Ergofit', 'saldo' => 8],
            ['codigo' => 'PROD-006', 'descricao' => 'Headset Gamer HyperX Cloud II Red', 'saldo' => 30],
            ['codigo' => 'PROD-007', 'descricao' => 'Webcam Full HD Logitech C920s Pro', 'saldo' => 20],
            ['codigo' => 'PROD-008', 'descricao' => 'SSD Kingston NV2 1TB M.2 NVMe', 'saldo' => 50],
            ['codigo' => 'PROD-009', 'descricao' => 'Memória RAM Corsair Vengeance 16GB DDR4 3200MHz', 'saldo' => 45],
            ['codigo' => 'PROD-010', 'descricao' => 'Processador AMD Ryzen 7 5700X', 'saldo' => 18],
            ['codigo' => 'PROD-011', 'descricao' => 'Placa de Vídeo RTX 4060 Ventus 8GB', 'saldo' => 10],
            ['codigo' => 'PROD-012', 'descricao' => 'Fonte Corsair CV650 650W 80 Plus Bronze', 'saldo' => 22],
            ['codigo' => 'PROD-013', 'descricao' => 'Gabinete Gamer NZXT H5 Flow Black', 'saldo' => 14],
            ['codigo' => 'PROD-014', 'descricao' => 'Water Cooler DeepCool LE520 240mm ARGB', 'saldo' => 16],
            ['codigo' => 'PROD-015', 'descricao' => 'Placa-Mãe ASUS TUF Gaming B550M-Plus', 'saldo' => 15],
            ['codigo' => 'PROD-016', 'descricao' => 'Fone de Ouvido Bluetooth Sony WH-1000XM5', 'saldo' => 7],
            ['codigo' => 'PROD-017', 'descricao' => 'Mousepad Gamer Extra Grande 90x40cm Black', 'saldo' => 60],
            ['codigo' => 'PROD-018', 'descricao' => 'Suporte Articulado para Monitor F80N ELG', 'saldo' => 35],
            ['codigo' => 'PROD-019', 'descricao' => 'Filtro de Linha 8 Tomadas Clamper iClamper Energia 8', 'saldo' => 40],
            ['codigo' => 'PROD-020', 'descricao' => 'Nobreak Intelbras Attiv 600VA 120V', 'saldo' => 11],
            ['codigo' => 'PROD-021', 'descricao' => 'Impressora Multifuncional Epson EcoTank L3250', 'saldo' => 9],
            ['codigo' => 'PROD-022', 'descricao' => 'Roteador Wi-Fi 6 TP-Link Archer AX12', 'saldo' => 28],
            ['codigo' => 'PROD-023', 'descricao' => 'Switch TP-Link 8 Portas Gigabit TL-SG108', 'saldo' => 19],
            ['codigo' => 'PROD-024', 'descricao' => 'Cabo HDMI 2.1 4K 120Hz 2 Metros', 'saldo' => 75],
            ['codigo' => 'PROD-025', 'descricao' => 'Hub USB-C 7 em 1 Baseus Dual Type-C', 'saldo' => 33],
            ['codigo' => 'PROD-026', 'descricao' => 'Mesa Gamer Com Regulagem de Altura Elétrica 140x70', 'saldo' => 5],
            ['codigo' => 'PROD-027', 'descricao' => 'Luminária de Monitor Baseus i-Wok Stepless Dimming', 'saldo' => 24],
            ['codigo' => 'PROD-028', 'descricao' => 'HD Externo Portátil Seagate Expansion 2TB', 'saldo' => 27],
            ['codigo' => 'PROD-029', 'descricao' => 'Pendrive SanDisk Ultra Flair 64GB USB 3.0', 'saldo' => 80],
            ['codigo' => 'PROD-030', 'descricao' => 'Adaptador Bluetooth 5.0 USB TP-Link UB500', 'saldo' => 55],
            ['codigo' => 'PROD-031', 'descricao' => 'Microfone Condensador Fifine K669B USB', 'saldo' => 17],
            ['codigo' => 'PROD-032', 'descricao' => 'Braço Articulado para Microfone Neewer NB-35', 'saldo' => 21],
            ['codigo' => 'PROD-033', 'descricao' => 'Ring Light 10 Polegadas Com Tripé 1.60m', 'saldo' => 13],
            ['codigo' => 'PROD-034', 'descricao' => 'Caixa de Som Edifier R1000T4 Bivolt 24W RMS', 'saldo' => 10],
            ['codigo' => 'PROD-035', 'descricao' => 'Organizador de Cabos Espiral 2 Metros Preto', 'saldo' => 100],
        ];

        foreach ($produtos as $produto) {
            Produto::updateOrCreate(
                ['codigo' => $produto['codigo']],
                ['descricao' => $produto['descricao'], 'saldo' => $produto['saldo']]
            );
        }
    }
}

