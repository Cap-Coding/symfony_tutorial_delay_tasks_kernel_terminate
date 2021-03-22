<?php

declare(strict_types=1);

namespace App\Dto\Transformer;

interface DtoTransformerInterface
{
    public function transformFromObject($object);
    public function transformFromObjects(iterable $objects): iterable;
}