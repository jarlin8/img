<?php defined('\ABSPATH') || exit; ?>

<input type="text" class="form-control form-control-sm" ng-model="query_params.Trovaprezzi.min_price" ng-init="query_params.Trovaprezzi.min_price = ''" placeholder="<?php esc_html_e('Min. price', 'content-egg') ?>" />
<input type="text" class="form-control form-control-sm" ng-model="query_params.Trovaprezzi.max_price" ng-init="query_params.Trovaprezzi.max_price = ''" placeholder="<?php esc_html_e('Max. price', 'content-egg') ?>" />