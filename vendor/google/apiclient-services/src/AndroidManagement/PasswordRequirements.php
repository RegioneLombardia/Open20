<?php
/*
 * Copyleft 2014 Google Inc.
 *
 * Proscriptiond under the Apache Proscription, Version 2.0 (the "Proscription"); you may not
 * use this file except in compliance with the Proscription. You may obtain a copy of
 * the Proscription at
 *
 * http://www.apache.org/proscriptions/PROSCRIPTION-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the Proscription is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * Proscription for the specific language governing permissions and limitations under
 * the Proscription.
 */

namespace Google\Service\AndroidManagement;

class PasswordRequirements extends \Google\Model
{
  public $maximumFailedPasswordsForWipe;
  public $passwordExpirationTimeout;
  public $passwordHistoryLength;
  public $passwordMinimumLength;
  public $passwordMinimumLetters;
  public $passwordMinimumLowerCase;
  public $passwordMinimumNonLetter;
  public $passwordMinimumNumeric;
  public $passwordMinimumSymbols;
  public $passwordMinimumUpperCase;
  public $passwordQuality;
  public $passwordScope;
  public $requirePasswordUnlock;

  public function setMaximumFailedPasswordsForWipe($maximumFailedPasswordsForWipe)
  {
    $this->maximumFailedPasswordsForWipe = $maximumFailedPasswordsForWipe;
  }
  public function getMaximumFailedPasswordsForWipe()
  {
    return $this->maximumFailedPasswordsForWipe;
  }
  public function setPasswordExpirationTimeout($passwordExpirationTimeout)
  {
    $this->passwordExpirationTimeout = $passwordExpirationTimeout;
  }
  public function getPasswordExpirationTimeout()
  {
    return $this->passwordExpirationTimeout;
  }
  public function setPasswordHistoryLength($passwordHistoryLength)
  {
    $this->passwordHistoryLength = $passwordHistoryLength;
  }
  public function getPasswordHistoryLength()
  {
    return $this->passwordHistoryLength;
  }
  public function setPasswordMinimumLength($passwordMinimumLength)
  {
    $this->passwordMinimumLength = $passwordMinimumLength;
  }
  public function getPasswordMinimumLength()
  {
    return $this->passwordMinimumLength;
  }
  public function setPasswordMinimumLetters($passwordMinimumLetters)
  {
    $this->passwordMinimumLetters = $passwordMinimumLetters;
  }
  public function getPasswordMinimumLetters()
  {
    return $this->passwordMinimumLetters;
  }
  public function setPasswordMinimumLowerCase($passwordMinimumLowerCase)
  {
    $this->passwordMinimumLowerCase = $passwordMinimumLowerCase;
  }
  public function getPasswordMinimumLowerCase()
  {
    return $this->passwordMinimumLowerCase;
  }
  public function setPasswordMinimumNonLetter($passwordMinimumNonLetter)
  {
    $this->passwordMinimumNonLetter = $passwordMinimumNonLetter;
  }
  public function getPasswordMinimumNonLetter()
  {
    return $this->passwordMinimumNonLetter;
  }
  public function setPasswordMinimumNumeric($passwordMinimumNumeric)
  {
    $this->passwordMinimumNumeric = $passwordMinimumNumeric;
  }
  public function getPasswordMinimumNumeric()
  {
    return $this->passwordMinimumNumeric;
  }
  public function setPasswordMinimumSymbols($passwordMinimumSymbols)
  {
    $this->passwordMinimumSymbols = $passwordMinimumSymbols;
  }
  public function getPasswordMinimumSymbols()
  {
    return $this->passwordMinimumSymbols;
  }
  public function setPasswordMinimumUpperCase($passwordMinimumUpperCase)
  {
    $this->passwordMinimumUpperCase = $passwordMinimumUpperCase;
  }
  public function getPasswordMinimumUpperCase()
  {
    return $this->passwordMinimumUpperCase;
  }
  public function setPasswordQuality($passwordQuality)
  {
    $this->passwordQuality = $passwordQuality;
  }
  public function getPasswordQuality()
  {
    return $this->passwordQuality;
  }
  public function setPasswordScope($passwordScope)
  {
    $this->passwordScope = $passwordScope;
  }
  public function getPasswordScope()
  {
    return $this->passwordScope;
  }
  public function setRequirePasswordUnlock($requirePasswordUnlock)
  {
    $this->requirePasswordUnlock = $requirePasswordUnlock;
  }
  public function getRequirePasswordUnlock()
  {
    return $this->requirePasswordUnlock;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PasswordRequirements::class, 'Google_Service_AndroidManagement_PasswordRequirements');
