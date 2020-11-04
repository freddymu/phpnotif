<?php


namespace Freddymu\Phpnotif\Tests\Unit;


use Freddymu\Phpnotif\Entities\GenericResponseEntity;
use Freddymu\Phpnotif\Tests\BaseTestCase;

class GenericResponseEntityTest extends BaseTestCase
{
    public function testGetProcessTime()
    {
        // Given
        $entity = new GenericResponseEntity();

        // When
        sleep(1);

        // Then
        self::assertGreaterThanOrEqual(1, (int)$entity->getProcessTime());
    }

    public function testIsFailed()
    {
        // Given
        $entity = new GenericResponseEntity();

        // When
        $entity->success = false;

        // Then
        self::assertTrue($entity->isFailed());
    }

    public function testIsSuccess()
    {
        // Given
        $entity = new GenericResponseEntity();

        // When
        $entity->success = true;

        // Then
        self::assertTrue($entity->isSucceed());
    }
}
