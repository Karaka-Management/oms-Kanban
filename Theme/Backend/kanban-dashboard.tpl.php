<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Kanban
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/** @var \Modules\Kanban\Models\KanbanBoard[] $boards */
$boards = $this->data['boards'];

$previous = empty($boards) ? 'kanban/dashboard' : 'kanban/dashboard?{?}&id=' . \reset($boards)->id . '&ptype=p';
$next     = empty($boards) ? 'kanban/dashboard' : 'kanban/dashboard?{?}&id=' . \end($boards)->id . '&ptype=n';

echo $this->data['nav']->render(); ?>

<div class="row">
    <?php if (empty($boards)) : ?>
        <div class="emptyPage"></div>
        <?php else : ?>
        <div class="plain-portlet">
            <a tabindex="0" class="button" href="<?= UriFactory::build($previous); ?>"><?= $this->getHtml('Previous', '0', '0'); ?></a>
            <a tabindex="0" class="button" href="<?= UriFactory::build($next); ?>"><?= $this->getHtml('Next', '0', '0'); ?></a>
        </div>
    <?php endif; ?>
</div>

<div class="row">
    <?php foreach ($boards as $board) :
        $url = UriFactory::build('{/base}/kanban/board?{?}&id=' . $board->id);
    ?>
    <div class="col-xs-12 col-sm-6 col-lg-3">
        <section class="portlet">
            <div class="portlet-head">
                <a href="<?= $url; ?>"><?= $this->printHtml($board->name); ?></a>
            </div>
            <div class="portlet-body">
                <article><?= $board->description; ?></article>
                <div class="tag-list">
                <?php foreach ($board->tags as $tag) : ?>
                    <span class="tag" style="background: <?= $this->printHtml($tag->color); ?>">
                        <?= empty($tag->icon) ? '' : '<i class="g-icon">' . $this->printHtml($tag->icon) . '</i>'; ?>
                        <?= $this->printHtml($tag->getL11n()); ?>
                    </span>
                <?php endforeach; ?>
                </div>
            </div>
            <div class="portlet-foot">
                 <div class="overflowfix">
                    <a tabindex="0" href="<?= $url; ?>" class="button rf"><?= $this->getHtml('Open', '0', '0'); ?></a>
                </div>
            </div>
        </section>
    </div>
    <?php endforeach; ?>
<div>
