jQuery('button#asp_sub_clear_cache_btn').click(function () {
	jQuery(this).prop('disabled', true);
	var btn = jQuery(this);
	var btn_html = btn.html();
	btn.html(aspSUBData.str.clearing);
	jQuery.ajax({ url: ajaxurl, method: 'POST', data: { action: 'asp_sub_clear_cache', nonce: aspSUBData.nonce_clear_cache } })
		.done(function (response) {
			if (response) {
				try {
					alert(response.msg);
				} catch (e) {
					alert(e);
					btn.html(btn_html);
					btn.prop('disabled', false);
					return false;
				}
			}
			btn.html(btn_html);
			btn.prop('disabled', false);
			return true;
		})
		.fail(function (e) {
			alert(aspSUBData.str.errorOccurred + ' ' + e.statusText);
			btn.html(btn_html);
			btn.prop('disabled', false);
		});
});

jQuery('a[data-tab-name="sub"]').click(function () {
	jQuery.ajax({ url: ajaxurl, data: { action: 'asp_sub_check_webhooks' } })
		.done(function (res) {
			if (res) {
				try {
					jQuery('span.asp-sub-live-webhook-status').html('<span class="dashicons dashicons-' + res.live.status + '"></span> ' + res.live.msg);
					jQuery('span.asp-sub-test-webhook-status').html('<span class="dashicons dashicons-' + res.test.status + '"></span> ' + res.test.msg);
					if (res.live.hidebtn) {
						jQuery('button.asp-sub-create-webhook-btn[data-hook-mode="live"]').hide();
					} else {
						jQuery('button.asp-sub-create-webhook-btn[data-hook-mode="live"]').show();
					}
					if (res.live.signsec !== false) {
						//jQuery('input[name="AcceptStripePayments-settings[live_webhook_secret]"]').val(res.live.signsec);
					}
					if (res.test.hidebtn) {
						jQuery('button.asp-sub-create-webhook-btn[data-hook-mode="test"]').hide();
					} else {
						jQuery('button.asp-sub-create-webhook-btn[data-hook-mode="test"]').show();
					}
					if (res.test.signsec !== false) {
						//jQuery('input[name="AcceptStripePayments-settings[test_webhook_secret]"]').val(res.test.signsec);
					}
				} catch (e) {
					alert(e);
					return false;
				}
			}
			return true;
		})
		.fail(function (e) {
		});
});

jQuery('button.asp-sub-create-webhook-btn').click(function (e) {
	e.preventDefault();
	var hookMode = jQuery(this).data('hook-mode');
	var createBtn = jQuery(this);
	createBtn.prop('disabled', true);
	var statusSpan = jQuery('span.asp-sub-' + hookMode + '-webhook-status');
	statusSpan.html('<span class="dashicons dashicons-update"></span> ' + aspSUBData.str.creatingWebhook);
	jQuery.ajax({ url: ajaxurl, method: 'POST', data: { action: 'asp_sub_create_webhook', mode: hookMode, nonce: aspSUBData.nonce_create_webhook } })
		.done(function (res) {
			createBtn.prop('disabled', false);
			if (res) {
				try {
					statusSpan.html('<span class="dashicons dashicons-' + res.status + '"></span> ' + res.msg);
					if (res.status === "yes" && res.signsec) {
						jQuery('input[name="AcceptStripePayments-settings[' + hookMode + '_webhook_secret]"]').val(res.signsec);
					}
					if (res.hidebtn) {
						createBtn.hide();
					} else {
						createBtn.show();
					}
				} catch (e) {
					alert(e);
					return false;
				}
			}
			return true;
		})
		.fail(function (e) {
		});
});

jQuery('button#asp-sub-delete-webhooks-btn').click(function (e) {
	deleteBtn = jQuery(this);
	deleteBtn.prop('disabled', true);
	prevHtml = deleteBtn.html();
	deleteBtn.html(aspSUBData.str.deleting);
	jQuery.ajax({ url: ajaxurl, method: 'POST', data: { action: 'asp_sub_delete_webhooks', nonce: aspSUBData.nonce_delete_webhooks } })
		.done(function (res) {
			deleteBtn.prop('disabled', false);
			deleteBtn.html(prevHtml);
			if (res) {
				try {
					if (res.success) {
						jQuery('a[data-tab-name="sub"]').click();
						alert(res.msg);
					} else {
						alert(res.msg);
						return false;
					}
				} catch (e) {
					alert(e);
					return false;
				}
			}
			return true;
		})
		.fail(function (e) {
		});
});