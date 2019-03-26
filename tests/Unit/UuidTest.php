<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 27 March 2019, 4:07 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

class UuidTest extends TestCase
{
    public function testUuid1()
    {
        $this->assertTrue(true);
        foreach (range(0, 100) as $_)
        {
            try
            {
                echo \Ramsey\Uuid\Uuid::uuid1()->toString() . "\n";
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }
    }

    public function testUuid4()
    {
        $this->assertTrue(true);
        foreach (range(0, 100) as $_)
        {
            try
            {
                echo \Ramsey\Uuid\Uuid::uuid4()->toString() . "\n";
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }
    }

}

?>
