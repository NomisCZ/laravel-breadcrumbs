<?php

namespace NomisCZ\Breadcrumbs\Tests\Exceptions;

use NomisCZ\Breadcrumbs\Exceptions\DefinitionNotFoundException;
use NomisCZ\Breadcrumbs\Tests\TestCase;

class DefinitionNotFoundExceptionTest extends TestCase
{
    /** @test */
    function it_can_be_instantiated()
    {
        $result = new DefinitionNotFoundException;

        $this->assertInstanceOf(DefinitionNotFoundException::class, $result);
    }
}
