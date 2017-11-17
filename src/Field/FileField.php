<?php
/**
 * Created by IntelliJ IDEA.
 * User: nico
 * Date: 11/11/17
 * Time: 7:54 PM
 */

namespace Clea\Form\Field;


use Clea\Form\Field;
use Clea\Form\FieldInterface;
use Clea\Tools\Util;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Http\UploadedFile;

class FileField extends Field
{
    /**
     * @var UploadedFileInterface
     */
    private $uploadedFile;

    /**
     * @param $value
     * @return FieldInterface
     */
    public function setValue($value): FieldInterface
    {
        if ($value instanceof UploadedFileInterface) {
            $this->setUploadedFile($value);
        }
        parent::setValue($value);
        return $this;
    }

    public function moveTo(string $directory, string $filename): string
    {

        $path = Util::joinPaths($directory, $filename);
        $this->getUploadedFile()->moveTo($path);
        $this->setValue($path);
        return $path;
    }

    /**
     * @return UploadedFileInterface
     */
    public function getUploadedFile(): ?UploadedFileInterface
    {
        return $this->uploadedFile;
    }

    /**
     * @param UploadedFileInterface $uploadedFile
     */
    public function setUploadedFile(UploadedFileInterface $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }


}