<?php

declare(strict_types=1);

it('returns a successful brochure response', function (): void {
    $this->get(route('brochure.home'))->assertOk();
});
