<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class JamPelajaran extends Model
{
    use HasFactory;

    public const JENIS_PEMBIASAAN = 'Pembiasaan';
    public const JENIS_PELAJARAN = 'Pelajaran';
    public const JENIS_ISTIRAHAT = 'Istirahat';

    protected $table = 'jam_pelajaran';

    protected $guarded = ['id'];

    protected $casts = [
        'jam_ke' => 'integer',
        'urutan' => 'integer',
    ];

    public function scopeHari(Builder $query, string $hari): Builder
    {
        return $query->where('hari', $hari);
    }

    public function scopeUrut(Builder $query): Builder
    {
        return $query->orderBy('urutan');
    }

    public function scopePelajaran(Builder $query): Builder
    {
        return $query->where('jenis', self::JENIS_PELAJARAN);
    }

    public static function slotHari(string $hari): Collection
    {
        return static::hari($hari)->urut()->get();
    }

    public static function ringkasanHari(string $hari): array
    {
        return static::ringkasan(static::slotHari($hari), $hari);
    }

    public static function ringkasan(Collection $slots, string $hari): array
    {
        $pembiasaan = null;
        $jam = [];
        $istirahat = [];
        $blokJam = [];
        $blokSaatIni = [];
        $jamTerakhir = 0;

        foreach ($slots as $slot) {
            if ($slot->jenis === self::JENIS_PEMBIASAAN) {
                $pembiasaan = [
                    'nama' => $slot->nama,
                    'keterangan' => $slot->keterangan,
                    'mulai' => $slot->jam_mulai,
                    'selesai' => $slot->jam_selesai,
                ];
                continue;
            }

            if ($slot->jenis === self::JENIS_PELAJARAN) {
                $item = [
                    'jam_ke' => (int) $slot->jam_ke,
                    'nama' => $slot->nama,
                    'jam_mulai' => $slot->jam_mulai,
                    'jam_selesai' => $slot->jam_selesai,
                ];
                $jam[] = $item;
                $blokSaatIni[] = $item;
                $jamTerakhir = (int) $slot->jam_ke;
                continue;
            }

            $istirahat[] = [
                'nama' => $slot->nama,
                'mulai' => $slot->jam_mulai,
                'selesai' => $slot->jam_selesai,
                'setelah_jam' => $jamTerakhir,
            ];

            if (!empty($blokSaatIni)) {
                $blokJam[] = $blokSaatIni;
                $blokSaatIni = [];
            }
        }

        if (!empty($blokSaatIni)) {
            $blokJam[] = $blokSaatIni;
        }

        $bagian = [];
        $totalBlok = count($blokJam);
        foreach ($blokJam as $indeks => $blok) {
            $awal = $blok[0];
            $akhir = $blok[count($blok) - 1];
            $bagian[] = [
                'label' => self::labelBagian($indeks, $totalBlok),
                'dari' => $awal['jam_ke'],
                'sampai' => $akhir['jam_ke'],
                'mulai' => $awal['jam_mulai'],
                'selesai' => $akhir['jam_selesai'],
            ];
        }

        return [
            'hari' => $hari,
            'pembiasaan' => $pembiasaan,
            'jam' => $jam,
            'maks_jam' => empty($jam) ? 0 : max(array_column($jam, 'jam_ke')),
            'bagian' => $bagian,
            'istirahat' => $istirahat,
        ];
    }

    public static function melewatiIstirahat(array $ringkasan, int $mulai, int $selesai): bool
    {
        foreach ($ringkasan['istirahat'] as $istirahat) {
            if ($istirahat['setelah_jam'] >= $mulai && $istirahat['setelah_jam'] < $selesai) {
                return true;
            }
        }

        return false;
    }

    private static function labelBagian(int $indeks, int $total): string
    {
        if ($total === 1) {
            return 'Sesi Belajar';
        }

        if ($total === 2) {
            return $indeks === 0 ? 'Pagi' : 'Sore';
        }

        if ($total === 3) {
            return ['Pagi', 'Siang', 'Sore'][$indeks];
        }

        return 'Sesi ' . ($indeks + 1);
    }
}
