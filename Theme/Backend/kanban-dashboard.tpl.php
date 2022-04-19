<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Kanban
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/** @var \Modules\Kanban\Models\KanbanBoard[] $boards */
$boards = $this->getData('boards');

$previous = empty($boards) ? '{/prefix}kanban/dashboard' : '{/prefix}kanban/dashboard?{?}&id=' . \reset($boards)->getId() . '&ptype=p';
$next     = empty($boards) ? '{/prefix}kanban/dashboard' : '{/prefix}kanban/dashboard?{?}&id=' . \end($boards)->getId() . '&ptype=n';

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
                        <span class="tag" style="background: <?= $this->printHtml($tag->color); ?>"><?= $tag->icon !== null ? '<i class="' . $this->printHtml($tag->icon ?? '') . '"></i>' : ''; ?><?= $this->printHtml($tag->getL11n()); ?></span>
                    <?php endforeach; ?>
                    <a tabindex="0" href="<?= $url; ?>" class="button floatRight"><?= $this->getHtml('Open', '0', '0'); ?></a>
                </div>
            </div>
        </section>
    </div>
    <?php endforeach; ?>

    <?php if (empty($boards)) : ?>
    <div class="emptyPage"></div>
    <?php else : ?>
    <div class="plain-portlet">
        <a tabindex="0" class="button" href="<?= UriFactory::build($previous); ?>"><?= $this->getHtml('Previous', '0', '0'); ?></a>
        <a tabindex="0" class="button" href="<?= UriFactory::build($next); ?>"><?= $this->getHtml('Next', '0', '0'); ?></a>
    </div>
    <?php endif; ?>
<div>
