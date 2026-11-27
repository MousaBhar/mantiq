<?php

namespace App\Support;

class VideoEmbed
{
    /**
     * Convert a public YouTube / Vimeo URL into a privacy-friendly embed URL.
     * Returns null for any other host so arbitrary iframes are never rendered.
     */
    public static function toEmbedUrl(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $parts = parse_url(trim($url));
        $host = strtolower($parts['host'] ?? '');
        $host = preg_replace('/^(www\.|m\.)/', '', $host);
        $path = $parts['path'] ?? '';
        parse_str($parts['query'] ?? '', $query);

        if ($host === 'youtu.be') {
            $id = trim($path, '/');
        } elseif (in_array($host, ['youtube.com', 'youtube-nocookie.com'], true)) {
            if (($query['v'] ?? null) !== null) {
                $id = $query['v'];
            } elseif (preg_match('#^/(embed|shorts|live)/([^/?]+)#', $path, $m)) {
                $id = $m[2];
            } else {
                return null;
            }
        } elseif ($host === 'vimeo.com' && preg_match('#^/(\d+)#', $path, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        } else {
            return null;
        }

        if (!preg_match('/^[A-Za-z0-9_-]{11}$/', (string) $id)) {
            return null;
        }

        return 'https://www.youtube-nocookie.com/embed/' . $id . '?rel=0';
    }
}
