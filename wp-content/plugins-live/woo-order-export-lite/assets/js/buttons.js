function woe_show_preview( response ) {
	var id = 'output_preview';
	if ( woe_is_flat_format( output_format ) ) {
		id = 'output_preview_csv';
	}
	if ( woe_is_object_format( output_format ) ) {
		jQuery( '#' + id ).text( response );
	}
	else {
		jQuery( '#' + id ).html( response );
	}
	jQuery( '#' + id ).show();
	window.scrollTo( 0, document.body.scrollHeight );
}

function woe_preview( size ) {

	jQuery( '#output_preview, #output_preview_csv' ).hide();

	var data = 'json=' + woe_make_json_var( jQuery( '#export_job_settings' ) );

	var estimate_data = data + "&action=order_exporter&method=estimate&mode=" + mode + "&id=" + job_id + '&woe_nonce=' + settings_form.woe_nonce + '&tab=' + settings_form.woe_active_tab;

	jQuery.post( ajaxurl, estimate_data, function ( response ) {
		if ( ! response || typeof response.total == 'undefined' ) {
			woe_show_error_message( response );
			return;
		}
		jQuery( '#output_preview_total' ).find( 'span' ).html( response.total );
		jQuery( '#preview_actions' ).removeClass( 'hide' );
	}, "json" ).fail( function ( xhr, textStatus, errorThrown ) {
		woe_show_error_message( xhr.responseText );
	} );


	data = data + "&action=order_exporter&method=preview&limit=" + size + "&mode=" + mode + "&id=" + job_id + '&woe_nonce=' + settings_form.woe_nonce + '&tab=' + settings_form.woe_active_tab;

	jQuery.post( ajaxurl, data, woe_show_preview, "html" ).fail( function ( xhr, textStatus, errorThrown ) {
		woe_show_preview( xhr.responseText );
	} );
}

function woe_is_object_format( format ) {
	return (
		settings_form.object_formats.indexOf( format ) > - 1
	);
}

// EXPORT FUNCTIONS

function woe_close_waiting_dialog() {
	jQuery( "#background" ).removeClass( "loading" );
}

function woe_get_data() {
	var data = new Array();
	data.push( {name: 'json', value: woe_make_json( jQuery( '#export_job_settings' ) )} );
	data.push( {name: 'action', value: 'order_exporter'} );
	data.push( {name: 'mode', value: mode} );
	data.push( {name: 'id', value: job_id} );
	return data;
}

function woe_validate_export() {

	if ( (
		     mode == settings_form.EXPORT_PROFILE
	     ) && (
		     ! jQuery( "[name='settings[title]']" ).val()
	     ) ) {
		alert( export_messages.empty_title );
		jQuery( "[name='settings[title]']" ).focus();
		return false;
	}

	if ( (
		     jQuery( "#from_date" ).val()
	     ) && (
		     jQuery( "#to_date" ).val()
	     ) ) {
		var d1 = new Date( jQuery( "#from_date" ).val() );
		var d2 = new Date( jQuery( "#to_date" ).val() );
		if ( d1.getTime() > d2.getTime() ) {
			alert( export_messages.wrong_date_range );
			return false;
		}
	}

	if ( jQuery( '#order_fields > li' ).length == 0 ) {
		alert( export_messages.no_fields );
		return false;
	}

	return true;
}

function woe_is_ipad_or_iphone() {
	return navigator.platform.match( /i(Phone|Pad)/i )
}

function woe_waiting_dialog() {

	jQuery( "#background" ).addClass( "loading" );

	jQuery( '#wpbody-content' ).keydown( function ( event ) {
		if ( event.keyCode == 27 ) {
			if ( ! window.cancelling ) {
				event.preventDefault();
				window.cancelling = true;

				jQuery.ajax( {
					type: "post",
					data: {
						action: 'order_exporter',
						method: 'cancel_export',
						file_id: window.file_id,
					},
					cache: false,
					url: ajaxurl,
					dataType: "json",
					error: function ( xhr, status, error ) {
						alert( xhr.responseText );
						woe_export_progress( 100, jQuery( '#progressBar' ) );
					},
					success: function ( response ) {
						woe_export_progress( 100, jQuery( '#progressBar' ) );
					}
				} );

				window.count = 0;
				window.file_id = '';
				jQuery( '#wpbody-content' ).off( 'keydown' );
			}
			return false;
		}
	} );
}

function woe_export_progress( percent, $element ) {

	if ( percent == 0 ) {
		$element.find( 'div' ).html( percent + "%&nbsp;" ).animate( {width: 0}, 0 );
		woe_waiting_dialog();
		jQuery( '#progress_div' ).show();
	}
	else {
		var progressBarWidth = percent * $element.width() / 100;
		$element.find( 'div' ).html( percent + "%&nbsp;" ).animate( {width: progressBarWidth}, 200 );

		if ( percent >= 100 ) {
			if ( ! woe_is_ipad_or_iphone() && ! ( output_format == 'HTML' && settings_form.settings.display_html_report_in_browser ) ) {
				jQuery( '#progress_div' ).hide();
				woe_close_waiting_dialog();
			}
		}
	}
}

function woe_get_all( start, percent, method ) {

	if ( window.cancelling ) {
		return;
	}

	woe_export_progress( parseInt( percent, 10 ), jQuery( '#progressBar' ) );

	if ( percent < 100 ) {
		data = woe_get_data();
		data.push( {name: 'method', value: method} );
		data.push( {name: 'start', value: start} );
		data.push( {name: 'file_id', value: window.file_id} );
		data.push( {name: 'woe_nonce', value: settings_form.woe_nonce} );
		data.push( {name: 'tab', value: settings_form.woe_active_tab} );

		jQuery.ajax( {
			type: "post",
			data: data,
			cache: false,
			url: ajaxurl,
			dataType: "json",
			error: function ( xhr, status, error ) {
				woe_show_error_message( xhr.responseText );
				woe_export_progress( 100, jQuery( '#progressBar' ) );
			},
			success: function ( response ) {
				if ( ! response ) {
					woe_show_error_message( response );
				} else if ( typeof response.error !== 'undefined' ) {
					woe_show_error_message( response.error );
				} else {
					woe_get_all( response.start, (
						                             response.start / window.count
					                             ) * 100, method )
				}
			}
		} );
	}
	else {
		data = woe_get_data();
		data.push( {name: 'method', value: 'export_finish'} );
		data.push( {name: 'file_id', value: window.file_id} );
		data.push( {name: 'woe_nonce', value: settings_form.woe_nonce} );
		data.push( {name: 'tab', value: settings_form.woe_active_tab} );
		jQuery.ajax( {
			type: "post",
			data: data,
			cache: false,
			url: ajaxurl,
			dataType: "json",
			error: function ( xhr, status, error ) {
				alert( xhr.responseText );
			},
			success: function ( response ) {
				var download_format = output_format;
				if ( output_format == 'XLS' && ! jQuery( '#format_xls_use_xls_format' ).prop( 'checked' ) ) {
					download_format = 'XLSX';
				}

				if ( woe_is_ipad_or_iphone() || ( output_format == 'HTML' && settings_form.settings.display_html_report_in_browser ) ) {

					$( '#progress_div .title-download a' ).attr( 'href', ajaxurl + (
						ajaxurl.indexOf( '?' ) === - 1 ? '?' : '&'
					) + 'action=order_exporter&method=export_download&format=' + download_format + '&file_id=' + window.file_id + '&tab=' + settings_form.woe_active_tab );
					jQuery( '#progress_div .title-download' ).show();
					jQuery( '#progress_div .title-cancel' ).hide();
					jQuery( '#progressBar' ).hide();
				} else {
					jQuery( '#export_new_window_frame' ).attr( "src", ajaxurl + (
						ajaxurl.indexOf( '?' ) === - 1 ? '?' : '&'
					) + 'action=order_exporter&method=export_download&format=' + download_format + '&file_id=' + window.file_id + '&tab=' + settings_form.woe_active_tab );
				}

				woe_reset_date_filter_for_cron();
			}
		} );
	}
}

jQuery( document ).ready( function ( $ ) {

	$( ".preview-btn" ).click( function () {
		woe_preview( jQuery( this ).attr( 'data-limit' ) );
		return false;
	} );

	$( "#export-btn, #my-quick-export-btn" ).click( function () {

		window.cancelling = false;

		data = woe_get_data();

		data.push( {name: 'method', value: 'export_start'} );
		data.push( {name: 'woe_nonce', value: settings_form.woe_nonce} );
		data.push( {name: 'tab', value: settings_form.woe_active_tab} );

		if ( (
			     $( "#from_date" ).val()
		     ) && (
			     $( "#to_date" ).val()
		     ) ) {
			var d1 = new Date( $( "#from_date" ).val() );
			var d2 = new Date( $( "#to_date" ).val() );
			if ( d1.getTime() > d2.getTime() ) {
				alert( export_messages.wrong_date_range );
				return false;
			}
		}

		if ( $( '#order_fields > li' ).length == 0 ) {
			alert( export_messages.no_fields );
			return false;
		}

		jQuery.ajax( {
			type: "post",
			data: data,
			cache: false,
			url: ajaxurl,
			dataType: "json",
			error: function ( xhr, status, error ) {
				woe_show_error_message( xhr.responseText.replace( /<\/?[^>]+(>|$)/g, "" ) );
			},
			success: function ( response ) {
				if ( ! response || typeof response['total'] == 'undefined' ) {
					woe_show_error_message( response );
					return;
				}
				window.count = response['total'];
				window.file_id = response['file_id'];
				console.log( window.count );

				if ( window.count > 0 ) {
					woe_get_all( 0, 0, 'export_part' );
				} else {
					alert( export_messages.no_results );
					woe_reset_date_filter_for_cron();
				}
			}
		} );

		return false;
	} );

	$( "#export-wo-pb-btn" ).click( function () {
		$( '#export_wo_pb_form' ).attr( "action", ajaxurl );
		$( '#export_wo_pb_form' ).find( '[name=json]' ).val( woe_make_json( $( '#export_job_settings' ) ) );
		$( '#export_wo_pb_form' ).submit();
		return false;
	} );

	$( "#reset-profile" ).click( function () {
		if ( confirm( localize_settings_form.reset_profile_confirm ) ) {
			var data = "action=order_exporter&method=reset_profile&mode=" + mode + "&id=" + '&woe_nonce=' + settings_form.woe_nonce + '&tab=' + settings_form.woe_active_tab;
			$.post( ajaxurl, data, function ( response ) {
				if ( response.success ) {
					document.location.reload();
				}
			}, "json" );
		}

		return false;
	} );

	$( "#save-only-btn" ).click( function () {

		if ( ! woe_validate_export() ) {
			return false;
		}

		woe_set_form_submitting();

		var data = 'json=' + woe_make_json_var( $( '#export_job_settings' ) )
		data = data + "&action=order_exporter&method=save_settings&mode=" + mode + "&id=" + job_id + '&woe_nonce=' + settings_form.woe_nonce + '&tab=' + settings_form.woe_active_tab;

		$( '#Settings_updated' ).hide();

		$.post( ajaxurl, data, function ( response ) {
			$( '#Settings_updated' ).show().delay( 5000 ).fadeOut();
		}, "json" );

		return false;
	} );

	$( "#save-btn" ).click( function () {

		if ( ! woe_validate_export() ) {
			return false;
		}

		woe_set_form_submitting();

		var data = 'json=' + woe_make_json_var( $( '#export_job_settings' ) )

		data = data + "&action=order_exporter&method=save_settings&mode=" + mode + "&id=" + job_id + '&woe_nonce=' + settings_form.woe_nonce + '&tab=' + settings_form.woe_active_tab;

		$.post( ajaxurl, data, function ( response ) {
			document.location = settings_form.save_settings_url;
		}, "json" );

		return false;
	} );

	$( '#progress_div .title-download' ).click( function () {
		$( '#progress_div .title-download' ).hide();
		$( '#progress_div .title-cancel' ).show();
		$( '#progressBar' ).show();
		jQuery( '#progress_div' ).hide();
		woe_close_waiting_dialog();
	} );

} );