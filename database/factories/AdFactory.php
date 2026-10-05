<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdFactory extends Factory
{
    public function definition(): array
    {
        $placements = ['header', 'sidebar', 'in_article', 'footer'];
        $placement = $this->faker->randomElement($placements);
        $dimensions = $this->getDimensionsForPlacement($placement);

        return [
            'name' => $this->faker->company() . ' - ' . ucfirst($placement),
            'type' => $this->faker->randomElement(['banner', 'script', 'adsense']),
            'placement' => $placement,
            'image' => $this->getPlaceholderImage($dimensions['width'], $dimensions['height']),
            'url' => $this->faker->url(),
            'script' => null,
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'is_active' => $this->faker->boolean(85),
        ];
    }

    private function getDimensionsForPlacement(string $placement): array
    {
        return match ($placement) {
            'header' => ['width' => 1200, 'height' => 128],
            'sidebar' => ['width' => 300, 'height' => 250],
            'in_article' => ['width' => 728, 'height' => 90],
            'footer' => ['width' => 1200, 'height' => 100],
            default => ['width' => 300, 'height' => 250],
        };
    }

    private function getPlaceholderImage(int $width, int $height): string
    {
        $colors = ['FF6B6B', '4ECDC4', '45B7D1', 'FFA07A', '98D8C8', 'F7DC6F', 'BB8FCE', '85C1E2'];
        $bgColor = $colors[array_rand($colors)];
        $textColor = 'FFFFFF';

        return "https://via.placeholder.com/{$width}x{$height}/{$bgColor}/{$textColor}?text=Ad";
    }
}
