//pass in array of array [name][value] [name][value]
function replaceQS(qsList) {
	var qs;
	var loc = location.href;

	if (loc.indexOf('?') != -1) {
		qs = loc.substring(loc.indexOf('?') + 1)
		loc = loc.substring(0, loc.indexOf('?'));

		qs = qs.split('&');
		var found;
		for (var iNew = 0; iNew < qsList.length; iNew++) {
			found = false;
			for (var i = 0; i < qs.length; i++) {
				if (qs[i].substring(0, qsList[iNew][0].length) == qsList[iNew][0]) {
					qs[i] = qsList[iNew][0] + '=' + qsList[iNew][1];
					found = true;
					break;
				}
			}
			if (!found) {
				qs[qs.length] = qsList[iNew][0] + '=' + qsList[iNew][1];
			}
		}
	} else {
		qs = new Array();
		for (var iNew = 0; iNew < qsList.length; iNew++) {
			qs[qs.length] = qsList[iNew][0] + '=' + qsList[iNew][1];
		}
	}
	for (var i = 0; i < qs.length; i++) {
		if (i == 0) loc += '?'; else loc += '&';
		loc += qs[i];
	}
	return loc;
}