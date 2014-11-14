<?php  namespace Jai\Authentication\Tests\Unit;

/**
 * Test EditableSubscriberTest
 *
 * @author Jai beschi Jai@Jaibeschi.com
 */
use Jai\Authentication\Events\EditableSubscriber;

class EditableSubscriberTest extends TestCase {

    /**
     * @test
     **/
    public function it_check_if_is_editable()
    {
        $model = new \StdClass;
        $model->protected = false;

        $sub = new EditableSubscriber();
        $sub->isEditable($model);
    }

    /**
     * @test
     * @expectedException \Jai\Authentication\Exceptions\PermissionException
     **/
    public function it_check_if_es_editable_and_throw_new_exception()
    {
        $model = new \StdClass;
        $model->protected = true;

        $sub = new EditableSubscriber();
        $sub->isEditable($model);
    }
}
 