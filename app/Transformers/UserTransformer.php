<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/14
 * Time: 17:27
 */

namespace App\Transformers;

use App\Models\User;

class UserTransformer extends Transformer
{
    public function transform(User $user)
    {
        return [

            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => (string) $user->created_at,
            'updated_at' => (string) $user->updated_at,

        ];
    }
}