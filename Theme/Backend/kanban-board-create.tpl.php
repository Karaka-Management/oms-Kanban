<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Template
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);
echo $this->data['nav']->render();
?>
<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-4">
        <section class="portlet">
            <form action="<?= \phpOMS\Uri\UriFactory::build('{/api}...'); ?>" method="post">
                <div class="portlet-head"><?= $this->getHtml('Kanban'); ?></div>
                <div class="portlet-body">
                    <div class="form-group">
                        <label for="iTitle"><?= $this->getHtml('Name'); ?></label>
                        <input id="iTitle" type="text" name="title" required>
                    </div>

                    <div class="form-group">
                        <label for="iDescription"><?= $this->getHtml('Description'); ?></label>
                        <textarea id="iDescription" name="description"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="iTags"><?= $this->getHtml('Tags'); ?></label>
                    </div>
                </div>
                <div class="portlet-foot">
                    <input type="Submit" value="<?= $this->getHtml('Create', '0', '0'); ?>">
                </div>
            </form>
        </section>
    </div>
</div>

<?= $this->data['permissionView']->render('board_permission'); ?>