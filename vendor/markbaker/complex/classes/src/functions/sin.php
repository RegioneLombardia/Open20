<?php

/**
 *
 * Function code for the complex sin() function
 *
 */
namespace Complex;

/**
 * Returns the sine of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The sine of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 */
function sin($complex)
{
    $complex = Complex::validateComplexArgument($complex);

    if ($complex->isReal()) {
        return new Complex(\sin($complex->getReal()));
    }

    return new Complex(
        \sin($complex->getReal()) * \cosh($complex->getImaginary()),
        \cos($complex->getReal()) * \sinh($complex->getImaginary()),
        $complex->getSuffix()
    );
}
