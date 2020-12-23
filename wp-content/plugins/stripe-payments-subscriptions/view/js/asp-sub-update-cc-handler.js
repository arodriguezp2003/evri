function smokeScreen(show) {
	if (show) {
		smokeScreenEl.style.display = "flex";
		submitBtn.disabled = true;
	} else {
		smokeScreenEl.style.display = "none";
		submitBtn.disabled = false;
	}
}

function showGlobalError(msg) {
	errorCont.innerHTML = msg;
	errorCont.style.display = 'block';
}

var tokenId = '';
var si_id = '';
var si_cs = '';
var form = document.getElementById('update-form');
var smokeScreenEl = document.getElementById('smoke-screen');
var submitBtn = document.getElementById('submitBtn');
var tokenInput = document.getElementById('pm_id');
var stripe = Stripe(vars.key);
var elements = stripe.elements();
var cardErrorCont = document.getElementById('card-errors');
var errorCont = document.getElementById('error-cont');
var style = {
	base: {
		fontSize: '16px',
	}
};

smokeScreen(true);

var card = elements.create('card', {
	style: style,
	hidePostalCode: true
});

card.on('ready', function () {
	smokeScreen(false);
});

card.mount('#card-element');

card.addEventListener('change', function (event) {
	if (event.error) {
		//		cardErrorCont.textContent = event.error.message;
	} else {
		//		cardErrorCont.textContent = '';
	}
	errorCont.style.display = 'none';
});
form.addEventListener('submit', function (event) {
	event.preventDefault();
	if (tokenInput.value !== '') {
		form.submit();
		return true;
	}
	event.preventDefault();
	smokeScreen(true);
	errorCont.style.display = 'none';

	stripe.createToken(card).then(function (result) {
		console.log(result);
		if (result.error) {
			smokeScreen(false);
			showGlobalError(result.error.message);
		} else {
			tokenId = result.token.id;
			var reqStr = 'action=asp_sub_create_si&cust_id=' + vars.custId + '&token_id=' + tokenId + '&is_live=' + vars.isLive;
			console.log('Doing action asp_sub_create_si');
			new ajaxRequest(vars.ajaxUrl, reqStr,
				function (res) {
					try {
						var resp = JSON.parse(res.responseText);
						console.log(resp);
						if (resp.err !== '') {
							smokeScreen(false);
							showGlobalError(resp.err);
							return false;
						}
						si_id = resp.si_id;
						si_cs = resp.si_cs;
						console.log('Doing handleCardSetup()');
						stripe.handleCardSetup(si_cs, card).then(function (result) {
							console.log(result);
							if (result.error) {
								showGlobalError(result.error.message);
								smokeScreen(false);
								return false;
							}
							tokenInput.value = result.setupIntent.payment_method;
							form.dispatchEvent(new Event('submit'));
						});
					} catch (e) {
						console.log(e);
						showGlobalError('Caught Exception: ' + e.description);
					}
				},
				function (res, errMsg) {
					smokeScreen(false);
					showGlobalError(errMsg);
				}
			);
		}
	});
});
