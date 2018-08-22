<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag\PhpDocumentor;

use Jasny\Annotations\Tag\AbstractTag;
use Jasny\Annotations\AnnotationException;

/**
 * Custom logic for PhpDocumentor 'method' tag
 */
class MethodTag extends AbstractTag
{
    /**
     * @var callable|null
     */
    protected $fqsenConvertor;

    /**
     * Class constructor.
     *
     * @param string        $name            Tag name
     * @param callable|null $fqsenConverter  Logic to convert class to FQCN
     */
    public function __construct(string $name, ?callable $fqsenConverter = null)
    {
        parent::__construct($name);

        $this->fqsenConvertor = $fqsenConverter;
    }

    /**
     * Process an annotation.
     *
     * @param array  $annotations
     * @param string $value
     * @return array
     */
    public function process(array $annotations, string $value): array
    {
        $regexp = '/^(?:(?<return_type>\S+)\s+)?(?<name>\w+)\((?<params>[^\)]+)?\)/';

        if (!preg_match($regexp, $value, $method)) {
            throw new AnnotationException("Failed to parse '@{$this->name} $value': invalid syntax");
        }

        if (isset($method['return_type']) && isset($this->fqsenConvertor)) {
            $method['return_type'] = call_user_func($this->fqsenConvertor, $method['return_type']);
        }

        $method['params'] = isset($method['params']) ? $this->processParams($value, $method['params']) : [];

        $annotations[$this->name] = $method;

        return $annotations;
    }

    /**
     * Process parameters from method annotation
     *
     * @param string $value  Input value
     * @param string $raw    Parameters string
     * @return array
     */
    protected function processParams(string $value, string $raw): array
    {
        $params = [];
        $rawParams = preg_split('/\s*,\s*/', $raw);

        $regexp = '/^(?:(?<type>[^$]+)\s+)?\$(?<name>\w+)(?:\s*=\s*(?<default>"[^"]+"|\[[^\]]+\]|[^,]+))?$/';

        foreach ($rawParams as $rawParam) {
            if (!preg_match($regexp, $rawParam, $param)) {
                throw new AnnotationException("Failed to parse '@{$this->name} {$value}': invalid syntax");
            }

            if (isset($param['type']) && isset($this->fqsenConvertor)) {
                $params['type'] = call_user_func($this->fqsenConvertor, $params['type']);
            }

            $params[$param['name']] = $param;
        }

        return $params;
    }
}
