<?php

namespace App\Services;

use App\Models\Jadwal;
use App\Models\JamPelajaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ChronosService
{
    private const FILE_PATH = 'chronos.json';

    private static array $dayMap = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ];

    private static array $englishToIndo = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu',
    ];

    public static function get(): array
    {
        if (Storage::disk('local')->exists(self::FILE_PATH)) {
            $data = json_decode(Storage::disk('local')->get(self::FILE_PATH), true);
            if (is_array($data)) {
                return array_merge(self::defaultData(), $data);
            }
        }

        return self::defaultData();
    }

    public static function save(array $data): void
    {
        $current = self::get();
        $merged = array_merge($current, $data);
        Storage::disk('local')->put(self::FILE_PATH, json_encode($merged, JSON_PRETTY_PRINT));
    }

    public static function enable(): void
    {
        self::save(['enabled' => true]);
        self::apply();
    }

    public static function disable(): void
    {
        self::save(['enabled' => false]);
        Carbon::setTestNow(null);
    }

    public static function setTime(string $date, int $hour, int $minute, int $second = 0): void
    {
        self::save([
            'enabled' => true,
            'date' => $date,
            'hour' => max(0, min(23, $hour)),
            'minute' => max(0, min(59, $minute)),
            'second' => max(0, min(59, $second)),
        ]);
        self::apply();
    }

    public static function applyPreset(int $dayOfWeek, int $hour, int $minute): void
    {
        $now = Carbon::now('Asia/Jakarta');
        $monday = (clone $now)->startOfWeek();
        $targetDate = (clone $monday)->addDays($dayOfWeek - 1)->format('Y-m-d');

        self::save([
            'enabled' => true,
            'date' => $targetDate,
            'hour' => $hour,
            'minute' => $minute,
            'second' => 0,
        ]);
        self::apply();
    }

    public static function apply(): void
    {
        $config = self::get();

        if (empty($config['enabled'])) {
            return;
        }

        try {
            $date = $config['date'] ?? Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $hour = sprintf('%02d', (int) ($config['hour'] ?? 7));
            $minute = sprintf('%02d', (int) ($config['minute'] ?? 0));
            $second = sprintf('%02d', (int) ($config['second'] ?? 0));

            $timeString = "{$date} {$hour}:{$minute}:{$second}";
            $virtual = Carbon::createFromFormat('Y-m-d H:i:s', $timeString, 'Asia/Jakarta');

            if ($virtual) {
                Carbon::setTestNow($virtual);
            }
        } catch (\Throwable $e) {
            Carbon::setTestNow(null);
        }
    }

    public static function status(): array
    {
        $config = self::get();
        $enabled = !empty($config['enabled']);

        if ($enabled) {
            $date = $config['date'] ?? Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $hour = (int) ($config['hour'] ?? 7);
            $minute = (int) ($config['minute'] ?? 0);
            $second = (int) ($config['second'] ?? 0);

            try {
                $virtual = Carbon::createFromFormat('Y-m-d H:i:s', "{$date} " . sprintf('%02d:%02d:%02d', $hour, $minute, $second), 'Asia/Jakarta');
                $dayName = self::$englishToIndo[$virtual->format('l')] ?? 'Senin';
            } catch (\Throwable) {
                $virtual = Carbon::now('Asia/Jakarta');
                $dayName = 'Senin';
            }

            return [
                'enabled' => true,
                'date' => $date,
                'hour' => $hour,
                'minute' => $minute,
                'second' => $second,
                'day_name' => $dayName,
                'formatted_time' => sprintf('%02d:%02d', $hour, $minute),
                'formatted_full_time' => sprintf('%02d:%02d:%02d', $hour, $minute, $second),
                'formatted_date' => $virtual->locale('id')->isoFormat('D MMMM Y'),
                'badge_text' => "{$dayName}, " . sprintf('%02d:%02d', $hour, $minute) . ' WIB',
            ];
        }

        $now = Carbon::now('Asia/Jakarta');
        $dayName = self::$englishToIndo[$now->format('l')] ?? 'Senin';

        return [
            'enabled' => false,
            'date' => $now->format('Y-m-d'),
            'hour' => (int) $now->format('H'),
            'minute' => (int) $now->format('i'),
            'second' => (int) $now->format('s'),
            'day_name' => $dayName,
            'formatted_time' => $now->format('H:i'),
            'formatted_full_time' => $now->format('H:i:s'),
            'formatted_date' => $now->locale('id')->isoFormat('D MMMM Y'),
            'badge_text' => "{$dayName}, " . $now->format('H:i') . ' WIB',
        ];
    }

    public static function getActiveJadwal(): array
    {
        $status = self::status();
        $dayName = $status['day_name'];
        $timeString = $status['formatted_time'];

        $jamKeMapPerHari = JamPelajaran::pelajaran()->urut()->get()
            ->groupBy('hari')
            ->map(fn ($slots) => $slots->keyBy('jam_ke'));
        $jamSlots = $jamKeMapPerHari->get($dayName, collect());

        $jadwals = Jadwal::with(['kelas', 'mapel', 'guru', 'ruangan'])
            ->where('hari', $dayName)
            ->get();

        $activeList = [];
        foreach ($jadwals as $j) {
            $mulaiSlot = $jamSlots->get($j->jam_ke_mulai);
            $selesaiSlot = $jamSlots->get($j->jam_ke_selesai);

            $mulai = $mulaiSlot->jam_mulai ?? sprintf('%02d:00', max(7, 7 + ($j->jam_ke_mulai - 1)));
            $selesai = $selesaiSlot->jam_selesai ?? sprintf('%02d:45', max(7, 7 + ($j->jam_ke_selesai - 1)));

            $isActive = ($timeString >= $mulai && $timeString <= $selesai);
            $isUpcoming = ($timeString < $mulai);

            if ($isActive || $isUpcoming) {
                $activeList[] = [
                    'jadwal' => $j,
                    'mulai' => $mulai,
                    'selesai' => $selesai,
                    'is_active' => $isActive,
                    'is_upcoming' => $isUpcoming,
                ];
            }
        }

        return $activeList;
    }

    private static function defaultData(): array
    {
        $now = Carbon::now('Asia/Jakarta');
        return [
            'enabled' => false,
            'date' => $now->format('Y-m-d'),
            'hour' => (int) $now->format('H'),
            'minute' => (int) $now->format('i'),
            'second' => (int) $now->format('s'),
        ];
    }
}
