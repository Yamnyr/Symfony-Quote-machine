<?php

namespace App\Event;

use App\Entity\Quote;
use Symfony\Contracts\EventDispatcher\Event;

class QuoteCreated extends Event
{
    private Quote $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function getQuote(): Quote
    {
        return $this->quote;
    }
}
