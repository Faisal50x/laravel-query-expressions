<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Language;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use RuntimeException;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class Cast implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    /**
     * @param  'bigint'|'int'  $type
     */
    public function __construct(
        private readonly string|Expression $expression,
        private readonly string $type,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $expression = $this->stringize($grammar, $this->expression);
        $type = match ($this->type) {
            'bigint' => $this->typeBigInt($grammar),

            'int' => $this->typeInt($grammar),
            default => throw new RuntimeException("Unknown casting type '{$this->type}'."),
        };

        return match ($this->identify($grammar)) {
            'pgsql' => "({$expression})::{$type}",
            default => "(cast({$expression} as {$type}))",
        };
    }

    private function typeBigInt(Grammar $grammar): string
    {
        return match ($this->identify($grammar)) {
            'mysql' => 'signed',
            default => 'bigint',
        };
    }

    private function typeInt(Grammar $grammar): string
    {
        return match ($this->identify($grammar)) {
            'mysql' => 'signed',
            default => 'int',
        };
    }
}
