<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\Tasks
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

$boards = $this->getData('boards');

echo $this->getData('nav')->render(); ?>

<div class="row">
    <?php foreach ($boards as $board) : ?>
    <div class="col-xs-12 col-sm-6 col-lg-3">
        <a href="<?= $this->printHtml(UriFactory::build('{/prefix}kanban/board?{?}&id=' . $board->getId())); ?>">
        <section class="portlet">
            <div class="portlet-head"><?= $this->printHtml($board->getName()); ?></div>
            <div class="portlet-body">
                <?= $this->printHtml($board->getDescription()); ?>
            </div>
        </section>
        </a>
    </div>
    <?php endforeach; ?>
<div>