<?php

namespace App\Repository;

use App\Entity\Destination;
use Faker\Factory;

class DestinationRepository implements Repository
{
    /**
     * @param int $id
     *
     * @return Destination
     */
    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD
        $generator    = \Faker\Factory::create();
        $generator->seed($id);

        return new Destination(
            $id,
            $generator->country,
            'en',
            $generator->slug()
        );
    }
}
