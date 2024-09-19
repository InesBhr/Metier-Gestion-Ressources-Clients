<?php

declare(strict_types=1);

/**
 * Ecosystème PHP d'Orange
 *
 * Copyright (C) 2020  Orange / CCPHP (ZZZ CDC PHP <cdc.php@orange.com>)
 *
 * This software is confidential and proprietary information of Orange.
 * You shall not disclose such Confidential Information and shall use it only in
 * accordance with the terms of the agreement you entered into.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * If you are Orange employee you shall use this software in accordance with
 * the Orange Source Charter (http://opensource.itn.ftgroup/index.php/Orange_Source_Charter)
 */

namespace App\Entity\Core;

use App\Repository\Core\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Epo\UserEntityBundle\Entity\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class that represent a User in your application.
 *
 * This class now belongs to your project, so you can add (or modify existing) properties, methods, annotations, ...
 *
 **/
#[ORM\Table(name: '010_epo_user')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: 'email', message: 'E-mail address is already registered')]
#[UniqueEntity(fields: 'username', message: 'Username is already registered')]
#[ORM\HasLifecycleCallbacks]
class User extends BaseUser
{
}
