<?php
function nofollow_links() { ?>
<script type="text/javascript">
addLoadEvent(addNofollowTag);
function addNofollowTag() {
	tables = document.getElementsByTagName('table');
	for (i = 0; i < tables.length; i++) {
		if (tables[i].getAttribute("class") == "links-table") {
			tr = tables[i].insertRow(1);
			th = document.createElement('th');
			th.setAttribute('scope', 'row');
			th.appendChild(document.createTextNode('不追踪此链接'));
			td = document.createElement('td');
			tr.appendChild(th);
			label = document.createElement('label');
			input = document.createElement('input');
			input.setAttribute('type', 'checkbox');
			input.setAttribute('id', 'nofollow');
			input.setAttribute('value', 'external nofollow');
			label.appendChild(input);
			label.appendChild(document.createTextNode(' nofollow'));
			td.appendChild(label);
			tr.appendChild(td);
			input.name = 'nofollow';
			input.className = 'valinp';
			if (document.getElementById('link_rel').value.indexOf('nofollow') != -1) {
				input.setAttribute('checked', 'checked');
			}
			return;
		}
	}
}
</script>
<?php
}
add_action('admin_head','nofollow_links');
return;