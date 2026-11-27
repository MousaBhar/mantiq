<?php

namespace Tests\Unit;

use App\Support\VideoEmbed;
use PHPUnit\Framework\TestCase;

class VideoEmbedTest extends TestCase
{
    public function test_it_converts_youtube_urls(): void
    {
        $expected = 'https://www.youtube-nocookie.com/embed/RGOj5yH7evk?rel=0';

        $this->assertSame($expected, VideoEmbed::toEmbedUrl('https://www.youtube.com/watch?v=RGOj5yH7evk'));
        $this->assertSame($expected, VideoEmbed::toEmbedUrl('https://youtu.be/RGOj5yH7evk'));
        $this->assertSame($expected, VideoEmbed::toEmbedUrl('https://m.youtube.com/shorts/RGOj5yH7evk'));
        $this->assertSame($expected, VideoEmbed::toEmbedUrl('https://www.youtube.com/embed/RGOj5yH7evk'));
    }

    public function test_it_converts_vimeo_urls(): void
    {
        $this->assertSame(
            'https://player.vimeo.com/video/123456',
            VideoEmbed::toEmbedUrl('https://vimeo.com/123456')
        );
    }

    public function test_it_rejects_unknown_hosts_and_bad_input(): void
    {
        $this->assertNull(VideoEmbed::toEmbedUrl(null));
        $this->assertNull(VideoEmbed::toEmbedUrl(''));
        $this->assertNull(VideoEmbed::toEmbedUrl('https://evil.example/watch?v=RGOj5yH7evk'));
        $this->assertNull(VideoEmbed::toEmbedUrl('javascript:alert(1)'));
        $this->assertNull(VideoEmbed::toEmbedUrl('https://www.youtube.com/watch?v=short'));
    }
}
