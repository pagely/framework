<?php

namespace Equip;

use Equip\Adr\DomainInterface;
use Equip\Adr\InputInterface;
use Equip\Adr\ResponderInterface;
use Equip\Input;
use Equip\Responder\ChainedResponder;

class Action
{
    /**
     * The domain specification.
     */
    protected string $domain;

    /**
     * The responder specification.
     */
    protected string $responder = ChainedResponder::class;

    /**
     * The input specification.
     */
    protected string $input = Input::class;

    public function __construct(
        string $domain,
        ?string $responder = null,
        ?string $input = null
    ) {
        $this->domain = $domain;

        if ($responder) {
            $this->responder = $responder;
        }

        if ($input) {
            $this->input = $input;
        }
    }

    /**
     * Returns the domain specification.
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Returns the responder specification.
     */
    public function getResponder(): string
    {
        return $this->responder;
    }

    /**
     * Returns the input specification.
     */
    public function getInput(): string
    {
        return $this->input;
    }
}
