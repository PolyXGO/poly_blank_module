<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php if ($this->session->flashdata('debug')) {
            ?>
                <div class="col-lg-12">
                    <div class="alert alert-warning">
                        <?php echo $this->session->flashdata('debug'); ?>
                    </div>
                </div>
            <?php
            } ?>
            <div class="col-md-3">
                <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
                    <?php
                    $i = 0;
                    foreach ($tabs as $group) { ?>
                        <li class="<?php echo POLY_BLANK_MODULE_SETTINGS_TAB?>-group-<?php echo html_escape($group['slug']); ?><?php echo ($i === 0) ? ' active' : '' ?>">
                            <a href="<?php echo admin_url(POLY_BLANK_MODULE.'/'.POLY_BLANK_MODULE_SETTINGS_TAB. '?group=' . $group['slug']); ?>" data-group="<?php echo html_escape($group['slug']); ?>">
                                <i class="<?php echo $group['icon'] ?: 'fa-regular fa-circle-question'; ?> menu-icon"></i>
                                <?php echo html_escape($group['name']); ?>

                                <?php if (isset($group['badge'], $group['badge']['value']) && !empty($group['badge'])) { ?>
                                    <span class="badge pull-right
        <?= isset($group['badge']['type']) && $group['badge']['type'] != '' ? "bg-{$group['badge']['type']}" : 'bg-info' ?>" <?= (isset($group['badge']['type']) && $group['badge']['type'] == '') ||
                                                                                                                                    isset($group['badge']['color']) ? "style='background-color: {$group['badge']['color']}'" : '' ?>>
                                        <?= $group['badge']['value'] ?>
                                    </span>
                                <?php } ?>
                            </a>
                        </li>
                    <?php $i++;
                    }
                    ?>
                </ul>
            </div>
            <div class="col-md-9">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php $this->load->view($tab['view']) ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>