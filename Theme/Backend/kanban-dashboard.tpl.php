<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Kanban
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/** @var \Modules\Kanban\Models\KanbanBoard[] $boards */
$boards = $this->getData('boards');

echo $this->getData('nav')->render(); ?>

<div class="row">
    <?php foreach ($boards as $board) :
        $url = UriFactory::build('{/prefix}kanban/board?{?}&id=' . $board->getId());
        ?>
    <div class="col-xs-12 col-sm-6 col-lg-3">
        <section class="portlet">
            <div class="portlet-head">
                <a href="<?= $url; ?>"><?= $this->printHtml($board->name); ?></a>
            </div>
            <div class="portlet-body">
                <article><?= $board->description; ?></article>
            </div>
            <div class="portlet-foot">
                 <div class="overflowfix">
                    <?php $tags = $board->getTags(); foreach ($tags as $tag) : ?>
                        <span class="tag" style="background: <?= $this->printHtml($tag->color); ?>"><?= $tag->icon !== null ? '<i class="' . $this->printHtml($tag->icon ?? '') . '"></i>' : ''; ?><?= $this->printHtml($tag->getTitle()); ?></span>
                    <?php endforeach; ?>
                    <a tabindex="0" href="<?= $url; ?>" class="button floatRight"><?= $this->getHtml('Open', '0', '0'); ?></a>
                </div>
            </div>
        </section>
        </a>
    </div>
    <?php endforeach; ?>
<div>
