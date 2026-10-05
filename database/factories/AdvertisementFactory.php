<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementFactory extends Factory
{
    public function definition(): array
    {
        $placements = ['header_banner', 'sidebar_top', 'sidebar_bottom', 'content_middle', 'footer'];
        $placement = $this->faker->randomElement($placements);
        $dimensions = $this->getDimensionsForPlacement($placement);

        return [
            'name' => $this->faker->company() . ' - ' . ucfirst(str_replace('_', ' ', $placement)),
            'type' => $this->faker->randomElement(['banner', 'adsense', 'custom']),
            'placement' => $placement,
            'image' => $this->getPlaceholderImage($dimensions['width'], $dimensions['height']),
            'url' => $this->faker->url(),
            'description' => $this->faker->sentence(10),
            'size' => $dimensions['width'] . 'x' . $dimensions['height'],
            'width' => $dimensions['width'],
            'height' => $dimensions['height'],
            'is_active' => $this->faker->boolean(85),
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'click_count' => $this->faker->numberBetween(0, 500),
            'view_count' => $this->faker->numberBetween(100, 5000),
        ];
    }

    private function getDimensionsForPlacement(string $placement): array
    {
        return match ($placement) {
            'header_banner' => ['width' => 1200, 'height' => 128],
            'sidebar_top' => ['width' => 300, 'height' => 250],
            'sidebar_bottom' => ['width' => 300, 'height' => 600],
            'content_middle' => ['width' => 728, 'height' => 90],
            'footer' => ['width' => 1200, 'height' => 100],
            default => ['width' => 300, 'height' => 250],
        };
    }

    private function getPlaceholderImage(int $width, int $height): string
    {
        $colors = ['FF6B6B', '4ECDC4', '45B7D1', 'FFA07A', '98D8C8', 'F7DC6F', 'BB8FCE', '85C1E2'];
        $bgColor = $colors[array_rand($colors)];
        $textColor = 'FFFFFF';

        return "https://via.placeholder.com/{$width}x{$height}/{$bgColor}/{$textColor}?text=Advertisement";
    }
}
