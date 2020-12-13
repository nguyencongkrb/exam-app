<?php
namespace App\Repositories\User;

use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\User::class;
    }

    public function getUserInfo()
    {
        return $this->model->get();
    }
}
