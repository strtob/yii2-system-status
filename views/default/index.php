<?php

/* @var $this yii\web\View */

use kartik\grid\GridView;
use strtob\yii2SystemStatus\assets\AppAsset;
use kartik\helpers\Html;

AppAsset::register($this);

$this->title = \Yii::t('app', 'System Status');

?>
<div id="system-status-index" class="form-container">

    <div class="card h-100">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1"><?= \Yii::t('app', 'System Status') ?></h4>
        </div><!-- end card header -->

        <div class="card-body">
            <input type="text" id="searchInput" placeholder="<?= \Yii::t('app', '(Search)') ?>">

            <table id="systemStatusTable" class="table">
                <thead>
                    <tr class="header">
                        <th style="width:20%;"><?= \Yii::t('app', 'Parameter') ?></th>
                        <th style="width:20%;"><?= \Yii::t('app', 'System Value') ?></th>
                        <th style="width:15%;"><?= \Yii::t('app', 'Criteria A') ?></th>
                        <th style="width:15%;"><?= \Yii::t('app', 'Criteria B') ?></th>
                        <th style="width:10%;"><?= \Yii::t('app', 'Check / Test') ?></th>
                        <th style="width:20%;"><?= \Yii::t('app', 'Comment') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result): ?>
                        <tr<?= ($result['criteria'] !== '') ? : ' class="systemStatusTableRowHeader"' ?>>
                            <td><?= Html::encode($result['parameter']) ?></td>
                            <td><?= Html::encode($result['value']) ?></td>
                            <td><?= Html::encode($result['criteria']) ?></td>
                            <td><?= Html::encode($result['criteria2']) ?></td>
                            <td>
                                <?php if ($result['criteria'] !== ''): ?>
                                    <?php if ($result['check'] == '1'): ?>
                                        <span class="badge bg-success"><?= yii::t('app', 'OK') ?></span>
                                    <?php elseif ($result['check'] == '-1') : ?>
                                        <span class="badge bg-warning"><?= yii::t('app', 'UNKNOWN') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-warning"><?= yii::t('app', 'UNKNOWN') ?></span>
                                    <?php endif; ?>
                                <?php endif ?>
                            </td>
                            <td><?= Html::encode($result['comment']) ?></td>
                        </tr>

                        <?php if (!empty($result['children'])): ?>
                            <?php foreach ($result['children'] as $child): ?>
                                <tr>
                                    <td class="system-check-child"><?= Html::encode($child['parameter']) ?></td>
                                    <td><?= Html::encode($child['value']) ?></td>
                                    <td><?= Html::encode($child['criteria']) ?></td>
                                    <td><?= Html::encode($child['criteria2']) ?></td>
                                    <td>
                                        <?php if ($child['check'] == '1'): ?>
                                            <span class="badge bg-success"><?= yii::t('app', 'OK') ?></span>
                                        <?php elseif ($child['check'] == '-1') : ?>
                                            <span class="badge bg-warning"><?= yii::t('app', 'UNKNOWN') ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-warning"><?= yii::t('app', 'UNKNOWN') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= Html::encode($child['comment']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>