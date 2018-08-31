<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag\PhpDocumentor;

use Jasny\Annotations\Tag\AbstractTag;
use Jasny\Annotations\AnnotationException;
use function Jasny\array_only;

/**
 * Custom logic for PhpDocumentor 'var', 'param' and 'property' tag
 */
class VarTag extends AbstractTag
{
    /**
     * @var array
     */
    protected $additional;

    /**
     * @var callable|null
     */
    protected $fqsenConvertor;

    /**
     * Class constructor.
     *
     * @param string        $name            Tag name
     * @param callable|null $fqsenConvertor  Logic to convert class to FQCN
     * @param array         $additional      Additional properties
     */
    public function __construct(string $name, ?callable $fqsenConvertor = null, array $additional = [])
    {
        parent::__construct($name);

        $this->fqsenConvertor = $fqsenConvertor;
        $this->additional = $additional;
    }

    /**
     * Get additional properties that are always applied.
     *
     * @return array
     */
    public function getAdditionalProperties(): array
    {
        return $this->additional;
    }

    /**
     * Process an annotation.
     *
     * @param array  $annotations
     * @param string $value
     * @return array
     * @throws AnnotationException
     */
    public function process(array $annotations, string $value): array
    {
        $regexp = '/^(?:(?<type>[^$\s]+)\s*)?(?:\$(?<name>\w+)\s*)?(?:"(?<id>[^"]+)")?/';
        preg_match($regexp, $value, $props); //regexp won't fail

        if (isset($props['type']) && $props['type'] === '') {
            unset($props['type']);
        }

        if (isset($props['type']) && isset($this->fqsenConvertor)) {
            $props['type'] = call_user_func($this->fqsenConvertor, $props['type']);
        }

        $props = array_only($props, ['type', 'name', 'id']);

        $annotations[$this->name] = $props + $this->additional;

        return $annotations;
    }
}
