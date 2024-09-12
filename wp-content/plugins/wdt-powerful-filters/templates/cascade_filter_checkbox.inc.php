<div class="col-sm-4 m-b-16 wdt-pf-cascade-filtering-block filtering-form-block">

    <h4 class="c-title-color m-b-4">
        <?php _e('Cascade filtering', 'wpdatatables'); ?>
        <i class="wpdt-icon-info-circle-thin" data-toggle="tooltip" data-placement="right"
           title="<?php _e('When turned on, all non-free-input filters (checkbox, selectbox) will narrow down the selection range in other checkboxes and selectboxes.', 'wpdatatables'); ?>"></i>
    </h4>
    <div class="toggle-switch" data-ts-color="blue">
        <input id="wdt-pf-cascade-filtering" type="checkbox" hidden="hidden">
        <label for="wdt-pf-cascade-filtering"
               class="ts-label"><?php _e('Enable cascade filtering', 'wpdatatables'); ?></label>
    </div>

</div>