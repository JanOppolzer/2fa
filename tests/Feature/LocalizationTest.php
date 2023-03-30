<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocalizationTest extends TestCase
{
    /** @test */
    public function czech_localization(): void
    {
        $this
            ->followingRedirects()
            ->get('language/cs');

        $this->assertEquals(app()->getLocale(), 'cs');
    }

    /** @test */
    public function english_localization(): void
    {
        $this
            ->followingRedirects()
            ->get('language/en');

        $this->assertEquals(app()->getLocale(), 'en');
    }
}
