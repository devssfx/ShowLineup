
function editLabels() {
	$('#dialog').dialog({
		resizable: false,
		modal: true
	});
}

function btnNewLabelSave_Click() {
	var txtNewLabel = $('#txtNewLabel');
	var newLabel = txtNewLabel.val();
	if (newLabel.length == 0) {
		alert('You need to enter a label first.');
	} else if (newLabel.length > 50) {
		alert('The label can only have 50 characters. This one has ' + newLabel.length.toString() + '.');
	} else {
		var labelForId = getLabelForId();

		var actionUrl = '/ajax/LabelSave';

		$.post(actionUrl, {
			LabelName: newLabel,
			LabelForId: labelForId
		})
		.fail(function () {
			alert('There was a problem. Reload the page and try again.');
		})
		.done(function (data) {
			var success = data[0].Success;

			if (success == 1) {
				alert('There was a problem. Reload the page and try again.');
			} else if (success == 0) { //success
				var labelId = data[1].LabelId;
				$('#divLabelList').append('<div><input type="checkbox" onchange="labelSelectChange(this, \'' + labelId + '\');" checked="checked" id="chk' + labelId + '">'
				+ '<label for="chk' + labelId + '">' + newLabel + '</label>'
				+ '</div>');
				txtNewLabel.val('');

				appendLabel(labelId, newLabel);
			} else {
				location.href = '/';
			}
		});
	}
}

function txtNewLabel_Keyup(e) {
	var unicode = e.keyCode ? e.keyCode : e.charCode
	if (unicode == 13) {
		btnNewLabelSave_Click();
	}
}

function getLabelForId() {
	var labelForId = $('#labelForId');
	if (labelForId.length == 0 || labelForId.val().length == 0) {
		alert('There is a problem, no labels can be added at this time. Please try again later.');
		location.href = location.href;
	} else {
		labelForId = labelForId.val();
	}

	return labelForId;
}

function labelSelectChange(chk, labelId) {
	var labelForId = getLabelForId();

	var actionUrl = '/ajax/LabelSelectionChange';
	$.post(actionUrl, {
		LabelId: labelId,
		LabelForId: labelForId,
		Selected: chk.checked
	})
		.fail(function () {
			alert('There was a problem. Reload the page and try again.');
		})
		.done(function (data) {
			var success = data[0].Success;

			if (success == 1) {
				alert('There was a problem. Reload the page and try again.');
			} else if (success == 0) { //success
				if (chk.checked) {
					appendLabel(labelId, $(chk).next('label').text());
					//$('#divLabelSelected').append('<div class="labelSelected" id="divLabel' + labelId + '">' + $(chk).next('label').text() + '</div>');
				} else {
					$('#divLabel' + labelId).remove();
				}
			} else {
				location.href = '/';
			}
		});
}

function appendLabel(labelId, labelName) {
	$('#divLabelSelected').append('<div class="labelSelected" id="divLabel' + labelId + '">' + labelName + '</div>');
}
