<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * @covers Email
 */
final class FormTest extends TestCase
{
    public function makeForm(): \Clea\Form\FormInterface
    {
        $collection = new \Clea\Form\Field\CollectionField("test_collection");
        $collection->setField(function () {
            return new \Clea\Form\Field\FormField(
                (new \Clea\Form\Form("embed"))
                    ->addField(
                        new \Clea\Form\Field\FormField(
                            (new \Clea\Form\Form("embed2"))
                                ->addField(new \Clea\Form\Field\TextField("embed2_test"))
                                ->addField(new \Clea\Form\Field\NumberField("number_field"))
                        )
                    )
                    ->addField(new \Clea\Form\Field\TextField("foo"))
            );
        });


        $form = new \Clea\Form\Form(null);
        $choice = new \Clea\Form\Field\ChoiceField("choice_field");
        $choice->setChoices(function(){
            return ["choice_1" => "Choice 1", "choice_2" => "Choice 2", "choice_3" => "Choice 3"];
        });
        $form->addField($choice);
        $form->addField(
            $collection
        )->addField(new \Clea\Form\Field\TextField("first_level", [new \Clea\Form\Validator(function ($value) {
            return is_int($value);
        })]));
        return $form;
    }

    public function getData()
    {
        return [
            "test_collection" => [
                1 => [
                    "embed2" => [
                        "embed2_test" => "embed2_test_value",
                        "number_field" => ""
                    ],
                    "foo" => "foo_value"

                ],
                2 => [
                    "embed2" => [
                        "embed2_test" => "embed2_test_value2",
                        "number_field" => "12"
                    ],
                    "foo" => "test"

                ]
            ],
            "choice_field" => "choice_1",
            "first_level" => 123
        ];
    }

    public function makeRequest(string $method = "GET", $data): \Psr\Http\Message\ServerRequestInterface
    {

        $request = $this->getMockBuilder(\Psr\Http\Message\ServerRequestInterface::class)
            ->getMock();

        $request->method("getMethod")->willReturn($method);
        if ($request->getMethod() == "GET") {
            $request->method("getQueryParams")->willReturn($data);
        } elseif ($request->getMethod() == "POST") {
            $request->method("getParsedBody")->willReturn($data);
        }

        return $request;
    }

    public function testToArray(){
        $form = $this->makeForm();
        $form->handleData($this->getData());

        $this->assertEquals($form->toArray()["fields"]["choice_field"], [
            "type" => "choices",
            "choices" => ["choice_1" => "Choice 1", "choice_2" => "Choice 2", "choice_3" => "Choice 3"],
            "name" => "choice_field",
            "value" => "choice_1",
            "errors" => []
        ]);
    }


    public function testFormValidationSuccess()
    {
        $form = $this->makeForm();
        $form->handleData($this->getData());


        $this->assertEquals($form->validate(), true);
    }

    public function testFormValidationError()
    {
        $form = $this->makeForm();
        $form->handleData($this->getData());

        $form->get("first_level")->setValue("test");
        $this->assertEquals($form->validate(), false);
    }

    public function testBinding()
    {
        $form = $this->makeForm();
        $form->handleData($this->getData());
        $this->assertEquals($this->getData(), $form->getData());
    }

    public function testHandleRequestGet()
    {
        $request = $this->makeRequest("GET", $this->getData());
        $form = $this->makeForm();

        $form->handleRequest($request);

        $this->assertEquals($this->getData(), $form->getData());
    }

    public function testHandleRequestPost()
    {
        $request = $this->makeRequest("POST", $this->getData());
        $form = $this->makeForm();

        $form->handleRequest($request);

        $this->assertEquals($this->getData(), $form->getData());
    }


}
