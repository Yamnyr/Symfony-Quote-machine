<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('new_quote')]
class NewQuoteIndicatorComponent
{
    public string $type = 'success';
    public string $message;
}

?>