<?php

/**
 *
 * Function code for the matrix division operation
 *
 */

namespace Matrix;

use Matrix\Operators\Division;

/**
 * Divides two or more matrix numbers
 *
 * @param array<int, mixed> $matrixValues The matrices to divide
 * @return Matrix
 * @throws Exception
 */
function divideby(...$matrixValues)
{
    if (count($matrixValues) < 2) {
        throw new Exception('Division operation requires at least 2 arguments');
    }

    $matrix = array_shift($matrixValues);

    if (is_array($matrix)) {
        $matrix = new Matrix($matrix);
    }
    if (!$matrix instanceof Matrix) {
        throw new Exception('Division arguments must be Matrix or array');
    }

    $result = new Division($matrix);

    foreach ($matrixValues as $matrix) {
        $result->execute($matrix);
    }

    return $result->result();
}
