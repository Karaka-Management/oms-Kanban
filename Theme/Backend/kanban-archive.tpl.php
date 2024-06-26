<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\News
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
/** @var \Modules\Kanban\Models\KanbanBoard[] $boards */
$boards = $this->data['boards'] ?? [];

$previous = empty($boards) ? 'kanban/archive' : 'kanban/archive?{?}&id=' . \reset($boards)->id . '&ptype=p';
$next     = empty($boards) ? 'kanban/archive' : 'kanban/archive?{?}&id=' . \end($boards)->id . '&ptype=n';

echo $this->data['nav']->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Archive'); ?><i class="g-icon download btn end-xs">download</i></div>
            <div class="slider">
            <table id="kanbanArchiveList" class="default sticky">
                <thead>
                <tr>
                    <td><?= $this->getHtml('Status'); ?>
                        <label for="kanbanArchiveList-sort-1">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-1">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="kanbanArchiveList-sort-2">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-2">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td class="wf-100"><?= $this->getHtml('Title'); ?>
                        <label for="kanbanArchiveList-sort-3">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-3">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="kanbanArchiveList-sort-4">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-4">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Date'); ?>
                        <label for="kanbanArchiveList-sort-7">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-7">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="kanbanArchiveList-sort-8">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-8">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
            <tbody>
                <?php
                    $count = 0;

                foreach ($boards as $key => $board) : ++$count;
                    $url = UriFactory::build('{/base}/kanban/board?{?}&id=' . $board->id);
                ?>
                    <tr tabindex="0" data-href="<?= $url; ?>">
                        <td><a href="<?= $url; ?>"><?= $this->getHtml(':bStatus' . $board->status); ?></a>
                        <td><a href="<?= $url; ?>"><?= $this->printHtml($board->name); ?></a>
                        <td><a href="<?= $url; ?>"><?= $this->printHtml($board->createdAt->format('Y-m-d')); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="3" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
            <div class="portlet-foot">
                <a class="button" href="<?= UriFactory::build($previous); ?>"><?= $this->getHtml('Previous', '0', '0'); ?></a>
                <a class="button" href="<?= UriFactory::build($next); ?>"><?= $this->getHtml('Next', '0', '0'); ?></a>
            </div>
        </section>
    </div>
</div>
