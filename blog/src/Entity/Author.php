<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Author
 *
 * @ORM\Table(name="author")
 * @ORM\Entity()
 */
class Author extends AbstractProfile
{
    const DISCRIMINATOR_COLUMN = 'author';
}
