<?php

/**
 *
 * Function code for the complex coth() function
 *
 */
namespace Complex;

/**
 * Returns the hyperbolic cotangent of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The hyperbolic cotangent of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 * @throws    \InvalidArgumentException    If function would result in a division by zero
 */
function coth($complex)
{
    $complex = Complex::validateComplexArgument($complex);
    return inverse(tanh($complex));
}
