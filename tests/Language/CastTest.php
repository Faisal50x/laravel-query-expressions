<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Language\Cast;

it('can cast a column to an bigint')
    ->expect(new Cast('val', 'bigint'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->string('val');
    })
    ->toBeMysql('cast(`val` as signed)')
    ->toBePgsql('("val")::bigint')
    ->toBeSqlite('cast("val" as bigint)')
    ->toBeSqlsrv('cast([val] as bigint)');

it('can cast an expression to an bigint')
    ->expect(new Cast(new Expression(3.1415), 'bigint'))
    ->toBeExecutable()
    ->toBeMysql('cast(3.1415` as signed)')
    ->toBePgsql('(3.1415)::bigint')
    ->toBeSqlite('cast(3.1415 as bigint)')
    ->toBeSqlsrv('cast(3.1415 as bigint)');

it('can cast a column to an int')
    ->expect(new Cast('val', 'int'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->string('val');
    })
    ->toBeMysql('cast(`val` as signed)')
    ->toBePgsql('("val")::int')
    ->toBeSqlite('cast("val" as int)')
    ->toBeSqlsrv('cast([val] as int)');

it('can cast an expression to an int')
    ->expect(new Cast(new Expression(3.1415), 'int'))
    ->toBeExecutable()
    ->toBeMysql('cast(3.1415` as signed)')
    ->toBePgsql('(3.1415)::int')
    ->toBeSqlite('cast(3.1415 as int)')
    ->toBeSqlsrv('cast(3.1415 as int)');

it('throws an exception for unknown types', fn (string $type) => new Cast('0', $type))->with([
    'enum',
    'varchar',
    'timestamptz',
]);
