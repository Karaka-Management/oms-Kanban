<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Template
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Kanban\Models\NullKanbanBoard;
use phpOMS\Uri\UriFactory;

$board = $this->data['board'] ?? new NullKanbanBoard();
$isNew = $board->id === 0;

echo $this->data['nav']->render();
?>
<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-4">
        <section class="portlet">
            <form action="<?= \phpOMS\Uri\UriFactory::build('{/api}kanban?csrf={$CSRF}'); ?>" method="<?= $isNew ? 'PUT' : 'POST'; ?>">
                <div class="portlet-head"><?= $this->getHtml('Board'); ?></div>
                <div class="portlet-body">
                    <div class="form-group">
                        <label for="iTitle"><?= $this->getHtml('Name'); ?></label>
                        <input id="iTitle" type="text" name="title" value="<?= $this->printHtml($board->name); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="iDescription"><?= $this->getHtml('Description'); ?></label>
                        <textarea id="iDescription" name="plain"><?= $this->printTextarea($board->descriptionRaw); ?></textarea>
                    </div>
                </div>
                <div class="portlet-foot">
                    <?php if ($isNew) : ?>
                        <input id="iCreateSubmit" type="Submit" value="<?= $this->getHtml('Create', '0', '0'); ?>">
                    <?php else : ?>
                        <input id="iSaveSubmit" type="Submit" value="<?= $this->getHtml('Save', '0', '0'); ?>">
                    <?php endif; ?>
                </div>
            </form>
        </section>
    </div>
</div>

<?php if (!$isNew) : ?>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <section class="portlet">
            <form id="columnForm" action="<?= UriFactory::build('{/api}kanban/column?csrf={$CSRF}'); ?>" method="post"
                data-ui-container="#columnTable tbody"
                data-add-form="columnForm"
                data-add-tpl="#columnTable tbody .oms-add-tpl-column">
                <div class="portlet-head"><?= $this->getHtml('Column'); ?></div>
                <div class="portlet-body">
                    <div class="form-group">
                        <label for="iColumnId"><?= $this->getHtml('ID', '0', '0'); ?></label>
                        <input type="text" id="iColumnId" name="id" data-tpl-text="/id" data-tpl-value="/id" disabled>
                    </div>

                    <div class="form-group">
                        <label for="iColumnTitle"><?= $this->getHtml('Name'); ?></label>
                        <input id="iColumnTitle" type="text" name="title" data-tpl-text="/title" data-tpl-value="/title" required>
                    </div>

                    <div class="form-group">
                        <label for="iColumnOrder"><?= $this->getHtml('Order'); ?></label>
                        <input id="iColumnOrder" type="number" step="1" min="0" name="order" data-tpl-text="/order" data-tpl-value="/order" required>
                    </div>
                </div>
                <div class="portlet-foot">
                    <input id="bAttributeAdd" formmethod="put" type="submit" class="add-form" value="<?= $this->getHtml('Add', '0', '0'); ?>">
                    <input id="bAttributeSave" formmethod="post" type="submit" class="save-form vh button save" value="<?= $this->getHtml('Update', '0', '0'); ?>">
                    <input id="bAttributeCancel" type="submit" class="cancel-form vh button close" value="<?= $this->getHtml('Cancel', '0', '0'); ?>">
                </div>
            </form>
        </section>
    </div>

    <div class="col-xs-12 col-md-6">
    <section class="portlet">
        <div class="portlet-head"><?= $this->getHtml('Columns',); ?><i class="g-icon download btn end-xs">download</i></div>
        <div class="slider">
            <table id="columnTable" class="default sticky"
                data-tag="form"
                data-ui-element="tr"
                data-add-tpl=".oms-add-tpl-column"
                data-update-form="columnForm">
            <thead>
                <tr>
                    <td>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                    <td><?= $this->getHtml('Order'); ?>
                    <td class="wf-100"><?= $this->getHtml('Name'); ?>
            <tbody>
            <template class="oms-add-tpl-column">
                <tr class="animated medium-duration greenCircleFade" data-id="" draggable="false">
                    <td>
                        <i class="g-icon btn update-form">settings</i>
                        <input id="columnTable-remove-0" type="checkbox" class="vh">
                        <label for="columnTable-remove-0" class="checked-visibility-alt"><i class="g-icon btn form-action">close</i></label>
                        <span class="checked-visibility">
                            <label for="columnTable-remove-0" class="link default"><?= $this->getHtml('Cancel', '0', '0'); ?></label>
                            <label for="columnTable-remove-0" class="remove-form link cancel"><?= $this->getHtml('Delete', '0', '0'); ?></label>
                        </span>
                    <td data-tpl-text="/id" data-tpl-value="/id"></td>
                    <td data-tpl-text="/order" data-tpl-value="/order" data-value=""></td>
                    <td data-tpl-text="/title" data-tpl-value="/title"></td>
                </tr>
            </template>
            <?php
            $c = 0;
            foreach ($board->columns as $column) : ++$c; ?>
                <tr data-id="<?= $column->id; ?>">
                    <td>
                        <i class="g-icon btn update-form">settings</i>
                        <input id="columnTable-remove-<?= $column->id; ?>" type="checkbox" class="vh">
                        <label for="columnTable-remove-<?= $column->id; ?>" class="checked-visibility-alt"><i class="g-icon btn form-action">close</i></label>
                        <span class="checked-visibility">
                            <label for="columnTable-remove-<?= $column->id; ?>" class="link default"><?= $this->getHtml('Cancel', '0', '0'); ?></label>
                            <label for="columnTable-remove-<?= $column->id; ?>" class="remove-form link cancel"><?= $this->getHtml('Delete', '0', '0'); ?></label>
                        </span>
                    <td data-tpl-text="/id" data-tpl-value="/id"><?= $column->id; ?>
                    <td data-tpl-text="/order" data-tpl-value="/order"><?= $column->order; ?>
                    <td data-tpl-text="/title" data-tpl-value="/title"><?= $this->printHtml($column->name); ?>
            <?php endforeach; ?>
            <?php if ($c === 0) : ?>
                <tr><td colspan="2" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
            <?php endif; ?>
            </table>
        </div>
    </section>
    </div>
</div>
<?php endif; ?>
<!--
@todo Implement permissions
-->