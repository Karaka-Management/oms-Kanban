<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\Kanban
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

/** @var \Modules\Kanban\Models\KanbanBoard $board */
$board = $this->data['board'];

/** @var \Modules\Kanban\Models\KanbanColumn[] $columns */
$columns = $board->getColumns();
?>
<div class="row kanban-board" style="flex-wrap: nowrap;">
    <?php $i = 0; foreach ($columns as $column) : $i++; $cards = $column->getCards(); ?>
    <div id="kanban-column-<?= $i; ?>" class="box col-xs-3" style="min-width: 300px;">
        <header><?= $this->printHtml($column->name); ?></header>
        <?php $j = 0; foreach ($cards as $card) : $j++;
            $url = \phpOMS\Uri\UriFactory::build('kanban/card?{?}&id=' . $card->id);
        ?>
            <section id="kanban-card-<?= $this->printHtml($i . '-' . $j); ?>" class="portlet" draggable="true">
                <div class="portlet-head">
                    <a href="<?= $url; ?>"><?= $this->printHtml($card->name); ?></a>
                    <span class="tag end-xs"><?= \count($card->commentList->comments); ?></span>
                </div>
                <div class="portlet-body">
                    <article><?= $card->description; ?></article>

                    <?php foreach ($card->tags as $tag) : ?>
                        <span class="tag" style="background: <?= $this->printHtml($tag->color); ?>">
                            <?= empty($tag->icon) ? '' : '<i class="g-icon">' . $this->printHtml($tag->icon) . '</i>'; ?>
                            <?= $this->printHtml($tag->getL11n()); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                <div class="portlet-foot">
                    <div class="overflowfix">
                        <a href="<?= $url; ?>" class="button rf"><?= $this->getHtml('More', '0', '0'); ?></a>
                    </div>
                </div>
            </section>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>
