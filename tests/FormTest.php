<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * @covers Email
 */
final class FormTest extends TestCase
{
    public function testSimpleFormCreation(): void
    {
        $form = new \Clea\Form\Form();
        $form->addField(new Clea\Form\Field("test",
            new \Clea\Form\Validator(function () {
                return true;
            }), "Test", "test"
        ));

        $form->handleData([
            "test" => "test_value"
        ]);

        $this->assertEquals(true, $form->validate());
        $this->assertEquals("test_value", $form->get("test")->getValue());
    }

    public function testEmbedFormCreation(): void
    {
        $date = new \DateTime("now");
        $datas = [
            "embed" => [
                "embed1_test" => "Hello",
                "embed2" => [
                    "embed2_test" => "Hi"
                ]
            ],
            "date" => $date->format("Y-m-d H:i:s")
        ];
        $form = new \Clea\Form\Form();
        $form->addField(
            new \Clea\Form\Field\FormField(
                (new \Clea\Form\Form("embed"))
                    ->addField(
                        new \Clea\Form\Field\FormField(
                            (new \Clea\Form\Form("embed2"))
                                ->addField(new \Clea\Form\Field\TextField("embed2_test"))
                        )
                    )
                    ->addField(new \Clea\Form\Field\TextField("embed1_test"))
            )
        )
            ->addField(new \Clea\Form\Field\DateField("date"));
        $form->handleData($datas);
        $datas["date"] = new \DateTime($datas["date"]);
        $this->assertEquals($datas, $form->getData());
    }

    public function testCollectionFormCreation(): void
    {

        $resultExcepted = [
            "test_collection" => [
                1 => [
                        "embed2" => [
                            "embed2_test" => "embed2_test_value"
                        ],
                        "foo" => "foo_value"

                ],
                2 => [
                        "embed2" => [
                            "embed2_test" => "embed2_test_value2"
                        ],
                        "foo" => "foo_value2"

                ]
            ],
            "first_level" => "first_level_value"
        ];
        $collection = new \Clea\Form\Field\CollectionField("test_collection");
        $collection->setField(function () {
            return new \Clea\Form\Field\FormField(
                (new \Clea\Form\Form("embed"))
                    ->addField(
                        new \Clea\Form\Field\FormField(
                            (new \Clea\Form\Form("embed2"))
                                ->addField(new \Clea\Form\Field\TextField("embed2_test"))
                        )
                    )
                    ->addField(new \Clea\Form\Field\TextField("foo"))
            );
        });


        $form = new \Clea\Form\Form();
        $form->addField(
            $collection
        )->addField(new \Clea\Form\Field\TextField("first_level", []));

        $form->handleData($resultExcepted);

        $this->assertEquals($resultExcepted, $form->getData());
    }

}