<?php

namespace App\Jev;

class RecordedAnswer
{
    /**
     * Keep the recorded decision and shift the numbers inside a range that still supports it.
     *
     * @param  array<string, mixed>  $response
     * @return array<string, mixed>
     */
    public static function vary(string $type, array $response): array
    {
        return match ($type) {
            'boolean', 'image' => ['probability' => self::varyBoolean((float) $response['probability'])],
            'choice' => self::varyChoice($response),
            'score' => self::varyScore($response),
            default => $response,
        };
    }

    private static function varyBoolean(float $anchor): float
    {
        if ($anchor >= 0.9) {
            return self::asFloat(self::clamp(
                min(self::thousandths($anchor), 960) + self::offset(40),
                900,
                990,
            ));
        }

        if ($anchor > 0.8) {
            return self::asFloat(self::clamp(
                self::thousandths($anchor) + self::offset(30),
                810,
                890,
            ));
        }

        return self::asFloat(self::clamp(
            self::thousandths($anchor) + self::offset(30),
            10,
            90,
        ));
    }

    /**
     * @param  array<string, mixed>  $response
     * @return array{choice: string, probabilities: array<string, float>, confidence: float}
     */
    private static function varyChoice(array $response): array
    {
        /** @var array<string, float|int> $probabilities */
        $probabilities = $response['probabilities'];
        $choice = (string) $response['choice'];
        $winner = self::clamp(
            min(self::thousandths((float) $probabilities[$choice]), 960) + self::offset(40),
            820,
            970,
        );
        $others = [];

        foreach ($probabilities as $option => $probability) {
            if ($option === $choice) {
                continue;
            }

            $others[$option] = max(1, self::thousandths((float) $probability));
        }

        $assigned = self::share(1000 - $winner, $others);
        $assigned[$choice] = $winner;

        $ordered = [];

        foreach (array_keys($probabilities) as $option) {
            $ordered[$option] = self::asFloat($assigned[$option]);
        }

        return [
            'choice' => $choice,
            'probabilities' => $ordered,
            'confidence' => self::varyConfidence($response['confidence'] ?? null, 750),
        ];
    }

    /**
     * @param  array<string, mixed>  $response
     * @return array{score: float, probabilities: list<float>, legend: list<string>, confidence: float}
     */
    private static function varyScore(array $response): array
    {
        /** @var list<float|int> $probabilities */
        $probabilities = array_values($response['probabilities']);
        $peak = array_search(max($probabilities), $probabilities, true);
        $peak = $peak === false ? 0 : $peak;
        $winner = self::clamp(
            min(self::thousandths((float) $probabilities[$peak]), 960) + self::offset(40),
            620,
            970,
        );
        $others = [];

        foreach ($probabilities as $index => $probability) {
            if ($index === $peak) {
                continue;
            }

            $others[$index] = max(1, self::thousandths((float) $probability));
        }

        $assigned = self::share(1000 - $winner, $others);
        $assigned[$peak] = $winner;
        ksort($assigned);

        $levelCount = count($response['legend']);
        $low = (int) round(max(0, $peak - 0.35) * 1000);
        $high = (int) round(min($levelCount - 1, $peak + 0.35) * 1000);

        return [
            'score' => self::asFloat(self::clamp(
                self::thousandths((float) $response['score']) + self::offset(120),
                $low,
                $high,
            )),
            'probabilities' => array_map(self::asFloat(...), array_values($assigned)),
            'legend' => $response['legend'],
            'confidence' => self::varyConfidence($response['confidence'] ?? null, 700),
        ];
    }

    private static function varyConfidence(mixed $anchor, int $floor): float
    {
        $start = $anchor === null ? 900 : min(self::thousandths((float) $anchor), 950);

        return self::asFloat(self::clamp($start + self::offset(50), $floor, 980));
    }

    /**
     * @param  array<int|string, int>  $weights
     * @return array<int|string, int>
     */
    private static function share(int $rest, array $weights): array
    {
        if ($weights === []) {
            return [];
        }

        $total = array_sum($weights);
        $assigned = [];
        $used = 0;
        $last = array_key_last($weights);

        foreach ($weights as $key => $weight) {
            if ($key === $last) {
                $assigned[$key] = $rest - $used;

                continue;
            }

            $share = (int) floor($rest * ($weight / $total));
            $assigned[$key] = $share;
            $used += $share;
        }

        return $assigned;
    }

    private static function thousandths(float $value): int
    {
        return (int) round($value * 1000);
    }

    private static function asFloat(int $thousandths): float
    {
        return (float) number_format($thousandths / 1000, 3, '.', '');
    }

    private static function offset(int $spread): int
    {
        return random_int(-$spread, $spread);
    }

    private static function clamp(int $value, int $low, int $high): int
    {
        return min($high, max($low, $value));
    }
}
