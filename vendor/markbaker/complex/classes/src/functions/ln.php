<?php

/**
 *
 * Function code for the complex ln() function
 *
 */
namespace Complex;

/**
 * Returns the natural logarithm of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The natural logarithm of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 * @throws    \InvalidArgumentException  If the real and the imaginary parts are both zero
 */
function ln($complex)
{
    $complex = Complex::validateComplexArgument($complex);

    if (($complex->getReal() == 0.0) && ($complex->getImaginary() == 0.0)) {
        throw new \InvalidArgumentException();
    }

    return new Complex(
        \log(rho($complex)),
        theta($complex),
        $complex->getSuffix()
    );
}
