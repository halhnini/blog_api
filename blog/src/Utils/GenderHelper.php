<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Utils;

/**
 * Class GenderHelper
 */
class GenderHelper
{
    const MISTER_CODE = 0;
    const MADAME_CODE = 1;
    const MISS_CODE = 2;
    const GENDER_CODE_LABEL = [
        self::MISTER_CODE => 'MISTER',
        self::MADAME_CODE => 'MADAME',
        self::MISS_CODE => 'MISS',
    ];

    /**
     * Get gender label from the given gender code.
     *
     * @param int $code
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public static function getGenderLabel(int $code): string
    {
        if (!array_key_exists($code, self::GENDER_CODE_LABEL)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid gender code [%d] given !', $code)
            );
        }

        return self::GENDER_CODE_LABEL[$code];
    }

    /**
     * Get gender code from the given gender label.
     *
     * @param string $label
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    public static function getGenderCode(string $label): int
    {
        if (false === $code = array_search($label, self::GENDER_CODE_LABEL)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid gender label [%s] given !', $label)
            );
        }

        return $code;
    }
}
