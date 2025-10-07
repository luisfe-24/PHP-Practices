<?php

declare(strict_types=1);

class NextMovie
{
    public function __construct(
        private int $days_until,
        private string $title,
        private string $following_production,
        private string $release_date,
        private string $poster_url,
        private string $overview,
    ) {
    }

    public function get_until_message(): string
    {
        return match (true) {
            $this->days_until === 0  => "Hoy se estrena",
            $this->days_until === 1  => "Mañana se estrena",
            $this->days_until < 7    => "Esta semana se estrena",
            $this->days_until < 30   => "Este mes se estrena",
            default                  => "{$this->days_until} días hasta el estreno",
        };
    }

    public static function fetch_and_create_movie(string $api_url): NextMovie
    {
        $result = file_get_contents($api_url);
        $data = json_decode($result, true);

        return new self(
            (int) $data["days_until"],
            $data["title"],
            $data["following_production"]['title'] ?? "Desconocido",
            $data["release_date"],
            $data["poster_url"],
            $data["overview"],
        );
    }

    public function get_data()
    {
        return get_object_vars($this);
    }
}
