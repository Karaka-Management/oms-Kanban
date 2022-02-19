<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\News
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
/** @var \Modules\News\Models\NewsArticle[] $boards */
$boards = $this->getData('boards') ?? [];

$previous = empty($boards) ? '{/prefix}kanban/archive' : '{/prefix}kanban/archive?{?}&id=' . \reset($boards)->getId() . '&ptype=p';
$next     = empty($boards) ? '{/prefix}kanban/archive' : '{/prefix}kanban/archive?{?}&id=' . \end($boards)->getId() . '&ptype=n';

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Archive'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <div class="slider">
            <table id="kanbanArchiveList" class="default sticky">
                <thead>
                <tr>
                    <td><?= $this->getHtml('Status'); ?>
                        <label for="kanbanArchiveList-sort-1">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="kanbanArchiveList-sort-2">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td class="wf-100"><?= $this->getHtml('Title'); ?>
                        <label for="kanbanArchiveList-sort-3">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-3">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="kanbanArchiveList-sort-4">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-4">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Date'); ?>
                        <label for="kanbanArchiveList-sort-7">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-7">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="kanbanArchiveList-sort-8">
                            <input type="radio" name="kanbanArchiveList-sort" id="kanbanArchiveList-sort-8">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
            <tbody>
                <?php
                    $count = 0;

                foreach ($boards as $key => $board) : ++$count;
                    $url   = UriFactory::build('{/prefix}kanban/board?{?}&id=' . $board->getId());
                ?>
                    <tr tabindex="0" data-href="<?= $url; ?>">
                        <td><a href="<?= $url; ?>"><?= $this->getHtml(':bStatus' . $board->getStatus()); ?></a>
                        <td><a href="<?= $url; ?>"><?= $this->printHtml($board->name); ?></a>
                        <td><a href="<?= $url; ?>"><?= $this->printHtml($board->createdAt->format('Y-m-d')); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="2" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
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
