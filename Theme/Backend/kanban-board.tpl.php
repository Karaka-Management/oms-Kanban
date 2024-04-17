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

use Modules\Kanban\Models\NullKanbanBoard;
use phpOMS\Uri\UriFactory;

/** @var \Modules\Kanban\Models\KanbanBoard $board */
$board = $this->data['board'] ?? new NullKanbanBoard();
?>
<div class="row">
    <div class="box">
        <a class="button" href="<?= UriFactory::build('{/base}/kanban/edit?id=' . $board->id); ?>"><?= $this->getHtml('Edit', '0', '0'); ?></a>
    </div>
</div>
<div class="row kanban-board" style="flex-wrap: nowrap;">
    <?php $i = 0; foreach ($board->columns as $column) : $i++; $cards = $column->getCards(); ?>
    <div id="kanban-column-<?= $i; ?>" class="box col-xs-3" style="min-width: 300px;">
        <header class="simple-flex">
            <span><?= $this->printHtml($column->name); ?></span>
            <a href="<?= UriFactory::build('{/base}/kanban/card/create?column=' . $board->id); ?>"><i class="g-icon">add_circle</i></a>
        </header>
        <?php $j = 0; foreach ($cards as $card) : $j++;
            $url = UriFactory::build('{/base}/kanban/card/view?{?}&id=' . $card->id);
        ?>
            <section id="kanban-card-<?= $this->printHtml($i . '-' . $j); ?>" class="portlet" draggable="true">
                <div class="portlet-head">
                    <a href="<?= $url; ?>"><?= $this->printHtml($card->name); ?></a>
                    <span class="tag end-xs"><?= \count($card->commentList->comments); ?></span>
                </div>
                <div class="portlet-body">
                    <article><?= $card->description; ?></article>

                    <div class="tag-list">
                    <?php foreach ($card->tags as $tag) : ?>
                        <span class="tag" style="background: <?= $this->printHtml($tag->color); ?>">
                            <?= empty($tag->icon) ? '' : '<i class="g-icon">' . $this->printHtml($tag->icon) . '</i>'; ?>
                            <?= $this->printHtml($tag->getL11n()); ?>
                        </span>
                    <?php endforeach; ?>
                    </div>
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
