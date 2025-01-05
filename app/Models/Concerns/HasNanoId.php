<?php

namespace App\Models\Concerns;

use Hidehalo\Nanoid\Client;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

trait HasNanoId
{
    use HasUuids;

    /**
     * Get the length of the unique ID.
     *
     * @return int
     */
    public function lengthUniqueId(): int
    {
        return 8;
    }

    /**
     * Generate a new unique key for the model.
     *
     * @return string
     */
    public function newUniqueId()
    {
        return (new Client)->generateId($this->lengthUniqueId(), Client::MODE_DYNAMIC);
    }

    /**
     * Determine if given key is valid.
     *
     * @param mixed $value
     *
     * @return bool
     */
    protected function isValidUniqueId($value): bool
    {
        return true;
    }
}
