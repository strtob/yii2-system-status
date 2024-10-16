<?php 

namespace strtob\yii2SystemStatus\models;

use yii\db\ActiveRecord;
use Yii;

class SystemCheck extends ActiveRecord
{
    public static function tableName()
    {
        return Yii::$app->db->tablePrefix . 'system_check';
    }

    public function executeCommand()
    {
        // Execute the command and return the result
        return shell_exec($this->command);
    }

    public function getStatus()
    {
        // Execute the command to get the current value
        return trim($this->executeCommand());
    }

    public function checkCriteria($value, $criteria, $criteria2 = null)
    {
        // Check the first criteria
        $firstCheck = $this->evaluateCriteria($value, $criteria);

        // If criteria2 is provided, check it too
        $secondCheck = $criteria2 !== null ? $this->evaluateCriteria($value, $criteria2) : 0;

        // Return 1 if at least one criteria is fulfilled
        return ($firstCheck === 1 || $secondCheck === 1) ? 1 : 0;
    }

    private function evaluateCriteria($value, $criteria)
    {
        // Check if the criteria has an operator
        if (preg_match('/^(\>=|\<=|>|<|=|!=|<>)(.*)$/', $criteria, $matches)) {
            $operator = trim($matches[1]);
            $expectedValue = trim($matches[2]);
        } else {
            // If no operator is specified, default to '='
            $operator = '=';
            $expectedValue = trim($criteria);
        }

        // Convert human-readable sizes like 8M, 2000M to bytes for comparison
        $valueInBytes = $this->convertToBytes($value);
        $expectedValueInBytes = $this->convertToBytes($expectedValue);

        // Perform numeric comparison on the byte values
        if ($valueInBytes !== false && $expectedValueInBytes !== false) {
            switch ($operator) {
                case '>=':
                    return $valueInBytes >= $expectedValueInBytes ? 1 : 0;
                case '<=':
                    return $valueInBytes <= $expectedValueInBytes ? 1 : 0;
                case '>':
                    return $valueInBytes > $expectedValueInBytes ? 1 : 0;
                case '<':
                    return $valueInBytes < $expectedValueInBytes ? 1 : 0;
                case '=':
                    return $valueInBytes == $expectedValueInBytes ? 1 : 0;
                case '!=':
                case '<>':
                    return $valueInBytes != $expectedValueInBytes ? 1 : 0;
                case '':
                    return $valueInBytes == $expectedValueInBytes ? 1 : 0;
            }
        }

        // Handle version comparisons if the expected value looks like a version
        if (preg_match('/^\d+(\.\d+)*$/', $value) && preg_match('/^\d+(\.\d+)*$/', $expectedValue)) {
            switch ($operator) {
                case '>=':
                    return version_compare($value, $expectedValue, '>=') ? 1 : 0;
                case '<=':
                    return version_compare($value, $expectedValue, '<=') ? 1 : 0;
                case '>':
                    return version_compare($value, $expectedValue, '>') ? 1 : 0;
                case '<':
                    return version_compare($value, $expectedValue, '<') ? 1 : 0;
                case '=':
                    return version_compare($value, $expectedValue, '=') ? 1 : 0;
                case '!=':
                case '<>':
                    return version_compare($value, $expectedValue, '!=') ? 1 : 0;
            }
        }

        // String comparisons for other non-numeric values
        if (is_string($value) && is_string($expectedValue)) {
            // Convert both values to lowercase for a case-insensitive comparison
            $valueLower = strtolower(trim($value));
            $expectedLower = strtolower(trim($expectedValue));

            // Additional conditions to check for "Enabled" or "Disabled"
            if ($expectedLower === 'enabled') {
                return in_array($valueLower, ['1', 'true', 'enabled'], true) ? 1 : 0;
            } elseif ($expectedLower === 'disabled') {
                return in_array($valueLower, ['0', 'false', 'disabled'], true) ? 1 : 0;
            }
            // Check for string containment
            return strpos($valueLower, $expectedLower) !== false ? 1 : 0;
        }

        return -1;
    }

    // Helper function to convert human-readable size (e.g., 8M) into bytes
    private function convertToBytes($size)
    {
        if (preg_match('/^(\d+)([KMG]?)$/i', $size, $matches)) {
            $value = (int)$matches[1];
            $unit = strtoupper($matches[2]);

            switch ($unit) {
                case 'G':
                    return $value * 1024 * 1024 * 1024;
                case 'M':
                    return $value * 1024 * 1024;
                case 'K':
                    return $value * 1024;
                default:
                    return $value;
            }
        }
        return false; // Return false if it's not a valid size format
    }

    // New method to get children by parent ID
    public static function getByParentId($parentId)
    {
        return self::find()
            ->andWhere(['parent_id' => $parentId])
            ->orderBy(['order_by' => SORT_ASC])
            ->all();
    }
}
