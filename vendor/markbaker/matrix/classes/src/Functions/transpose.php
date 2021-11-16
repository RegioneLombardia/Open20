<?php

/**
 *
 * Function code for the matrix transpose() function
 *
 */

namespace Matrix;

/**
 * Returns the transpose of a matrix or an array.
 *
 * @param Matrix|array $matrix Matrix or an array to treat as a matrix.
 * @return Matrix The transposed matrix
 * @throws Exception If argument isn't a valid matrix or array.
 */
function transpose($matrix)
{
    if (is_array($matrix)) {
        $matrix = new Matrix($matrix);
    }
    if (!$matrix instanceof Matrix) {
        throw new Exception('Must be Matrix or array');
    }

    return Functions::transpose($matrix);
}
