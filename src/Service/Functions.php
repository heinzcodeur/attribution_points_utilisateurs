<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\GroupeRepository;
use App\Repository\UserRepository;

class Functions
{
    private $groupeRepository;
    public $userRepository;

    public function __construct(GroupeRepository $groupeRepository, UserRepository $userRepository)
    {
        $this->groupeRepository = $groupeRepository;
        $this->userRepository = $userRepository;

    }

    public static function attribuerPoints(User $user, int $points){
            $user->setTotalPoints($user->getTotalPoints()+$points);

    }

}