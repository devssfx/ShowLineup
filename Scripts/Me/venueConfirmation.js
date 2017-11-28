function venueConfirmation(showDateId, venueId, confirm, btn) {
	//var actionUrl = '@Url.Action("VenueConfirmation", "ajax")';
	var actionUrl = '/ajax/VenueConfirmation';

	$.post(actionUrl, {
		ShowDateId: showDateId,
		VenueId: venueId,
		Confirm: confirm
	})
	.done(function (data) {
		var rtn = data[0].Success;
		if (rtn == 1) {
			alert('There was a problem. Reload the page and try again.');
		} else if (rtn == 0) { //success

			var btns = $(btn).parent().find('input');
			if (btns.length == 2) {
				if (confirm == 1) {
					$(btns[0]).css('display', 'none');
					$(btns[1]).css('display', 'inline');
				} else if (confirm == -1) {
					$(btns[0]).css('display', 'inline');
					$(btns[1]).css('display', 'none');
				}
			}

			btns = $(btn).parent().find('span');
			if (btns.length == 2) {
				if (confirm == 1) {
					$(btns[0]).css('display', 'none');
					$(btns[1]).css('display', 'inline');
				} else if (confirm == -1) {
					$(btns[0]).css('display', 'inline');
					$(btns[1]).css('display', 'none');
				}
			}




		} else {
			location.href = '/';
		}
	})
	.fail(function () {
		alert('There was a problem. Reload the page and try again.');
	});
}