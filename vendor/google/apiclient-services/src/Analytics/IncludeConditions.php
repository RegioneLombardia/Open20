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

namespace Google\Service\Analytics;

class IncludeConditions extends \Google\Model
{
  public $daysToLookBack;
  public $isSmartList;
  public $kind;
  public $membershipDurationDays;
  public $segment;

  public function setDaysToLookBack($daysToLookBack)
  {
    $this->daysToLookBack = $daysToLookBack;
  }
  public function getDaysToLookBack()
  {
    return $this->daysToLookBack;
  }
  public function setIsSmartList($isSmartList)
  {
    $this->isSmartList = $isSmartList;
  }
  public function getIsSmartList()
  {
    return $this->isSmartList;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setMembershipDurationDays($membershipDurationDays)
  {
    $this->membershipDurationDays = $membershipDurationDays;
  }
  public function getMembershipDurationDays()
  {
    return $this->membershipDurationDays;
  }
  public function setSegment($segment)
  {
    $this->segment = $segment;
  }
  public function getSegment()
  {
    return $this->segment;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(IncludeConditions::class, 'Google_Service_Analytics_IncludeConditions');
