<?php

declare(strict_types=1);

it('loads the brochure landing page', function (): void {
    $page = visit('/');

    $page->assertNoJavascriptErrors();
});
