<?php

namespace Wer\GuideBundle\Tests\Model;

use Wer\GuideBundle\Model\WerCategory;

class DefaultControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testReadCatBySec()
    {
        $o_cat = new WerCategory();
        $results = $o_cat->readCatBySec(1, array('cat_name' => 'test cat'));
        error_log($results);
    }
}
