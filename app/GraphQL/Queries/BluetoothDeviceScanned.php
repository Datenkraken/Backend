<?php

namespace App\GraphQL\Queries;

use App\Models\Datamining\BluetoothDeviceScan;
use Ds\Map;
use Ds\Set;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class BluetoothDeviceScanned
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field.
     * In this case, it is always `null`.
     * @param  mixed  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that
     * is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query
     * itself, such as the execution state, the field name, path to the field from the root, and more.
     *
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $data = BluetoothDeviceScan::orderBy('timestamp', 'asc')->get();

        $connections = [];
        $users = new Map();
        $users_array = [];
        $devices = new Map();
        $devices_array = [];

        $user_map_size = 0;
        $device_map_size = 0;
        foreach($data as $record) {
            $user_index = $users->get($record->user_id, -1);
            if ($user_index == -1) {
                $users->put($record->user_id, $user_map_size);
                $users_array[] = $record->user_id;
                $user_index = $user_map_size;
                $user_map_size++;
            }

            $device_index = $devices->get($record->address, null);

            if ($device_index == null) {
                $devices->put($record->address, $device_map_size);
                $devices_array[] = [
                    'a' => $record->address,
                    'n' => $record->name,
                ];

                $device_index = $device_map_size;
                $device_map_size++;
            } else if($record->name != null && $record->name != "") {
                $devices_array[$device_index]['n'] = $record->name;
            }

            $connections[] = [
                "t" => $record->timestamp->getTimestamp(),
                "u" => $user_index,
                "d" => $device_index,
                "k" => $record->known];
        }

        return [
            "scans" => $connections,
            "users" => $users_array,
            "devices" => $devices_array,
        ];
    }
}
