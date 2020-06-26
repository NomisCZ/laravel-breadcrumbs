<?php

namespace NomisCZ\Breadcrumbs\Tests\Exceptions;

use NomisCZ\Breadcrumbs\Exceptions\DefinitionAlreadyExistsException;
use NomisCZ\Breadcrumbs\Tests\TestCase;

class DefinitionAlreadyExistsExceptionTest extends TestCase
{
    /** @test */
    function it_can_be_instantiated()
    {
        $result = new DefinitionAlreadyExistsException;

        $this->assertInstanceOf(DefinitionAlreadyExistsException::class, $result);
    }
}
