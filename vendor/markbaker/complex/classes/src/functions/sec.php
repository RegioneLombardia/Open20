<?php

/**
 *
 * Function code for the complex sec() function
 *
 */
namespace Complex;

/**
 * Returns the secant of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The secant of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 * @throws    \InvalidArgumentException    If function would result in a division by zero
 */
function sec($complex)
{
    $complex = Complex::validateComplexArgument($complex);

    return inverse(cos($complex));
}
