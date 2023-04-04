<?php

namespace Modules\Support\Exports;

interface DocumentsInterface
{
    /**
     * @return array
     */
    public function getData($company): array;

    /**
     * @return bool
     */
    public function isAvailable($company): bool;
}
