<?php

/**
 *
 * Function code for the complex negative() function
 *
 */
namespace Complex;

/**
 * Returns the negative of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    float            The negative value of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 *
 *
 */
function negative($complex)
{
    $complex = Complex::validateComplexArgument($complex);

    return new Complex(
        -1 * $complex->getReal(),
        -1 * $complex->getImaginary(),
        $complex->getSuffix()
    );
}
