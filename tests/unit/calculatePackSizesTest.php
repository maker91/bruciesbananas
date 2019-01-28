<?php


class calculatePackSizesTest extends \Codeception\Test\Unit
{
    public function generateAvailablePacks($pack_sizes)
    {
        $available_packs = [];
        $mock_builder = $this->getMockBuilder('app\models\Pack')->setMethods(['attributes']);

        foreach ($pack_sizes as $pack_size) {
            $pack = $mock_builder->getMock();
            $pack->method('attributes')->willReturn(['size']);
            $pack->size = $pack_size;
            $available_packs[] = $pack;
        }

        return $available_packs;
    }

    public function testSingleBananaDefaultPacks()
    {
        $available_packs = $this->generateAvailablePacks([5000, 2000, 1000, 500, 250]);

        $order = new \app\models\Order();
        $order->quantity = 1;

        $result = $order->calculatePackSizes($available_packs);
        $expected = [
            "packs" => ["250" => 1],
            "total_packs" => 1,
            "total_bananas" => 250
        ];

        $this->assertEquals($expected, $result);
    }

    public function test250BananasDefaultPacks()
    {
        $available_packs = $this->generateAvailablePacks([5000, 2000, 1000, 500, 250]);

        $order = new \app\models\Order();
        $order->quantity = 250;

        $result = $order->calculatePackSizes($available_packs);
        $expected = [
            "packs" => ["250" => 1],
            "total_packs" => 1,
            "total_bananas" => 250
        ];

        $this->assertEquals($expected, $result);
    }

    public function test251BananasDefaultPacks()
    {
        $available_packs = $this->generateAvailablePacks([5000, 2000, 1000, 500, 250]);

        $order = new \app\models\Order();
        $order->quantity = 251;

        $result = $order->calculatePackSizes($available_packs);
        $expected = [
            "packs" => ["500" => 1],
            "total_packs" => 1,
            "total_bananas" => 500
        ];

        $this->assertEquals($expected, $result);
    }

    public function test501BananasDefaultPacks()
    {
        $available_packs = $this->generateAvailablePacks([5000, 2000, 1000, 500, 250]);

        $order = new \app\models\Order();
        $order->quantity = 501;

        $result = $order->calculatePackSizes($available_packs);
        $expected = [
            "packs" => ["500" => 1, "250" => 1],
            "total_packs" => 2,
            "total_bananas" => 750
        ];

        $this->assertEquals($expected, $result);
    }

    public function test12001BananasDefaultPacks()
    {
        $available_packs = $this->generateAvailablePacks([5000, 2000, 1000, 500, 250]);

        $order = new \app\models\Order();
        $order->quantity = 12001;

        $result = $order->calculatePackSizes($available_packs);
        $expected = [
            "packs" => ["5000" => 2, "2000" => 1, "250" => 1],
            "total_packs" => 4,
            "total_bananas" => 12250
        ];

        $this->assertEquals($expected, $result);
    }

    public function test999BananasDefaultPack()
    {
        // This tests the case where is is best to use a single pack that is greater
        // than the order quantity instead of breakdown the order in to smaller pack sizes.
        // 999 could be solved as: 500x2  = 1000 bananas
        // A better solution is  : 1000x1 = 1000 bananas (fewer packs)

        $available_packs = $this->generateAvailablePacks([5000, 2000, 1000, 500, 250]);

        $order = new \app\models\Order();
        $order->quantity = 999;

        $result = $order->calculatePackSizes($available_packs);
        $expected = [
            "packs" => ["1000" => 1],
            "total_packs" => 1,
            "total_bananas" => 1000
        ];

        $this->assertEquals($expected, $result);
    }

    public function test501BananasAdditionalPacks()
    {
        // This tests the case where it isn't best to use as many of the higher pack
        // sizes as possible. Instead a better solution (fewer surplus bananas) is to
        // split the order in to a greater number of smaller pack sizes.
        // 501 could be solved as: 500x1, 250x1 = 750 bananas
        // A better solution is:   251x1, 250x1 = 501 bananas (fewer bananas)

        $available_packs = $this->generateAvailablePacks([5000, 2000, 1000, 500, 251, 250]);

        $order = new \app\models\Order();
        $order->quantity = 501;

        $result = $order->calculatePackSizes($available_packs);
        $expected = [
            "packs" => ["251" => 1, "250" => 1],
            "total_packs" => 2,
            "total_bananas" => 501
        ];

        $this->assertEquals($expected, $result);
    }
}
