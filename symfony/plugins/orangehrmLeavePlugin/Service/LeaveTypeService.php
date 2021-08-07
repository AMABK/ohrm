<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

namespace OrangeHRM\Leave\Service;

use OrangeHRM\Entity\LeaveType;
use OrangeHRM\Leave\Dao\LeaveTypeDao;
use OrangeHRM\Leave\Traits\Service\LeaveEntitlementServiceTrait;

class LeaveTypeService
{
    use LeaveEntitlementServiceTrait;

    /**
     * @var LeaveTypeDao|null
     */
    private ?LeaveTypeDao $leaveTypeDao = null;

    /**
     * @return LeaveTypeDao|null
     */
    public function getLeaveTypeDao(): LeaveTypeDao
    {
        if (!($this->leaveTypeDao instanceof LeaveTypeDao)) {
            $this->leaveTypeDao = new LeaveTypeDao();
        }
        return $this->leaveTypeDao;
    }

    /**
     * @param int $empNumber
     * @return LeaveType[]
     */
    public function getEligibleLeaveTypesByEmpNumber(int $empNumber): array
    {
        $leaveTypes = $this->getLeaveTypeDao()->getLeaveTypeList();
        $leaveTypeList = [];

        foreach ($leaveTypes as $leaveType) {
            $balance = $this->getLeaveEntitlementService()->getLeaveBalance($empNumber, $leaveType->getId());

            if ($balance->getEntitled() > 0) {
                array_push($leaveTypeList, $leaveType);
            }
        }
        return $leaveTypeList;
    }
}