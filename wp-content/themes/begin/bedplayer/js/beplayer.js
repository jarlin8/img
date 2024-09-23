function BePlayer(pid) {
	this.Num = 0;
	this.pid = pid;
	this.Videos = eval('bevideo_vids_' + pid);
	for (var vg in this.Videos) {
		this.vtype = vg;
		break;
	}
	this.PlayUrlLen = eval('this.Videos.' + this.vtype + '.length');
}

BePlayer.prototype.GoPreUrl = function() {
	if (this.Num - 1 >= 0) {
		this.Go(this.Num - 1);
	}
};

BePlayer.prototype.GoNextUrl = function() {
	if (this.Num + 1 < this.PlayUrlLen) {
		this.Go(this.Num + 1);
	}
};

BePlayer.prototype.Go = function(n, t) {
	if (!t) {
		t = this.vtype;
	} else {
		this.vtype = t;
	}
	if (document.getElementById('be_ifr_' + t + '_' + this.pid)) {
		var pstr = document.getElementById('be_ifr_' + t + '_' + this.pid).value;
		var cur = eval('this.Videos.' + t)[n];

		document.getElementById("topdes_" + this.pid).innerHTML = '' + eval('be_playing_' + this.pid) + cur.pre + '';
		try {
			eval('be_' + pstr + '_' + this.pid + '(' + this.pid + ', cur);');
		} catch(e) {
			pstr = pstr.replace('{type}', t);
			pstr = pstr.replace('{vid}', cur.video);
			if (pstr.indexOf(cur.video) == -1) pstr = pstr.replace('url=', 'url=' + cur.video);
			document.getElementById('playleft_' + this.pid).innerHTML = pstr;
		}
		this.Num = n;
		var bottoma = document.getElementById('be-set-list_' + t + '_' + this.pid).getElementsByTagName('a');
		for (var i = 0; i < bottoma.length; i++) {
			if (i == parseInt(n)) bottoma[i].className = bottoma[i].className.replace('list_on', '') + ' list_on';
			else bottoma[i].className = bottoma[i].className.replace('list_on', '');
		}
	}
};