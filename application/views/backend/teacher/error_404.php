<div class="row">
    <div class="col-md-12 text-center" style="margin-top: 100px;">
        <div class="error-container">
            <div class="error-code" style="font-size: 120px; font-weight: bold; color: #f44336;">404</div>
            <h2 class="error-title"><?php echo get_phrase('page_not_found'); ?></h2>
            <p class="error-description" style="font-size: 18px;">
                <?php echo get_phrase('the_page_you_are_looking_for_might_have_been_removed_or_is_temporarily_unavailable'); ?>
            </p>
            <div class="error-actions" style="margin-top: 30px;">
                <a href="<?php echo base_url('teacher/dashboard'); ?>" class="btn btn-primary btn-lg">
                    <i class="fa fa-home"></i> <?php echo get_phrase('back_to_dashboard'); ?>
                </a>
                <a href="<?php echo base_url('teacher/my_diaries'); ?>" class="btn btn-success btn-lg">
                    <i class="fa fa-book"></i> <?php echo get_phrase('go_to_my_diaries'); ?>
                </a>
                <?php if ($this->agent->referrer()): ?>
                    <a href="<?php echo $this->agent->referrer(); ?>" class="btn btn-default btn-lg">
                        <i class="fa fa-arrow-left"></i> <?php echo get_phrase('go_back'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 