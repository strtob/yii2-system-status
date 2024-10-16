<?php
namespace strtob\yii2SystemStatus\controllers;

use yii\web\Controller;
use strtob\yii2SystemStatus\models\SystemCheck;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        // Fetch parent checks ordered by 'order_by'
        $checks = SystemCheck::find()
            ->where(['parent_id' => null])
            ->orderBy('order_by')
            ->all();

        $results = [];

        foreach ($checks as $check) {
            // Get children for the current parent check
            $children = SystemCheck::getByParentId($check->id);

            // Initialize comment and check status
            $comment = trim($check->comment); // Ensure this is defined

            if (!empty($children)) {
                // If the check has children, add the parent without value, criteria, or status
                $results[] = [
                    'parameter' => $check->parameter,
                    'value' => '',  // No value for parent
                    'criteria' => '', // No criteria for parent
                    'criteria2' => '', // No criteria2 for parent
                    'status' => '', // No status for parent
                    'check' => '',
                    'comment' => $comment, // Add comment here
                    'children' => [], // Initialize children array
                ];
            } else {
                // If the check has no children, execute the command and check criteria
                $value = trim($check->executeCommand());
                $criteria = trim($check->criteria);
                $criteria2 = trim($check->criteria2); // Get the second criteria
                $status = trim($check->status);
                $checkStatus = $check->checkCriteria($value, $criteria); // Store the result of checkCriteria

                // Add the check with its value, criteria, and status
                $results[] = [
                    'parameter' => $check->parameter, // Access the original check object
                    'value' => $value,
                    'criteria' => $criteria, // Original criteria
                    'criteria2' => $criteria2, // Add criteria2
                    'status' => $status,
                    'check' => $checkStatus, // Store the result of checkCriteria
                    'comment' => $comment, // Ensure comment is added
                    'children' => [], // Initialize empty children array
                ];
            }

            // If there are children, calculate their statuses and add them to the results
            if (!empty($children)) {
                foreach ($children as $child) {
                    $childValue = trim($child->executeCommand());
                    $childCriteria = trim($child->criteria);
                    $childCriteria2 = trim($child->criteria2); // Get the second criteria
                    $childStatus = trim($child->status);
                    $childCheck = $child->checkCriteria($childValue, $childCriteria);
                    $childComment = trim($child->comment); // Ensure child comment is set
                    
                    // Add each child to the last result entry
                    $results[count($results) - 1]['children'][] = [
                        'parameter' => $child->parameter,
                        'value' => $childValue,
                        'criteria' => $childCriteria, // Add original child criteria
                        'criteria2' => $childCriteria2, // Add criteria2 for child
                        'status' => $childStatus,
                        'check' => $childCheck, // Add check status for badge
                        'comment' => $childComment, // Add child comment
                    ];
                }
            }
        }

        return $this->render('index', ['results' => $results]);
    }
}
