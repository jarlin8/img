<div class="col-sm-4 wdt-pf-cascade-filtering-logic-block hidden">

    <h4 class="c-title-color m-b-4">
        <?php _e('Cascade Filtering Logic', 'wpdatatables'); ?>
        <i class="wpdt-icon-info-circle-thin" data-toggle="tooltip" data-placement="right"
           title="<?php _e('When defined as \'from left to right\' each next cascading filter will be accessible only when you make the selection in previous filter, in order from left to right. When set as \'free\' all the filters will be enabled all the time and will narrow down each other freely.', 'wpdatatables'); ?>"></i>
    </h4>

    <div class="form-group">
        <div class="fg-line">
            <div class="select">
                <select class="form-control selectpicker" id="wdt-pf-cascade-filtering-logic">
                    <option value="dependent"><?php _e('From left to right (Dependent)', 'wpdatatables'); ?></option>
                    <option value="independent"><?php _e('Free (Independent)', 'wpdatatables'); ?></option>
                </select>
            </div>
        </div>
    </div>

</div>