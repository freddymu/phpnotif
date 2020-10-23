<?php

namespace Freddymu\Phpnotif\Tests\Unit;

use Freddymu\Phpnotif\Entities\PhpNotifEntity;
use Freddymu\Phpnotif\Helper\Test;
use Freddymu\Phpnotif\Tests\BaseTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class PhpNotifModelTest extends BaseTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * This is a bad example of mocking/stub usage
     * Because the system under test is insignificant or unclear
     * I've put this here on purpose for my internal workshop
     * @test
     */
    public function save()
    {
        // Given
        $entity = Test::createEntity();

        $sampleReturn = new \StdClass;
        $sampleReturn->n = 1;
        $mock = \Mockery::mock('Freddymu\Phpnotif\Models\PhpNotifModel');
        $mock
            ->shouldReceive('save')
            ->andReturn([
                $sampleReturn
            ]);

        // When
        $result = $mock->save($entity->toArray());

        // Then
        self::assertEquals(1, $result[0]->n);
    }

    /**
     * @test
     */
    public function getInboxByUserId()
    {
        self::markTestSkipped();
    }

    /**
     * @test
     */
    public function setMessageAsRead()
    {
        self::markTestSkipped();
    }

    /**
     * @test
     */
    public function getInboxSummary()
    {
        self::markTestSkipped();
    }
}
