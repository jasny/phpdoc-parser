<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Tag\PhpDocumentor;

use Jasny\PhpdocParser\PhpdocException;
use Jasny\PhpdocParser\Tag\AbstractTag;
use function Jasny\array_only;

/**
 * Use the first word of tag as type, the rest as desciption
 */
class TypeTag extends AbstractTag
{
    /**
     * @var callable|null
     */
    protected $fqsenConvertor;

    /**
     * WordTag constructor.
     *
     * @param string      $name
     * @param callable|null $fqsenConvertor  Logic to convert class to FQCN
     */
    public function __construct(string $name, ?callable $fqsenConvertor = null)
    {
        parent::__construct($name);

        $this->fqsenConvertor = $fqsenConvertor;
    }

    /**
     * Process a notation.
     *
     * @param array  $notations
     * @param string $value
     * @return array
     */
    public function process(array $notations, string $value): array
    {
        if ($value === '') {
            throw new PhpdocException("Failed to parse '@{$this->name}': tag value should not be empty");
        }

        $match = preg_match('/^(?<type>\S+)(?:\s+(?<description>.*))?/', $value, $data); //regexp won't fail

        $this->processType($data);
        $data = array_only($data, ['type', 'description']);

        $notations[$this->name] = $data;

        return $notations;
    }

    /**
     * Process type parameter
     *
     * @param array $data
     */
    protected function processType(array &$data): void
    {
        if (isset($data['type']) && $this->fqsenConvertor) {
            $data['type'] = call_user_func($this->fqsenConvertor, $data['type']);
        }
    }
}
