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
 * Class Editor
 *
 * @ORM\Table(name="editor")
 * @ORM\Entity()
 */
class Editor extends AbstractProfile
{
    const DISCRIMINATOR_COLUMN = 'editor';
}
