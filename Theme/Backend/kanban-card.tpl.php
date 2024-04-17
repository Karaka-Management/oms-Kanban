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

use Modules\Comments\Models\CommentListStatus;
use phpOMS\Uri\UriFactory;

/** @var \Modules\Kanban\Models\KanbanCard $card */
$card = $this->data['card'];
$isNew = $card->id === 0;

$editPossible = $card->createdBy->id === $this->request->header->account;
?>

<div class="row">
    <div class="box">
        <a tabindex="0" class="button" href="<?= $this->request->getReferer() !== '' ? $this->request->getReferer() : UriFactory::build('kanban/dashboard'); ?>"><?= $this->getHtml('Back'); ?></a>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
    <?php if ($editPossible) : ?>
    <form id="iCard" method="<?= $isNew ? 'PUT' : 'POST'; ?>" action="<?= UriFactory::build('{/api}kanban/card?id=' . $card->id . '&csrf={$CSRF}'); ?>"
            data-ui-container="#iCard"
            data-ui-element=".portlet"
            data-update-tpl="#iCard .portlet-tpl">
        <template class="portlet-tpl">
        <section class="portlet">
            <div class="portlet-head"><input id="iTitle" type="text" name="title" data-tpl-text="/title" data-tpl-value="/title"></div>
            <div class="portlet-body">
                <div class="form-group">
                <textarea id="iDescription" name="description"
                    data-tpl-value="/description" required></textarea>
                </div>
            </div>
            <div class="portlet-foot">
                <button class="save-form"><?= $this->getHtml('Save', '0', '0'); ?></button>
                <button class="cancel cancel-form"><?= $this->getHtml('Cancel', '0', '0'); ?></button>
            </div>
        </section>
        </template>
        <?php endif; ?>
        <section class="portlet" data-id="<?= $card->id; ?>">
            <div class="portlet-head" data-tpl-text="/title" data-tpl-value="/title"><?= $this->printHtml($card->name); ?></div>
            <div class="portlet-body">
                <article <?php if ($editPossible) : ?>
                    data-tpl-value="/description"
                    data-value="<?= \trim(\str_replace(["\r\n", "\n"], ['&#10;', '&#10;'], $this->printHtml($card->descriptionRaw))); ?>"<?php endif; ?>><?= $card->description; ?></article>
                <div>
                    <?php $files = $card->files; foreach ($files as $file) : ?>
                        <span><a class="content" href="<?= UriFactory::build('{/base}/media/view?id=' . $file->id);?>"><?= $file->name; ?></a></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="portlet-foot">
                <span><a class="content" href="<?= UriFactory::build('{/base}/profile/view?{?}&id=' . $card->createdBy->id); ?>">
                    <?= $this->printHtml($this->renderUserName(
                        '%3$s %2$s %1$s',
                        [$card->createdBy->name1, $card->createdBy->name2, $card->createdBy->name3, $card->createdBy->login ?? '']
                    )); ?>
                </a></span>
                <span><?= $card->createdAt->format('Y-m-d H:i:s'); ?></span>
                <?php if ($editPossible) : ?>
                <div class="end-xs">
                    <button class="update-form"><?= $this->getHtml('Edit', '0', '0'); ?></button>
                </div>
                <?php endif; ?>
            </div>
        </section>
    <?php if ($editPossible) : ?>
    </form>
    <?php endif; ?>
    </div>
</div>

<?php
$commentList = $card->commentList;
if ($this->data['commentPermissions']['write']
    && $commentList?->status === CommentListStatus::ACTIVE
) :
  echo $this->getData('commentCreate')->render(1);
endif;

if ($this->data['commentPermissions']['list_modify']
    || ($this->data['commentPermissions']['list_read'] && $commentList->status !== CommentListStatus::INACTIVE)
) :
    echo $this->getData('commentList')->render($commentList);
endif;
