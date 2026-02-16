<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class FooFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Foo';
    public $depends = [FnordFixture::class];
}
