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

/** @var \Modules\Kanban\Models\KanbanBoard $board */
$board = $this->getData('board');

/** @var \Modules\Kanban\Models\KanbanColumn[] $columns */
$columns = $board->getColumns();
?>
<div class="row">
    <?php $i = 0; foreach ($columns as $column) : $i++; $cards = $column->getCards(); ?>
    <div id="kanban-column-<?= $i; ?>" class="col-xs-12 col-md-6 col-lg-3 box kanban-column">
        <header><?= $this->printHtml($column->name); ?></header>
        <?php $j = 0; foreach ($cards as $card) : $j++;
            $url = \phpOMS\Uri\UriFactory::build('kanban/card?{?}&id=' . $card->getId());
        ?>
            <section id="kanban-card-<?= $this->printHtml($i . '-' . $j); ?>" class="portlet" draggable="true">
                <div class="portlet-head">
                    <a href="<?= $url; ?>"><?= $this->printHtml($card->name); ?></a>
                    <div><span class="tag"><?= $card->getCommentCount(); ?></span></div>
                </div>
                <div class="portlet-body">
                    <article><?= $card->description; ?></article>
                </div>
                <div class="portlet-foot">
                    <div class="overflowfix">
                        <?php $tags = $card->getTags(); foreach ($tags as $tag) : ?>
                            <span class="tag" style="background: <?= $this->printHtml($tag->color); ?>"><?= $tag->icon !== null ? '<i class="' . $this->printHtml($tag->icon ?? '') . '"></i>' : ''; ?><?= $this->printHtml($tag->getL11n()); ?></span>
                        <?php endforeach; ?>
                        <a href="<?= $url; ?>" class="button floatRight"><?= $this->getHtml('More', '0', '0'); ?></a>
                    </div>
                </div>
            </section>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>
