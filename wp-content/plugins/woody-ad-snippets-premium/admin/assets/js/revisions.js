jQuery(function ($) {
    // CONTROLS

    // handler for revision selector (radio button)
    $('.winp_rev_radio_from, .winp_rev_radio_to').click(function () {
        var val = $(this).val();
        if ($(this).hasClass('winp_rev_radio_from')) {
            $('.winp_rev_radio_to[value=' + val + ']').prop("checked", false);
        } else {
            $('.winp_rev_radio_from[value=' + val + ']').prop("checked", false);
        }
    });

    $('.winp_rev_compare').click(function () {
        var from = $('.winp_rev_radio_from:checked').val();
        var to = $('.winp_rev_radio_to:checked').val();
        var snippet = $('input[name=winp_rbox_snippet]').val();
        if (!snippet) console.error('Bad snippet id in revisions metabox');
        var url_params = {
            snippet: snippet

        };
        if (from) {
            url_params.from = from
        }
        if (to) {
            url_params.to = to
        }
        if (from > to) {
            url_params.from = to;
            url_params.to = from
        }
        // snippet url
        var url = '/wp-admin/edit.php?post_type=wbcr-snippets&page=revisions-wbcr_insert_php&' + $.param(url_params);
        window.location.href = url;
    });


    // ACTIONS

    // delete
    $('.winp_rev_delete').click(function () {
        var _nonce = $('#winp_rev_del_nonce').val();
        var page_url = '/wp-admin/edit.php?post_type=wbcr-snippets&page=revisions-wbcr_insert_php';
        var rev_ids = [];
        $('input[name=winp_rev_delete_mark]:checked').each(function (k, v) {
            rev_ids.push($(v).val());
        });
        ids = rev_ids.join(',');

        if (!ids) {
            console.log('Bad revisions id. Action: delete');
            return;
        }

        var url_params = {
            action: 'delete',
            ids: ids,
            _wpnonce: _nonce,
            ref: window.location.href
        };

        // if delete active revisions, redirect to last
        var current_params = getUrlParams();
        for (var i = 0; i < rev_ids.length; i++) {
            if (rev_ids[i] == current_params['from'] || rev_ids[i] == current_params['to']) {
                url_params.ref = page_url + '&snippet=' + current_params['snippet'];
            }
        }

        window.location.href = page_url + '&' + $.param(url_params);


    });

    function getUrlParams() {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

});