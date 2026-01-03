<?php

namespace App\Services\Admin\Vehicles;

use App\Services\Admin\Vehicles\Actions\CreateVehicle;

class VehicleWriteService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected CreateVehicle $createVehicle
    ) {}

    public function create(array $payload, array $uploadedTempPaths = [])
    {
        return $this->createVehicle->handle($payload, $uploadedTempPaths);
    }
}
