<?php

/**
 *
 * Function code for the complex rho() function
 *
 */
namespace Complex;

/**
 * Returns the rho of a complex number.
 * This is the distance/radius from the centrepoint to the representation of the number in polar coordinates.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    float            The rho value of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 */
function rho($complex)
{
    $complex = Complex::validateComplexArgument($complex);

    return \sqrt(
        ($complex->getReal() * $complex->getReal()) +
        ($complex->getImaginary() * $complex->getImaginary())
    );
}
